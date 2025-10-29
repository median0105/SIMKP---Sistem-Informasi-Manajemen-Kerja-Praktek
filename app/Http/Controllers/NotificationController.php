<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notifikasi::where('user_id', auth()->id())
                                  ->where('is_read', false)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(20);

        $unreadCount = $notifications->total();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markRead(Notifikasi $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        NotificationService::markAsRead($notification->id);

        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllRead()
    {
        NotificationService::markAllAsRead(auth()->id());
        
        return back()->with('success', 'Semua Notifikasi ditandai sudah dibaca.');
    }
}