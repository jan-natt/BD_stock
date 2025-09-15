<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuditLogService
{
    /**
     * Log an action to the audit log.
     */
    public static function log(string $action, ?int $userId = null, ?Request $request = null): void
    {
        try {
            $ipAddress = $request ? $request->ip() : request()->ip();
            $userAgent = $request ? $request->userAgent() : request()->userAgent();

            AuditLog::create([
                'user_id' => $userId,
                'action' => $action,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);

        } catch (\Exception $e) {
            // Fallback to regular log if audit log fails
            Log::error('Audit log failed: ' . $e->getMessage(), [
                'action' => $action,
                'user_id' => $userId,
                'ip_address' => $ipAddress ?? 'unknown',
            ]);
        }
    }

    /**
     * Log a user login action.
     */
    public static function logLogin(int $userId, Request $request): void
    {
        self::log('user_login', $userId, $request);
    }

    /**
     * Log a user logout action.
     */
    public static function logLogout(int $userId, Request $request): void
    {
        self::log('user_logout', $userId, $request);
    }

    /**
     * Log a failed login attempt.
     */
    public static function logFailedLogin(string $email, Request $request): void
    {
        self::log("failed_login attempt for: {$email}", null, $request);
    }

    /**
     * Log a create action.
     */
    public static function logCreate(string $model, int $modelId, ?int $userId = null): void
    {
        self::log("created {$model} #{$modelId}", $userId);
    }

    /**
     * Log an update action.
     */
    public static function logUpdate(string $model, int $modelId, ?int $userId = null): void
    {
        self::log("updated {$model} #{$modelId}", $userId);
    }

    /**
     * Log a delete action.
     */
    public static function logDelete(string $model, int $modelId, ?int $userId = null): void
    {
        self::log("deleted {$model} #{$modelId}", $userId);
    }

    /**
     * Log an export action.
     */
    public static function logExport(string $type, ?int $userId = null): void
    {
        self::log("exported {$type} data", $userId);
    }

    /**
     * Log an import action.
     */
    public static function logImport(string $type, ?int $userId = null): void
    {
        self::log("imported {$type} data", $userId);
    }

    /**
     * Log a password change.
     */
    public static function logPasswordChange(int $userId): void
    {
        self::log('password_changed', $userId);
    }

    /**
     * Log a profile update.
     */
    public static function logProfileUpdate(int $userId): void
    {
        self::log('profile_updated', $userId);
    }
}