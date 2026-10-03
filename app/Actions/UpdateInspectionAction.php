<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\BookingEvent;
use App\Models\Inspection;
use App\Services\BookingTimeline;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;

/**
 * Update an existing inspection and record the milestones its changes imply.
 */
class UpdateInspectionAction
{
    public function __construct(private readonly BookingTimeline $timeline) {}

    /**
     * Execute the action.
     *
     * @param array<string, mixed> $data    Validated inspection attributes plus an optional client_summary
     * @param int|null             $actorId Staff user making the change
     */
    public function execute(Inspection $inspection, array $data, ?int $actorId = null): Inspection
    {
        // Pulled before the update: client_summary belongs to the timeline event, and
        // leaving it in would be silently dropped by mass-assignment protection.
        $summary = Arr::pull($data, 'client_summary');

        $previousSlot = $inspection->scheduled_at;
        $wasStarted = $inspection->started_at !== null;
        $wasCompleted = $inspection->ended_at !== null;

        $inspection->update($data);

        $booking = $inspection->booking;
        $startedNow = ! $wasStarted && $inspection->started_at !== null;
        $completedNow = ! $wasCompleted && $inspection->ended_at !== null;

        if ($startedNow) {
            $this->timeline->record($booking, BookingEvent::TYPE_STARTED, actorId: $actorId);
        }

        if ($completedNow) {
            $this->timeline->record($booking, BookingEvent::TYPE_COMPLETED, summary: $summary, actorId: $actorId);
        }

        // A completed booking has no appointment to move, and the completion notice
        // already tells the customer what happened.
        if (! $wasCompleted && ! $completedNow && $this->slotMoved($data, $inspection, $previousSlot)) {
            $this->timeline->record(
                $booking,
                BookingEvent::TYPE_RESCHEDULED,
                previousSlot: $previousSlot,
                actorId: $actorId
            );
        }

        return $inspection->fresh();
    }

    /**
     * Determine whether this update moved the appointment to a different time.
     *
     * @param array<string, mixed> $data
     * @param CarbonImmutable|null $previousSlot
     */
    private function slotMoved(array $data, Inspection $inspection, mixed $previousSlot): bool
    {
        return Arr::has($data, 'scheduled_at')
            && $previousSlot !== null
            && $inspection->scheduled_at !== null
            && $inspection->scheduled_at->ne($previousSlot);
    }
}
