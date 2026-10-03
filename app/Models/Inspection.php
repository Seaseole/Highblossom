<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\InspectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * On-site inspection records linked to bookings.
 * Maps to the `inspections` database table.
 *
 * @property-read string $status
 */
final class Inspection extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return InspectionFactory
     */
    protected static function newFactory()
    {
        return InspectionFactory::new();
    }

    protected $fillable = [
        'booking_id', 'staff_id', 'scheduled_at', 'started_at', 'ended_at',
        'location', 'type',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the booking this inspection belongs to.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the staff member assigned to this inspection.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the internal notes for this inspection, newest first.
     *
     * Internal only: never render these on a customer-facing surface.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(InspectionNote::class)->latest('id');
    }

    /**
     * Get the computed status of the inspection.
     */
    public function getStatusAttribute(): string
    {
        if ($this->ended_at !== null) {
            return 'completed';
        }

        if ($this->started_at !== null) {
            return 'in_progress';
        }

        return $this->scheduled_at->isPast() ? 'overdue' : 'scheduled';
    }
}
