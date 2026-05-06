<?php

declare(strict_types=1);

namespace App\Actions;

use App\Domains\Bookings\Models\Quote;
use App\Domains\Content\Models\CompanySetting;
use App\Mail\QuoteSubmittedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final readonly class SendQuoteEmailNotificationsAction
{
    /**
     * Send queued email notifications for the quote.
     *
     * @param Quote $quote The quote to send notifications for
     * @return void
     */
    public function execute(Quote $quote): void
    {
        try {
            $smtpSource = $this->configureSmtp();

            // Get admin notification emails
            $notificationEmails = $this->getNotificationEmails();

            // Send notifications
            $this->sendEmails($quote, $notificationEmails);

            Log::info('Quote email notifications queued', [
                'quote_id' => $quote->id,
                'admin_count' => count($notificationEmails),
                'customer_notified' => !empty($quote->email),
                'smtp_source' => $smtpSource,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue quote email notifications: ' . $e->getMessage(), [
                'quote_id' => $quote->id,
            ]);
        }
    }

    /**
     * Configure SMTP based on company settings or fallback to env.
     */
    private function configureSmtp(): string
    {
        $smtpHost = CompanySetting::get('smtp_host');

        if (empty($smtpHost)) {
            return 'env';
        }

        config([
            'mail.mailers.smtp.host' => $smtpHost,
            'mail.mailers.smtp.port' => CompanySetting::get('smtp_port', 587),
            'mail.mailers.smtp.username' => CompanySetting::get('smtp_username'),
            'mail.mailers.smtp.password' => CompanySetting::get('smtp_password'),
            'mail.mailers.smtp.encryption' => CompanySetting::get('smtp_encryption', 'tls'),
            'mail.from.address' => CompanySetting::get('smtp_username'),
            'mail.from.name' => CompanySetting::get('company_name', 'Highblossom'),
        ]);

        return 'company_settings';
    }

    /**
     * Send queued emails to admin and customer.
     */
    private function sendEmails(Quote $quote, array $adminEmails): void
    {
        $primaryPhone = CompanySetting::get('primary_phone');
        $primaryEmail = CompanySetting::get('primary_email');

        // Queue admin notifications
        foreach ($adminEmails as $email) {
            Mail::to($email)->queue(new QuoteSubmittedMail(
                quote: $quote,
                recipientType: 'admin',
                primaryPhone: $primaryPhone,
                primaryEmail: $primaryEmail
            ));
        }

        // Queue customer confirmation if email provided
        if (!empty($quote->email)) {
            Mail::to($quote->email)->queue(new QuoteSubmittedMail(
                quote: $quote,
                recipientType: 'customer',
                primaryPhone: $primaryPhone,
                primaryEmail: $primaryEmail
            ));
        }
    }

    /**
     * Get list of admin notification emails.
     *
     * @return array<string>
     */
    private function getNotificationEmails(): array
    {
        $emailsSetting = CompanySetting::get('quote_notification_emails');

        if (!empty($emailsSetting)) {
            $emails = array_map('trim', explode(',', $emailsSetting));
            return array_filter($emails, fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL));
        }

        // Fallback to primary email
        $primaryEmail = CompanySetting::get('primary_email');
        if (!empty($primaryEmail)) {
            return [$primaryEmail];
        }

        return [];
    }

    public function __invoke(Quote $quote): void
    {
        $this->execute($quote);
    }
}
