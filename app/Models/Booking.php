<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingDamageType;
use App\Enums\BookingServiceType;
use Carbon\CarbonInterface;
use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'user_id', 'client_name', 'client_email', 'client_phone', 'prefers_whatsapp',
        'vehicle_details', 'service_type', 'damage_type', 'vehicle_make', 'vehicle_model',
        'vehicle_year', 'damage_photo_path', 'scheduled_at', 'duration_minutes', 'location',
        'client_address', 'status', 'total_price', 'reference',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'total_price' => 'decimal:2',
        'vehicle_year' => 'integer',
        'duration_minutes' => 'integer',
        'prefers_whatsapp' => 'boolean',
        'service_type' => BookingServiceType::class,
        'damage_type' => BookingDamageType::class,
    ];

    /**
     * The moment the job finishes, derived from the booked start and its duration.
     *
     * The customer never picks an end time, so this is the only place it exists.
     * Null when the booking has no slot yet.
     */
    protected function endsAt(): Attribute
    {
        return Attribute::get(function (): ?CarbonInterface {
            if ($this->scheduled_at === null) {
                return null;
            }

            return $this->scheduled_at->copy()->addMinutes($this->durationMinutes());
        });
    }

    /**
     * How long the job runs, falling back to its service type when unset.
     */
    public function durationMinutes(): int
    {
        return max(15, (int) ($this->duration_minutes ?: 60));
    }

    /**
     * The reference shown to customers.
     *
     * Bookings created before references were stored, and factory rows in tests,
     * have no column value but must still render the same shape.
     */
    public function displayReference(): string
    {
        return $this->reference ?? 'HB-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * One-line description of the vehicle, assembled from the structured fields.
     */
    public function vehicleSummary(): string
    {
        $parts = array_filter([
            $this->vehicle_year !== null ? (string) $this->vehicle_year : null,
            $this->vehicle_make,
            $this->vehicle_model,
        ]);

        $structured = trim(implode(' ', $parts));

        return $structured !== '' ? $structured : (string) $this->vehicle_details;
    }

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
