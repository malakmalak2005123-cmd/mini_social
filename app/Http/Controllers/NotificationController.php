<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
                                     ->with('actor')
                                     ->latest()
                                     ->get();

        // Mark all as read
        Notification::where('user_id', auth()->id())
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Remove the specified notification from storage.
     */
    public function destroy(Notification $notification)
    {
        // Ensure the user owns the notification
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
