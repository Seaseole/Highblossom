<?php

declare(strict_types=1);

namespace App\Http\View\Composers;

use App\Services\Settings\SettingsManager;
use Illuminate\View\View;

final readonly class GlobalSettingsComposer
{
    public function __construct(
        private SettingsManager $settings
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $settings = $this->settings->all();
        $view->with('settings', $settings);

        // Map snake_case settings to camelCase variables expected by views
        $view->with([
            'companyName' => $settings->get('company_name'),
            'logoText' => $settings->get('logo_text'),
            'primaryEmail' => $settings->get('primary_email'),
            'companyAddress' => $settings->get('address'),
            'primaryPhone' => $settings->get('primary_phone'),
            'whatsappDefault' => $settings->get('whatsapp_number_default'),
            'whatsappAdditional' => $settings->get('whatsapp_additional_numbers'),
            'workingHours' => $settings->get('working_hours'),
            'timeFormatDisplay' => $settings->get('time_format_display'),
            'businessLogo' => $settings->get('business_logo'),
            'favicon' => $settings->get('favicon'),
            'googleMapsApiKey' => $settings->get('google_maps_api_key'),
            'mapDirectionsLink' => $settings->get('map_directions_link'),
            'timezone' => $settings->get('timezone'),
            'locale' => $settings->get('locale'),
            'dateFormat' => $settings->get('date_format'),
            'timeFormat' => $settings->get('time_format'),
            'currencySymbol' => $settings->get('currency_symbol'),
            'facebookUrl' => $settings->get('facebook_url'),
            'instagramUrl' => $settings->get('instagram_url'),
            'linkedinUrl' => $settings->get('linkedin_url'),
            'announcementActive' => (bool) $settings->get('announcement_active'),
            'announcements' => $settings->get('announcements'),
            'galleryMetrics' => $settings->get('gallery_metrics'),
        ]);
    }
}
