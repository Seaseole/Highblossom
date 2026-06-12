<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanySetting;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[Singleton(name: 'company_settings')]
final class CompanySettingService
{
    private const SIMPLE_FIELDS = [
        'company_name', 'logo_text', 'primary_email', 'address', 'primary_phone',
        'whatsapp_number_default', 'timezone', 'locale', 'date_format', 'time_format',
        'time_format_display', 'currency_symbol', 'google_maps_api_key', 'map_directions_link',
        'facebook_url', 'instagram_url', 'linkedin_url', 'quote_notification_emails',
    ];

    private const DAY_KEYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function update(array $data, Request $request): void
    {
        $this->handleLogoUpload($request);
        $this->handleFaviconUpload($request);
        $this->saveSimpleFields($data);
        $this->saveJsonFields($data);
        CompanySetting::set('announcement_active', $request->boolean('announcement_active') ? '1' : '0', 'boolean');
    }

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

        // Branch 3: Direct file upload
        if ($request->hasFile('business_logo') && $request->file('business_logo')->isValid()) {
            $this->deleteStoredImage($oldLogo);
            $path = $request->file('business_logo')->store('settings', 'public');
            CompanySetting::set('business_logo', $path);
        }
    }

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

    private function handleFaviconUpload(Request $request): void
    {
        $imagePath = $request->input('favicon_path');

        if (! empty($imagePath)) {
            $oldFavicon = CompanySetting::get('favicon', '');
            try {
                if ($oldFavicon && $oldFavicon !== $imagePath && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
            } catch (\Exception $e) {
                Log::error('Failed to remove old favicon: '.$e->getMessage());
            }
            CompanySetting::set('favicon', $imagePath);
        } elseif ($request->hasFile('favicon')) {
            $oldFavicon = CompanySetting::get('favicon', '');
            try {
                if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
            } catch (\Exception $e) {
                Log::error('Failed to remove old favicon: '.$e->getMessage());
            }
            $path = $request->file('favicon')->store('settings', 'public');
            CompanySetting::set('favicon', $path);
        }
    }

    private function saveSimpleFields(array $data): void
    {
        foreach (self::SIMPLE_FIELDS as $field) {
            if (array_key_exists($field, $data)) {
                CompanySetting::set($field, $data[$field]);
            }
        }
    }

    private function saveJsonFields(array $data): void
    {
        CompanySetting::set('whatsapp_additional_numbers', $data['whatsapp_additional_numbers'] ?? [], 'json');
        CompanySetting::set('working_hours', $this->prepareWorkingHours($data['working_hours'] ?? []), 'json');
        CompanySetting::set('gallery_metrics', $data['gallery_metrics'] ?? [], 'json');
        CompanySetting::set('announcements', $data['announcements'] ?? [], 'json');
    }

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
