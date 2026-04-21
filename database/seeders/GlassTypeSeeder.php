<?php

namespace Database\Seeders;

use App\Domains\Content\Models\GlassType;
use Illuminate\Database\Seeder;

class GlassTypeSeeder extends Seeder
{
    public function run(): void
    {
        $glassTypes = [
            ['name' => 'Windscreen', 'slug' => 'windscreen', 'sort_order' => 1],
            ['name' => 'Side Window', 'slug' => 'side-window', 'sort_order' => 2],
            ['name' => 'Rear Window', 'slug' => 'rear-window', 'sort_order' => 3],
            ['name' => 'Quarter Glass', 'slug' => 'quarter-glass', 'sort_order' => 4],
            ['name' => 'Machinery', 'slug' => 'machinery', 'sort_order' => 5],
            ['name' => 'Other', 'slug' => 'other', 'sort_order' => 6],
        ];

        foreach ($glassTypes as $glassType) {
            GlassType::firstOrCreate(
                ['slug' => $glassType['slug']],
                $glassType
            );
        }
    }
}
