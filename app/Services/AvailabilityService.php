<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Inspection;
use App\Models\StaffAbsence;
use App\Services\Contracts\AvailabilityServiceInterface;
use App\Services\Settings\SettingsManager;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Facades\Date;

/**
 * Decides which inspection slots a customer may book.
 *
 * All slot maths runs in the application timezone, because `scheduled_at` is
 * stored as naive wall-clock in that same timezone. Mixing in the display
 * timezone setting would compare two different frames.
 */
final class AvailabilityService implements AvailabilityServiceInterface
{
    /** How far ahead the booking calendar reaches. */
    public const HORIZON_DAYS = 90;

    public function __construct(private readonly SettingsManager $settings) {}

    /**
     * Explain why a slot is or is not bookable.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     */
    public function slotStatus(DateTimeInterface|string $scheduledAt): string
    {
        $slot = Date::parse($scheduledAt);

        if ($this->openHoursFor($slot) === null) {
            return self::STATUS_CLOSED;
        }

        if ($slot->lt($this->noticeCutoff())) {
            return self::STATUS_TOO_LATE;
        }

        return $this->isSlotFree($slot, false)
            ? self::STATUS_AVAILABLE
            : self::STATUS_TAKEN;
    }

    /**
     * Check whether a slot is bookable.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     */
    public function isSlotAvailable(DateTimeInterface|string $scheduledAt): bool
    {
        return $this->slotStatus($scheduledAt) === self::STATUS_AVAILABLE;
    }

    /**
     * Check availability while holding a row/gap lock, for use inside a transaction.
     *
     * Locking the non-existent-row range is what stops two concurrent submissions
     * from both passing the availability re-check and inserting the same slot.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     */
    public function isSlotAvailableForUpdate(DateTimeInterface|string $scheduledAt): bool
    {
        $slot = Date::parse($scheduledAt);

        if ($this->openHoursFor($slot) === null || $slot->lt($this->noticeCutoff())) {
            return false;
        }

        return $this->isSlotFree($slot, true);
    }

    /**
     * Every hour-aligned slot on the given date, each with a display label and status.
     *
     * @param DateTimeInterface|string $date The date to build slots for
     *
     * @return list<array{time: string, label: string, available: bool, status: string}>
     */
    public function slotsForDate(DateTimeInterface|string $date): array
    {
        $day = Date::parse($date)->startOfDay();
        $hours = $this->openHoursFor($day);

        if ($hours === null) {
            return [];
        }

        $open = $this->atTime($day, $hours['open']);
        $close = $this->atTime($day, $hours['close']);
        $slots = [];

        for ($current = $open; $current->lt($close); $current = $current->addHour()) {
            $status = $this->slotStatus($current);

            $slots[] = [
                'time' => $current->format('H:i'),
                'label' => $this->formatTime($current),
                'available' => $status === self::STATUS_AVAILABLE,
                'status' => $status,
            ];
        }

        return $slots;
    }

    /**
     * The first hour-aligned slot that could possibly be bookable, or null past the horizon.
     *
     * Deliberately avoids querying taken slots so the "earliest you can book" hint
     * costs nothing on every page load.
     */
    public function earliestBookableSlot(): ?CarbonInterface
    {
        $candidate = $this->roundUpToHour($this->noticeCutoff());

        for ($offset = 0; $offset <= self::HORIZON_DAYS; $offset++) {
            $hours = $this->openHoursFor($candidate);

            if ($hours !== null) {
                $open = $this->atTime($candidate, $hours['open']);
                $close = $this->atTime($candidate, $hours['close']);

                if ($candidate->lt($open)) {
                    return $open;
                }

                if ($candidate->lt($close)) {
                    return $candidate;
                }
            }

            $candidate = $candidate->addDay()->startOfDay();
        }

        return null;
    }

    /**
     * The next open days that still have at least one bookable slot.
     *
     * @param int $count Maximum number of days to return
     *
     * @return list<array{date: string, day: string, weekday: string, month: string, is_today: bool, first_slot: string}>
     */
    public function upcomingOpenDays(int $count): array
    {
        $earliest = $this->earliestBookableSlot();

        if ($earliest === null) {
            return [];
        }

        $cutoff = $this->noticeCutoff();
        $today = Date::today();
        $days = [];

        for ($offset = 0; $offset <= self::HORIZON_DAYS && count($days) < $count; $offset++) {
            $date = $today->copy()->addDays($offset);
            $hours = $this->openHoursFor($date);

            if ($hours !== null) {
                $open = $this->atTime($date, $hours['open']);
                $close = $this->atTime($date, $hours['close']);
                $first = $open->lt($cutoff) ? $earliest : $open;

                if ($first->lt($close)) {
                    $days[] = [
                        'date' => $date->toDateString(),
                        'day' => $date->format('d'),
                        'weekday' => $date->format('D'),
                        'month' => $date->format('M'),
                        'is_today' => $date->isSameDay($today),
                        'first_slot' => $this->formatTime($first),
                    ];
                }
            }
        }

        return $days;
    }

    /**
     * Minimum number of hours a customer must book ahead of the current time.
     */
    public function leadTimeHours(): int
    {
        return max(0, (int) $this->settings->get('booking_lead_time_hours', 2));
    }

    /**
     * Whether the business takes bookings at all on the given day.
     *
     * @param DateTimeInterface $date The day to check
     */
    public function isOpenDay(DateTimeInterface $date): bool
    {
        return $this->openHoursFor(Date::parse($date)) !== null;
    }

    /**
     * Format a moment for display using the configured 12/24-hour preference.
     *
     * @param DateTimeInterface $moment The moment to format
     */
    public function formatTime(DateTimeInterface $moment): string
    {
        $isTwelveHour = (string) $this->settings->get('time_format_display', '12') === '12';

        return Date::parse($moment)->format($isTwelveHour ? 'h:i A' : 'H:i');
    }

    /**
     * The earliest instant a customer may book, ignoring hour alignment.
     */
    private function noticeCutoff(): CarbonInterface
    {
        return Date::now()->addHours($this->leadTimeHours());
    }

    /**
     * Opening and closing times for the day, or null when the day is closed.
     *
     * @return array{open: string, close: string}|null
     */
    private function openHoursFor(CarbonInterface $date): ?array
    {
        $workingHours = $this->settings->get('working_hours', []);
        $day = $workingHours[strtolower($date->format('l'))] ?? null;

        if ($day === null || ($day['is_closed'] ?? true) || empty($day['open']) || empty($day['close'])) {
            return null;
        }

        return ['open' => $day['open'], 'close' => $day['close']];
    }

    /**
     * Run the taken-slot checks against bookings, inspections, and staff absences.
     *
     * Cancelled bookings are excluded here, and the database no longer enforces a
     * unique slot, so cancelling a booking genuinely frees the time.
     */
    private function isSlotFree(CarbonInterface $slot, bool $forUpdate): bool
    {
        $hasAbsence = StaffAbsence::where('starts_at', '<=', $slot)
            ->where('ends_at', '>=', $slot)
            ->exists();

        if ($hasAbsence) {
            return false;
        }

        $conflictingBooking = Booking::where('scheduled_at', $slot)
            ->where('status', '!=', 'cancelled');

        if ($forUpdate) {
            $conflictingBooking = $conflictingBooking->lockForUpdate();
        }

        if ($conflictingBooking->exists()) {
            return false;
        }

        return ! Inspection::where('scheduled_at', $slot)->exists();
    }

    /**
     * Build a Carbon instant on the given date at the supplied HH:MM wall-clock time.
     */
    private function atTime(CarbonInterface $date, string $time): CarbonInterface
    {
        return Date::parse($date->format('Y-m-d').' '.$time);
    }

    /**
     * Move forward to the next hour boundary, staying there when already aligned.
     */
    private function roundUpToHour(CarbonInterface $moment): CarbonInterface
    {
        $rounded = $moment->copy()->setMinutes(0)->setSeconds(0);

        return $rounded->lt($moment) ? $rounded->addHour() : $rounded;
    }
}
