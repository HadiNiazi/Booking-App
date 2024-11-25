<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Event;
use \Stripe\Checkout\Session;

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

        \Stripe\Stripe::setApiKey( config('services.stripe.secret') );

        $userId = auth()->id();
        $eventId = $request->event_id;

        $event = Event::findOrFail($eventId);

        $alreadyRegistered = Booking::where('user_id', $userId)->where('event_id', $eventId)
                                    // ->where('status', 'paid')
                                    // ->orWhere('status', 'free')
                                    ->where( function($query) {
                                        $query->where('status', 'paid')
                                        ->orWhere('status', 'free');
                                    })
                                    ->first();

        if ($alreadyRegistered) {
            return back()->with('booking_failed', 'You are already registered for this event');
        }

        if ($event->type == 'PAID') {

            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $event->name,
                        ],
                        'unit_amount' => $event->price * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'metadata' => [
                    'user_id' => $userId,
                    'event_id' => $event->id,
                    // 'preferred_payment_method' => 'ideal',
                ],
                'success_url' => route('site.thanku') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('site.cancel'),
            ]);

            $this->saveEventInDatabase($userId, $eventId);

            session()->flash('success_msg', 'Your Booking Confirmed');
            session()->put('event_type', 'PAID');

            if ($checkoutSession->url) {
                return redirect($checkoutSession->url);
            }
            else {
                return back()->with('booking_failed', 'Payment failed');
            }

        }
        // else if ($event->type == 'FREE')
        else
        {
            $status = 'FREE';

            $this->saveEventInDatabase($userId, $eventId, $status);

            return to_route('site.thanku')
                            ->with('success_msg', 'Your Booking Confirmed!');
        }


    }

    public function openThankuPage()
    {
        if (session()->has('event_type')) {

            if (session()->get('event_type') == 'PAID') {

                \Stripe\Stripe::setApiKey( config('services.stripe.secret') );

                $sessionId = request()->session_id;

                $session = Session::retrieve($sessionId);

                $metaData = $session->metadata;

                $userId = $metaData->user_id;
                $eventId = $metaData->event_id;

                $booking = Booking::where('user_id', $userId)->where('event_id', $eventId)
                                ->where('status', 'unpaid')
                                ->first();

                if ($booking) {
                    $booking->update([
                        'status' => 'paid'
                    ]);
                }

            }

        }


        return view('site.thanku');
    }

    public function openCancelPage()
    {
        return view('site.cancel');
    }


    private function saveEventInDatabase($userId, $eventId, $status = null)
    {
        try {
            Booking::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'status' => $status != null ? 'free': 'unpaid'
            ]);
        }
        catch(\Exception $ex) {
            return back()->with('booking_failed', 'Your booking is failed, please try again!');
        }
    }

}
