<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SmtpSettingRequest;
use App\Http\Requests\Admin\TestEmailRequest;
use App\Services\SmtpSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class SmtpSettingController
{
    public function __construct(
        private readonly SmtpSettingService $smtpService,
    ) {}

    public function index(): View
    {
        $settings = $this->smtpService->getSettings();

        return view('admin.smtp.index', compact('settings'));
    }

    public function update(SmtpSettingRequest $request): RedirectResponse
    {
        $this->smtpService->update($request->validated());

        return redirect()->back()->with('success', 'SMTP settings saved successfully.');
    }

    public function sendTest(TestEmailRequest $request): RedirectResponse
    {
        $success = $this->smtpService->sendTestEmail($request->input('test_email'));

        if ($success) {
            return redirect()->back()->with('success', 'Test email sent successfully!');
        }

        return redirect()->back()->with('error', 'Failed to send test email.');
    }
}
