<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Trade;
use Illuminate\Auth\Access\Response;

class TradePolicy
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
    public function view(User $user, Trade $trade): bool
    {
        // Users can view trades they participated in
        return $user->id === $trade->buyOrder->user_id || 
               $user->id === $trade->sellOrder->user_id || 
               $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // Trades are created automatically by the system
    }

    /**
     * Determine whether the user can execute trades.
     */
    public function execute(User $user): bool
    {
        return $user->user_type === 'admin'; // Only admins can manually execute trades
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Trade $trade): bool
    {
        return false; // Trades are immutable
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Trade $trade): bool
    {
        return false; // Trades should not be deleted
    }

    /**
     * Determine whether the user can export trades.
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