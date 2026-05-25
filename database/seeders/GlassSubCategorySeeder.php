<?php

namespace Database\Seeders;

use App\Models\GlassSubCategory;
use App\Models\GlassType;
use Illuminate\Database\Seeder;

class GlassSubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $glassTypes = GlassType::all()->keyBy('slug');

        $subCategories = [
            // Windshield sub-categories
            'windshield' => [
                ['name' => 'Front Windshield', 'sort_order' => 1],
                ['name' => 'Rear Windshield', 'sort_order' => 2],
                ['name' => 'Heated Windshield', 'sort_order' => 3],
                ['name' => 'Tinted Windshield', 'sort_order' => 4],
            ],

            // Door Glass sub-categories
            'door-glass' => [
                ['name' => 'Left Front Door Glass', 'sort_order' => 1],
                ['name' => 'Right Front Door Glass', 'sort_order' => 2],
                ['name' => 'Left Rear Door Glass', 'sort_order' => 3],
                ['name' => 'Right Rear Door Glass', 'sort_order' => 4],
                ['name' => 'Driver Door Glass', 'sort_order' => 5],
                ['name' => 'Passenger Door Glass', 'sort_order' => 6],
            ],

            // Window Glass sub-categories
            'window-glass' => [
                ['name' => 'Left Front Window', 'sort_order' => 1],
                ['name' => 'Right Front Window', 'sort_order' => 2],
                ['name' => 'Left Rear Window', 'sort_order' => 3],
                ['name' => 'Right Rear Window', 'sort_order' => 4],
                ['name' => 'Quarter Window', 'sort_order' => 5],
                ['name' => 'Vent Window', 'sort_order' => 6],
            ],

            // Rear Glass sub-categories
            'rear-glass' => [
                ['name' => 'Rear Window', 'sort_order' => 1],
                ['name' => 'Rear Windshield', 'sort_order' => 2],
                ['name' => 'Back Glass', 'sort_order' => 3],
                ['name' => 'Hatchback Glass', 'sort_order' => 4],
            ],

            // Side Mirror sub-categories
            'side-mirror' => [
                ['name' => 'Left Side Mirror', 'sort_order' => 1],
                ['name' => 'Right Side Mirror', 'sort_order' => 2],
                ['name' => 'Driver Side Mirror', 'sort_order' => 3],
                ['name' => 'Passenger Side Mirror', 'sort_order' => 4],
                ['name' => 'Heated Side Mirror', 'sort_order' => 5],
            ],

            // Sunroof sub-categories
            'sunroof' => [
                ['name' => 'Front Sunroof', 'sort_order' => 1],
                ['name' => 'Rear Sunroof', 'sort_order' => 2],
                ['name' => 'Panoramic Sunroof', 'sort_order' => 3],
                ['name' => 'Moonroof', 'sort_order' => 4],
            ],

            // Quarter Glass sub-categories
            'quarter-glass' => [
                ['name' => 'Left Quarter Glass', 'sort_order' => 1],
                ['name' => 'Right Quarter Glass', 'sort_order' => 2],
                ['name' => 'Fixed Quarter Glass', 'sort_order' => 3],
                ['name' => 'Vent Quarter Glass', 'sort_order' => 4],
            ],

            // Specialty Glass sub-categories
            'specialty-glass' => [
                ['name' => 'Headlight Glass', 'sort_order' => 1],
                ['name' => 'Taillight Glass', 'sort_order' => 2],
                ['name' => 'Fog Light Glass', 'sort_order' => 3],
                ['name' => 'Turn Signal Glass', 'sort_order' => 4],
                ['name' => 'Backup Light Glass', 'sort_order' => 5],
            ],
        ];

        foreach ($subCategories as $glassTypeSlug => $categories) {
            if (! $glassTypes->has($glassTypeSlug)) {
                continue;
            }

            $glassType = $glassTypes->get($glassTypeSlug);

            foreach ($categories as $categoryData) {
                GlassSubCategory::create([
                    'glass_type_id' => $glassType->id,
                    'name' => $categoryData['name'],
                    'slug' => str($categoryData['name'])->slug()->toString(),
                    'sort_order' => $categoryData['sort_order'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
