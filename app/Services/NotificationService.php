<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Send a notification to a user.
     */
    public static function send(
        int $userId, 
        string $title, 
        string $message, 
        string $type = 'info', 
        string $priority = 'normal',
        ?string $actionUrl = null,
        ?string $actionText = null
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'priority' => $priority,
            'action_url' => $actionUrl,
            'action_text' => $actionText,
            'is_read' => false,
        ]);
    }

    /**
     * Send notification to multiple users.
     */
    public static function sendToMany(
        array $userIds, 
        string $title, 
        string $message, 
        string $type = 'info', 
        string $priority = 'normal',
        ?string $actionUrl = null,
        ?string $actionText = null
    ): int {
        $notifications = [];
        $now = now();

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'priority' => $priority,
                'action_url' => $actionUrl,
                'action_text' => $actionText,
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return Notification::insert($notifications);
    }

    /**
     * Broadcast notification to all users.
     */
    public static function broadcast(
        string $title, 
        string $message, 
        string $type = 'info', 
        string $priority = 'normal',
        ?string $actionUrl = null,
        ?string $actionText = null
    ): int {
        $userIds = User::where('status', true)->pluck('id');
        return self::sendToMany($userIds->toArray(), $title, $message, $type, $priority, $actionUrl, $actionText);
    }

    /**
     * Send system notification.
     */
    public static function system(
        int $userId, 
        string $message, 
        string $priority = 'normal',
        ?string $actionUrl = null,
        ?string $actionText = null
    ): Notification {
        return self::send($userId, 'System Notification', $message, 'system', $priority, $actionUrl, $actionText);
    }

    /**
     * Send security alert.
     */
    public static function security(
        int $userId, 
        string $message, 
        ?string $actionUrl = null,
        ?string $actionText = null
    ): Notification {
        return self::send($userId, 'Security Alert', $message, 'security', 'high', $actionUrl, $actionText);
    }

    /**
     * Send success notification.
     */
    public static function success(
        int $userId, 
        string $message, 
        ?string $actionUrl = null,
        ?string $actionText = null
    ): Notification {
        return self::send($userId, 'Success', $message, 'success', 'normal', $actionUrl, $actionText);
    }

    /**
     * Send error notification.
     */
    public static function error(
        int $userId, 
        string $message, 
        ?string $actionUrl = null,
        ?string $actionText = null
    ): Notification {
        return self::send($userId, 'Error', $message, 'error', 'high', $actionUrl, $actionText);
    }

    /**
     * Send warning notification.
     */
    public static function warning(
        int $userId, 
        string $message, 
        ?string $actionUrl = null,
        ?string $actionText = null
    ): Notification {
        return self::send($userId, 'Warning', $message, 'warning', 'normal', $actionUrl, $actionText);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public static function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get unread count for a user.
     */
    public static function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Clean up old read notifications.
     */
    public static function cleanup(int $days = 30): int
    {
        return Notification::where('is_read', true)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }
}