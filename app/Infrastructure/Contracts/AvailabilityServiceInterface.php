<?php

namespace App\Infrastructure\Contracts;

interface AvailabilityServiceInterface
{
    public function isSlotAvailable(\DateTimeInterface|string $scheduledAt): bool;
}
