<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotificationService
{
    public static function sendToUser($userId, $title, $message, $type = 'info', $kerjaPraktekId = null, $actionUrl = null)
    {
        return Notifikasi::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'kerja_praktek_id' => $kerjaPraktekId,
            'action_url' => $actionUrl,
        ]);
    }

    public static function sendToRole($role, $title, $message, $type = 'info', $kerjaPraktekId = null, $actionUrl = null)
    {
        $users = User::where('role', $role)->where('is_active', true)->get();

        foreach ($users as $user) {
            self::sendToUser($user->id, $title, $message, $type, $kerjaPraktekId, $actionUrl);
        }
    }

    public static function markAsRead($notificationId)
    {
        return Notifikasi::where('id', $notificationId)->update(['is_read' => true]);
    }

    public static function markAllAsRead($userId)
    {
        return Notifikasi::where('user_id', $userId)->update(['is_read' => true]);
    }

    public static function getUnreadCount($userId)
    {
        return Notifikasi::where('user_id', $userId)->where('is_read', false)->count();
    }
}