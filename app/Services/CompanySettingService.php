<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanySetting;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
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
    /** @var list<string> Fields that are saved as simple key-value pairs */
    private const SIMPLE_FIELDS = [
        'company_name', 'logo_text', 'primary_email', 'address', 'primary_phone',
        'whatsapp_number_default', 'timezone', 'locale', 'date_format', 'time_format',
        'time_format_display', 'currency_symbol', 'google_maps_api_key', 'map_directions_link',
        'facebook_url', 'instagram_url', 'linkedin_url', 'quote_notification_emails',
    ];

    /** @var list<string> Day keys used for working hours configuration */
    private const DAY_KEYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    /** Logo containment dimensions (max width/height). Preserves aspect ratio. */
    private const LOGO_MAX_DIMENSIONS = [512, 512];

    /** Favicon cover dimensions (square). */
    private const FAVICON_DIMENSIONS = [256, 256];

    /** Output quality for processed images (1-100). */
    private const IMAGE_QUALITY = 82;

    public function __construct(private readonly EnvEditor $envEditor) {}

    /**
     * Update company settings from form data and request.
     */
    public function update(array $data, Request $request): void
    {
        $this->handleLogoUpload($request);
        $this->handleFaviconUpload($request);
        $this->saveSimpleFields($data);
        $this->saveJsonFields($data);
        $this->handleEnvUpdate($data);
        CompanySetting::set('announcement_active', $request->boolean('announcement_active') ? '1' : '0', 'boolean');
    }

    /**
     * Persist environment variable changes from the data array.
     */
    private function handleEnvUpdate(array $data): void
    {
        if (isset($data['env']) && is_array($data['env'])) {
            foreach ($data['env'] as $key => $value) {
                // Ignore empty or null values if you want, but env vars can be empty strings.
                $this->envEditor->set((string) $key, (string) $value);
            }
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
        CompanySetting::set('gallery_metrics', $data['gallery_metrics'] ?? [], 'json');
        CompanySetting::set('announcements', $data['announcements'] ?? [], 'json');
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
     * Get all default settings merged with any stored overrides.
     */
    public function getDefaultSettings(): array
    {
        return [
            'company_name' => CompanySetting::get('company_name', 'Highblossom PTY LTD'),
            'logo_text' => CompanySetting::get('logo_text', 'Highblossom'),
            'primary_email' => CompanySetting::get('primary_email', 'info@highblossom.co.bw'),
            'address' => CompanySetting::get('address', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana'),
            'primary_phone' => CompanySetting::get('primary_phone', '+267 123 4567'),
            'whatsapp_number_default' => CompanySetting::get('whatsapp_number_default', '+267 123 4567'),
            'whatsapp_additional_numbers' => CompanySetting::get('whatsapp_additional_numbers', []),
            'working_hours' => CompanySetting::get('working_hours', $this->getDefaultWorkingHours()),
            'timezone' => CompanySetting::get('timezone', 'Africa/Gaborone'),
            'locale' => CompanySetting::get('locale', 'en_GB'),
            'date_format' => CompanySetting::get('date_format', 'd/M/Y'),
            'time_format' => CompanySetting::get('time_format', 'H:i'),
            'time_format_display' => CompanySetting::get('time_format_display', '12'),
            'currency_symbol' => CompanySetting::get('currency_symbol', 'P'),
            'business_logo' => CompanySetting::get('business_logo', ''),
            'favicon' => CompanySetting::get('favicon', ''),
            'google_maps_api_key' => CompanySetting::get('google_maps_api_key', ''),
            'map_directions_link' => CompanySetting::get('map_directions_link', 'https://maps.app.goo.gl/KJip8MytQrPrULg58'),
            'facebook_url' => CompanySetting::get('facebook_url', 'https://facebook.com'),
            'instagram_url' => CompanySetting::get('instagram_url', 'https://instagram.com'),
            'linkedin_url' => CompanySetting::get('linkedin_url', 'https://linkedin.com'),
            'quote_notification_emails' => (string) CompanySetting::get('quote_notification_emails', ''),
            'announcement_active' => CompanySetting::get('announcement_active', false),
            'announcements' => CompanySetting::get('announcements', []),
            'gallery_metrics' => CompanySetting::get('gallery_metrics', [
                ['label' => 'Vehicles Serviced', 'value' => '2,500', 'suffix' => '+'],
                ['label' => 'Heavy Machines', 'value' => '150', 'suffix' => '+'],
                ['label' => 'Fleet Accounts', 'value' => '45', 'suffix' => '+'],
            ]),
        ];
    }

    /**
     * Get default working hours for all days of the week.
     */
    private function getDefaultWorkingHours(): array
    {
        return [
            'monday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'tuesday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'wednesday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'thursday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'friday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'saturday' => ['open' => '08:00', 'close' => '12:00', 'is_closed' => false],
            'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
        ];
    }
}
