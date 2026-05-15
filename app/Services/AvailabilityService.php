<?php

namespace App\Services;

use App\Services\Contracts\AvailabilityServiceInterface;
use App\Models\Inspection;
use App\Models\StaffAbsence;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

final class AvailabilityService implements AvailabilityServiceInterface
{
    public function isSlotAvailable(\DateTimeInterface|string $scheduledAt): bool
    {
        $date = Date::parse($scheduledAt);

        // Rule: No bookings on weekends (matching StoreBookingRequest logic)
        if ($date->isWeekend()) {
            return false;
        }

        // Rule: Check for staff absences (Rule 2: subqueries)
        $hasAbsence = StaffAbsence::where('starts_at', '<=', $date)
            ->where('ends_at', '>=', $date)
            ->exists();

        if ($hasAbsence) {
            return false;
        }

        // Rule: Check for existing inspections at the same time
        // Simple logic for now: one inspection per slot
        return !Inspection::where('scheduled_at', $date)->exists();
    }
}
