<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StaffAbsenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Staff absence records with date ranges.
 * Maps to the `staff_absences` database table.
 */
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

    /**
     * Get the staff member associated with this absence.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
