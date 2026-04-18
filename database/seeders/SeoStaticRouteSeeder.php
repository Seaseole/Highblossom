<?php

namespace Database\Seeders;

use App\Domains\Content\Models\SeoStaticRoute;
use Illuminate\Database\Seeder;

class SeoStaticRouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            [
                'route_name' => 'home',
                'meta_title' => 'Highblossom PTY LTD | Precision Automotive Glass',
                'meta_description' => 'Premium automotive glass solutions and heavy machinery glazing for Gaborone\'s most discerning vehicle owners and commercial fleets. 20+ years of experience.',
                'og_type' => 'website',
                'priority' => 1.0,
                'changefreq' => 'weekly',
            ],
            [
                'route_name' => 'services',
                'meta_title' => 'Our Services',
                'meta_description' => 'Comprehensive automotive glass services including windscreens, side & rear glass, heavy machinery glazing, and ADAS calibration for all vehicle types.',
                'og_type' => 'website',
                'priority' => 0.8,
                'changefreq' => 'weekly',
            ],
            [
                'route_name' => 'gallery',
                'meta_title' => 'Project Gallery',
                'meta_description' => 'Browse our portfolio of automotive glass installations. See our precision work on luxury vehicles, commercial fleets, and heavy machinery.',
                'og_type' => 'website',
                'priority' => 0.6,
                'changefreq' => 'weekly',
            ],
            [
                'route_name' => 'quote',
                'meta_title' => 'Get a Quote',
                'meta_description' => 'Request a free quote for your automotive glass needs. Mobile service available across Gaborone. Fast response times.',
                'og_type' => 'website',
                'priority' => 0.9,
                'changefreq' => 'monthly',
            ],
            [
                'route_name' => 'contact',
                'meta_title' => 'Contact Us',
                'meta_description' => 'Get in touch with Highblossom PTY LTD for all your automotive glass needs. Visit our Gaborone workshop or request mobile service.',
                'og_type' => 'website',
                'priority' => 0.7,
                'changefreq' => 'monthly',
            ],
        ];

        foreach ($routes as $route) {
            SeoStaticRoute::firstOrCreate(
                ['route_name' => $route['route_name']],
                $route
            );
        }
    }
}
