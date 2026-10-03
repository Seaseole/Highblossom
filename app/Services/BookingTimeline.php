<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\BookingCancelledClientMail;
use App\Mail\BookingCompletedClientMail;
use App\Mail\BookingConfirmationMail;
use App\Mail\BookingConfirmedClientMail;
use App\Mail\BookingRescheduledClientMail;
use App\Models\Booking;
use App\Models\BookingEvent;
use DateTimeInterface;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

/**
 * The single authority for booking milestones and the customer mail they trigger.
 *
 * Status changes used to live in three disconnected places, each with its own idea
 * about whether to email the client: confirming could double-send, while completing,
 * cancelling and rescheduling sent nothing at all. Every transition now goes through
 * record(), so the timeline, the booking status and the customer's inbox agree.
 */
final class BookingTimeline
{
    /**
     * Record a milestone, sync the booking status, and queue the customer email.
     *
     * @param Booking                $booking      The booking the milestone belongs to
     * @param string                 $type         One of BookingEvent::ALL
     * @param string|null            $summary      Customer-facing free text, if any
     * @param DateTimeInterface|null $previousSlot The slot replaced by a reschedule
     * @param int|null               $actorId      Staff user who caused the milestone
     *
     * @return BookingEvent|null Null when nothing was recorded
     *
     * @throws InvalidArgumentException
     */
    public function record(
        Booking $booking,
        string $type,
        ?string $summary = null,
        ?DateTimeInterface $previousSlot = null,
        ?int $actorId = null
    ): ?BookingEvent {
        if (! in_array($type, BookingEvent::ALL, true)) {
            throw new InvalidArgumentException("Unknown booking milestone [{$type}].");
        }

        // Cancellation ends the customer's stake in the booking: a later completion
        // or reschedule must neither re-open it nor email them. Reverting to pending
        // is the only way back, and that is an explicit admin action.
        if ($booking->isCancelled() && $type !== BookingEvent::TYPE_CANCELLED) {
            return null;
        }

        if ($this->isDuplicate($booking, $type, $summary)) {
            return null;
        }

        $event = $booking->events()->create([
            'type' => $type,
            'summary' => $summary,
            'previous_scheduled_at' => $previousSlot,
            'user_id' => $actorId,
            'happened_at' => now(),
        ]);

        $this->applyStatus($booking, $type);

        if ($event->isNotifiable()) {
            Mail::to($booking->client_email)->queue($this->mailableFor($booking, $event));
        }

        return $event;
    }

    /**
     * Mirror the milestone onto the coarse booking status.
     *
     * Started and rescheduled deliberately leave the status alone: they are progress
     * within an already-confirmed appointment, not a change of lifecycle state.
     */
    private function applyStatus(Booking $booking, string $type): void
    {
        $status = match ($type) {
            BookingEvent::TYPE_CONFIRMED => 'confirmed',
            BookingEvent::TYPE_COMPLETED => 'completed',
            BookingEvent::TYPE_CANCELLED => 'cancelled',
            default => null,
        };

        if ($status !== null && $booking->status !== $status) {
            $booking->update(['status' => $status]);
        }
    }

    /**
     * Collapse back-to-back repeats of the same milestone into one.
     *
     * Comparing only against the latest event lets a booking be rescheduled twice
     * while still stopping the two historical confirm paths from both firing.
     */
    private function isDuplicate(Booking $booking, string $type, ?string $summary): bool
    {
        $latest = $booking->events()->reorder('id', 'desc')->first();

        return $latest !== null && $latest->type === $type && $latest->summary === $summary;
    }

    /**
     * Resolve the email a milestone owes the customer.
     */
    private function mailableFor(Booking $booking, BookingEvent $event): Mailable
    {
        return match ($event->type) {
            BookingEvent::TYPE_RECEIVED => new BookingConfirmationMail($booking),
            BookingEvent::TYPE_CONFIRMED => new BookingConfirmedClientMail($booking),
            BookingEvent::TYPE_RESCHEDULED => new BookingRescheduledClientMail($booking, $event->previous_scheduled_at),
            BookingEvent::TYPE_COMPLETED => new BookingCompletedClientMail($booking, $event->summary),
            BookingEvent::TYPE_CANCELLED => new BookingCancelledClientMail($booking),
            default => throw new InvalidArgumentException("No email is mapped to [{$event->type}]."),
        };
    }
}
