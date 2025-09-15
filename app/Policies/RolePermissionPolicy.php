<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePermissionPolicy
{
    /**
     * Determine whether the user can manage role permissions.
     */
    public function manage(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view role permissions.
     */
    public function view(User $user): bool
    {
        return $user->user_type === 'admin';
    }
}