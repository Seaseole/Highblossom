<?php

namespace App\Infrastructure\Mail;

use App\Domains\Bookings\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Rule 9: Implement ShouldQueue + afterCommit()
 */
final class BookingConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $afterCommit = true; // Rule 9: Only send if DB transaction succeeds

    public function __construct(
        public readonly Booking $booking
    ) {}

    public function build(): self
    {
        return $this->subject('Highblossom: Booking Received')
                    ->markdown('emails.bookings.confirmation');
    }
}
