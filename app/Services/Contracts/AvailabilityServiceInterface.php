<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use Carbon\CarbonInterface;

interface AvailabilityServiceInterface
{
    /** The slot can be booked. */
    public const STATUS_AVAILABLE = 'available';

    /** The day is closed, so no slot on it can ever be booked. */
    public const STATUS_CLOSED = 'closed';

    /** The slot is inside the minimum booking notice window, including every past slot. */
    public const STATUS_TOO_LATE = 'too_late';

    /** The slot is taken by a booking, inspection, or staff absence. */
    public const STATUS_TAKEN = 'taken';

    /**
     * Explain why a slot is or is not bookable.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     * @param string|null              $serviceType The job being booked, which sets its duration
     * @param string|null              $location    mobile|workshop, which selects the capacity pool
     */
    public function slotStatus(
        \DateTimeInterface|string $scheduledAt,
        ?string $serviceType = null,
        ?string $location = null
    ): string;

    /**
     * Check whether a slot is bookable.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     */
    public function isSlotAvailable(
        \DateTimeInterface|string $scheduledAt,
        ?string $serviceType = null,
        ?string $location = null
    ): bool;

    /**
     * Check availability while holding a row lock, for use inside a transaction.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     */
    public function isSlotAvailableForUpdate(
        \DateTimeInterface|string $scheduledAt,
        ?string $serviceType = null,
        ?string $location = null
    ): bool;

    /**
     * Every slot on the given date, each with a display label and status.
     *
     * @param DateTimeInterface|string $date        The date to build slots for
     * @param string|null              $serviceType The job being booked, which sets its duration
     * @param string|null              $location    mobile|workshop, which selects the capacity pool
     *
     * @return list<array{time: string, label: string, end: string, end_label: string, available: bool, status: string}>
     */
    public function slotsForDate(
        \DateTimeInterface|string $date,
        ?string $serviceType = null,
        ?string $location = null
    ): array;

    /**
     * The first slot that could possibly be bookable, or null past the horizon.
     */
    public function earliestBookableSlot(?string $serviceType = null, ?string $location = null): ?CarbonInterface;

    /**
     * The next open days that still have at least one bookable slot.
     *
     * @param int $count Maximum number of days to return
     *
     * @return list<array{date: string, day: string, weekday: string, month: string, is_today: bool, first_slot: string}>
     */
    public function upcomingOpenDays(int $count, ?string $serviceType = null, ?string $location = null): array;

    /**
     * Which days of a month still have a bookable slot, in one batched lookup.
     *
     * @param CarbonInterface $month Any moment inside the month to survey
     *
     * @return array{year: int, month: int, available_dates: list<string>, open_dates: list<string>, available_count: int, open_count: int}
     */
    public function monthAvailability(
        CarbonInterface $month,
        ?string $serviceType = null,
        ?string $location = null
    ): array;

    /**
     * Minimum number of hours a customer must book ahead of the current time.
     */
    public function leadTimeHours(): int;

    /**
     * Minutes between two consecutive slot start times.
     */
    public function slotIntervalMinutes(): int;

    /**
     * How long a job of the given service type occupies a slot.
     */
    public function durationFor(?string $serviceType): int;

    /**
     * How many jobs can run at once for the given location.
     */
    public function capacityFor(?string $location): int;

    /**
     * Whether the business takes bookings at all on the given day.
     *
     * @param DateTimeInterface $date The day to check
     */
    public function isOpenDay(\DateTimeInterface $date): bool;

    /**
     * Format a moment for display using the configured 12/24-hour preference.
     *
     * @param DateTimeInterface $moment The moment to format
     */
    public function formatTime(\DateTimeInterface $moment): string;
}
