<?php

namespace App\Actions\Bookings;

use App\Domains\Bookings\Models\Booking;
use App\Domains\Bookings\Events\BookingCreatedEvent;
use App\Infrastructure\Contracts\AvailabilityServiceInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateBookingAction
{
    public function __construct(
        private readonly AvailabilityServiceInterface $availabilityService
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(array $data): Booking
    {
        if (! $this->availabilityService->isSlotAvailable($data['scheduled_at'])) {
            throw new \Exception('Slot is no longer available.');
        }

        return DB::transaction(function () use ($data) {
            $booking = Booking::create($data);

            BookingCreatedEvent::dispatch($booking);

            return $booking;
        });
    }
}
