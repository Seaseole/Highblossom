<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
final class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->paragraph(3),
            'featured_image' => 'https://picsum.photos/seed/' . $this->faker->word . '/800/400',
            'author_id' => User::factory(),
            'category_id' => null,
            'seo_metadata' => [
                'meta_title' => $title,
                'meta_description' => $this->faker->sentence(),
            ],
            'is_published' => true,
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_featured' => $this->faker->boolean(20),
            'reading_time_minutes' => $this->faker->numberBetween(3, 15),
        ];
    }

    public function published(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => now()->subDays(rand(1, 30)),
        ]);
    }

    public function draft(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function featured(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
