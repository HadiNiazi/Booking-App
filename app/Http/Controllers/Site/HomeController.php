<?php

namespace App\Http\Controllers\Site;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use \Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function openHomePage()
    {
        $events = Event::with(['category'])->get();

        return view('site.index', compact('events'));
    }

    public function openEventDetailsPage($id)
    {
        $event = Event::findOrFail($id);

        return view('site.details', compact('event'));
    }

    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $userId = auth()->id();
        $eventId = $request->event_id;

        DB::beginTransaction();

        try {
            // Lock event row to prevent race conditions
            $event = Event::where('id', $eventId)->lockForUpdate()->firstOrFail();

            // Check if user has already booked the event
            if ($this->isUserAlreadyRegistered($userId, $eventId)) {
                throw new \Exception('You are already registered for this event.');
            }

            // Check if there are seats available
            if ($event->max_attendees <= 0) {
                throw new \Exception('Event seats are booked. You can enroll in another event.');
            }

            // Save booking in the database
            $this->saveEventInDatabase($userId, $eventId, $event->type == 'PAID' ? 'unpaid' : 'free');

            // Decrement available seats
            $event->decrement('max_attendees');

            DB::commit();

            // Redirect to payment if event is PAID
            if ($event->type == 'PAID') {
                return $this->createStripeCheckoutSession($event, $userId);
            }

            // Confirm free event booking
            return to_route('site.thanku')->with('success_msg', 'Your Booking Confirmed!');

        } catch (\Exception $ex) {
            DB::rollback();
            return back()->with('booking_failed', $ex->getMessage());
        }
    }

    private function isUserAlreadyRegistered($userId, $eventId)
    {
        return Booking::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where(function($query) {
                $query->where('status', 'paid')->orWhere('status', 'free');
            })
            ->exists();
    }

    private function createStripeCheckoutSession($event, $userId)
    {
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $event->name,
                    ],
                    'unit_amount' => $event->price * 100, // Price in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [
                'user_id' => $userId,
                'event_id' => $event->id,
            ],
            'success_url' => route('site.thanku') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('site.cancel') . '?session_id={CHECKOUT_SESSION_ID}',
        ]);

        session()->flash('success_msg', 'Your Booking Confirmed');
        session()->put('event_type', 'PAID');

        return redirect($checkoutSession->url);
    }

    public function openThankuPage()
    {
        if (session()->has('event_type') && session()->get('event_type') == 'PAID') {
            $this->updateBookingStatusToPaid();
        }

        session()->forget('event_type');
        return view('site.thanku');
    }

    public function openCancelPage()
    {
        if (session()->has('event_type') && session()->get('event_type') == 'PAID') {
            $this->cancelBookingAndReleaseSeat();
        }

        session()->forget('event_type');
        return view('site.cancel');
    }

    private function updateBookingStatusToPaid()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = request()->session_id;
        $session = Session::retrieve($sessionId);
        $metaData = $session->metadata;

        $userId = $metaData->user_id;
        $eventId = $metaData->event_id;

        $booking = Booking::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('status', 'unpaid')
            ->first();

        if ($booking) {
            $booking->update(['status' => 'paid']);
        }
    }

    private function cancelBookingAndReleaseSeat()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = request()->session_id;
        $session = Session::retrieve($sessionId);
        $metaData = $session->metadata;

        $userId = $metaData->user_id;
        $eventId = $metaData->event_id;

        $booking = Booking::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('status', 'unpaid')
            ->first();

        if ($booking) {
            $booking->delete();
        }

        $event = Event::find($eventId);
        if (!$event) {
            Log::emergency("Booking was not completed, but seat is still reserved because we are not able to find the event. Event ID: {$eventId}");
            return back()->with('booking_failed', 'Failed to find the event, please contact support.');
        }

        $event->increment('max_attendees'); // Increment seat count
    }

    private function saveEventInDatabase($userId, $eventId, $status)
    {
        try {
            Booking::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'status' => $status,
            ]);
        } catch (\Exception $ex) {
            return back()->with('booking_failed', 'Your booking failed, please try again!');
        }
    }
}
