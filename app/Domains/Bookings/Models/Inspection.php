<?php

namespace App\Domains\Bookings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Inspection extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\InspectionFactory::new();
    }
    protected $fillable = [
        'booking_id', 'staff_id', 'scheduled_at', 'ended_at', 
        'location', 'type'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'staff_id');
    }
}
