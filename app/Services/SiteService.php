<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\GalleryImage;
use App\Models\Service;
use App\Models\Testimonial;

final readonly class SiteService
{
    /**
     * Get data for the home page.
     */
    public function getHomeData(): array
    {
        return [
            'featuredTestimonial' => Testimonial::where('is_featured', true)->active()->with([])->first(),
            'otherTestimonials' => Testimonial::active()->where('is_featured', false)->ordered()->with([])->get(),
            'featuredServices' => Service::active()->ordered()->with([])->take(3)->get(),
            'featuredGalleryImages' => GalleryImage::featured()->active()->with('category')->ordered()->take(3)->get(),
            //            'workingHours' => $this->getWorkingHours(),
            'timeFormatDisplay' => CompanySetting::get('time_format_display', '24'),
        ];
    }

    /**
     * Get data for the contact page.
     */
    public function getContactData(): array
    {
        $workingHours = CompanySetting::get('working_hours', []);

        return [
            'primaryPhone' => CompanySetting::get('primary_phone', '+267 123 4567'),
            'primaryEmail' => CompanySetting::get('primary_email', 'sales@highblossom.net'),
            'workingHours' => $workingHours,
            'timeFormatDisplay' => CompanySetting::get('time_format_display', '12'),
            'hasWorkingHours' => is_array($workingHours) && ! empty($workingHours) && isset($workingHours['monday']),
            'dayOrder' => [
                'monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday',
                'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday',
            ],
            'companyName' => CompanySetting::get('company_name', 'Highblossom PTY LTD'),
            'companyAddress' => CompanySetting::get('address', 'Plot 123, Broadhurst, Gaborone, Botswana'),
            'googleMapsApiKey' => CompanySetting::get('google_maps_api_key', ''),
            'mapDirectionsLink' => CompanySetting::get('map_directions_link', 'https://maps.google.com'),
        ];
    }

    /**
     * Get working hours from settings with defaults.
     */
    //    private function getWorkingHours(): array
    //    {
    //        return CompanySetting::get('working_hours', [
    //            'monday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
    //            'tuesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
    //            'wednesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
    //            'thursday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
    //            'friday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
    //            'saturday' => ['open' => '08:00', 'close' => '12:00', 'is_closed' => true],
    //            'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
    //        ]);
    //    }
}
