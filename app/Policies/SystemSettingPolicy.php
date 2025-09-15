<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Auth\Access\Response;

class SystemSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SystemSetting $systemSetting): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SystemSetting $systemSetting): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SystemSetting $systemSetting): bool
    {
        return $user->user_type === 'admin' && !$systemSetting->is_protected;
    }

    /**
     * Determine whether the user can import settings.
     */
    public function import(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can export settings.
     */
    public function export(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view public settings.
     */
    public function viewPublic(User $user): bool
    {
        return true; // Public settings are accessible to all
    }

    /**
     * Determine whether the user can reset settings.
     */
    public function reset(User $user, SystemSetting $systemSetting): bool
    {
        return $user->user_type === 'admin' && !$systemSetting->is_protected;
    }

    /**
     * Determine whether the user can bulk update settings.
     */
    public function bulkUpdate(User $user): bool
    {
        return $user->user_type === 'admin';
    }
}