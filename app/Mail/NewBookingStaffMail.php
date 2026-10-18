<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable sent to staff when a new booking needs review.
 *
 * Rendered on the shared branded email layout so the staff notice matches every
 * other message the shop sends. It is dispatched from a queued notification, so
 * it is deliberately not itself ShouldQueue — the notification owns the queueing.
 */
final class NewBookingStaffMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly Booking $booking
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->subject('Action Required: New Appointment Booking')
            ->view('emails.bookings.staff-new');
    }
}
