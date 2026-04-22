<?php

namespace Database\Seeders;

use App\Domains\Content\Models\GalleryCategory;
use Illuminate\Database\Seeder;

class GalleryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Heavy Machinery',
                'slug' => 'heavy_machinery',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Fleet',
                'slug' => 'fleet',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            GalleryCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
