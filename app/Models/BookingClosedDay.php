<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BookingClosedDayFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A single day the shop takes no bookings at all.
 *
 * Maps to the `booking_closed_days` database table.
 */
final class BookingClosedDay extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return BookingClosedDayFactory
     */
    protected static function newFactory()
    {
        return BookingClosedDayFactory::new();
    }

    protected $fillable = ['date', 'reason'];

    protected $casts = [
        'date' => 'date',
    ];
}
