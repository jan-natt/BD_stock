<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserRolePolicy
{
    /**
     * Determine whether the user can manage user roles.
     */
    public function manage(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view user roles.
     */
    public function view(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can assign admin role.
     */
    public function assignAdmin(User $user): bool
    {
        // Only allow users who are already admins to assign admin role
        return $user->hasRole('admin');
    }
}