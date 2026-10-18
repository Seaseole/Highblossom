<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GalleryJourneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = GalleryCategory::first();
        $categoryId = $category ? $category->id : 1;

        $journeys = [
            ['title' => 'Initial Assessment', 'description' => 'Starting the journey with a careful crack inspection on a vintage windshield.'],
            ['title' => 'Precision Cleaning', 'description' => 'Clearing debris to ensure a perfect resin bond.'],
            ['title' => 'Resin Injection', 'description' => 'The heart of the repair: filling the chip with high-grade optical resin.'],
            ['title' => 'Curing Process', 'description' => 'Using UV light to solidify the repair and restore structural integrity.'],
            ['title' => 'Final Polish', 'description' => 'Polishing the glass for a crystal-clear finish, marking the end of the repair journey.'],
        ];

        foreach ($journeys as $index => $data) {
            GalleryImage::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'image_path' => 'placeholder.gif',
                'source' => GalleryImage::SOURCE_GALLERY,
                'gallery_category_id' => $categoryId,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
