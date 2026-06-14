<?php

declare(strict_types=1);

namespace App\Services\Settings;

use App\Models\CompanySetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class SettingsManager
{
    /**
     * Cache for settings to avoid multiple lookups in a single request.
     */
    private ?Collection $settings = null;

    /**
     * Get all settings as a collection.
     */
    public function all(): Collection
    {
        if ($this->settings !== null) {
            return $this->settings;
        }

        return $this->settings = collect($this->getDefaults())
            ->map(fn ($default, $key) => CompanySetting::get($key, $default));
    }

    /**
     * Get a specific setting.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()->get($key, $default);
    }

    /**
     * Magic getter for settings.
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Get default values for all settings.
     */
    public function getDefaults(): array
    {
        return [
            'company_name' => 'Highblossom PTY LTD',
            'logo_text' => 'Highblossom',
            'primary_email' => 'info@highblossom.co.bw',
            'address' => 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana',
            'primary_phone' => '+267 123 4567',
            'whatsapp_number_default' => '+267 123 4567',
            'whatsapp_additional_numbers' => [],
            'working_hours' => [
                'monday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'tuesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'wednesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'thursday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'friday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'saturday' => ['open' => '08:00', 'close' => '12:00', 'is_closed' => false],
                'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
            ],
            'time_format_display' => '12',
            'business_logo' => '',
            'favicon' => '',
            'google_maps_api_key' => '',
            'map_directions_link' => 'https://maps.app.goo.gl/KJip8MytQrPrULg58',
            'timezone' => 'Africa/Gaborone',
            'locale' => 'en_GB',
            'date_format' => 'd/M/Y',
            'time_format' => 'H:i',
            'currency_symbol' => 'P',
            'facebook_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'announcement_active' => false,
            'announcements' => [],
            'gallery_metrics' => [],
            'quote_notification_emails' => '',
        ];
    }
}
