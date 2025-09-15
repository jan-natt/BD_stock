<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'priority',
        'action_url',
        'action_text',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope a query to only include notifications of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include notifications of a specific priority.
     */
    public function scopeOfPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include recent notifications.
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
        return $this->created_at->format('M j, Y g:i A');
    }

    /**
     * Get human-readable time difference.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get CSS class for notification type.
     */
    public function getTypeClassAttribute(): string
    {
        return match($this->type) {
            'success' => 'bg-green-100 text-green-800 border-green-200',
            'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'error' => 'bg-red-100 text-red-800 border-red-200',
            'system' => 'bg-blue-100 text-blue-800 border-blue-200',
            'security' => 'bg-purple-100 text-purple-800 border-purple-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200' // info
        };
    }

    /**
     * Get CSS class for notification priority.
     */
    public function getPriorityClassAttribute(): string
    {
        return match($this->priority) {
            'high' => 'border-l-4 border-yellow-500',
            'urgent' => 'border-l-4 border-red-500',
            default => 'border-l-4 border-gray-300' // low, normal
        };
    }

    /**
     * Get icon for notification type.
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'success' => '✓',
            'warning' => '⚠',
            'error' => '✗',
            'system' => '⚙',
            'security' => '🔒',
            default => 'ℹ' // info
        };
    }

    /**
     * Check if notification has an action.
     */
    public function getHasActionAttribute(): bool
    {
        return !empty($this->action_url) && !empty($this->action_text);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Get excerpt of the message.
     */
    public function getExcerptAttribute(): string
    {
        return strlen($this->message) > 100 
            ? substr($this->message, 0, 100) . '...' 
            : $this->message;
    }
}