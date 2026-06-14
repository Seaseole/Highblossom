<?php

namespace App\Listeners;

use App\Events\ContactMessageReceived;
use App\Services\Settings\SettingsManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContactMessageEmail implements ShouldQueue
{
    public function __construct(
        protected SettingsManager $settings
    ) {}

    public function handle(ContactMessageReceived $event): void
    {
        try {
            $contactMessage = $event->contactMessage;

            $adminEmail = $this->settings->primary_email ?? config('mail.from.address');
            $companyName = $this->settings->company_name ?? config('mail.from.name');

            Mail::send('emails.contact', [
                'contactMessage' => $contactMessage,
                'companyName' => $companyName,
            ], function ($message) use ($contactMessage, $adminEmail) {
                $message->to($adminEmail)
                    ->subject('New Contact Message: '.$contactMessage->subject);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send contact message email: '.$e->getMessage(), [
                'contact_message_id' => $event->contactMessage->id ?? null,
                'exception' => $e,
            ]);
            throw $e; // Re-throw to allow queue to handle retry logic
        }
    }
}
