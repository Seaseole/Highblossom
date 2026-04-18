<?php

namespace Database\Factories;

use App\Domains\Bookings\Models\Inspection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inspection>
 */
class InspectionFactory extends Factory
{
    protected $model = Inspection::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scheduledAt = now()->addDay()->setHour(10)->setMinute(0)->setSecond(0);
        return [
            'booking_id' => \App\Domains\Bookings\Models\Booking::factory(),
            'staff_id' => \App\Models\User::factory(),
            'scheduled_at' => $scheduledAt,
            'ended_at' => $scheduledAt->copy()->addHour(),
            'location' => 'workshop',
            'type' => 'workshop',
        ];
    }
}
