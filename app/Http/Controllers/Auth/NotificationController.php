<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function markAsRead($notificationId, $userId)
    {
        $notification = Notification::find($notificationId);

        if ( $notification) {

            $notification->users()->updateExistingPivot($userId, ['is_read' => true]);

            return back()->with('message', 'Notification marked as read');
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }

    public function openAllNotifications()
    {
        // mark all notifications as read

        auth()->user()->notifications()->updateExistingPivot(
            auth()->user()->notifications->pluck('id'),
            [
                'is_read' => true
            ]
        );

        $notifications = auth()->user()->notifications()->get();

        return view('auth.notifications.index', compact('notifications'));
    }
}
