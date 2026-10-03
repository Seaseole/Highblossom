<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\AvailabilityService;
use App\Services\Contracts\AvailabilityServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Date;

/**
 * Controller for returning booking slot availability.
 */
final class BookingAvailabilityController extends Controller
{
    /**
     * Return the bookable time slots for a given date.
     *
     * Same-day dates are allowed; slots inside the minimum booking notice window
     * come back with a `too_late` status so the UI can explain itself rather than
     * silently returning an empty list.
     */
    public function availability(Request $request, AvailabilityServiceInterface $availability): JsonResponse
    {
        $today = Date::today();
        $horizon = $today->copy()->addDays(AvailabilityService::HORIZON_DAYS);

        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                'after_or_equal:'.$today->toDateString(),
                'before_or_equal:'.$horizon->toDateString(),
            ],
        ]);

        $date = Date::parse($validated['date']);
        $slots = $availability->slotsForDate($date);
        $earliest = $availability->earliestBookableSlot();

        return response()->json([
            'date' => $date->toDateString(),
            'is_today' => $date->isToday(),
            'closed' => $slots === [],
            'lead_time_hours' => $availability->leadTimeHours(),
            'earliest' => $earliest === null ? null : [
                'date' => $earliest->toDateString(),
                'day_label' => $earliest->format('D d M'),
                'time_label' => $availability->formatTime($earliest),
            ],
            'slots' => $slots,
        ]);
    }
}
