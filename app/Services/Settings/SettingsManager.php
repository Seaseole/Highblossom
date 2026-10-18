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
            'primary_email' => 'sales@highblossom.net',
            'address' => 'Plot 22147 Gaborone West Industrial site, Gaborone, Botswana',
            'primary_phone' => '+267 311 7480',
            'secondary_phone' => '+2677773262',
            'whatsapp_number_default' => '+26773732867',
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
            'booking_lead_time_hours' => 2,
            'booking_slot_interval_minutes' => 30,
            'booking_capacity_workshop' => 1,
            'booking_capacity_mobile' => 1,
            'booking_service_durations' => [
                'windscreen' => 90,
                'side_rear' => 45,
                'heavy_machinery' => 120,
                'fleet' => 60,
            ],
            'time_format_display' => '24',
            'business_logo' => '',
            'favicon' => '',
            'google_maps_api_key' => '',
            'map_directions_link' => 'https://maps.app.goo.gl/KJip8MytQrPrULg58',
            'timezone' => 'Africa/Gaborone',
            'locale' => 'en_GB',
            'date_format' => 'd/m/Y',
            'time_format' => 'H:i',
            'currency_symbol' => 'P',
            'facebook_url' => 'https://facebook.com/profile.php?id=61569031504825',
            'instagram_url' => '',
            'linkedin_url' => '',
            'announcement_active' => false,
            'require_email_verification' => false,
            'enable_registration' => false,
            'session_idle_timeout_minutes' => 0,
            'announcements' => [],
            'gallery_metrics' => [],
            'quote_notification_emails' => '',
            'seo_meta_title' => '',
            'seo_meta_description' => '',
            'seo_meta_keywords' => '',
            'seo_meta_image' => '',
            'google_site_verification' => '',
            'home_tagline' => 'Precision Automotive Glass',
            'hero_image' => '',
            'home_years_experience' => '20+',
            'home_fact_tiles' => [
                ['label' => 'Years in business', 'value' => '20', 'suffix' => '+'],
                ['label' => 'Workmanship warranty', 'value' => 'Lifetime', 'suffix' => ''],
                ['label' => 'Response time', 'value' => '24', 'suffix' => 'h'],
                ['label' => 'Mobile fitting', 'value' => 'Same', 'suffix' => ' day'],
            ],
            'home_warranty_heading' => 'Lifetime Workmanship Warranty',
            'home_warranty_text' => 'Every windscreen, side screen and rear glass we fit is covered for as long as you own the vehicle. We use original-equipment or certified equivalent glass bonded to the manufacturer spec, so if a seal leaks, a bond fails or you can hear a wind whistle, we refit it at no cost.',
            'home_warranty_highlights' => [
                ['title' => 'Mobile installation', 'text' => 'Fully equipped fitting vans come to your home, office or yard anywhere in Gaborone.'],
                ['title' => 'ADAS calibration', 'text' => 'After any windscreen swap we re-check and recalibrate the camera and sensor setup, so lane assist and emergency braking stay truthful.'],
                ['title' => 'Free no-obligation quote', 'text' => 'Send your vehicle details and get a firm price before you commit to anything.'],
            ],
            'home_urgency_enabled' => true,
            'home_urgency_text' => 'Cracked windscreen? Same-day mobile fitting.',
            'home_cta_heading' => 'Get your free quote today',
            'home_cta_text' => 'Tell us the vehicle, the glass and where you are parked. We will come back with a firm price and the next available fitting slot.',
            'home_final_cta_heading' => 'Ready for Crystal Clear Vision?',
            'home_final_cta_text' => 'Get a free quote today. Our team is ready to help with all your automotive glass needs.',
            'home_service_area' => 'Gaborone, Gabane, Molepolole, Lobatse and the surrounding Kweneng and South East districts.',
            'home_mobile_coverage' => 'Mobile units cover Greater Gaborone, or visit our Gaborone West Industrial workshop.',
            'home_whatsapp_message' => 'I would like a quote for automotive glass work.',
            'home_process_steps' => [
                ['title' => 'Consultation', 'text' => 'We assess your vehicle and the damage, then give you a transparent, free, no-obligation quote.'],
                ['title' => 'Precision preparation', 'text' => 'Our technicians prepare your high-quality glass using state-of-the-art tools and materials.'],
                ['title' => 'Quality installation', 'text' => 'Expert fitting — at the workshop or on your driveway — with a full safety and visibility check before we hand back the keys.'],
            ],
            'home_faqs' => [
                [
                    'question' => 'Do you handle insurance claims?',
                    'answer' => 'Yes. If your windscreen is covered, we claim directly with your insurer, supply the excess report, and follow up so you are not chasing paperwork. Send your policy details with the quote request and we will confirm cover before we start.',
                ],
                [
                    'question' => 'Can a chip be repaired, or does the whole windscreen need replacing?',
                    'answer' => 'Chips smaller than a two-centimetre coin, and cracks under roughly 150 mm that do not run to the edge of the glass, can usually be resin-repaired. Anything in the driver\'s line of sight, in the bond line, or crossing the screen needs a full replacement. We will tell you which applies before quoting.',
                ],
                [
                    'question' => 'How long does a fit take, and when can I drive?',
                    'answer' => 'A standard replacement takes about 90 minutes, plus the urethane cure time. Most vehicles are safe to drive after one hour; if your car needs ADAS camera recalibration, allow a little longer. We give you the drive-away time in writing when we hand the vehicle back.',
                ],
                [
                    'question' => 'What does the warranty cover?',
                    'answer' => 'Our workmanship warranty runs for as long as you own the vehicle and covers leaks, wind noise, bond failure and ADAS calibration faults. Glass itself carries the manufacturer warranty, typically against defects in materials and fit.',
                ],
            ],
        ];
    }
}
