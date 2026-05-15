<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use Illuminate\Support\Str;

final class GalleryCategoryService
{
    public function create(array $data): GalleryCategory
    {
        $data['slug'] = Str::slug($data['name']);

        return GalleryCategory::create($data);
    }

    public function update(GalleryCategory $category, array $data): GalleryCategory
    {
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return $category->fresh();
    }

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
