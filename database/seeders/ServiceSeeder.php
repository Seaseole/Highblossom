<?php

namespace Database\Seeders;

use App\Domains\Content\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Windscreens',
                'slug' => 'windscreens',
                'icon' => '',
                'short_description' => 'OEM-quality windscreen installations for all vehicle makes and models. From minor chip repairs to complete replacements.',
                'full_description' => 'OEM-quality windscreen installations for all vehicle makes and models. From minor chip repairs to complete replacements, we ensure perfect fitment and safety compliance.',
                'features' => [
                    'Same-day service available',
                    'OEM & aftermarket options',
                    'ADAS calibration included',
                    'Lifetime workmanship warranty'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Side & Rear Windows',
                'slug' => 'side-rear-windows',
                'icon' => '',
                'short_description' => 'Full replacement of tempered and laminated side windows, heated rear screens, and quarter glass.',
                'full_description' => 'Full replacement of tempered and laminated side windows, heated rear screens, and quarter glass. We handle all vehicle types from sedans to SUVs.',
                'features' => [
                    'Tempered safety glass',
                    'Heated rear screens',
                    'Privacy tinting options',
                    'Quick turnaround'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800&q=80',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Heavy Machinery',
                'slug' => 'heavy-machinery',
                'icon' => '',
                'short_description' => 'Specialized toughened glass solutions for mining, construction, and agricultural equipment.',
                'full_description' => 'Specialized toughened glass solutions for mining, construction, and agricultural equipment. Custom fabricated to your exact specifications.',
                'features' => [
                    'Excavators & bulldozers',
                    'Cranes & lifts',
                    'Agricultural machinery',
                    'Mining equipment specialists'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Fleet Services',
                'slug' => 'fleet-services',
                'icon' => '',
                'short_description' => 'Dedicated fleet maintenance programs for commercial vehicle operators.',
                'full_description' => 'Dedicated fleet maintenance programs for commercial vehicle operators. Volume pricing and priority scheduling to keep your fleet on the road.',
                'features' => [
                    'Volume pricing discounts',
                    'Priority scheduling',
                    'Account management',
                    'Regular maintenance programs'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800&q=80',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Mobile Service',
                'slug' => 'mobile-service',
                'icon' => '',
                'short_description' => "Can't come to us? We'll come to you. Our fully-equipped mobile units serve Gaborone and surrounding areas.",
                'full_description' => "Can't come to us? We'll come to you. Our fully-equipped mobile units serve Gaborone and surrounding areas with the same quality service.",
                'features' => [
                    'On-site replacement',
                    'Gaborone metro area coverage',
                    'Same-day service',
                    'No additional call-out fee'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=800&q=80',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Emergency Repairs',
                'slug' => 'emergency-repairs',
                'icon' => '',
                'short_description' => 'Broken glass can\'t wait. Our emergency response team provides rapid assessment and immediate solutions.',
                'full_description' => 'Broken glass can\'t wait. Our emergency response team provides rapid assessment and immediate solutions to get you back on the road safely.',
                'features' => [
                    '24/7 availability',
                    'Rapid response times',
                    'Temporary safety solutions',
                    'Insurance claim assistance'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800&q=80',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}
