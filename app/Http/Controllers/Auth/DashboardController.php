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
        $user = auth()->user();

        $completedBookings = $this->getCompletedBookingsCount($user);
        $incompleteBookings = $this->getIncompleteBookingsCount($user);

        $categoriesCount = Category::count();

        $events = Event::take(3)->orderBy('created_at', 'desc')->get();

        return view('auth.dashboard', compact('categoriesCount', 'events'), ['completed_booking' => $completedBookings, 'incomplete_bookings' => $incompleteBookings]);
    }

    private function getCompletedBookingsCount($user)
    {
        $query = Booking::query();

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return $query->where(function ($query) {
            $query->where('status', 'paid')
                  ->orWhere('status', 'free');
        })->count();
    }

    private function getIncompleteBookingsCount($user)
    {
        $query = Booking::query();

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return $query->where('status', 'unpaid')->count();
    }
}
