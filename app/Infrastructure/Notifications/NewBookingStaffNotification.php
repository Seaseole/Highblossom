<?php

namespace App\Infrastructure\Notifications;

use App\Domains\Bookings\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

final class NewBookingStaffNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $afterCommit = true;

    public function __construct(
        private readonly Booking $booking
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Action Required: New Inspection Booking')
            ->line("New booking from {$this->booking->client_name}.")
            ->action('View Booking', route('admin.bookings.show', $this->booking));
    }
}
