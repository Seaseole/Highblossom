<?php

declare(strict_types=1);

namespace App\Mail;

use App\Domains\Bookings\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class QuoteSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Quote $quote,
        public readonly string $recipientType = 'admin',
        public readonly ?string $primaryPhone = null,
        public readonly ?string $primaryEmail = null
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'admin'
            ? 'New Quote Request - Highblossom'
            : 'Quote Request Received - Highblossom';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $view = $this->recipientType === 'admin'
            ? 'emails.quotes.submitted-admin'
            : 'emails.quotes.submitted-customer';

        return new Content(
            view: $view,
            with: [
                'quote' => $this->quote,
                'companyName' => 'Highblossom',
                'primaryPhone' => $this->primaryPhone,
                'primaryEmail' => $this->primaryEmail,
            ],
        );
    }
}
