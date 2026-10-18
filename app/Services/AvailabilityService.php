<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BookingServiceType;
use App\Models\Booking;
use App\Models\BookingClosedDay;
use App\Models\Inspection;
use App\Models\StaffAbsence;
use App\Services\Contracts\AvailabilityServiceInterface;
use App\Services\Settings\SettingsManager;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

/**
 * Decides which fitting slots a customer may book.
 *
 * All slot maths runs in the application timezone, because `scheduled_at` is
 * stored as naive wall-clock in that same timezone. Mixing in the display
 * timezone setting would compare two different frames.
 *
 * A slot is a *start* time. What occupies the shop is the job's whole span, so
 * every conflict test compares intervals rather than instants: a 90-minute
 * windscreen starting at 08:00 is still holding a bay at 09:00. That is also why
 * the last slot of a day is earlier than closing time — a job must finish inside
 * trading hours, not merely start there.
 *
 * Occupancy is deliberately read in one pass per window instead of per slot. The
 * old design asked "is this exact instant free?" nine times a day, three queries
 * each; at half-hourly intervals across a three-month calendar that is thousands
 * of round trips for one page.
 */
final class AvailabilityService implements AvailabilityServiceInterface
{
    /** How far ahead the booking calendar reaches. */
    public const HORIZON_DAYS = 90;

    /** Guards against a settings typo turning every day into an infinite slot list. */
    private const MIN_INTERVAL_MINUTES = 15;

    private const MAX_INTERVAL_MINUTES = 240;

    /** @var Collection<int, Booking>|null Committed jobs inside the loaded window. */
    private ?Collection $jobCache = null;

    /** @var list<array{start: CarbonInterface, end: CarbonInterface}> Absences inside the loaded window. */
    private array $absenceCache = [];

    /** @var CarbonInterface|null Start of the window currently held in memory. */
    private ?CarbonInterface $loadedFrom = null;

    /** @var CarbonInterface|null End of the window currently held in memory. */
    private ?CarbonInterface $loadedTo = null;

    /** @var list<string> Closure dates, loaded once per request. */
    private ?array $closedDates = null;

    public function __construct(private readonly SettingsManager $settings) {}

    /**
     * Explain why a slot is or is not bookable.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     * @param string|null              $serviceType The job being booked, which sets its duration
     * @param string|null              $location    mobile|workshop, which selects the capacity pool
     */
    public function slotStatus(
        DateTimeInterface|string $scheduledAt,
        ?string $serviceType = null,
        ?string $location = null
    ): string {
        $slot = Date::parse($scheduledAt);
        $duration = $this->durationFor($serviceType);

        if ($this->openHoursFor($slot) === null) {
            return self::STATUS_CLOSED;
        }

        if ($slot->lt($this->noticeCutoff()) || ! $this->fitsBeforeClose($slot, $duration)) {
            return self::STATUS_TOO_LATE;
        }

        return $this->hasRoom($slot, $duration, $location, false)
            ? self::STATUS_AVAILABLE
            : self::STATUS_TAKEN;
    }

    /**
     * Check whether a slot is bookable.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     */
    public function isSlotAvailable(
        DateTimeInterface|string $scheduledAt,
        ?string $serviceType = null,
        ?string $location = null
    ): bool {
        return $this->slotStatus($scheduledAt, $serviceType, $location) === self::STATUS_AVAILABLE;
    }

    /**
     * Check availability while holding a row lock, for use inside a transaction.
     *
     * Locking the day's committed jobs is what stops two concurrent submissions
     * from both counting the same occupancy and both inserting.
     *
     * @param DateTimeInterface|string $scheduledAt The slot to check
     */
    public function isSlotAvailableForUpdate(
        DateTimeInterface|string $scheduledAt,
        ?string $serviceType = null,
        ?string $location = null
    ): bool {
        $slot = Date::parse($scheduledAt);
        $duration = $this->durationFor($serviceType);

        if ($this->openHoursFor($slot) === null || $slot->lt($this->noticeCutoff())) {
            return false;
        }

        if (! $this->fitsBeforeClose($slot, $duration)) {
            return false;
        }

        return $this->hasRoom($slot, $duration, $location, true);
    }

    /**
     * Every slot on the given date, each with a display label and status.
     *
     * @param DateTimeInterface|string $date        The date to build slots for
     * @param string|null              $serviceType The job being booked, which sets its duration
     * @param string|null              $location    mobile|workshop, which selects the capacity pool
     *
     * @return list<array{time: string, label: string, end: string, end_label: string, available: bool, status: string}>
     */
    public function slotsForDate(
        DateTimeInterface|string $date,
        ?string $serviceType = null,
        ?string $location = null
    ): array {
        $day = Date::parse($date)->startOfDay();
        $duration = $this->durationFor($serviceType);
        $starts = $this->slotStartsFor($day, $duration);

        if ($starts === []) {
            return [];
        }

        $this->ensureWindow($day->copy()->startOfDay(), $day->copy()->endOfDay());

        $cutoff = $this->noticeCutoff();
        $slots = [];

        foreach ($starts as $start) {
            $status = $this->statusForLoadedSlot($start, $duration, $location, $cutoff);

            $end = $start->copy()->addMinutes($duration);

            $slots[] = [
                'time' => $start->format('H:i'),
                'label' => $this->formatTime($start),
                'end' => $end->format('H:i'),
                'end_label' => $this->formatTime($end),
                'available' => $status === self::STATUS_AVAILABLE,
                'status' => $status,
            ];
        }

        return $slots;
    }

    /**
     * The first slot that could possibly be bookable, or null past the horizon.
     */
    public function earliestBookableSlot(?string $serviceType = null, ?string $location = null): ?CarbonInterface
    {
        $duration = $this->durationFor($serviceType);
        $candidate = $this->alignForward($this->noticeCutoff());

        for ($offset = 0; $offset <= self::HORIZON_DAYS; $offset++) {
            foreach ($this->slotStartsFor($candidate, $duration) as $start) {
                if ($start->gte($candidate)) {
                    return $start;
                }
            }

            $candidate = $candidate->addDay()->startOfDay();
        }

        return null;
    }

    /**
     * The next open days that still have at least one bookable slot.
     *
     * @param int $count Maximum number of days to return
     *
     * @return list<array{date: string, day: string, weekday: string, month: string, is_today: bool, first_slot: string}>
     */
    public function upcomingOpenDays(int $count, ?string $serviceType = null, ?string $location = null): array
    {
        $earliest = $this->earliestBookableSlot($serviceType, $location);

        if ($earliest === null) {
            return [];
        }

        $today = Date::today();
        $horizon = $today->copy()->addDays(self::HORIZON_DAYS);
        $this->ensureWindow($today->copy()->startOfDay(), $horizon->copy()->endOfDay());

        $cutoff = $this->noticeCutoff();
        $duration = $this->durationFor($serviceType);
        $days = [];

        for ($cursor = $today->copy(); $cursor->lte($horizon) && count($days) < $count; $cursor = $cursor->addDay()) {
            foreach ($this->slotStartsFor($cursor, $duration) as $start) {
                if ($this->statusForLoadedSlot($start, $duration, $location, $cutoff) === self::STATUS_AVAILABLE) {
                    $days[] = [
                        'date' => $cursor->toDateString(),
                        'day' => $cursor->format('d'),
                        'weekday' => $cursor->format('D'),
                        'month' => $cursor->format('M'),
                        'is_today' => $cursor->isSameDay($today),
                        'first_slot' => $this->formatTime($start),
                    ];

                    break;
                }
            }
        }

        return $days;
    }

    /**
     * Which days of a month still have a bookable slot, in one batched lookup.
     *
     * This is what lets the calendar dim a fully-booked day. Before it, the grid
     * only knew a day was open and in the future, so a booked-out Saturday looked
     * exactly as pickable as a free one and only failed once the customer tapped it.
     *
     * @param CarbonInterface $month Any moment inside the month to survey
     *
     * @return array{year: int, month: int, available_dates: list<string>, open_dates: list<string>, available_count: int, open_count: int}
     */
    public function monthAvailability(
        CarbonInterface $month,
        ?string $serviceType = null,
        ?string $location = null
    ): array {
        $first = Date::parse($month)->startOfMonth();
        $last = $first->copy()->endOfMonth();
        $today = Date::today();

        if ($first->lt($today)) {
            $first = $today->copy()->startOfDay();
        }

        $this->ensureWindow($first->copy()->startOfDay(), $last->copy()->endOfDay());

        $cutoff = $this->noticeCutoff();
        $duration = $this->durationFor($serviceType);
        $available = [];
        $open = [];

        for ($cursor = $first->copy(); $cursor->lte($last); $cursor = $cursor->addDay()) {
            $starts = $this->slotStartsFor($cursor, $duration);

            if ($starts === []) {
                continue;
            }

            $open[] = $cursor->toDateString();

            foreach ($starts as $start) {
                if ($this->statusForLoadedSlot($start, $duration, $location, $cutoff) === self::STATUS_AVAILABLE) {
                    $available[] = $cursor->toDateString();

                    break;
                }
            }
        }

        return [
            'year' => $first->year,
            'month' => $first->month,
            'available_dates' => $available,
            'open_dates' => $open,
            'available_count' => count($available),
            'open_count' => count($open),
        ];
    }

    /**
     * Minimum number of hours a customer must book ahead of the current time.
     */
    public function leadTimeHours(): int
    {
        return max(0, (int) $this->settings->get('booking_lead_time_hours', 2));
    }

    /**
     * Minutes between two consecutive slot start times.
     */
    public function slotIntervalMinutes(): int
    {
        $interval = $this->intSetting('booking_slot_interval_minutes', 30);

        return min(self::MAX_INTERVAL_MINUTES, max(self::MIN_INTERVAL_MINUTES, $interval));
    }

    /**
     * How long a job of the given service type occupies a slot.
     */
    public function durationFor(?string $serviceType): int
    {
        $fallback = BookingServiceType::tryFrom((string) $serviceType)?->defaultDurationMinutes() ?? 60;
        $durations = $this->settings->get('booking_service_durations', []);

        if (! is_array($durations) || ! array_key_exists((string) $serviceType, $durations)) {
            return $fallback;
        }

        return min(480, max(self::MIN_INTERVAL_MINUTES, (int) $durations[$serviceType]));
    }

    /**
     * How many jobs can run at once for the given location.
     *
     * An unknown location takes the smaller of the two pools. Guessing high would
     * offer slots the shop cannot honour; guessing low only costs a booking.
     */
    public function capacityFor(?string $location): int
    {
        $workshop = $this->capacitySetting('booking_capacity_workshop');
        $mobile = $this->capacitySetting('booking_capacity_mobile');

        return match ($location) {
            'workshop' => $workshop,
            'mobile' => $mobile,
            default => min($workshop, $mobile),
        };
    }

    /**
     * Whether the business takes bookings at all on the given day.
     *
     * @param DateTimeInterface $date The day to check
     */
    public function isOpenDay(DateTimeInterface $date): bool
    {
        return $this->openHoursFor(Date::parse($date)) !== null;
    }

    /**
     * Format a moment for display using the configured 12/24-hour preference.
     *
     * @param DateTimeInterface $moment The moment to format
     */
    public function formatTime(DateTimeInterface $moment): string
    {
        $isTwelveHour = (string) $this->settings->get('time_format_display', '12') === '12';

        return Date::parse($moment)->format($isTwelveHour ? 'h:i A' : 'H:i');
    }

    /**
     * Status a slot against the window already loaded, without touching the database.
     */
    private function statusForLoadedSlot(
        CarbonInterface $start,
        int $duration,
        ?string $location,
        CarbonInterface $cutoff
    ): string {
        if ($start->lt($cutoff)) {
            return self::STATUS_TOO_LATE;
        }

        return $this->hasRoom($start, $duration, $location, false)
            ? self::STATUS_AVAILABLE
            : self::STATUS_TAKEN;
    }

    /**
     * Determine whether one more job of this length fits at this time.
     */
    private function hasRoom(CarbonInterface $start, int $duration, ?string $location, bool $forUpdate): bool
    {
        $capacity = $this->capacityFor($location);

        if ($capacity < 1) {
            return false;
        }

        $end = $start->copy()->addMinutes($duration);

        if ($forUpdate) {
            $committed = $this->lockedJobs($start, $end, $location)->count()
                + $this->lockedAbsences($start, $end);
        } else {
            $this->ensureWindow($start->copy()->startOfDay(), $start->copy()->endOfDay());

            $committed = $this->overlappingJobs($start, $end, $location)->count()
                + $this->overlappingAbsences($start, $end);
        }

        return $committed < $capacity;
    }

    /**
     * Committed jobs whose span overlaps the given window.
     *
     * An inspection reschedules its own booking, so the appointment time is the
     * inspection's when one exists. Counting the booking row and its inspection row
     * separately would bill the shop for the same job twice.
     *
     * @return Collection<int, Booking>
     */
    private function overlappingJobs(CarbonInterface $start, CarbonInterface $end, ?string $location): Collection
    {
        return $this->jobCache->filter(
            fn (Booking $booking): bool => $this->occupies($booking, $start, $end, $location)
        );
    }

    /**
     * Whether one committed job actually overlaps the slot being tested.
     *
     * A slot is a start time but a job spans its whole duration, so this is an
     * interval test rather than an instant one. An inspection reschedules its own
     * booking, so the job's real start is the inspection's when one exists; counting
     * the booking row and its inspection row separately would bill the shop twice for
     * one job. A job with no location is counted against every pool, because the shop
     * cannot know which bay it will occupy.
     *
     * Both the cached survey and the locked reservation count through this one
     * predicate. They used to diverge: the locked path counted every row inside its
     * ±1-day lock range without testing overlap, so a single 09:00 booking made every
     * workshop slot within a day of it read as taken and the picker and the submission
     * disagreed about the very same time.
     *
     * @param Booking         $booking  The committed job to test
     * @param CarbonInterface $start    Start of the slot being tested
     * @param CarbonInterface $end      End of the slot being tested
     * @param string|null     $location Capacity pool being booked, or null for any
     *
     * @return bool True when this job holds the bay the slot needs
     */
    private function occupies(
        Booking $booking,
        CarbonInterface $start,
        CarbonInterface $end,
        ?string $location
    ): bool {
        if ($location !== null && $booking->location !== null && $booking->location !== $location) {
            return false;
        }

        $jobStart = $booking->inspection?->scheduled_at ?? $booking->scheduled_at;

        if ($jobStart === null) {
            return false;
        }

        return $jobStart->lt($end) && $jobStart->copy()->addMinutes($booking->durationMinutes())->gt($start);
    }

    /**
     * Cached absences whose span overlaps the given window.
     */
    private function overlappingAbsences(CarbonInterface $start, CarbonInterface $end): int
    {
        return count(array_filter(
            $this->absenceCache,
            fn (array $absence): bool => $absence['start']->lt($end) && $absence['end']->gt($start)
        ));
    }

    /**
     * Re-read and lock the jobs that overlap a window, for use inside a transaction.
     *
     * The `whereBetween` range is padded by a day on each side purely to widen the gap
     * lock, so a concurrent insert at the same instant blocks instead of slipping in
     * beside us. Locking a row is not the same as counting it: the padded set is then
     * narrowed through the same `occupies()` test the picker uses, so the reservation
     * and the calendar can never disagree about the same slot. A job with no location
     * is drawn into every pool's lock and count, matching the survey path.
     *
     * @return Collection<int, Booking>
     */
    private function lockedJobs(CarbonInterface $start, CarbonInterface $end, ?string $location): Collection
    {
        $locked = $this->attachInspections(Booking::query()
            ->where('status', '!=', 'cancelled')
            ->when(
                $location !== null,
                fn ($query) => $query->where(
                    fn ($scope) => $scope->where('location', $location)->orWhereNull('location')
                )
            )
            ->whereBetween('scheduled_at', [
                $start->copy()->subDay(),
                $end->copy()->addDay(),
            ])
            ->lockForUpdate()
            ->get());

        return $locked->filter(
            fn (Booking $booking): bool => $this->occupies($booking, $start, $end, $location)
        );
    }

    /**
     * Count staff off across the window while holding the absence rows.
     */
    private function lockedAbsences(CarbonInterface $start, CarbonInterface $end): int
    {
        return StaffAbsence::where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->lockForUpdate()
            ->count();
    }

    /**
     * Load a window into memory only when it is not already covered.
     *
     * The padded range means a survey of three months answers every per-slot
     * question inside it from one pair of queries.
     */
    private function ensureWindow(CarbonInterface $from, CarbonInterface $to): void
    {
        if ($this->jobCache !== null
            && $this->loadedFrom->lte($from)
            && $this->loadedTo->gte($to)) {
            return;
        }

        $this->loadWindow($from->copy()->subDay(), $to->copy()->addDay());
    }

    /**
     * Read every committed job and absence in the window in three queries.
     *
     * The inspection half is resolved with its own query rather than a `whereHas`,
     * because a rescheduled appointment moves the job's real start off the booking
     * row.
     */
    private function loadWindow(CarbonInterface $from, CarbonInterface $to): void
    {
        $rescheduledBookingIds = Inspection::query()
            ->whereBetween('scheduled_at', [$from, $to])
            ->pluck('booking_id');

        $this->jobCache = $this->attachInspections(Booking::query()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($from, $to, $rescheduledBookingIds): void {
                $query->whereBetween('scheduled_at', [$from, $to])
                    ->orWhereIn('id', $rescheduledBookingIds);
            })
            ->get());

        $this->absenceCache = StaffAbsence::where('starts_at', '<', $to)
            ->where('ends_at', '>', $from)
            ->get(['starts_at', 'ends_at'])
            ->map(fn (StaffAbsence $absence): array => [
                'start' => $absence->starts_at,
                'end' => $absence->ends_at,
            ])
            ->all();

        $this->loadedFrom = $from;
        $this->loadedTo = $to;
    }

    /**
     * Attach each booking's inspection with one extra query.
     *
     * `with('inspection')` runs its query inside the abstract Relation class, which
     * the query detector reports as a lazy-loaded relation whenever the same window
     * is surveyed more than once in a request — the booking calendar surveys three
     * months, so the false positive fired on every page load.
     *
     * @param Collection<int, Booking> $bookings
     *
     * @return Collection<int, Booking>
     */
    private function attachInspections(Collection $bookings): Collection
    {
        if ($bookings->isEmpty()) {
            return $bookings;
        }

        $inspections = Inspection::query()
            ->whereIn('booking_id', $bookings->modelKeys())
            ->get()
            ->keyBy('booking_id');

        return $bookings->each(function (Booking $booking) use ($inspections): void {
            $booking->setRelation('inspection', $inspections->get($booking->getKey()));
        });
    }

    /**
     * Every slot start time on a day, or an empty array when the shop is closed.
     *
     * A start is only offered when the whole job finishes by closing time, so a
     * 90-minute job on a day that shuts at 17:00 never starts at 16:30.
     *
     * @return list<CarbonInterface>
     */
    private function slotStartsFor(CarbonInterface $date, int $duration): array
    {
        $hours = $this->openHoursFor($date);

        if ($hours === null) {
            return [];
        }

        $open = $this->atTime($date, $hours['open']);
        $close = $this->atTime($date, $hours['close']);
        $interval = $this->slotIntervalMinutes();
        $starts = [];

        for ($cursor = $open; $cursor->copy()->addMinutes($duration)->lte($close); $cursor = $cursor->addMinutes($interval)) {
            $starts[] = $cursor;
        }

        return $starts;
    }

    /**
     * Determine whether a job starting now still finishes inside trading hours.
     */
    private function fitsBeforeClose(CarbonInterface $start, int $duration): bool
    {
        $hours = $this->openHoursFor($start);

        if ($hours === null) {
            return false;
        }

        return $start->copy()->addMinutes($duration)->lte($this->atTime($start, $hours['close']));
    }

    /**
     * Opening and closing times for the day, or null when the day is closed.
     *
     * @return array{open: string, close: string}|null
     */
    private function openHoursFor(CarbonInterface $date): ?array
    {
        if (in_array($date->toDateString(), $this->closedDateList(), true)) {
            return null;
        }

        $workingHours = $this->settings->get('working_hours', []);
        $day = $workingHours[strtolower($date->format('l'))] ?? null;

        if ($day === null || ($day['is_closed'] ?? true) || empty($day['open']) || empty($day['close'])) {
            return null;
        }

        return ['open' => $day['open'], 'close' => $day['close']];
    }

    /**
     * Closure dates held in memory for the life of this request.
     *
     * @return list<string>
     */
    private function closedDateList(): array
    {
        if ($this->closedDates === null) {
            $this->closedDates = BookingClosedDay::query()
                ->where('date', '>=', Date::today()->toDateString())
                ->orderBy('date')
                ->pluck('date')
                ->map(fn ($date): string => Date::parse($date)->toDateString())
                ->all();
        }

        return $this->closedDates;
    }

    /**
     * Read a capacity setting, where zero genuinely means zero bays, not "unset".
     */
    private function capacitySetting(string $key): int
    {
        return max(0, $this->intSetting($key, 1));
    }

    /**
     * Read a numeric setting, treating a blank form field as "not configured".
     *
     * The settings store keeps a cleared input as an empty string, and casting
     * that to an int would silently hand the shop 15-minute slots or zero bays.
     */
    private function intSetting(string $key, int $default): int
    {
        $value = $this->settings->get($key);

        return $value === null || $value === '' || $value === [] ? $default : (int) $value;
    }

    /**
     * The earliest instant a customer may book, ignoring slot alignment.
     */
    private function noticeCutoff(): CarbonInterface
    {
        return Date::now()->addHours($this->leadTimeHours());
    }

    /**
     * Build a Carbon instant on the given date at the supplied HH:MM wall-clock time.
     */
    private function atTime(CarbonInterface $date, string $time): CarbonInterface
    {
        return Date::parse($date->format('Y-m-d').' '.$time);
    }

    /**
     * Move forward to the next slot boundary on the day's own grid.
     */
    private function alignForward(CarbonInterface $moment): CarbonInterface
    {
        $hours = $this->openHoursFor($moment);
        $grid = $hours === null
            ? $moment->copy()->startOfDay()
            : $this->atTime($moment, $hours['open']);

        $candidate = $grid->copy();
        $interval = $this->slotIntervalMinutes();

        while ($candidate->lt($moment)) {
            $candidate = $candidate->addMinutes($interval);
        }

        return $candidate;
    }
}
