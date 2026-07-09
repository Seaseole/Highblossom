<?php

declare(strict_types=1);

namespace App\Services\Contracts;

interface AvailabilityServiceInterface
{
    public function isSlotAvailable(\DateTimeInterface|string $scheduledAt): bool;

    /**
     * Get available 1-hour time slots for a given date.
     *
     * @param \DateTimeInterface                                                           $date         The date to check
     * @param array<string, array{open: string|null, close: string|null, is_closed: bool}> $workingHours
     * @param string                                                                       $timezone     Timezone for slot generation
     *
     * @return array<string, bool> Associative array of 'HH:MM' => availability
     */
    public function getAvailableSlots(\DateTimeInterface $date, array $workingHours, string $timezone = 'UTC'): array;
}
