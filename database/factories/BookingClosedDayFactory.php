<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BookingClosedDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingClosedDay>
 */
class BookingClosedDayFactory extends Factory
{
    protected $model = BookingClosedDay::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => now()->addDays($this->faker->numberBetween(1, 30))->toDateString(),
            'reason' => $this->faker->randomElement(['Public holiday', 'Stock take', 'Training day']),
        ];
    }
}
