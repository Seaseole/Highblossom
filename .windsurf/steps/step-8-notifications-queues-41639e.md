# Step 8: Notifications, Mailables & Queues

## Async Communication (Rule 9)

### `BookingConfirmationMail.php` (Rule 9)
```php
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
```

### `NewBookingStaffNotification.php` (Rule 9)
```php
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
```

## Robust Job Handling (Rule 9)

### Configured Retries and Backoff
```php
// app/Jobs/ProcessMediaOptimization.php
final class ProcessMediaOptimization implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;
    public int $backoff = 60; // Wait 60s before retry

    public function handle(): void
    {
        // Optimization logic
    }

    public function failed(Throwable $exception): void
    {
        // Rule 9: Always implement failed()
        logger()->error("Media optimization failed: {$exception->getMessage()}");
    }
}
```

## Event Discovery & Commitment (Rule 10)
All booking-related side effects are triggered via events defined with `ShouldDispatchAfterCommit` to ensure data integrity.

---
**Status:** Notifications and Mailables implemented as queued jobs with `afterCommit` protection. Ready for Blade views and components.
