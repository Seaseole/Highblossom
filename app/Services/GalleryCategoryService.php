<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use Illuminate\Support\Str;

/**
 * Service for managing gallery categories.
 */
final class GalleryCategoryService
{
    /**
     * Create a new gallery category.
     */
    public function create(array $data): GalleryCategory
    {
        $data['slug'] = Str::slug($data['name']);

        return GalleryCategory::create($data);
    }

    /**
     * Update an existing gallery category.
     */
    public function update(GalleryCategory $category, array $data): GalleryCategory
    {
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return $category->fresh();
    }

    /**
     * Delete a gallery category and reassign its images to the default category.
     */
    public function delete(GalleryCategory $category): void
    {
        // Reassign images to default category (other)
        $defaultCategory = GalleryCategory::where('slug', 'other')->first();

        if ($defaultCategory) {
            GalleryImage::where('gallery_category_id', $category->id)
                ->update(['gallery_category_id' => $defaultCategory->id]);
        }

        $category->delete();
    }
}
