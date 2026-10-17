<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $serviceTypes = [
            ['name' => 'Chip & Crack Repair', 'slug' => 'chip-crack-repair', 'sort_order' => 1],
            ['name' => 'Full Replacement', 'slug' => 'full-replacement', 'sort_order' => 2],
            ['name' => 'ADAS Calibration', 'slug' => 'adas-calibration', 'sort_order' => 3],
            ['name' => 'Seal & Leak Reglazing', 'slug' => 'seal-leak-reglazing', 'sort_order' => 4],
            ['name' => 'Custom Cut & Fit', 'slug' => 'custom-cut-fit', 'sort_order' => 5],
            ['name' => 'Tinting & Solar Film', 'slug' => 'tinting-solar-film', 'sort_order' => 6],
            ['name' => 'Emergency Board-Up', 'slug' => 'emergency-board-up', 'sort_order' => 7],
            ['name' => 'Fleet Glass Programme', 'slug' => 'fleet-glass-programme', 'sort_order' => 8],
            ['name' => 'Architectural Glazing', 'slug' => 'architectural-glazing', 'sort_order' => 9],
            ['name' => 'Mirror Reglass', 'slug' => 'mirror-reglass', 'sort_order' => 10],
            ['name' => 'Headlight Lens Restoration', 'slug' => 'headlight-lens-restoration', 'sort_order' => 11],
            ['name' => 'Insurance Claim Assessment', 'slug' => 'insurance-claim-assessment', 'sort_order' => 12],
            ['name' => 'After-Sales Warranty Re-Fit', 'slug' => 'after-sales-warranty-re-fit', 'sort_order' => 13],
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::firstOrCreate(
                ['slug' => $serviceType['slug']],
                $serviceType + ['is_active' => true]
            );
        }
    }
}
