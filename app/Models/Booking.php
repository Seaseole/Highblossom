<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Customer bookings for services with pricing and status.
 * Maps to the `bookings` database table.
 */
final class Booking extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return BookingFactory
     */
    protected static function newFactory()
    {
        return BookingFactory::new();
    }

    protected $fillable = [
        'user_id', 'client_name', 'client_email', 'client_phone',
        'vehicle_details', 'scheduled_at', 'location', 'client_address', 'status', 'total_price',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the user who made this booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the inspection associated with this booking.
     */
    public function inspection(): HasOne
    {
        return $this->hasOne(Inspection::class);
    }

    /**
     * Get the customer-visible milestone history for this booking.
     *
     * This is the only history safe to render on the public status page; internal
     * remarks live on Inspection::notes().
     */
    public function events(): HasMany
    {
        return $this->hasMany(BookingEvent::class)->orderBy('happened_at');
    }

    /**
     * Determine whether the booking has been cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
