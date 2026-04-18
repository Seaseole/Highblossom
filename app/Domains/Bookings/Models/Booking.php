<?php

namespace App\Domains\Bookings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Booking extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\BookingFactory::new();
    }
    protected $fillable = [
        'user_id', 'client_name', 'client_email', 'client_phone', 
        'vehicle_details', 'status', 'total_price'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function inspection(): HasOne
    {
        return $this->hasOne(Inspection::class);
    }
}
