<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

/**
 * Service for managing blog tags.
 */
final class TagService
{
    /**
     * Create a new tag.
     */
    public function create(array $data): Tag
    {
        $data['slug'] = $this->uniqueSlug($data['name']);

        return Tag::create($data);
    }

    /**
     * Update an existing tag.
     */
    public function update(Tag $tag, array $data): Tag
    {
        $data['slug'] = $this->uniqueSlug($data['name'], $tag);

        $tag->update($data);

        return $tag->fresh();
    }

    /**
     * Delete a tag.
     */
    public function delete(Tag $tag): void
    {
        $tag->delete();
    }

    /**
     * Build a slug that will not collide with another tag's unique slug.
     */
    private function uniqueSlug(string $name, ?Tag $current = null): string
    {
        $base = Str::slug($name) ?: 'tag';
        $slug = $base;
        $suffix = 2;

        while (Tag::where('slug', $slug)
            ->when($current, fn ($query) => $query->whereKeyNot($current->getKey()))
            ->exists()
        ) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
