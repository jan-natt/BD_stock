<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the audit log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include logs for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include logs for a specific action.
     */
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to only include logs from a specific IP address.
     */
    public function scopeForIp($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    /**
     * Scope a query to only include logs within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include recent logs.
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Get formatted created at timestamp.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    /**
     * Get human-readable time difference.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if log is from a system action.
     */
    public function getIsSystemActionAttribute(): bool
    {
        return $this->user_id === null;
    }

    /**
     * Get abbreviated user agent.
     */
    public function getShortUserAgentAttribute(): string
    {
        if (!$this->user_agent) {
            return 'Unknown';
        }

        // Extract browser name
        if (strpos($this->user_agent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($this->user_agent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($this->user_agent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($this->user_agent, 'Edge') !== false) {
            return 'Edge';
        } elseif (strpos($this->user_agent, 'Opera') !== false) {
            return 'Opera';
        }

        return substr($this->user_agent, 0, 50) . (strlen($this->user_agent) > 50 ? '...' : '');
    }

    /**
     * Get action category.
     */
    public function getActionCategoryAttribute(): string
    {
        if (strpos($this->action, 'login') !== false) {
            return 'authentication';
        } elseif (strpos($this->action, 'create') !== false) {
            return 'create';
        } elseif (strpos($this->action, 'update') !== false) {
            return 'update';
        } elseif (strpos($this->action, 'delete') !== false) {
            return 'delete';
        } elseif (strpos($this->action, 'export') !== false) {
            return 'export';
        } elseif (strpos($this->action, 'import') !== false) {
            return 'import';
        }

        return 'other';
    }

    /**
     * Get CSS class for action category.
     */
    public function getActionCategoryClassAttribute(): string
    {
        return match($this->action_category) {
            'authentication' => 'bg-blue-100 text-blue-800',
            'create' => 'bg-green-100 text-green-800',
            'update' => 'bg-yellow-100 text-yellow-800',
            'delete' => 'bg-red-100 text-red-800',
            'export' => 'bg-purple-100 text-purple-800',
            'import' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}