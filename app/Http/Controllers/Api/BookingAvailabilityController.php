<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\BookingServiceType;
use App\Services\AvailabilityService;
use App\Services\BookingCalendarService;
use App\Services\Contracts\AvailabilityServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;

/**
 * Controller for returning booking slot and calendar availability.
 *
 * Both endpoints take the service type and location the customer is booking,
 * because neither changes what is merely *open* — they change how long a slot is
 * held and which capacity pool it draws from. A grid built without them would
 * offer a 16:30 windscreen that the shop cannot finish before it shuts.
 */
final class BookingAvailabilityController extends Controller
{
    /**
     * Rules shared by both availability lookups.
     *
     * @return array<string, mixed>
     */
    private function contextRules(): array
    {
        return [
            'service' => ['nullable', 'string', Rule::enum(BookingServiceType::class)],
            'location' => ['nullable', 'string', Rule::in(['mobile', 'workshop'])],
        ];
    }

    /**
     * Return the bookable time slots for a given date.
     *
     * Same-day dates are allowed; slots inside the minimum booking notice window
     * come back with a `too_late` status so the UI can explain itself rather than
     * silently returning an empty list.
     */
    public function availability(
        Request $request,
        AvailabilityServiceInterface $availability
    ): JsonResponse {
        $today = Date::today();
        $horizon = $today->copy()->addDays(AvailabilityService::HORIZON_DAYS);

        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                'after_or_equal:'.$today->toDateString(),
                'before_or_equal:'.$horizon->toDateString(),
            ],
        ] + $this->contextRules());

        $date = Date::parse($validated['date']);
        $service = $validated['service'] ?? null;
        $location = $validated['location'] ?? null;

        $slots = $availability->slotsForDate($date, $service, $location);
        $earliest = $availability->earliestBookableSlot($service, $location);

        return response()->json([
            'date' => $date->toDateString(),
            'is_today' => $date->isToday(),
            'closed' => $slots === [],
            'lead_time_hours' => $availability->leadTimeHours(),
            'interval_minutes' => $availability->slotIntervalMinutes(),
            'duration_minutes' => $availability->durationFor($service),
            'available_count' => count(array_filter($slots, fn (array $slot): bool => $slot['available'])),
            'earliest' => $earliest === null ? null : [
                'date' => $earliest->toDateString(),
                'time' => $earliest->format('H:i'),
                'day_label' => $earliest->format('D d M'),
                'time_label' => $availability->formatTime($earliest),
            ],
            'slots' => $slots,
        ]);
    }

    /**
     * Return one month of the calendar, with the days that have no free slot dimmed.
     *
     * The picker asks for this whenever the customer turns the month or changes the
     * service or location, so the grid it shows is always the grid the server will
     * accept a booking against.
     */
    public function calendar(Request $request, BookingCalendarService $calendar): JsonResponse
    {
        $today = Date::today();
        $horizon = $today->copy()->addDays(AvailabilityService::HORIZON_DAYS);

        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
        ] + $this->contextRules());

        $month = Date::parse($validated['month'].'-01');

        if ($month->gt($horizon)) {
            $month = $horizon->copy()->startOfMonth();
        }

        return response()->json($calendar->monthPayload(
            Carbon::parse($validated['month'].'-01'),
            $validated['service'] ?? null,
            $validated['location'] ?? null,
        ));
    }
}
