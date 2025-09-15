<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PriceHistory;
use Illuminate\Auth\Access\Response;

class PriceHistoryPolicy
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
    public function view(User $user, PriceHistory $priceHistory): bool
    {
        return true; // Price history is public data
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
    public function update(User $user, PriceHistory $priceHistory): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PriceHistory $priceHistory): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can import data.
     */
    public function import(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view technical indicators.
     */
    public function viewTechnical(User $user): bool
    {
        return true; // Technical indicators are public
    }

    /**
     * Determine whether the user can view price alerts.
     */
    public function viewAlerts(User $user): bool
    {
        return true; // Price alerts are public
    }
}