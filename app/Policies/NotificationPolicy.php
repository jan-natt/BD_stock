<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Access\Response;

class NotificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can always view their own notifications
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_type === 'admin'; // Only admins can create notifications
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id;
    }

    /**
     * Determine whether the user can view statistics.
     */
    public function viewStatistics(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can export notifications.
     */
    public function export(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can broadcast notifications.
     */
    public function broadcast(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can manage preferences.
     */
    public function managePreferences(User $user): bool
    {
        return true; // Users can always manage their own preferences
    }
}