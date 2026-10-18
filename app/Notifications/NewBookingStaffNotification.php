<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Mail\NewBookingStaffMail;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Notification sent to staff when a new booking is created.
 *
 * Renders the shared branded mailable rather than a stock MailMessage so the
 * staff notice matches every other email the shop sends.
 */
final class NewBookingStaffNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     */
    public function toMail($notifiable): NewBookingStaffMail
    {
        return (new NewBookingStaffMail($this->booking))
            ->to($notifiable->routeNotificationFor('mail', $this) ?? $notifiable->email);
    }
}
