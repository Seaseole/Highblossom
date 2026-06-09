<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CompanySettingRequest;
use App\Http\Requests\CompanyValidationRules;
use App\Services\CompanySettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class CompanySettingController
{
    public function __construct(
        private readonly CompanySettingService $settingService,
        private readonly \App\Services\SeoService $seoService,
    ) {}

    public function index(): View
    {
        $settings = $this->settingService->getDefaultSettings();
        $availableRoutes = collect(config('seo.static_routes', []))->mapWithKeys(function ($route) {
            return [$route => $this->seoService->getRouteLabel($route)];
        })->toArray();

        return view('admin.settings.index', compact('settings', 'availableRoutes'));
    }

    public function gallerySettings(): View
    {
        $settings = $this->settingService->getDefaultSettings();

        return view('admin.gallery.settings', compact('settings'));
    }

    public function updateGallerySettings(CompanySettingRequest $request): RedirectResponse
    {
        $this->settingService->update($request->validated(), $request);

        return redirect()->route('admin.gallery-settings.index')->with('success', __('messages.settings_saved'));
    }

    public function update(CompanySettingRequest $request): RedirectResponse
    {
        $this->settingService->update($request->validated(), $request);

        $redirect = redirect()->back();

        if ($request->has('tab')) {
            $redirect->withInput(['tab' => $request->input('tab')]);
            // Better yet, just append it to the URL if we can,
            // but redirect()->back() usually doesn't allow easy param appending.
            // Let's use redirect()->to() with the previous URL + tab if it exists.
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
