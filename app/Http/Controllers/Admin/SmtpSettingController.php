<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Mail\TestEmail;
use App\Services\EnvEditor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

final class SmtpSettingController
{
    public function __construct(
        private EnvEditor $envEditor
    ) {}

    public function index(): View
    {
        $settings = [
            'mail_mailer' => $this->envEditor->get('MAIL_MAILER', 'smtp'),
            'mail_host' => $this->envEditor->get('MAIL_HOST', ''),
            'mail_port' => $this->envEditor->get('MAIL_PORT', '587'),
            'mail_username' => $this->envEditor->get('MAIL_USERNAME', ''),
            'mail_password' => $this->envEditor->get('MAIL_PASSWORD', ''),
            'mail_encryption' => $this->envEditor->get('MAIL_ENCRYPTION', 'tls'),
            'mail_from_address' => $this->envEditor->get('MAIL_FROM_ADDRESS', ''),
            'mail_from_name' => $this->envEditor->get('MAIL_FROM_NAME', config('app.name')),
        ];

        return view('admin.smtp.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|string|max:50',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:10|in:tls,ssl,null',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        $this->envEditor->set('MAIL_MAILER', $validated['mail_mailer']);
        $this->envEditor->set('MAIL_HOST', $validated['mail_host']);
        $this->envEditor->set('MAIL_PORT', (string) $validated['mail_port']);
        $this->envEditor->set('MAIL_USERNAME', $validated['mail_username'] ?? '');
        $this->envEditor->set('MAIL_PASSWORD', $validated['mail_password'] ?? '');
        $this->envEditor->set('MAIL_ENCRYPTION', $validated['mail_encryption'] ?? '');
        $this->envEditor->set('MAIL_FROM_ADDRESS', $validated['mail_from_address']);
        $this->envEditor->set('MAIL_FROM_NAME', $validated['mail_from_name']);

        return redirect()->back()->with('success', 'SMTP settings saved successfully.');
    }

    public function sendTest(Request $request): RedirectResponse
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::to($request->input('test_email'))->send(new TestEmail());

            return redirect()->back()->with('success', 'Test email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
