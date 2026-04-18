<?php

namespace Database\Factories;

use App\Domains\Bookings\Models\StaffAbsence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffAbsence>
 */
class StaffAbsenceFactory extends Factory
{
    protected $model = StaffAbsence::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'staff_id' => \App\Models\User::factory(),
            'starts_at' => now()->addDay()->setHour(8)->setMinute(0),
            'ends_at' => now()->addDay()->setHour(17)->setMinute(0),
            'reason' => $this->faker->sentence(),
        ];
    }
}
