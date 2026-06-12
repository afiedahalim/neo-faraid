<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        // Authorization check
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        return redirect()->back()
            ->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return redirect()->back()
            ->with('success', 'All notifications marked as read.');
    }

    public function destroy(Notification $notification)
    {
        // Authorization check
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->delete();
        
        return redirect()->back()
            ->with('success', 'Notification deleted.');
    }

    public function clearAll(Request $request)
    {
        Auth::user()->notifications()->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'All notifications cleared.');
    }
}