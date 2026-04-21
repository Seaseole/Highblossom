<?php

namespace Database\Factories;

use App\Domains\Content\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug(),
            'icon' => $this->faker->randomElement(['wrench', 'cog', 'star', 'car', 'tools', 'shield']),
            'short_description' => $this->faker->sentence(),
            'full_description' => $this->faker->paragraphs(3, true),
            'features' => $this->faker->words(4, false),
            'image_url' => $this->faker->imageUrl(800, 600),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
