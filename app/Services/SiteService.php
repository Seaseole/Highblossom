<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GalleryImage;
use App\Models\Service;
use App\Models\Testimonial;
use App\Services\Settings\SettingsManager;

final readonly class SiteService
{
    public function __construct(
        private SettingsManager $settings
    ) {}

    /**
     * Get data for the home page.
     */
    public function getHomeData(): array
    {
        return [
            'featuredTestimonial' => Testimonial::where('is_featured', true)->active()->first(),
            'otherTestimonials' => Testimonial::active()->where('is_featured', false)->ordered()->get(),
            'featuredServices' => Service::active()->ordered()->take(3)->get(),
            'featuredGalleryImages' => GalleryImage::featured()->active()->with('category')->ordered()->take(3)->get(),
            //            'workingHours' => $this->getWorkingHours(),
            'timeFormatDisplay' => $this->settings->time_format_display,
        ];
    }

    /**
     * Get data for the contact page.
     */
    public function getContactData(): array
    {
        $workingHours = $this->settings->working_hours;

        return [
            'primaryPhone' => $this->settings->primary_phone,
            'primaryEmail' => $this->settings->primary_email,
            'workingHours' => $workingHours,
            'timeFormatDisplay' => $this->settings->time_format_display,
            'hasWorkingHours' => is_array($workingHours) && ! empty($workingHours) && isset($workingHours['monday']),
            'dayOrder' => [
                'monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday',
                'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday',
            ],
            'companyName' => $this->settings->company_name,
            'companyAddress' => $this->settings->address,
            'googleMapsApiKey' => $this->settings->google_maps_api_key,
            'mapDirectionsLink' => $this->settings->map_directions_link,
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
