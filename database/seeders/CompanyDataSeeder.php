<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class CompanyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCompanySettings();
        $this->seedTestimonials();
    }

    private function seedCompanySettings(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'Highblossom PTY LTD', 'type' => 'text'],
            ['key' => 'logo_text', 'value' => 'Highblossom', 'type' => 'text'],
            ['key' => 'primary_email', 'value' => 'sales@highblossom.net', 'type' => 'text'],
            ['key' => 'address', 'value' => 'Plot 22147 Gaborone West Industrial site, Gaborone, Botswana', 'type' => 'text'],
            ['key' => 'primary_phone', 'value' => '+267 311 7480', 'type' => 'text'],
            ['key' => 'secondary_phone', 'value' => '+2677773262', 'type' => 'text'],
            ['key' => 'whatsapp_number_default', 'value' => '+26773732867', 'type' => 'text'],
            ['key' => 'whatsapp_additional_numbers', 'value' => [], 'type' => 'json'],
            ['key' => 'working_hours', 'value' => [
                'monday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'tuesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'wednesday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'thursday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'friday' => ['open' => '08:00', 'close' => '17:00', 'is_closed' => false],
                'saturday' => ['open' => '08:00', 'close' => '12:00', 'is_closed' => false],
                'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
            ], 'type' => 'json'],
            ['key' => 'booking_lead_time_hours', 'value' => '2', 'type' => 'text'],
            ['key' => 'booking_slot_interval_minutes', 'value' => '30', 'type' => 'number'],
            ['key' => 'booking_capacity_workshop', 'value' => '1', 'type' => 'number'],
            ['key' => 'booking_capacity_mobile', 'value' => '1', 'type' => 'number'],
            ['key' => 'booking_service_durations', 'value' => [
                'windscreen' => 90,
                'side_rear' => 45,
                'heavy_machinery' => 120,
                'fleet' => 60,
            ], 'type' => 'json'],
            ['key' => 'timezone', 'value' => 'Africa/Gaborone', 'type' => 'text'],
            ['key' => 'locale', 'value' => 'en_GB', 'type' => 'text'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'type' => 'text'],
            ['key' => 'time_format', 'value' => 'H:i', 'type' => 'text'],
            ['key' => 'time_format_display', 'value' => '24', 'type' => 'text'],
            ['key' => 'currency_symbol', 'value' => 'P', 'type' => 'text'],
            // Environment-specific values: the API key is a credential and the logo/favicon
            // live under storage(app/public), which is gitignored — both stay empty here.
            ['key' => 'business_logo', 'value' => '', 'type' => 'text'],
            ['key' => 'favicon', 'value' => '', 'type' => 'text'],
            ['key' => 'google_maps_api_key', 'value' => '', 'type' => 'text'],
            ['key' => 'map_directions_link', 'value' => 'https://maps.app.goo.gl/KJip8MytQrPrULg58', 'type' => 'text'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/profile.php?id=61569031504825', 'type' => 'text'],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'text'],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'text'],
            ['key' => 'quote_notification_emails', 'value' => '', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            CompanySetting::set($setting['key'], $setting['value'], $setting['type']);
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'Thabo Molefe',
                'role' => 'Operations Manager, Local Mining Co.',
                'rating' => 5,
                'content' => 'Highblossom handled my mining fleet glass replacements with incredible speed. The on-time delivery isn\'t just a marketing slogan; it\'s their standard. Exceptional service in Gaborone.',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sarah Johnson',
                'role' => 'Fleet Manager, Express Logistics',
                'rating' => 5,
                'content' => 'We\'ve been using Highblossom for our entire fleet for over 3 years. Their mobile service saves us downtime and their quality is unmatched.',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'David Kgosi',
                'role' => 'Construction Site Supervisor',
                'rating' => 5,
                'content' => 'When our excavator\'s cabin glass was damaged, Highblossom came to our site and replaced it the same day. Professional and efficient!',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
