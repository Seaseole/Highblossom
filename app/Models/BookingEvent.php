<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A customer-visible milestone in a booking's history.
 * Maps to the `booking_events` database table.
 *
 * `summary` is the only free text on this model that reaches the customer, so it
 * must never be populated from internal notes. Use InspectionNote for anything
 * the customer should not read.
 */
final class BookingEvent extends Model
{
    public const TYPE_RECEIVED = 'received';

    public const TYPE_CONFIRMED = 'confirmed';

    public const TYPE_RESCHEDULED = 'rescheduled';

    public const TYPE_STARTED = 'started';

    public const TYPE_COMPLETED = 'completed';

    public const TYPE_CANCELLED = 'cancelled';

    /** @var list<string> Every milestone the timeline understands. */
    public const ALL = [
        self::TYPE_RECEIVED,
        self::TYPE_CONFIRMED,
        self::TYPE_RESCHEDULED,
        self::TYPE_STARTED,
        self::TYPE_COMPLETED,
        self::TYPE_CANCELLED,
    ];

    /** @var list<string> Milestones that earn the customer an email. */
    public const NOTIFIABLE = [
        self::TYPE_RECEIVED,
        self::TYPE_CONFIRMED,
        self::TYPE_RESCHEDULED,
        self::TYPE_COMPLETED,
        self::TYPE_CANCELLED,
    ];

    protected $fillable = [
        'booking_id', 'type', 'summary', 'previous_scheduled_at', 'user_id', 'happened_at',
    ];

    protected $casts = [
        'happened_at' => 'datetime',
        'previous_scheduled_at' => 'datetime',
    ];

    /**
     * Get the booking this milestone belongs to.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the staff member who caused the milestone, if any.
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Determine whether this milestone earns the customer an email.
     *
     * `started` is deliberately excluded: it belongs on the status timeline but
     * emailing both "we've begun" and "we've finished" for a one-hour job is noise.
     */
    public function isNotifiable(): bool
    {
        return in_array($this->type, self::NOTIFIABLE, true);
    }
}
