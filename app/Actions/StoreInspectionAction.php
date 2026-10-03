<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\BookingEvent;
use App\Models\Inspection;
use App\Services\BookingTimeline;
use Illuminate\Support\Facades\DB;

/**
 * Create a new inspection and confirm the related booking.
 */
class StoreInspectionAction
{
    public function __construct(private readonly BookingTimeline $timeline) {}

    /**
     * Execute the action.
     *
     * @param array<string, mixed> $data
     * @param int|null             $actorId Staff user who scheduled the inspection
     */
    public function execute(array $data, ?int $actorId = null): Inspection
    {
        return DB::transaction(function () use ($data, $actorId) {
            $inspection = Inspection::create($data);

            // Scheduling an inspection confirms the booking. The timeline collapses a
            // repeat into a no-op, so a booking already confirmed by hand cannot be
            // emailed twice.
            $this->timeline->record(
                $inspection->booking,
                BookingEvent::TYPE_CONFIRMED,
                actorId: $actorId
            );

            return $inspection;
        });
    }
}
