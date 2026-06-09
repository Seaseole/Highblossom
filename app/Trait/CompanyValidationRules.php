<?php

namespace App\Trait;

trait CompanyValidationRules
{
    public function validateCompanyRules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'logo_text' => ['nullable', 'string', 'max:255'],
            'primary_email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'primary_phone' => ['required', 'string', 'max:20'],
            'whatsapp_number_default' => ['required', 'string', 'max:20'],
            'whatsapp_additional_numbers' => ['nullable', 'array'],
            'whatsapp_additional_numbers.*.label' => ['required', 'string', 'max:50'],
            'whatsapp_additional_numbers.*.number' => ['required', 'string', 'max:20'],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.open' => ['nullable', 'string', 'max:5'],
            'working_hours.*.close' => ['nullable', 'string', 'max:5'],
            'working_hours.*.is_closed' => ['nullable', 'boolean'],
            'timezone' => ['required', 'string', 'max:100'],
            'locale' => ['required', 'string', 'max:10'],
            'date_format' => ['required', 'string', 'max:20'],
            'time_format' => ['required', 'string', 'max:10'],
            'time_format_display' => ['required', 'in:12,24'],
            'currency_symbol' => ['required', 'string', 'max:5'],
            'business_logo' => ['nullable', 'image', 'max:2048'],
            'business_logo_path' => ['nullable', 'string'],
            'remove_business_logo' => ['nullable', 'boolean'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'favicon_path' => ['nullable', 'string'],
            'google_maps_api_key' => ['nullable', 'string', 'max:255'],
            'map_directions_link' => ['nullable', 'url', 'max:500'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'quote_notification_emails' => ['nullable', 'string', 'max:500'],
            'announcement_active' => ['nullable', 'boolean'],
            'announcements' => ['nullable', 'array'],
            'announcements.*.text' => ['required', 'string', 'max:500'],
            'announcements.*.link' => ['nullable', 'string', 'max:500'],
            'gallery_metrics' => ['nullable', 'array', 'max:6'],
            'gallery_metrics.*.label' => ['required', 'string', 'max:80'],
            'gallery_metrics.*.value' => ['required', 'string', 'max:20'],
            'gallery_metrics.*.suffix' => ['nullable', 'string', 'max:10'],
        ];

    }
}
