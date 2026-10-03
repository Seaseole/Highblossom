<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable sent to the client when their inspection is completed.
 *
 * `$summary` is the optional customer-facing note an admin writes at completion.
 * It is the only free text allowed in this email — internal inspection notes must
 * never be passed through it.
 */
final class BookingCompletedClientMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly Booking $booking,
        public readonly ?string $summary = null
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->subject('Highblossom: Appointment Completed')
            ->markdown('emails.bookings.completed');
    }
}
