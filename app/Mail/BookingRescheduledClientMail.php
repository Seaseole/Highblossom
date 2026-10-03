<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Booking;
use DateTimeInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable sent to the client when their appointment time is moved.
 *
 * `$previousSlot` is the appointment being replaced. Without it the notice cannot
 * answer the only question the client cares about: "moved from when?" It cannot be
 * called `$from`, which Mailable already owns as the sender address.
 */
final class BookingRescheduledClientMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly Booking $booking,
        public readonly ?DateTimeInterface $previousSlot = null
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->subject('Highblossom: Appointment Rescheduled')
            ->markdown('emails.bookings.rescheduled');
    }
}
