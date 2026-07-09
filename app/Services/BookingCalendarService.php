<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;

/**
 * Service for building a server-rendered booking calendar grid.
 *
 * Generates month matrices of selectable days using Carbon, disabling
 * weekends and past dates so the public booking form can be rendered
 * without relying on a client-side date-picker package.
 */
final class BookingCalendarService
{
    /**
     * Build a list of month grids starting from the current month.
     *
     * @param int $count Number of consecutive months to render
     *
     * @return array<int, array{label: string, weeks: array<int, array<int, array{day: int, date: string, isWeekend: bool, isPast: bool, selectable: bool}|null>>}>
     */
    public function getMonths(int $count = 3): array
    {
        $today = Carbon::today();
        $months = [];

        for ($i = 0; $i < $count; $i++) {
            $months[] = $this->buildMonth($today->copy()->addMonths($i), $today);
        }

        return $months;
    }

    /**
     * Build a single month matrix laid out in Sunday-first weeks.
     *
     * @param Carbon $month The month to render
     * @param Carbon $today Reference "today" used to disable past days
     *
     * @return array{label: string, weeks: array<int, array<int, array{day: int, date: string, isWeekend: bool, isPast: bool, selectable: bool}|null>>}
     */
    private function buildMonth(Carbon $month, Carbon $today): array
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $daysInMonth = $month->daysInMonth;
        $leadingBlanks = (int) $startOfMonth->dayOfWeek;

        $weeks = [];
        $week = array_fill(0, $leadingBlanks, null);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $startOfMonth->copy()->addDays($day - 1);
            $isPast = $date->isBefore($today);
            $isWeekend = $date->isWeekend();

            $week[] = [
                'day' => $day,
                'date' => $date->toDateString(),
                'isWeekend' => $isWeekend,
                'isPast' => $isPast,
                'selectable' => ! $isWeekend && ! $isPast,
            ];

            if (count($week) === 7) {
                $weeks[] = $week;
                $week = [];
            }
        }

        if ($week !== []) {
            while (count($week) < 7) {
                $week[] = null;
            }
            $weeks[] = $week;
        }

        return [
            'label' => $month->format('F Y'),
            'weeks' => $weeks,
        ];
    }
}
