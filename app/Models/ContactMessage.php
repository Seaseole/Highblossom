<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Contact form submissions from visitors.
 * Maps to the `contact_messages` database table.
 */
final class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Mark the message as read.
     */
    public function markAsRead(): static
    {
        $this->update(['is_read' => true]);

        return $this;
    }

    /**
     * Scope query to only include read messages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope query to only include unread messages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
