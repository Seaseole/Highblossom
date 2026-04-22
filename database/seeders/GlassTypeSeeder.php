<?php

namespace Database\Seeders;

use App\Domains\Content\Models\GlassType;
use Illuminate\Database\Seeder;

class GlassTypeSeeder extends Seeder
{
    public function run(): void
    {
        $glassTypes = [
            ['name' => 'Acrylic', 'slug' => 'acrylic', 'sort_order' => 1],
            ['name' => 'Aluminium', 'slug' => 'aluminium', 'sort_order' => 2],
            ['name' => 'Bilston', 'slug' => 'bilston', 'sort_order' => 3],
            ['name' => 'Brown', 'slug' => 'brown', 'sort_order' => 4],
            ['name' => 'Dome', 'slug' => 'dome', 'sort_order' => 5],
            ['name' => 'Electric', 'slug' => 'electric', 'sort_order' => 6],
            ['name' => 'Frosted', 'slug' => 'frosted', 'sort_order' => 7],
            ['name' => 'Hard', 'slug' => 'hard', 'sort_order' => 8],
            ['name' => 'Laminated', 'slug' => 'laminated', 'sort_order' => 9],
            ['name' => 'Lexan', 'slug' => 'lexan', 'sort_order' => 10],
            ['name' => 'Mirror', 'slug' => 'mirror', 'sort_order' => 11],
            ['name' => 'Plastic', 'slug' => 'plastic', 'sort_order' => 12],
            ['name' => 'Round', 'slug' => 'round', 'sort_order' => 13],
            ['name' => 'Safety', 'slug' => 'safety', 'sort_order' => 14],
            ['name' => 'Shot Blast', 'slug' => 'shot-blast', 'sort_order' => 15],
            ['name' => 'Soda Lime', 'slug' => 'soda-lime', 'sort_order' => 16],
            ['name' => 'Solid', 'slug' => 'solid', 'sort_order' => 17],
            ['name' => 'Toughened', 'slug' => 'toughened', 'sort_order' => 18],
            ['name' => 'Tritan', 'slug' => 'tritan', 'sort_order' => 19],
            ['name' => 'Vacuum', 'slug' => 'vacuum', 'sort_order' => 20],
            ['name' => 'Windscreen', 'slug' => 'windscreen', 'sort_order' => 21],
            ['name' => 'Side Window', 'slug' => 'side-window', 'sort_order' => 22],
            ['name' => 'Rear Window', 'slug' => 'rear-window', 'sort_order' => 23],
            ['name' => 'Quarter Glass', 'slug' => 'quarter-glass', 'sort_order' => 24],
            ['name' => 'Bus Glass', 'slug' => 'bus-glass', 'sort_order' => 25],
        ];

        foreach ($glassTypes as $glassType) {
            GlassType::firstOrCreate(
                ['slug' => $glassType['slug']],
                $glassType
            );
        }
    }
}
