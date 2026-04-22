<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

final class CompanySettingController
{
    public function index(): View
    {
        $settings = [
            'company_name' => CompanySetting::get('company_name', 'Highblossom PTY LTD'),
            'logo_text' => CompanySetting::get('logo_text', 'Highblossom'),
            'primary_email' => CompanySetting::get('primary_email', 'info@highblossom.co.bw'),
            'address' => CompanySetting::get('address', 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana'),
            'primary_phone' => CompanySetting::get('primary_phone', '+267 123 4567'),
            'whatsapp_number_default' => CompanySetting::get('whatsapp_number_default', '+267 123 4567'),
            'whatsapp_additional_numbers' => CompanySetting::get('whatsapp_additional_numbers', []),
            'working_hours' => CompanySetting::get('working_hours', [
                'monday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
                'tuesday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
                'wednesday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
                'thursday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
                'friday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
                'saturday' => ['open' => null, 'close' => null, 'is_closed' => true],
                'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
            ]),
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
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'logo_text' => 'nullable|string|max:255',
            'primary_email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'primary_phone' => 'required|string|max:20',
            'whatsapp_number_default' => 'required|string|max:20',
            'whatsapp_additional_numbers' => 'nullable|array',
            'whatsapp_additional_numbers.*.label' => 'required|string|max:50',
            'whatsapp_additional_numbers.*.number' => 'required|string|max:20',
            'working_hours' => 'nullable|array',
            'working_hours.*.open' => 'nullable|string|max:5',
            'working_hours.*.close' => 'nullable|string|max:5',
            'working_hours.*.is_closed' => 'nullable|boolean',
            'timezone' => 'required|string|max:100',
            'locale' => 'required|string|max:10',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:10',
            'time_format_display' => 'required|in:12,24',
            'currency_symbol' => 'required|string|max:5',
            'business_logo' => 'nullable|image|max:2048',
            'business_logo_path' => 'nullable|string',
            'favicon' => 'nullable|image|max:1024',
            'favicon_path' => 'nullable|string',
            'google_maps_api_key' => 'nullable|string|max:255',
            'map_directions_link' => 'nullable|url|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ]);

        // Handle File Uploads
        // Use AJAX uploaded path if provided, otherwise use traditional file upload
        if (!empty($validated['business_logo_path'])) {
            $oldLogo = CompanySetting::get('business_logo', '');
            if ($oldLogo && $oldLogo !== $validated['business_logo_path']) {
                Storage::disk('public')->delete($oldLogo);
            }
            CompanySetting::set('business_logo', $validated['business_logo_path']);
        } elseif ($request->hasFile('business_logo')) {
            $oldLogo = CompanySetting::get('business_logo', '');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('business_logo')->store('settings', 'public');
            CompanySetting::set('business_logo', $path);
        }

        if (!empty($validated['favicon_path'])) {
            $oldFavicon = CompanySetting::get('favicon', '');
            if ($oldFavicon && $oldFavicon !== $validated['favicon_path']) {
                Storage::disk('public')->delete($oldFavicon);
            }
            CompanySetting::set('favicon', $validated['favicon_path']);
        } elseif ($request->hasFile('favicon')) {
            $oldFavicon = CompanySetting::get('favicon', '');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $path = $request->file('favicon')->store('settings', 'public');
            CompanySetting::set('favicon', $path);
        }

        // Save other settings
        $simpleFields = [
            'company_name', 'logo_text', 'primary_email', 'address', 'primary_phone',
            'whatsapp_number_default', 'timezone', 'locale', 'date_format', 'time_format',
            'time_format_display', 'currency_symbol', 'google_maps_api_key', 'map_directions_link',
            'facebook_url', 'instagram_url', 'linkedin_url'
        ];

        foreach ($simpleFields as $field) {
            if (isset($validated[$field])) {
                CompanySetting::set($field, $validated[$field]);
            }
        }

        // Handle JSON fields
        CompanySetting::set('whatsapp_additional_numbers', $validated['whatsapp_additional_numbers'] ?? [], 'json');
        
        // Handle working_hours - ensure all days have is_closed set
        $workingHours = $validated['working_hours'] ?? [];
        $dayKeys = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($dayKeys as $day) {
            if (!isset($workingHours[$day])) {
                $workingHours[$day] = ['open' => null, 'close' => null, 'is_closed' => false];
            } else {
                // Convert checkbox value to boolean
                $workingHours[$day]['is_closed'] = isset($workingHours[$day]['is_closed']);
            }
        }
        CompanySetting::set('working_hours', $workingHours, 'json');

        return redirect()->back()->with('success', __('messages.settings_saved'));
    }
}
