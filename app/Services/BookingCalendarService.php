<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Contracts\AvailabilityServiceInterface;
use Carbon\Carbon;

/**
 * Service for building the booking calendar.
 *
 * Produces a flat list of days per month so the public booking form can be
 * rendered without relying on a client-side date-picker package, and so the exact
 * same shape can come back over the API when the picker turns the month.
 *
 * Days are laid out Monday-first: the shop trades Monday to Saturday, and the
 * grid reads as a working week rather than one that opens on a closed day.
 *
 * A day is only selectable when it still has a bookable slot. It used to be
 * selectable whenever the shop was simply open that day, which meant a fully
 * booked Saturday looked identical to a free one and only failed after the
 * customer had committed to tapping it.
 */
final class BookingCalendarService
{
    public function __construct(private readonly AvailabilityServiceInterface $availability) {}

    /**
     * Build consecutive months of the calendar, starting from the current one.
     *
     * @param int         $count       Number of consecutive months to render
     * @param string|null $serviceType The job being booked, which sets its duration
     * @param string|null $location    mobile|workshop, which selects the capacity pool
     *
     * @return list<array<string, mixed>>
     */
    public function getMonths(int $count = 3, ?string $serviceType = null, ?string $location = null): array
    {
        $today = Carbon::today();
        $months = [];

        for ($i = 0; $i < $count; $i++) {
            $months[] = $this->monthPayload($today->copy()->addMonths($i), $serviceType, $location);
        }

        return $months;
    }

    /**
     * Build one month as the payload the picker draws and the API returns.
     *
     * Availability is decided here rather than in JavaScript: the grid the customer
     * sees must not be able to disagree with what the server will accept.
     *
     * @param string|null $serviceType The job being booked
     * @param string|null $location    mobile|workshop
     *
     * @return array{month: string, label: string, leading_blanks: int, available_count: int, open_count: int, days: list<array{date: string, day: int, selectable: bool, reason: string|null, is_today: bool}>}
     */
    public function monthPayload(Carbon $month, ?string $serviceType = null, ?string $location = null): array
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $today = Carbon::today();
        $survey = $this->availability->monthAvailability($startOfMonth, $serviceType, $location);
        $available = array_flip($survey['available_dates']);
        $open = array_flip($survey['open_dates']);

        $days = [];

        for ($day = 1; $day <= $startOfMonth->daysInMonth; $day++) {
            $date = $startOfMonth->copy()->addDays($day - 1);
            $key = $date->toDateString();
            $isPast = $date->isBefore($today);
            $isOpen = isset($open[$key]);
            $selectable = isset($available[$key]);

            $days[] = [
                'date' => $key,
                'day' => $day,
                'selectable' => $selectable,
                'reason' => match (true) {
                    $isPast => 'past',
                    ! $isOpen => 'closed',
                    ! $selectable => 'full',
                    default => null,
                },
                'is_today' => $date->isSameDay($today),
            ];
        }

        return [
            'month' => $startOfMonth->format('Y-m'),
            'label' => $startOfMonth->format('F Y'),
            'leading_blanks' => ((int) $startOfMonth->dayOfWeek + 6) % 7,
            'available_count' => $survey['available_count'],
            'open_count' => $survey['open_count'],
            'days' => $days,
        ];
    }
}
