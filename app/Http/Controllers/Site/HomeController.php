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
        // session()->flush();
        // dd(session()->get('event_type'));
        $event = Event::findOrFail($id);

        return view('site.details', compact('event'));
    }

    public function checkout(Request $request)
    {

        \Stripe\Stripe::setApiKey( config('services.stripe.secret') );

        $userId = auth()->id();
        $eventId = $request->event_id;

        DB::beginTransaction();

        $event = Event::where('id', $eventId)->lockForUpdate()->first();

        try {

            $alreadyRegistered = Booking::where('user_id', $userId)->where('event_id', $eventId)
                                    // ->where('status', 'paid')
                                    // ->orWhere('status', 'free')
                                    ->where( function($query) {
                                        $query->where('status', 'paid')
                                        ->orWhere('status', 'free');
                                    })
                                    ->first();

            $seatsLeft = $event->max_attendees;

            if ($seatsLeft < 1) {
                throw new \Exception('Event seats are booked, you can enroll into any other event');
                // return back()->with('booking_failed', 'Event seats are booked, you can enroll into any other event.');
            }

            if ($alreadyRegistered) {
                throw new \Exception('You are already registered for this event');
                // return back()->with('booking_failed', 'You are already registered for this event');
            }


            $this->saveEventInDatabase($userId, $eventId, $event->type == 'PAID' ? 'unpaid': 'free');
            $event->decrement('max_attendees');

            DB::commit();

        }
        catch (\Exception $ex) {
            DB::rollback();
            return back()->with('booking_failed', $ex->getMessage());
        }

        // in case of any error it should increment one attende.


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

            // $event->update([
            //     'max_attendees' => $event->max_attendes - 1
            // ]);

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
            return to_route('site.thanku')->with('success_msg', 'Your Booking Confirmed!');
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

        session()->forget('event_type');

        return view('site.thanku');
    }

    public function openCancelPage()
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

                $booking->increment('max_attendees'); // + 1 in seats

            }

        }

        session()->forget('event_type');
        // $event->decremnt('max_attendees');
        // return view('site.cancel'); // before this fix the above cancel booking seats decrement issue
    }


    private function saveEventInDatabase($userId, $eventId, $status)
    {
        try {
            Booking::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'status' => $status
            ]);
        }
        catch(\Exception $ex) {
            return back()->with('booking_failed', 'Your booking is failed, please try again!');
        }
    }

}
