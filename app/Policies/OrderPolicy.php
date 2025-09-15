<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\Response;

class OrderPolicy
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
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create orders
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return false; // Orders should not be deleted, only cancelled
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->status === 'open';
    }

    /**
     * Determine whether the user can export orders.
     */
    public function export(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view order book.
     */
    public function viewOrderBook(User $user): bool
    {
        return true; // Order book is public
    }
}