<?php

namespace Database\Factories;

use App\Domains\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_name' => $this->faker->name(),
            'client_email' => $this->faker->unique()->safeEmail(),
            'client_phone' => $this->faker->phoneNumber(),
            'vehicle_details' => $this->faker->sentence(),
            'status' => 'pending',
            'total_price' => 0.00,
        ];
    }
}
