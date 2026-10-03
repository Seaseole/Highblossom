<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Internal, timestamped admin note on an inspection.
 * Maps to the `inspection_notes` database table.
 *
 * Never emailed to a client and never rendered on the public status page. Notes
 * flagged `is_action` represent work still to be done and stay open until
 * `done_at` is set.
 */
final class InspectionNote extends Model
{
    protected $fillable = [
        'inspection_id', 'user_id', 'body', 'is_action', 'done_at',
    ];

    protected $casts = [
        'is_action' => 'boolean',
        'done_at' => 'datetime',
    ];

    /**
     * Get the inspection this note belongs to.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Get the admin who wrote the note.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Restrict to action items that have not been ticked off yet.
     *
     * @param Builder<self> $query
     */
    public function scopeOutstanding(Builder $query): Builder
    {
        return $query->where('is_action', true)->whereNull('done_at');
    }

    /**
     * Determine whether the note is still waiting to be actioned.
     */
    public function isOpen(): bool
    {
        return $this->is_action && $this->done_at === null;
    }
}
