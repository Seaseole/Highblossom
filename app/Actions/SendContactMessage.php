<?php

namespace App\Actions;

use App\Domains\Content\Models\CompanySetting;
use App\Domains\Content\Models\ContactMessage;
use App\Http\Requests\ContactFormRequest;
use Illuminate\Support\Facades\Mail;

class SendContactMessage
{
    public function handle(ContactFormRequest $request): array
    {
        $validated = $request->validated();

        // Save to database
        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // Send email notification if SMTP is configured
        try {
            $this->sendEmailNotification($contactMessage);
            return [
                'success' => true,
                'message' => 'Thank you for your message. We will get back to you soon!',
            ];
        } catch (\Exception $e) {
            // Message was saved but email failed - still consider it a success
            return [
                'success' => true,
                'message' => 'Your message has been received. Our team will contact you shortly.',
            ];
        }
    }

    private function sendEmailNotification(ContactMessage $contactMessage): void
    {
        $smtpHost = CompanySetting::get('smtp_host');

        if (empty($smtpHost)) {
            return; // SMTP not configured
        }

        // Configure mailer dynamically
        config([
            'mail.mailers.smtp.host' => $smtpHost,
            'mail.mailers.smtp.port' => CompanySetting::get('smtp_port', 587),
            'mail.mailers.smtp.username' => CompanySetting::get('smtp_username'),
            'mail.mailers.smtp.password' => CompanySetting::get('smtp_password'),
            'mail.mailers.smtp.encryption' => CompanySetting::get('smtp_encryption', 'tls'),
            'mail.from.address' => CompanySetting::get('smtp_username'),
            'mail.from.name' => CompanySetting::get('company_name', 'Highblossom'),
        ]);

        $toEmail = CompanySetting::get('primary_email', $contactMessage->email);

        Mail::send('emails.contact', [
            'contactMessage' => $contactMessage,
            'companyName' => CompanySetting::get('company_name', 'Highblossom'),
        ], function ($message) use ($contactMessage, $toEmail) {
            $message->to($toEmail)
                ->subject('New Contact Message: ' . $contactMessage->subject);
        });
    }
}
