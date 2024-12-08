<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Event;

class DashboardController extends Controller
{
    public function openDashboardPage()
    {
        $completedBookings = 0;
        $incompleteBookings = 0;

        $user = auth()->user();

        if ($user->role == 'admin') {

            $completedBookings = Booking::where( function($query) {
                $query->where('status', 'paid')
                ->orWhere('status', 'free');
            })->count();

            $incompleteBookings = Booking::where('status', 'unpaid')->count();

        }
        else {

            $completedBookings = Booking::where('user_id', $user->id)->where( function($query) {
                $query->where('status', 'paid')
                ->orWhere('status', 'free');
            })->count();

            $incompleteBookings = Booking::where('user_id', $user->id)->where('status', 'unpaid')->count();

        }



        $categoriesCount = Category::count();

        $events = Event::take(3)->orderBy('created_at', 'desc')->get();

        return view('auth.dashboard', compact('categoriesCount', 'events'), ['completed_booking' => $completedBookings, 'incomplete_bookings' => $incompleteBookings]);
    }
}
