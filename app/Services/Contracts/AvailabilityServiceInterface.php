<?php

namespace App\Services\Contracts;

interface AvailabilityServiceInterface
{
    public function isSlotAvailable(\DateTimeInterface|string $scheduledAt): bool;
}
