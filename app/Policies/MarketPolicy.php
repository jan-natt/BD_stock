<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Market;
use Illuminate\Auth\Access\Response;

class MarketPolicy
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
    public function view(User $user, Market $market): bool
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
    public function update(User $user, Market $market): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Market $market): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can import markets.
     */
    public function import(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can export markets.
     */
    public function export(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view statistics.
     */
    public function statistics(User $user): bool
    {
        return $user->user_type === 'admin';
    }
}