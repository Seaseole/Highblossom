<?php

namespace App\Actions\Bookings;

use App\Domains\Bookings\Models\Booking;
use App\Domains\Bookings\Models\Inspection;
use Illuminate\Support\Facades\DB;

final class ScheduleInspectionAction
{
    public function __invoke(Booking $booking, array $inspectionData): Inspection
    {
        return DB::transaction(function () use ($booking, $inspectionData) {
            $inspection = $booking->inspection()->create($inspectionData);
            
            // Side effect: notify staff
            // Note: notifyStaffOfNewInspection would be a method on Booking or a separate service/event
            // For now, we follow the step description
            if (method_exists($booking, 'notifyStaffOfNewInspection')) {
                defer(fn() => $booking->notifyStaffOfNewInspection($inspection));
            }
            
            return $inspection;
        });
    }
}
