<?php

namespace Database\Seeders;

use App\Domains\Content\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        GalleryImage::create([
            'title' => 'Mining Excavator Cabin',
            'description' => 'Custom toughened glass fitment for CAT 320D excavator at Jwaneng Mine',
            'image_path' => 'gallery/excavator-cabin.jpg',
            'category' => 'heavy_machinery',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
            'latitude' => -24.5333,
            'longitude' => 24.6833,
            'location_address' => 'Jwaneng Mine, Botswana',
        ]);

        GalleryImage::create([
            'title' => 'Luxury Windshield Replacement',
            'description' => 'Premium OEM windshield replacement for Mercedes-Benz S-Class',
            'image_path' => 'gallery/luxury-windshield.jpg',
            'category' => 'automotive',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 2,
            'latitude' => -24.6532,
            'longitude' => 25.9087,
            'location_address' => 'Main Mall, Gaborone, Botswana',
        ]);

        GalleryImage::create([
            'title' => 'Commercial Fleet Service',
            'description' => 'Complete glass replacement for 15-vehicle delivery fleet',
            'image_path' => 'gallery/fleet-service.jpg',
            'category' => 'fleet',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 3,
            'latitude' => -24.6282,
            'longitude' => 25.9231,
            'location_address' => 'Broadhurst Industrial, Gaborone, Botswana',
        ]);

        GalleryImage::create([
            'title' => 'Loader Windshield',
            'description' => 'Heavy-duty laminated glass for Komatsu WA500 loader',
            'image_path' => 'gallery/loader-windshield.jpg',
            'category' => 'heavy_machinery',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 4,
            'latitude' => -21.9833,
            'longitude' => 27.4833,
            'location_address' => 'Francistown, Botswana',
        ]);

        GalleryImage::create([
            'title' => 'Sports Car Glass',
            'description' => 'Specialized tinted glass installation for Porsche 911',
            'image_path' => 'gallery/sports-car.jpg',
            'category' => 'automotive',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 5,
            'latitude' => -24.6532,
            'longitude' => 25.9087,
            'location_address' => 'CBD, Gaborone, Botswana',
        ]);

        GalleryImage::create([
            'title' => 'Truck Fleet Maintenance',
            'description' => 'Scheduled glass maintenance for long-haul truck fleet',
            'image_path' => 'gallery/truck-fleet.jpg',
            'category' => 'fleet',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 6,
            'latitude' => -22.0000,
            'longitude' => 23.5000,
            'location_address' => 'Lobatse, Botswana',
        ]);

        GalleryImage::create([
            'title' => 'Bulldozer Cab Glass',
            'description' => 'Armored glass installation for Caterpillar D6 bulldozer',
            'image_path' => 'gallery/bulldozer-cab.jpg',
            'category' => 'heavy_machinery',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 7,
            'latitude' => -24.5333,
            'longitude' => 24.6833,
            'location_address' => 'Mmamabula Mine, Botswana',
        ]);

        GalleryImage::create([
            'title' => 'SUV Side Windows',
            'description' => 'Tinted side window replacement for Toyota Land Cruiser',
            'image_path' => 'gallery/suv-windows.jpg',
            'category' => 'automotive',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 8,
            'latitude' => -24.6532,
            'longitude' => 25.9087,
            'location_address' => 'Phakalane, Gaborone, Botswana',
        ]);

        GalleryImage::create([
            'title' => 'Bus Fleet Project',
            'description' => 'Complete glass overhaul for 20-seater bus fleet',
            'image_path' => 'gallery/bus-fleet.jpg',
            'category' => 'fleet',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 9,
            'latitude' => -25.0167,
            'longitude' => 25.6500,
            'location_address' => 'Kanye, Botswana',
        ]);
    }
}
