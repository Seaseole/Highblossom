<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CompanySettingRequest;
use App\Services\CompanySettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class CompanySettingController
{
    public function __construct(
        private readonly CompanySettingService $settingService,
    ) {}

    public function index(): View
    {
        $settings = $this->settingService->getDefaultSettings();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(CompanySettingRequest $request): RedirectResponse
    {
        $this->settingService->update($request->validated(), $request);

        return redirect()->back()->with('success', __('messages.settings_saved'));
    }
}
