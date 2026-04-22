<?php

namespace Database\Seeders;

use App\Domains\Content\Models\AboutUsContent;
use Illuminate\Database\Seeder;

class AboutUsContentSeeder extends Seeder
{
    public function run(): void
    {
        AboutUsContent::create([
            'title' => 'About Highblossom',
            'subtitle' => 'Your trusted partner for premium automotive glass solutions',
            'body' => 'Highblossom is a leading provider of automotive glass solutions in Botswana. With years of experience and a commitment to quality, we deliver exceptional service for windscreen repairs, replacements, and custom glass fittings.',
            'hero_image' => null,
            'mission' => 'To provide the highest quality automotive glass services with unmatched customer satisfaction.',
            'vision' => 'To be Botswana\'s most trusted and innovative automotive glass solutions provider.',
            'is_active' => true,
        ]);
    }
}
