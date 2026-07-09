<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Inspection;
use App\Models\StaffAbsence;
use App\Services\Contracts\AvailabilityServiceInterface;
use Carbon\Carbon;
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

        // Rule: Check for existing bookings at the same time (not cancelled)
        $hasBooking = Booking::where('scheduled_at', $date)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($hasBooking) {
            return false;
        }

        // Rule: Check for existing inspections at the same time
        // Simple logic for now: one inspection per slot
        return ! Inspection::where('scheduled_at', $date)->exists();
    }

    /**
     * Get available 1-hour time slots for a given date.
     *
     * @param \DateTimeInterface                                                           $date         The date to check
     * @param array<string, array{open: string|null, close: string|null, is_closed: bool}> $workingHours
     * @param string                                                                       $timezone     Timezone for slot generation
     *
     * @return array<string, bool> Associative array of 'HH:MM' => availability
     */
    public function getAvailableSlots(\DateTimeInterface $date, array $workingHours, string $timezone = 'UTC'): array
    {
        $dayName = strtolower(Carbon::createFromInterface($date)->format('l'));

        if (! isset($workingHours[$dayName])) {
            return [];
        }

        $day = $workingHours[$dayName];

        if ($day['is_closed'] || empty($day['open']) || empty($day['close'])) {
            return [];
        }

        $slots = [];
        $tz = new \DateTimeZone($timezone);

        $current = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d').' '.$day['open'], $tz);
        $close = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d').' '.$day['close'], $tz);

        if (! $current || ! $close) {
            return [];
        }

        while ($current->lt($close)) {
            $timeString = $current->format('H:i');
            $slots[$timeString] = $this->isSlotAvailable($current);
            $current = $current->addHour();
        }

        return $slots;
    }
}
