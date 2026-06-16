<?php

declare(strict_types=1);

namespace App\Services\Contracts;

/**
 * Interface for availability checking services.
 */
interface AvailabilityServiceInterface
{
    /**
     * Check if a given time slot is available for booking.
     *
     * @param \DateTimeInterface|string $scheduledAt The date/time to check
     */
    public function isSlotAvailable(\DateTimeInterface|string $scheduledAt): bool;
}
