<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\TestEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Service for managing SMTP mail configuration in the .env file.
 */
final class SmtpSettingService
{
    public function __construct(
        private readonly EnvEditor $envEditor,
    ) {}

    /**
     * Update SMTP settings in the .env file.
     */
    public function update(array $data): void
    {
        $this->envEditor->set('MAIL_MAILER', $data['mail_mailer']);
        $this->envEditor->set('MAIL_HOST', $data['mail_host']);
        $this->envEditor->set('MAIL_PORT', (string) $data['mail_port']);
        $this->envEditor->set('MAIL_USERNAME', $data['mail_username'] ?? '');
        $this->envEditor->set('MAIL_PASSWORD', $data['mail_password'] ?? '');
        $this->envEditor->set('MAIL_ENCRYPTION', $data['mail_encryption'] ?? '');
        $this->envEditor->set('MAIL_FROM_ADDRESS', $data['mail_from_address']);
        $this->envEditor->set('MAIL_FROM_NAME', $data['mail_from_name']);
    }

    /**
     * Send a test email to verify SMTP configuration.
     */
    public function sendTestEmail(string $email): bool
    {
        try {
            Mail::to($email)->send(new TestEmail);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get current SMTP settings from the .env file with defaults.
     */
    public function getSettings(): array
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

        if (! isset($settings['mail_mailer'])) {
            Log::error('Settings mail_mailer missing', $settings);
        }

        return $settings;
    }
}
