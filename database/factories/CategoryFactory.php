<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Content\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
final class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->words(rand(1, 3), true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->optional()->sentence(),
            'type' => $this->faker->randomElement(['post', 'page']),
            'sort_order' => 0,
            'seo_metadata' => null,
        ];
    }

    public function forPosts(): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'post',
        ]);
    }

    public function forPages(): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'page',
        ]);
    }
}
