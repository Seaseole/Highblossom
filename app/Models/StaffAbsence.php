<?php

namespace App\Models;

use Database\Factories\StaffAbsenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class StaffAbsence extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return StaffAbsenceFactory::new();
    }

    protected $fillable = ['staff_id', 'starts_at', 'ends_at', 'reason'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
