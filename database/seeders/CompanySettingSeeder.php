<?php

namespace Database\Seeders;

use App\Domains\Content\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'company_name' => ['value' => 'Highblossom PTY LTD', 'type' => 'text'],
            'logo_text' => ['value' => 'Highblossom', 'type' => 'text'],
            'primary_email' => ['value' => 'sales@highblossom.net', 'type' => 'text'],
            'address' => ['value' => 'Plot 22147 Gaborone West Industrial site, Gaborone, Botswana', 'type' => 'text'],
            'primary_phone' => ['value' => '+267 123 4567', 'type' => 'text'],
            'whatsapp_number_default' => ['value' => '+267 123 4567', 'type' => 'text'],
            'whatsapp_additional_numbers' => ['value' => [], 'type' => 'json'],
            'working_hours' => ['value' => [
                'monday' => ['open' => '07:30', 'close' => '17:00', 'is_closed' => false],
                'tuesday' => ['open' => '07:30', 'close' => '17:00', 'is_closed' => false],
                'wednesday' => ['open' => '07:30', 'close' => '17:00', 'is_closed' => false],
                'thursday' => ['open' => '07:30', 'close' => '17:00', 'is_closed' => false],
                'friday' => ['open' => '07:30', 'close' => '17:00', 'is_closed' => false],
                'saturday' => ['open' => '08:00', 'close' => '13:00', 'is_closed' => false],
                'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
            ], 'type' => 'json'],
            'timezone' => ['value' => 'Africa/Gaborone', 'type' => 'text'],
            'locale' => ['value' => 'en_GB', 'type' => 'text'],
            'date_format' => ['value' => 'd/M/Y', 'type' => 'text'],
            'time_format' => ['value' => 'H:i', 'type' => 'text'],
            'time_format_display' => ['value' => '12', 'type' => 'text'],
            'currency_symbol' => ['value' => 'P', 'type' => 'text'],
            'business_logo' => ['value' => 'settings/M3SNQTUYfWG1K0QZvCJUV8yau8qNbAlyAuf2wEV3.jpg', 'type' => 'text'],
            'favicon' => ['value' => '', 'type' => 'text'],
            'google_maps_api_key' => ['value' => '', 'type' => 'text'],
            'map_directions_link' => ['value' => 'https://maps.app.goo.gl/KJip8MytQrPrULg58', 'type' => 'text'],
            'facebook_url' => ['value' => 'https://facebook.com/profile.php?id=61569031504825', 'type' => 'text'],
            'instagram_url' => ['value' => 'https://instagram.com', 'type' => 'text'],
            'linkedin_url' => ['value' => 'https://linkedin.com', 'type' => 'text'],
        ];

        foreach ($settings as $key => $data) {
            CompanySetting::set($key, $data['value'], $data['type']);
        }
    }
}
