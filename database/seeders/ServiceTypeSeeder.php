<?php

namespace Database\Seeders;

use App\Domains\Content\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $serviceTypes = [
            ['name' => 'Full Replacement', 'slug' => 'full-replacement', 'sort_order' => 1],
            ['name' => 'Chip Repair', 'slug' => 'chip-repair', 'sort_order' => 2],
            ['name' => 'Inspection', 'slug' => 'inspection', 'sort_order' => 3],
            ['name' => 'Emergency', 'slug' => 'emergency', 'sort_order' => 4],
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::firstOrCreate(
                ['slug' => $serviceType['slug']],
                $serviceType
            );
        }
    }
}
