<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Inspection;
use App\Models\StaffAbsence;
use App\Services\Contracts\AvailabilityServiceInterface;
use Illuminate\Support\Facades\Date;

/**
 * Service for checking booking slot availability.
 */
final class AvailabilityService implements AvailabilityServiceInterface
{
    /**
     * Check if a given time slot is available for booking.
     *
     * @param \DateTimeInterface|string $scheduledAt The date/time to check
     *
     * @return bool True if the slot is available
     */
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
        return ! Inspection::where('scheduled_at', $date)->exists();
    }
}
