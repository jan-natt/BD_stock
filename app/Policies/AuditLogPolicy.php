<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Auth\Access\Response;

class AuditLogPolicy
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
    public function view(User $user, AuditLog $auditLog): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // Audit logs are created automatically by the system
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AuditLog $auditLog): bool
    {
        return false; // Audit logs are immutable
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AuditLog $auditLog): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can export audit logs.
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

    /**
     * Determine whether the user can cleanup audit logs.
     */
    public function cleanup(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view suspicious activity.
     */
    public function viewSuspiciousActivity(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view user activity reports.
     */
    public function viewUserActivity(User $user): bool
    {
        return $user->user_type === 'admin';
    }
}