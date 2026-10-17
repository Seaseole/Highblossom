<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

/**
 * Service for managing blog categories.
 */
final class CategoryService
{
    /**
     * Create a new category.
     */
    public function create(array $data): Category
    {
        $data['slug'] = $this->uniqueSlug($data['name']);

        return Category::create($data);
    }

    /**
     * Update an existing category.
     */
    public function update(Category $category, array $data): Category
    {
        $data['slug'] = $this->uniqueSlug($data['name'], $category);

        $category->update($data);

        return $category->fresh();
    }

    /**
     * Delete a category.
     */
    public function delete(Category $category): void
    {
        $category->delete();
    }

    /**
     * Build a slug that will not collide with another category's unique slug.
     */
    private function uniqueSlug(string $name, ?Category $current = null): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $suffix = 2;

        while (Category::where('slug', $slug)
            ->when($current, fn ($query) => $query->whereKeyNot($current->getKey()))
            ->exists()
        ) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
