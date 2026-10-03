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
     */
    public function slotStatus(\DateTimeInterface|string $scheduledAt): string;

    public function isSlotAvailable(\DateTimeInterface|string $scheduledAt): bool;

    /**
     * Check availability while holding a row/gap lock, for use inside a transaction.
     */
    public function isSlotAvailableForUpdate(\DateTimeInterface|string $scheduledAt): bool;

    /**
     * Every hour-aligned slot on the given date, each with a display label and status.
     *
     * @return list<array{time: string, label: string, available: bool, status: string}>
     */
    public function slotsForDate(\DateTimeInterface|string $date): array;

    /**
     * The first hour-aligned slot that could possibly be bookable, or null past the horizon.
     */
    public function earliestBookableSlot(): ?CarbonInterface;

    /**
     * The next open days that still have at least one bookable slot.
     *
     * @return list<array{date: string, day: string, weekday: string, month: string, is_today: bool, first_slot: string}>
     */
    public function upcomingOpenDays(int $count): array;

    /**
     * Minimum number of hours a customer must book ahead of the current time.
     */
    public function leadTimeHours(): int;

    /**
     * Whether the business takes bookings at all on the given day.
     */
    public function isOpenDay(\DateTimeInterface $date): bool;

    /**
     * Format a moment for display using the configured 12/24-hour preference.
     */
    public function formatTime(\DateTimeInterface $moment): string;
}
