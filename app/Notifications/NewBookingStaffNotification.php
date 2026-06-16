<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification sent to staff when a new booking is created.
 */
final class NewBookingStaffNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $afterCommit = true;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly Booking $booking
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Action Required: New Inspection Booking')
            ->line("New booking from {$this->booking->client_name}.")
            ->action('View Booking', route('admin.bookings.show', $this->booking));
    }
}
