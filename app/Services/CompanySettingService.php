<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BookingServiceType;
use App\Models\CompanySetting;
use App\Services\Settings\SettingsManager;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Service for managing company-wide settings including logos, favicons,
 * working hours, social links, and environment configuration.
 */
#[Singleton(name: 'company_settings')]
final class CompanySettingService
{
    /** @var list<string> Scalar homepage copy fields saved straight to the settings store */
    private const HOME_SIMPLE_FIELDS = [
        'home_tagline', 'home_years_experience', 'home_warranty_heading', 'home_warranty_text',
        'home_urgency_text', 'home_cta_heading', 'home_cta_text', 'home_service_area',
        'home_mobile_coverage', 'home_whatsapp_message', 'home_final_cta_heading', 'home_final_cta_text',
    ];

    /** @var list<string> Homepage repeater fields stored as JSON arrays */
    private const HOME_JSON_FIELDS = [
        'home_fact_tiles', 'home_warranty_highlights', 'home_process_steps', 'home_faqs',
    ];

    /** @var list<string> Fields that are saved as simple key-value pairs */
    private const SIMPLE_FIELDS = [
        'company_name', 'logo_text', 'primary_email', 'address', 'primary_phone', 'secondary_phone',
        'whatsapp_number_default', 'timezone', 'locale', 'date_format', 'time_format',
        'time_format_display', 'currency_symbol', 'google_maps_api_key', 'map_directions_link',
        'booking_lead_time_hours', 'booking_slot_interval_minutes', 'booking_capacity_workshop',
        'booking_capacity_mobile', 'require_email_verification', 'session_idle_timeout_minutes',
        'facebook_url', 'instagram_url', 'linkedin_url', 'quote_notification_emails',
        ...self::HOME_SIMPLE_FIELDS,
    ];

    /** @var list<string> Day keys used for working hours configuration */
    private const DAY_KEYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    /** Logo containment dimensions (max width/height). Preserves aspect ratio. */
    private const LOGO_MAX_DIMENSIONS = [512, 512];

    /** Favicon cover dimensions (square). */
    private const FAVICON_DIMENSIONS = [256, 256];

    /** Hero banner cover dimensions. Cropped to fill the full-bleed desktop hero. */
    private const HERO_MAX_DIMENSIONS = [1920, 1080];

    /** Output quality for processed images (1-100). */
    private const IMAGE_QUALITY = 82;

    public function __construct(
        private readonly EnvEditor $envEditor,
        private readonly SettingsManager $settingsManager,
    ) {}

    /**
     * Update company settings from form data and request.
     */
    public function update(array $data, Request $request): void
    {
        $this->handleLogoUpload($request);
        $this->handleFaviconUpload($request);
        $this->handleHeroImageUpload($request);
        $this->saveSimpleFields($data);
        $this->saveJsonFields($data);
        $this->handleEnvUpdate($data);
        CompanySetting::set('announcement_active', $request->boolean('announcement_active') ? '1' : '0', 'boolean');
        CompanySetting::set('enable_registration', $request->boolean('enable_registration') ? '1' : '0', 'boolean');
        CompanySetting::set('home_urgency_enabled', $request->boolean('home_urgency_enabled') ? '1' : '0', 'boolean');
    }

    /**
     * Persist environment variable changes from the data array.
     *
     * Only keys in {@see EnvEditor::SAFE_EDITABLE_KEYS} are written; unknown or
     * secret keys from request input are silently ignored so a compromised
     * admin session cannot overwrite APP_KEY, DB_*, etc.
     */
    private function handleEnvUpdate(array $data): void
    {
        if (! isset($data['env']) || ! is_array($data['env'])) {
            return;
        }

        if (Gate::denies('manage environment')) {
            return;
        }

        $changed = false;

        foreach ($data['env'] as $key => $value) {
            // setEditable() rejects any key outside the admin allowlist.
            if ($this->envEditor->setEditable((string) $key, (string) $value)) {
                $changed = true;
            }
        }

        if ($changed) {
            // Drop the cached config so newly written values take effect.
            Artisan::call('config:clear');
        }
    }

    /**
     * Handle business logo upload, replacement, or removal.
     *
     * Three branches:
     *  1. Explicit removal request -> delete the stored image and clear the field.
     *  2. Pre-uploaded path provided (e.g. from the AJAX media uploader) -> adopt it,
     *     deleting the previous file if it has changed.
     *  3. Direct file upload -> process through Laravel 13.20 `Image` facade (contain
     *     within 512x512, normalised to WebP at fixed quality) and store on the public
     *     disk, deleting the previous file if present.
     */
    private function handleLogoUpload(Request $request): void
    {
        $oldLogo = CompanySetting::get('business_logo', '');

        // Branch 1: Explicit removal requested
        if ($request->boolean('remove_business_logo')) {
            $this->deleteStoredImage($oldLogo);
            CompanySetting::set('business_logo', '');

            return;
        }

        // Branch 2: Pre-uploaded path provided (e.g. from a temp upload or CDN)
        $imagePath = $request->input('business_logo_path');

        if (filled($imagePath)) {
            if ($oldLogo && $oldLogo !== $imagePath) {
                $this->deleteStoredImage($oldLogo);
            }
            CompanySetting::set('business_logo', $imagePath);

            return;
        }

        // Branch 3: Direct file upload - process and normalise before storage.
        if ($request->hasFile('business_logo') && $request->file('business_logo')->isValid()) {
            try {
                $this->deleteStoredImage($oldLogo);
                $file = $request->file('business_logo');
                $path = Image::fromUpload($file)
                    ->contain(self::LOGO_MAX_DIMENSIONS[0], self::LOGO_MAX_DIMENSIONS[1])
                    ->toWebp()
                    ->quality(self::IMAGE_QUALITY)
                    ->store('settings', 'public');

                if ($path !== false) {
                    CompanySetting::set('business_logo', $path);
                }
            } catch (ImageException $e) {
                Log::error('Failed to process logo upload: '.$e->getMessage());
            }
        }
    }

    /**
     * Handle business hero banner upload, replacement, or removal.
     *
     * Mirrors the logo branches: explicit removal, adoption of a pre-uploaded
     * media-library path, or a direct upload normalised to WebP on the public disk.
     */
    private function handleHeroImageUpload(Request $request): void
    {
        $oldImage = (string) CompanySetting::get('hero_image', '');

        if ($request->boolean('remove_hero_image')) {
            $this->deleteStoredImage($oldImage);
            CompanySetting::set('hero_image', '');

            return;
        }

        $imagePath = $request->input('hero_image_path');

        if (filled($imagePath)) {
            if ($oldImage !== '' && $oldImage !== $imagePath) {
                $this->deleteStoredImage($oldImage);
            }
            CompanySetting::set('hero_image', $imagePath);

            return;
        }

        if ($request->hasFile('hero_image') && $request->file('hero_image')->isValid()) {
            try {
                $this->deleteStoredImage($oldImage);
                $path = Image::fromUpload($request->file('hero_image'))
                    ->cover(self::HERO_MAX_DIMENSIONS[0], self::HERO_MAX_DIMENSIONS[1])
                    ->toWebp()
                    ->quality(self::IMAGE_QUALITY)
                    ->store('settings', 'public');

                if ($path !== false) {
                    CompanySetting::set('hero_image', $path);
                }
            } catch (ImageException $e) {
                Log::error('Failed to process hero image upload: '.$e->getMessage());
            }
        }
    }

    /**
     * Delete a stored image from the public disk if it exists.
     */
    private function deleteStoredImage(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            Log::warning('Could not delete stored image', [
                'path' => $path,
                'reason' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle favicon upload, replacement, or removal.
     *
     * Two branches:
     *  1. Pre-uploaded path provided -> adopt it, deleting the previous file if changed.
     *  2. Direct file upload -> process through Laravel 13.20 `Image` facade (cover to
     *     256x256 square, normalised to WebP at fixed quality) and store on the public
     *     disk, deleting the previous file if present.
     */
    private function handleFaviconUpload(Request $request): void
    {
        $imagePath = $request->input('favicon_path');

        if (! empty($imagePath)) {
            $oldFavicon = CompanySetting::get('favicon', '');
            try {
                if ($oldFavicon && $oldFavicon !== $imagePath) {
                    $this->deleteStoredImage($oldFavicon);
                }
            } catch (\Exception $e) {
                Log::error('Failed to remove old favicon: '.$e->getMessage());
            }
            CompanySetting::set('favicon', $imagePath);
        } elseif ($request->hasFile('favicon')) {
            $oldFavicon = CompanySetting::get('favicon', '');
            try {
                if ($oldFavicon) {
                    $this->deleteStoredImage($oldFavicon);
                }
                $file = $request->file('favicon');
                $path = Image::fromUpload($file)
                    ->cover(self::FAVICON_DIMENSIONS[0], self::FAVICON_DIMENSIONS[1])
                    ->toWebp()
                    ->quality(self::IMAGE_QUALITY)
                    ->store('settings', 'public');

                if ($path !== false) {
                    CompanySetting::set('favicon', $path);
                }
            } catch (ImageException $e) {
                Log::error('Failed to process favicon upload: '.$e->getMessage());
            }
        }
    }

    /**
     * Persist simple scalar fields to the company settings store.
     */
    private function saveSimpleFields(array $data): void
    {
        foreach (self::SIMPLE_FIELDS as $field) {
            if (array_key_exists($field, $data)) {
                CompanySetting::set($field, $data[$field]);
            }
        }
    }

    /**
     * Persist JSON-encoded array fields to the company settings store.
     */
    private function saveJsonFields(array $data): void
    {
        CompanySetting::set('whatsapp_additional_numbers', $data['whatsapp_additional_numbers'] ?? [], 'json');
        CompanySetting::set('working_hours', $this->prepareWorkingHours($data['working_hours'] ?? []), 'json');
        CompanySetting::set('booking_service_durations', $this->prepareDurations($data['booking_service_durations'] ?? []), 'json');
        CompanySetting::set('gallery_metrics', $data['gallery_metrics'] ?? [], 'json');
        CompanySetting::set('announcements', $data['announcements'] ?? [], 'json');

        foreach (self::HOME_JSON_FIELDS as $field) {
            CompanySetting::set($field, $this->pruneRows($data[$field] ?? []), 'json');
        }
    }

    /**
     * Drop empty repeater rows so an unsaved admin tab cannot blank a section's
     * fallback copy, and so nothing renders a tile or accordion with no content.
     *
     * @param array<array<string, string>> $rows
     *
     * @return list<array<string, string>>
     */
    private function pruneRows(array $rows): array
    {
        return collect($rows)
            ->filter(fn ($row) => is_array($row) && collect($row)->filter(fn ($value) => filled($value))->isNotEmpty())
            ->map(fn ($row) => array_map(fn ($value) => is_array($value) ? $value : trim((string) $value), $row))
            ->values()
            ->all();
    }

    /**
     * Normalize and fill defaults for the working hours array.
     */
    private function prepareWorkingHours(array $workingHours): array
    {
        foreach (self::DAY_KEYS as $day) {
            if (! isset($workingHours[$day])) {
                $workingHours[$day] = ['open' => null, 'close' => null, 'is_closed' => false];
            } else {
                $workingHours[$day]['is_closed'] = isset($workingHours[$day]['is_closed']);
            }
        }

        return $workingHours;
    }

    /**
     * Normalise the per-service durations, keyed by every bookable service.
     *
     * A service missing from the form keeps its enum default rather than collapsing
     * to zero, because a zero-minute job would make every slot on the calendar
     * overlap into the next one.
     *
     * @param array<string, mixed> $durations
     *
     * @return array<string, int>
     */
    private function prepareDurations(array $durations): array
    {
        $prepared = [];

        foreach (BookingServiceType::selectable() as $type) {
            $value = (int) ($durations[$type->value] ?? $type->defaultDurationMinutes());

            $prepared[$type->value] = min(480, max(15, $value ?: $type->defaultDurationMinutes()));
        }

        return $prepared;
    }

    /**
     * The durations used before an admin has saved the tab.
     *
     * @return array<string, int>
     */
    private function getDefaultDurations(): array
    {
        return collect(BookingServiceType::selectable())
            ->mapWithKeys(fn (BookingServiceType $type): array => [$type->value => $type->defaultDurationMinutes()])
            ->all();
    }

    /**
     * Get all default settings merged with any stored overrides.
     */
    public function getDefaultSettings(): array
    {
        return [
            'company_name' => CompanySetting::get('company_name', 'Highblossom PTY LTD'),
            'logo_text' => CompanySetting::get('logo_text', 'Highblossom'),
            'primary_email' => CompanySetting::get('primary_email', 'sales@highblossom.net'),
            'address' => CompanySetting::get('address', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana'),
            'primary_phone' => CompanySetting::get('primary_phone', '+267 123 4567'),
            'secondary_phone' => CompanySetting::get('secondary_phone', ''),
            'whatsapp_number_default' => CompanySetting::get('whatsapp_number_default', '+267 123 4567'),
            'whatsapp_additional_numbers' => CompanySetting::get('whatsapp_additional_numbers', []),
            'working_hours' => CompanySetting::get('working_hours', $this->getDefaultWorkingHours()),
            'timezone' => CompanySetting::get('timezone', 'Africa/Gaborone'),
            'locale' => CompanySetting::get('locale', 'en_GB'),
            'date_format' => CompanySetting::get('date_format', 'd/M/Y'),
            'time_format' => CompanySetting::get('time_format', 'H:i'),
            'time_format_display' => CompanySetting::get('time_format_display', '12'),
            'booking_lead_time_hours' => CompanySetting::get('booking_lead_time_hours', 2),
            'booking_slot_interval_minutes' => CompanySetting::get('booking_slot_interval_minutes', 30),
            'booking_capacity_workshop' => CompanySetting::get('booking_capacity_workshop', 1),
            'booking_capacity_mobile' => CompanySetting::get('booking_capacity_mobile', 1),
            'booking_service_durations' => CompanySetting::get('booking_service_durations', $this->getDefaultDurations()),
            'currency_symbol' => CompanySetting::get('currency_symbol', 'P'),
            'business_logo' => CompanySetting::get('business_logo', ''),
            'favicon' => CompanySetting::get('favicon', ''),
            'google_maps_api_key' => CompanySetting::get('google_maps_api_key', ''),
            'map_directions_link' => CompanySetting::get('map_directions_link', 'https://maps.app.goo.gl/KJip8MytQrPrULg58'),
            'facebook_url' => CompanySetting::get('facebook_url', 'https://facebook.com'),
            'instagram_url' => CompanySetting::get('instagram_url', 'https://instagram.com'),
            'linkedin_url' => CompanySetting::get('linkedin_url', 'https://linkedin.com'),
            'quote_notification_emails' => (string) CompanySetting::get('quote_notification_emails', ''),
            'require_email_verification' => filter_var(CompanySetting::get('require_email_verification', '0'), FILTER_VALIDATE_BOOLEAN),
            'enable_registration' => filter_var(CompanySetting::get('enable_registration', '0'), FILTER_VALIDATE_BOOLEAN),
            'session_idle_timeout_minutes' => (int) CompanySetting::get('session_idle_timeout_minutes', 0),
            'announcement_active' => CompanySetting::get('announcement_active', false),
            'announcements' => CompanySetting::get('announcements', []),
            'gallery_metrics' => CompanySetting::get('gallery_metrics', [
                ['label' => 'Vehicles Serviced', 'value' => '2,500', 'suffix' => '+'],
                ['label' => 'Heavy Machines', 'value' => '150', 'suffix' => '+'],
                ['label' => 'Fleet Accounts', 'value' => '45', 'suffix' => '+'],
            ]),
            ...$this->homepageSettings(),
        ];
    }

    /**
     * Read every homepage content setting for the admin form.
     *
     * Defaults come from SettingsManager so the admin screen and the public
     * view can never drift onto two separate copies of the same copy.
     *
     * @return array<string, mixed>
     */
    private function homepageSettings(): array
    {
        $sharedDefaults = $this->settingsManager->getDefaults();
        $keys = [...self::HOME_SIMPLE_FIELDS, ...self::HOME_JSON_FIELDS, 'hero_image'];

        $settings = [];

        foreach ($keys as $key) {
            $settings[$key] = CompanySetting::get($key, $sharedDefaults[$key] ?? null);
        }

        $settings['home_urgency_enabled'] = filter_var(
            CompanySetting::get('home_urgency_enabled', $sharedDefaults['home_urgency_enabled'] ?? true),
            FILTER_VALIDATE_BOOLEAN
        );

        return $settings;
    }

    /**
     * Get default working hours for all days of the week.
     *
     * Delegates to SettingsManager so the admin screen and the availability
     * API can never drift apart on two separate hardcoded copies.
     */
    private function getDefaultWorkingHours(): array
    {
        return $this->settingsManager->getDefaults()['working_hours'];
    }
}
