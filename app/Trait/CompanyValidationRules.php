<?php

declare(strict_types=1);

namespace App\Trait;

use App\Rules\SafeStoredUploadPath;

/**
 * Validation rules for company settings.
 *
 * Provides a reusable set of validation rules for company configuration fields.
 */
trait CompanyValidationRules
{
    /**
     * Get the validation rules for company settings.
     *
     * @return array<string, mixed>
     */
    public function validateCompanyRules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'logo_text' => ['nullable', 'string', 'max:255'],
            'primary_email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'primary_phone' => ['required', 'string', 'max:20'],
            'secondary_phone' => ['nullable', 'string', 'max:20'],
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
            'booking_lead_time_hours' => ['required', 'integer', 'min:0', 'max:24'],
            'booking_slot_interval_minutes' => ['nullable', 'integer', 'min:15', 'max:240'],
            'booking_capacity_workshop' => ['nullable', 'integer', 'min:0', 'max:20'],
            'booking_capacity_mobile' => ['nullable', 'integer', 'min:0', 'max:20'],
            'booking_service_durations' => ['nullable', 'array'],
            'booking_service_durations.*' => ['nullable', 'integer', 'min:15', 'max:480'],
            'currency_symbol' => ['required', 'string', 'max:5'],
            'business_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'business_logo_path' => ['nullable', 'string', new SafeStoredUploadPath(['settings', 'uploads', 'uploads/images'])],
            'remove_business_logo' => ['nullable', 'boolean'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'dimensions:min_width=128,min_height=128', 'max:1024'],
            'favicon_path' => ['nullable', 'string', new SafeStoredUploadPath(['settings', 'uploads', 'uploads/images'])],
            'remove_favicon' => ['nullable', 'boolean'],
            'google_maps_api_key' => ['nullable', 'string', 'max:255'],
            'map_directions_link' => ['nullable', 'url', 'max:500'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'quote_notification_emails' => ['nullable', 'string', 'max:500'],
            'require_email_verification' => ['nullable', 'in:0,1'],
            'enable_registration' => ['nullable', 'boolean'],
            'session_idle_timeout_minutes' => ['nullable', 'integer', 'min:0', 'max:4320'],
            'announcement_active' => ['nullable', 'boolean'],
            'announcements' => ['nullable', 'array'],
            'announcements.*.text' => ['required', 'string', 'max:500'],
            'announcements.*.link' => ['nullable', 'string', 'max:500'],
            'announcements.*.expires_at' => ['nullable', 'date'],
            'gallery_metrics' => ['nullable', 'array', 'max:6'],
            'gallery_metrics.*.label' => ['required', 'string', 'max:80'],
            'gallery_metrics.*.value' => ['required', 'string', 'max:20'],
            'gallery_metrics.*.suffix' => ['nullable', 'string', 'max:10'],
            'home_tagline' => ['nullable', 'string', 'max:120'],
            'home_years_experience' => ['nullable', 'string', 'max:20'],
            'home_warranty_heading' => ['nullable', 'string', 'max:120'],
            'home_warranty_text' => ['nullable', 'string', 'max:1000'],
            'home_urgency_enabled' => ['nullable', 'boolean'],
            'home_urgency_text' => ['nullable', 'string', 'max:200'],
            'home_cta_heading' => ['nullable', 'string', 'max:120'],
            'home_cta_text' => ['nullable', 'string', 'max:1000'],
            'home_final_cta_heading' => ['nullable', 'string', 'max:120'],
            'home_final_cta_text' => ['nullable', 'string', 'max:1000'],
            'home_service_area' => ['nullable', 'string', 'max:500'],
            'home_mobile_coverage' => ['nullable', 'string', 'max:500'],
            'home_whatsapp_message' => ['nullable', 'string', 'max:300'],
            'hero_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'hero_image_path' => ['nullable', 'string', new SafeStoredUploadPath(['settings', 'uploads', 'uploads/images', 'uploads/videos', 'uploads/videos/thumbnails'])],
            'remove_hero_image' => ['nullable', 'boolean'],
            'home_fact_tiles' => ['nullable', 'array', 'max:4'],
            'home_fact_tiles.*.label' => ['required', 'string', 'max:60'],
            'home_fact_tiles.*.value' => ['required', 'string', 'max:30'],
            'home_fact_tiles.*.suffix' => ['nullable', 'string', 'max:10'],
            'home_warranty_highlights' => ['nullable', 'array', 'max:3'],
            'home_warranty_highlights.*.title' => ['required', 'string', 'max:80'],
            'home_warranty_highlights.*.text' => ['required', 'string', 'max:400'],
            'home_process_steps' => ['nullable', 'array', 'max:4'],
            'home_process_steps.*.title' => ['required', 'string', 'max:80'],
            'home_process_steps.*.text' => ['required', 'string', 'max:500'],
            'home_faqs' => ['nullable', 'array', 'max:8'],
            'home_faqs.*.question' => ['required', 'string', 'max:200'],
            'home_faqs.*.answer' => ['required', 'string', 'max:2000'],
            'env' => ['nullable', 'array', 'max:30'],
            'env.*' => ['nullable', 'string', 'max:1000'],
        ];

    }
}
