<?php

namespace App\Actions;

use App\Domains\Bookings\Models\Quote;
use App\Domains\Content\Models\CompanySetting;
use App\Mail\QuoteSubmittedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StoreQuoteAction
{
    public function __construct(
        protected Quote $quote
    ) {}

    public function execute(Request $request): array
    {
        try {
            $imagePath = null;
            
            // Use AJAX uploaded path if provided, otherwise use traditional file upload
            if (!empty($request->input('image_path'))) {
                $imagePath = $request->input('image_path');
            } elseif ($request->hasFile('image')) {
                try {
                    $file = $request->file('image');
                    if ($file && $file->isValid()) {
                        $imagePath = $file->store('quotes', 'public');
                    }
                } catch (\Exception $e) {
                    // Continue without image if upload fails
                    Log::error('Failed to store quote image: ' . $e->getMessage());
                }
            }

            $quote = $this->quote->create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'vehicle_type' => $request->input('vehicle_type'),
                'make_model' => $request->input('make_model'),
                'reg_number' => $request->input('reg_number'),
                'year' => $request->input('year'),
                'glass_type_id' => $request->input('glass_type_id'),
                'service_type_id' => $request->input('service_type_id'),
                'image_path' => $imagePath,
                'mobile_service' => $request->boolean('mobile_service', false),
                'status' => 'pending',
            ]);

            // Load relationships for email
            $quote->load(['glassType', 'serviceType']);

            // Send email notifications (queued, non-blocking)
            $this->sendEmailNotifications($quote);

            return [
                'success' => true,
                'message' => 'Your quote request has been submitted successfully. We will contact you within 24 hours.',
                'quote' => $quote,
            ];
        } catch (\Exception $e) {
            Log::error('Quote submission error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'There was an error submitting your quote request. Please try again.',
            ];
        }
    }

    /**
     * Send queued email notifications for the quote.
     */
    private function sendEmailNotifications(Quote $quote): void
    {
        $smtpHost = CompanySetting::get('smtp_host');
        $usingCompanySettings = false;

        try {
            if (!empty($smtpHost)) {
                // Use CompanySettings (admin-configured SMTP)
                config([
                    'mail.mailers.smtp.host' => $smtpHost,
                    'mail.mailers.smtp.port' => CompanySetting::get('smtp_port', 587),
                    'mail.mailers.smtp.username' => CompanySetting::get('smtp_username'),
                    'mail.mailers.smtp.password' => CompanySetting::get('smtp_password'),
                    'mail.mailers.smtp.encryption' => CompanySetting::get('smtp_encryption', 'tls'),
                    'mail.from.address' => CompanySetting::get('smtp_username'),
                    'mail.from.name' => CompanySetting::get('company_name', 'Highblossom'),
                ]);
                $usingCompanySettings = true;
                Log::info('Using CompanySettings SMTP configuration');
            } else {
                // Use .env settings (Laravel default)
                Log::info('Using .env SMTP configuration');
            }

            // Get admin notification emails
            $notificationEmails = $this->getNotificationEmails();

            // Queue admin notifications
            foreach ($notificationEmails as $email) {
                Mail::to($email)->queue(new QuoteSubmittedMail(
                    quote: $quote,
                    recipientType: 'admin',
                    primaryPhone: CompanySetting::get('primary_phone'),
                    primaryEmail: CompanySetting::get('primary_email')
                ));
            }

            // Queue customer confirmation if email provided
            if (!empty($quote->email)) {
                Mail::to($quote->email)->queue(new QuoteSubmittedMail(
                    quote: $quote,
                    recipientType: 'customer',
                    primaryPhone: CompanySetting::get('primary_phone'),
                    primaryEmail: CompanySetting::get('primary_email')
                ));
            }

            Log::info('Quote email notifications queued', [
                'quote_id' => $quote->id,
                'admin_count' => count($notificationEmails),
                'customer_notified' => !empty($quote->email),
                'smtp_source' => $usingCompanySettings ? 'company_settings' : 'env',
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the quote submission
            Log::error('Failed to queue quote email notifications: ' . $e->getMessage(), [
                'quote_id' => $quote->id,
                'smtp_source' => $usingCompanySettings ? 'company_settings' : 'env',
            ]);
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

    public function __invoke(Request $request): array
    {
        return $this->execute($request);
    }
}
