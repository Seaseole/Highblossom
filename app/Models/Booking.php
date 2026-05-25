<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Booking extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return BookingFactory::new();
    }

    protected $fillable = [
        'user_id', 'client_name', 'client_email', 'client_phone',
        'vehicle_details', 'status', 'total_price',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inspection(): HasOne
    {
        return $this->hasOne(Inspection::class);
    }
}
