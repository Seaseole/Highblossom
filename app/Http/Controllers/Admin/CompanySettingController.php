<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CompanySettingRequest;
use App\Services\CompanySettingService;
use App\Services\EnvEditor;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manage company-wide settings (general, gallery, and environment).
 */
final class CompanySettingController
{
    public function __construct(
        private readonly CompanySettingService $settingService,
        private readonly SeoService $seoService,
        private readonly EnvEditor $envEditor,
    ) {}

    /**
     * Display the company settings page.
     */
    public function index(): View
    {

        $settings = $this->settingService->getDefaultSettings();
        $availableRoutes = collect(config('seo.static_routes', []))->mapWithKeys(function ($route) {
            return [$route => $this->seoService->getRouteLabel($route)];
        })->toArray();
        $envConfig = $this->envEditor->all();

        return view('admin.settings.index', compact('settings', 'availableRoutes', 'envConfig'));
    }

    /**
     * Display the gallery settings page.
     */
    public function gallerySettings(): View
    {
        $settings = $this->settingService->getDefaultSettings();

        return view('admin.gallery.settings', compact('settings'));
    }

    /**
     * Update gallery-specific settings.
     */
    public function updateGallerySettings(CompanySettingRequest $request): RedirectResponse
    {
        $this->settingService->update($request->validated(), $request);

        return redirect()->route('admin.gallery-settings.index')->with('success', __('messages.settings_saved'));
    }

    /**
     * Update general company settings.
     */
    public function update(CompanySettingRequest $request): RedirectResponse
    {
        $this->settingService->update($request->validated(), $request);

        $redirect = redirect()->back();

        if ($request->has('tab')) {
            $redirect->withInput(['tab' => $request->input('tab')]);
            $previousUrl = url()->previous();
            $tab = $request->input('tab');

            if (str_contains($previousUrl, '?')) {
                $previousUrl = preg_replace('/tab=[^&]+/', "tab=$tab", $previousUrl);
                if (! str_contains($previousUrl, "tab=$tab")) {
                    $previousUrl .= "&tab=$tab";
                }
            } else {
                $previousUrl .= "?tab=$tab";
            }

            return redirect()->to($previousUrl)->with('success', __('messages.settings_saved'));
        }

        return $redirect->with('success', __('messages.settings_saved'));
    }
}
