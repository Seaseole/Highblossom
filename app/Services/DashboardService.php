<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BookingServiceType;
use App\Models\Booking;
use App\Models\BookingEvent;
use App\Models\ContactMessage;
use App\Models\Inspection;
use App\Models\Quote;
use App\Models\User;
use App\Services\Contracts\AvailabilityServiceInterface;
use App\Services\Settings\SettingsManager;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

/**
 * Builds the day desk: the shop's own day, measured against its own rules.
 *
 * The rail is drawn from the trading hours and the per-job durations that
 * `AvailabilityService` already uses to decide what is bookable, so a span on the
 * desk can never show a time the booking form would have refused. Nothing here is
 * cached: the numbers are read fresh on every visit, and the answer is a set of
 * primitives, so the view never touches the database itself.
 *
 * Every figure carries the window it was counted over, and a comparison only
 * appears when the window before it actually exists.
 */
final class DashboardService
{
    /** How far back the quote-arrival series reaches. */
    private const ARRIVAL_WINDOW_DAYS = 14;

    /** How many days forward the workload row looks. */
    private const FORECAST_DAYS = 7;

    /** How far back the milestone rail reaches. */
    private const ACTIVITY_WINDOW_HOURS = 24;

    /** How many days of fitting the service mix is drawn from. */
    private const MIX_WINDOW_DAYS = 90;

    /** How many days of completed work the team row measures. */
    private const TEAM_WINDOW_DAYS = 7;

    /** How many days of comparison a ledger figure carries. */
    private const LEDGER_WINDOW_DAYS = 7;

    /** Rows per group before the attention list folds. */
    private const ATTENTION_LIMIT = 6;

    private const MILESTONE_LIMIT = 9;

    public function __construct(
        private readonly AvailabilityServiceInterface $availability,
        private readonly SettingsManager $settings,
    ) {}

    /**
     * Everything the day desk shows, as plain arrays.
     *
     * @return array<string, mixed>
     */
    public function desk(): array
    {
        $today = CarbonImmutable::today();
        $now = CarbonImmutable::now();
        $hours = $this->availability->openHoursOn($today);
        $day = $this->day($today, $now, $hours);
        $rail = $this->rail($today, $now, $hours);

        return [
            'day' => $day,
            'rail' => $rail,
            'attention' => $this->attention(),
            'pipeline' => $this->pipeline($today),
            'week' => $this->week($today, $now),
            'mix' => $this->mix($today),
            'team' => $this->team($now),
            'milestones' => $this->milestones($now),
            'ledger' => $this->ledger($now),
            'colophon' => [
                'measured_at' => $this->availability->formatTime($now),
                'measured_iso' => $now->toIso8601String(),
                'timezone' => (string) config('app.timezone'),
                'window_label' => $day['window_label'],
                'references' => [
                    __('admin-dashboard.source_bookings'),
                    __('admin-dashboard.source_quotes'),
                    __('admin-dashboard.source_milestones'),
                ],
            ],
        ];
    }

    /**
     * The day itself: its bounds, where now falls inside them, and what is left.
     *
     * @param array{open: string, close: string}|null $hours
     *
     * @return array<string, mixed>
     */
    private function day(CarbonImmutable $today, CarbonImmutable $now, ?array $hours): array
    {
        $base = [
            'label' => $today->translatedFormat('l j F Y'),
            'short_label' => $today->translatedFormat('D j M'),
            'iso' => $today->toDateString(),
            'clock' => $this->availability->formatTime($now),
            'clock_iso' => $now->toIso8601String(),
            'is_open' => $hours !== null,
            'open' => null,
            'close' => null,
            'window_minutes' => 0,
            'window_label' => null,
            'minutes_in' => null,
            'minutes_left' => null,
            'now_position' => null,
            'state_label' => __('admin-dashboard.day_closed'),
            'next_open_label' => null,
            // The clock hands the desk module: where the render stood, and in what shape to print.
            'render_minute' => round((int) $now->format('G') * 60 + (int) $now->format('i') + (int) $now->format('s') / 60, 3),
            'render_epoch' => $now->getTimestampMs(),
            'open_minute' => null,
            'clock_format' => (string) $this->settings->get('time_format_display', '12') === '24' ? '24' : '12',
        ];

        if ($hours === null) {
            return array_merge($base, [
                'next_open_label' => $this->nextOpenDay($today)?->translatedFormat('l j F'),
            ]);
        }

        [$open, $close] = $this->bounds($today, $hours);
        $window = $this->minutesBetween($open, $close);
        $minutesIn = $this->minutesBetween($open, $now);
        $clampedIn = max(0, min($window, $minutesIn));

        return array_merge($base, [
            'open' => $this->availability->formatTime($open),
            'close' => $this->availability->formatTime($close),
            'window_minutes' => $window,
            'window_label' => $this->durationLabel($window),
            'minutes_in' => $minutesIn,
            'minutes_left' => max(0, $window - $minutesIn),
            'now_position' => $window > 0 ? round($clampedIn / $window * 100, 3) : null,
            'state_label' => match (true) {
                $minutesIn < 0 => __('admin-dashboard.day_before_open'),
                $minutesIn > $window => __('admin-dashboard.day_after_close'),
                default => __('admin-dashboard.day_in_progress'),
            },
            'open_minute' => (int) $open->format('G') * 60 + (int) $open->format('i'),
            'label_before' => __('admin-dashboard.day_before_open'),
            'label_in' => __('admin-dashboard.day_in_progress'),
            'label_after' => __('admin-dashboard.day_after_close'),
        ]);
    }

    /**
     * The rail: two lanes on one scale, each job laid at the length it actually holds.
     *
     * @param array{open: string, close: string}|null $hours
     *
     * @return array<string, mixed>
     */
    private function rail(CarbonImmutable $today, CarbonImmutable $now, ?array $hours): array
    {
        $jobs = Booking::query()
            ->with(['inspection:id,booking_id,started_at,ended_at,staff_id', 'inspection.staff:id,name'])
            ->whereDate('scheduled_at', $today)
            ->orderBy('scheduled_at')
            ->get();

        $cancelled = $jobs->filter(fn (Booking $job): bool => $job->status === 'cancelled');
        $working = $jobs->reject(fn (Booking $job): bool => $job->status === 'cancelled');

        if ($hours === null) {
            $open = $today->setTime(0, 0);
            $close = $today->setTime(23, 59);
            $window = $this->minutesBetween($open, $close);
            $bounded = false;
        } else {
            [$open, $close] = $this->bounds($today, $hours);
            $window = $this->minutesBetween($open, $close);
            $bounded = true;
        }

        $spans = $working
            ->map(fn (Booking $job): array => $this->span($job, $open, $window))
            ->values()
            ->all();

        $next = $this->nextJob($spans);

        return [
            'bounded' => $bounded,
            'ticks' => $bounded ? $this->ticks($open, $close, $window) : [],
            'lanes' => [
                $this->lane('workshop', $spans, $window),
                $this->lane('mobile', $spans, $window),
            ],
            'counts' => [
                'today' => count($spans),
                'cancelled' => $cancelled->count(),
                'later' => count(array_filter(
                    $spans,
                    static fn (array $span): bool => in_array($span['state'], ['waiting', 'confirmed'], true)
                )),
            ],
            'next' => $next,
            'legend' => $this->legend(),
        ];
    }

    /**
     * One job as a measured span, clipped to the day it belongs to.
     *
     * @return array<string, mixed>
     */
    private function span(Booking $job, CarbonImmutable $open, int $window): array
    {
        $start = $job->scheduled_at instanceof DateTimeInterface
            ? CarbonImmutable::instance($job->scheduled_at)
            : $open;

        $minutesIn = $this->minutesBetween($open, $start);
        $duration = $job->durationMinutes();
        $inspection = $job->inspection;
        $canReadBookings = Gate::allows('view bookings');
        $canReadInspections = Gate::allows('view inspections');

        $left = $window > 0 ? max(0, min($window, $minutesIn)) : 0;
        $right = $window > 0 ? max(0, min($window, $minutesIn + $duration)) : 0;

        $vehicle = trim(implode(' ', array_filter([$job->vehicle_make, $job->vehicle_model])));

        return [
            'id' => $job->id,
            'reference' => $job->displayReference(),
            'who' => $job->client_name,
            'service' => $job->service_type?->label() ?? __('admin-dashboard.service_unspecified'),
            'lane' => $job->location === 'mobile' ? 'mobile' : 'workshop',
            'vehicle' => $vehicle,
            'start_label' => $this->availability->formatTime($start),
            'end_label' => $job->ends_at === null ? null : $this->availability->formatTime($job->ends_at),
            'duration' => $duration,
            'duration_label' => $this->durationLabel($duration),
            'state' => $this->stateFor($job),
            'staff' => $inspection?->staff?->name,
            'left' => $window > 0 ? round($left / $window * 100, 3) : 0.0,
            'width' => $window > 0 ? max(0.8, round(($right - $left) / $window * 100, 3)) : 0.0,
            'overrun' => max(0, $minutesIn + $duration - $window),
            'starts_early' => $minutesIn < 0,
            'href' => match (true) {
                $canReadBookings => route('admin.bookings.show', $job),
                $canReadInspections && $inspection !== null => route('admin.inspections.show', $inspection),
                default => null,
            },
        ];
    }

    /**
     * What the job looks like right now, from its status and its inspection.
     */
    private function stateFor(Booking $job): string
    {
        if ($job->status === 'cancelled') {
            return 'cancelled';
        }

        $inspection = $job->inspection;

        if ($inspection?->ended_at !== null) {
            return 'completed';
        }

        if ($inspection?->started_at !== null) {
            return 'in_progress';
        }

        if ($job->ends_at !== null && $job->ends_at->isPast()) {
            return 'overdue';
        }

        return $job->status === 'confirmed' ? 'confirmed' : 'waiting';
    }

    /**
     * The lane a set of spans sits in, and how much of the day it holds.
     *
     * @param array<int, array<string, mixed>> $spans
     *
     * @return array<string, mixed>
     */
    private function lane(string $key, array $spans, int $window): array
    {
        $mine = array_values(array_filter(
            $spans,
            static fn (array $span): bool => $span['lane'] === $key
        ));

        $busy = (int) array_sum(array_column($mine, 'duration'));
        $states = array_count_values(array_column($mine, 'state'));
        $capacity = max(1, $this->availability->capacityFor($key));

        return [
            'key' => $key,
            'label' => $key === 'mobile'
                ? __('admin-dashboard.lane_mobile')
                : __('admin-dashboard.lane_workshop'),
            'hint' => $key === 'mobile'
                ? __('admin-dashboard.lane_mobile_hint')
                : __('admin-dashboard.lane_workshop_hint'),
            'capacity' => $capacity,
            'count' => count($mine),
            'busy_label' => $this->durationLabel($busy),
            'utilisation' => $window > 0 ? (int) min(100, round($busy / ($window * $capacity) * 100)) : 0,
            'spans' => $mine,
            'running' => $states['in_progress'] ?? 0,
            'queued' => ($states['waiting'] ?? 0) + ($states['confirmed'] ?? 0),
            'late' => $states['overdue'] ?? 0,
            // Two lanes are two different kinds of empty, and one sentence
            // printed twice down a page is the same claim nobody checked.
            'empty_label' => $key === 'mobile'
                ? __('admin-dashboard.rail_empty_mobile')
                : __('admin-dashboard.rail_empty_bay'),
        ];
    }

    /**
     * Ticks along the scale, at a step that keeps every label readable.
     *
     * @return array<int, array<string, mixed>>
     */
    private function ticks(CarbonImmutable $open, CarbonImmutable $close, int $window): array
    {
        $step = $window > 330 ? 60 : 30;
        $ticks = [];

        for ($minute = 0; $minute < $window; $minute += $step) {
            $ticks[] = [
                'label' => $this->availability->formatTime($open->addMinutes($minute)),
                'position' => round($minute / max(1, $window) * 100, 3),
            ];
        }

        $ticks[] = [
            'label' => $this->availability->formatTime($close),
            'position' => 100.0,
        ];

        return $ticks;
    }

    /**
     * The first job that has not finished, which is the one the day hangs on.
     *
     * @param array<int, array<string, mixed>> $spans
     *
     * @return array<string, mixed>|null
     */
    private function nextJob(array $spans): ?array
    {
        foreach ($spans as $span) {
            if (! in_array($span['state'], ['cancelled', 'completed'], true)) {
                return $span;
            }
        }

        return null;
    }

    /**
     * The states a span can be in, in the order the legend reads them.
     *
     * @return array<int, array<string, mixed>>
     */
    private function legend(): array
    {
        return array_map(
            static fn (string $key): array => [
                'key' => $key,
                'label' => __('admin-dashboard.state.'.$key),
            ],
            ['in_progress', 'waiting', 'confirmed', 'overdue', 'completed']
        );
    }

    /**
     * Quote work by status, plus the arrivals that made it.
     *
     * @return array<string, mixed>
     */
    private function pipeline(CarbonImmutable $today): array
    {
        $counts = Quote::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->all();

        $ordered = [];

        foreach (['pending', 'contacted', 'completed', 'cancelled'] as $status) {
            $ordered[$status] = (int) ($counts[$status] ?? 0);
            unset($counts[$status]);
        }

        foreach ($counts as $status => $count) {
            $ordered[(string) $status] = (int) $count;
        }

        $total = (int) array_sum($ordered);
        $statuses = [];

        foreach ($ordered as $status => $count) {
            $statuses[] = [
                'key' => $status,
                'label' => __('admin-dashboard.'.$status),
                'value' => $count,
                'position' => $total > 0 ? round($count / $total * 100, 3) : 0.0,
                'href' => Gate::allows('view bookings')
                    ? route('admin.quotes.index', ['status' => $status])
                    : null,
            ];
        }

        $from = $today->subDays(self::ARRIVAL_WINDOW_DAYS - 1);
        $perDay = [];

        for ($offset = 0; $offset < self::ARRIVAL_WINDOW_DAYS; $offset++) {
            $perDay[$from->addDays($offset)->toDateString()] = 0;
        }

        foreach (Quote::query()->where('created_at', '>=', $from)->pluck('created_at') as $moment) {
            $key = $moment->toDateString();

            if (array_key_exists($key, $perDay)) {
                $perDay[$key]++;
            }
        }

        $peak = (int) max($perDay);
        $days = [];

        foreach ($perDay as $date => $count) {
            $moment = CarbonImmutable::parse($date);

            $days[] = [
                'date' => $date,
                'label' => $moment->format('j'),
                'weekday' => $moment->translatedFormat('D'),
                'value' => $count,
                'position' => $peak > 0 ? round($count / $peak * 100, 2) : 0.0,
            ];
        }

        $arrivalTotal = (int) array_sum($perDay);

        return [
            'total' => $total,
            'statuses' => $statuses,
            'open' => ($ordered['pending'] ?? 0) + ($ordered['contacted'] ?? 0),
            'arrivals' => [
                'days' => $days,
                'window' => self::ARRIVAL_WINDOW_DAYS,
                'total' => $arrivalTotal,
                'peak' => $peak,
                // $perDay is keyed by date, so array_search hands back the key, not an
                // index. Casting it to an int and adding it to $from once printed a
                // busiest day in May inside a window that ran to November.
                'peak_label' => $peak > 0
                    ? $this->dayLabel(CarbonImmutable::parse((string) array_search($peak, $perDay, true)))
                    : null,
                'average' => round($arrivalTotal / self::ARRIVAL_WINDOW_DAYS, 1),
                'quiet' => count(array_filter($perDay, static fn (int $count): bool => $count === 0)),
                'spark' => $this->sparkline($days),
            ],
        ];
    }

    /**
     * A polyline and its filled area, in a 100 by 32 box, from the same numbers.
     *
     * @param array<int, array<string, mixed>> $days
     *
     * @return array{line: string, area: string}
     */
    private function sparkline(array $days): array
    {
        $last = max(1, count($days) - 1);
        $points = [];

        foreach (array_values($days) as $index => $day) {
            $points[] = sprintf(
                '%s,%s',
                round($index / $last * 100, 2),
                round(32 - ($day['position'] / 100 * 28) - 2, 2)
            );
        }

        $line = implode(' ', $points);

        return ['line' => $line, 'area' => $line === '' ? '' : '0,32 '.$line.' 100,32'];
    }

    /**
     * The week ahead, one bar per day, with today marked.
     *
     * @return array<string, mixed>
     */
    private function week(CarbonImmutable $today, CarbonImmutable $now): array
    {
        $until = $today->addDays(self::FORECAST_DAYS - 1)->endOfDay();
        $perDay = [];

        for ($offset = 0; $offset < self::FORECAST_DAYS; $offset++) {
            $perDay[$today->addDays($offset)->toDateString()] = 0;
        }

        $scheduled = Booking::query()
            ->where('status', '!=', 'cancelled')
            ->whereBetween('scheduled_at', [$today->startOfDay(), $until])
            ->pluck('scheduled_at');

        foreach ($scheduled as $moment) {
            $key = $moment->toDateString();

            if (array_key_exists($key, $perDay)) {
                $perDay[$key]++;
            }
        }

        $peak = (int) max($perDay);
        $days = [];

        foreach ($perDay as $date => $count) {
            $moment = CarbonImmutable::parse($date);

            $days[] = [
                'date' => $date,
                'weekday' => $moment->translatedFormat('D'),
                'day' => $moment->format('j'),
                'label' => $this->dayLabel($moment),
                'value' => $count,
                'position' => $peak > 0 ? round($count / $peak * 100, 2) : 0.0,
                'is_today' => $date === $today->toDateString(),
                'is_closed' => ! $this->availability->isOpenDay($moment),
                'href' => Gate::allows('view bookings') ? route('admin.bookings.index') : null,
            ];
        }

        return [
            'days' => $days,
            'window' => self::FORECAST_DAYS,
            'total' => (int) array_sum($perDay),
            'busiest' => $peak > 0
                ? $this->dayLabel(CarbonImmutable::parse((string) array_search($peak, $perDay, true)))
                : null,
            'closed_count' => count(array_filter($days, static fn (array $day): bool => $day['is_closed'])),
        ];
    }

    /**
     * What the shop actually fits, over the window it has been fitting it.
     *
     * @return array<string, mixed>
     */
    private function mix(CarbonImmutable $today): array
    {
        $from = $today->subDays(self::MIX_WINDOW_DAYS);

        $counts = Booking::query()
            ->where('created_at', '>=', $from)
            ->selectRaw('service_type, count(*) as aggregate')
            ->groupBy('service_type')
            ->pluck('aggregate', 'service_type')
            ->all();

        $total = (int) array_sum($counts);
        $rows = [];
        $minutes = 0;

        foreach (BookingServiceType::selectable() as $case) {
            $value = (int) ($counts[$case->value] ?? 0);
            unset($counts[$case->value]);

            if ($value === 0) {
                continue;
            }

            $duration = $this->availability->durationFor($case->value);
            $minutes += $value * $duration;

            $rows[] = [
                'key' => $case->value,
                'label' => $case->label(),
                'value' => $value,
                'position' => $total > 0 ? round($value / $total * 100, 2) : 0.0,
                'duration' => $this->durationLabel($duration),
                'href' => Gate::allows('view bookings') ? route('admin.bookings.index') : null,
            ];
        }

        foreach ($counts as $type => $value) {
            $duration = $this->availability->durationFor((string) $type ?: null);
            $minutes += (int) $value * $duration;

            $rows[] = [
                'key' => (string) $type,
                'label' => __('admin-dashboard.service_unspecified'),
                'value' => (int) $value,
                'position' => $total > 0 ? round((int) $value / $total * 100, 2) : 0.0,
                'duration' => $this->durationLabel($duration),
                'href' => null,
            ];
        }

        return [
            'rows' => $rows,
            'total' => $total,
            'window' => self::MIX_WINDOW_DAYS,
            'on_glass' => $this->durationLabel($minutes),
        ];
    }

    /**
     * Who carries the work, measured over the window just worked.
     *
     * @return array<string, mixed>
     */
    private function team(CarbonImmutable $now): array
    {
        $from = $now->subDays(self::TEAM_WINDOW_DAYS);
        // The window runs to the end of today, not to the current minute: an
        // appointment booked for this afternoon is this week's work, and a row
        // that ignores everything after the clock is a row that lies about load.
        $until = $now->endOfDay();

        $inspections = Inspection::query()
            ->with(['staff:id,name'])
            ->whereBetween('scheduled_at', [$from, $until])
            ->get(['id', 'booking_id', 'staff_id', 'started_at', 'ended_at', 'scheduled_at']);

        $byStaff = [];
        $unassigned = 0;

        foreach ($inspections as $inspection) {
            $name = $inspection->staff?->name;

            if ($name === null) {
                $unassigned++;

                continue;
            }

            $byStaff[$name] = ($byStaff[$name] ?? 0) + 1;
        }

        arsort($byStaff);

        $total = $inspections->count();
        $peak = $byStaff === [] ? 0 : (int) reset($byStaff);
        $rows = [];

        foreach ($byStaff as $name => $value) {
            $rows[] = [
                'key' => $name,
                'label' => $name,
                'value' => $value,
                'position' => $peak > 0 ? round($value / $peak * 100, 2) : 0.0,
                'share' => $total > 0 ? (int) round($value / $total * 100) : 0,
                'href' => Gate::allows('view inspections') ? route('admin.inspections.index') : null,
            ];
        }

        if ($unassigned > 0) {
            $rows[] = [
                'key' => 'unassigned',
                'label' => __('admin-dashboard.unassigned'),
                'value' => $unassigned,
                'position' => $peak > 0 ? round($unassigned / max($peak, $unassigned) * 100, 2) : 100.0,
                'share' => $total > 0 ? (int) round($unassigned / $total * 100) : 0,
                'href' => Gate::allows('view inspections') ? route('admin.inspections.index') : null,
            ];
        }

        $finished = $inspections->filter(static fn (Inspection $inspection): bool => $inspection->ended_at !== null)->count();

        return [
            'rows' => $rows,
            'total' => $total,
            'window' => self::TEAM_WINDOW_DAYS,
            'finished' => $finished,
            'open_jobs' => $total - $finished,
        ];
    }

    /**
     * The queue a visit here should empty: waiting quotes, running jobs, unread mail.
     *
     * @return array<string, mixed>
     */
    private function attention(): array
    {
        $now = CarbonImmutable::now();
        $rows = [];

        if (Gate::allows('view bookings')) {
            $quotes = Quote::query()
                ->where('status', 'pending')
                ->orderBy('created_at')
                ->limit(self::ATTENTION_LIMIT)
                ->get(['id', 'name', 'created_at', 'mobile_service']);

            foreach ($quotes as $quote) {
                $minutes = $this->minutesBetween(CarbonImmutable::instance($quote->created_at), $now);

                $rows[] = [
                    'kind' => 'quote',
                    'tone' => $minutes >= 1440 ? 'late' : 'waiting',
                    'title' => (string) $quote->name,
                    'detail' => $quote->mobile_service
                        ? __('admin-dashboard.quote_mobile')
                        : __('admin-dashboard.quote_workshop'),
                    'age' => $this->measureAge($minutes),
                    'minutes' => $minutes,
                    'moment' => $quote->created_at->toIso8601String(),
                    'href' => route('admin.quotes.show', $quote),
                ];
            }
        }

        if (Gate::allows('view inspections')) {
            $running = Inspection::query()
                ->with(['booking:id,client_name', 'staff:id,name'])
                ->whereNotNull('started_at')
                ->whereNull('ended_at')
                ->orderBy('started_at')
                ->limit(self::ATTENTION_LIMIT)
                ->get();

            foreach ($running as $inspection) {
                $minutes = $this->minutesBetween(CarbonImmutable::instance($inspection->started_at), $now);

                $rows[] = [
                    'kind' => 'inspection',
                    'tone' => 'running',
                    'title' => $inspection->booking?->client_name ?? __('admin-dashboard.unassigned'),
                    'detail' => $inspection->staff?->name ?? __('admin-dashboard.unassigned'),
                    'age' => $this->measureAge($minutes),
                    'minutes' => $minutes,
                    'moment' => $inspection->started_at->toIso8601String(),
                    'href' => route('admin.inspections.show', $inspection),
                ];
            }
        }

        if (Gate::allows('view contact messages')) {
            $messages = ContactMessage::query()
                ->where('is_read', false)
                ->orderBy('created_at')
                ->limit(self::ATTENTION_LIMIT)
                ->get(['id', 'name', 'subject', 'created_at']);

            foreach ($messages as $message) {
                $minutes = $this->minutesBetween(CarbonImmutable::instance($message->created_at), $now);

                $rows[] = [
                    'kind' => 'message',
                    'tone' => $minutes >= 4320 ? 'late' : 'waiting',
                    'title' => ($message->subject ?? '') !== '' ? $message->subject : $message->name,
                    'detail' => $message->name,
                    'age' => $this->measureAge($minutes),
                    'minutes' => $minutes,
                    'moment' => $message->created_at->toIso8601String(),
                    'href' => route('admin.contact-messages.show', $message),
                ];
            }
        }

        $pendingQuotes = Gate::allows('view bookings')
            ? Quote::query()->where('status', 'pending')->count()
            : 0;
        $openInspections = Gate::allows('view inspections')
            ? Inspection::query()->whereNotNull('started_at')->whereNull('ended_at')->count()
            : 0;
        $unread = Gate::allows('view contact messages')
            ? ContactMessage::query()->where('is_read', false)->count()
            : 0;

        $groups = [];

        if ($pendingQuotes > 0) {
            $groups[] = [
                'key' => 'quote',
                'label' => __('admin-dashboard.quotes_waiting'),
                'value' => $pendingQuotes,
                'href' => route('admin.quotes.index', ['status' => 'pending']),
            ];
        }

        if ($openInspections > 0) {
            $groups[] = [
                'key' => 'inspection',
                'label' => __('admin-dashboard.inspections_open'),
                'value' => $openInspections,
                'href' => route('admin.inspections.index'),
            ];
        }

        if ($unread > 0) {
            $groups[] = [
                'key' => 'message',
                'label' => __('admin-dashboard.unread_messages'),
                'value' => $unread,
                'href' => route('admin.contact-messages.index'),
            ];
        }

        return [
            'rows' => $rows,
            'count' => $pendingQuotes + $openInspections + $unread,
            'groups' => $groups,
        ];
    }

    /**
     * The milestones of the last day, in the order they happened.
     *
     * @return array<string, mixed>
     */
    private function milestones(CarbonImmutable $now): array
    {
        $from = $now->subHours(self::ACTIVITY_WINDOW_HOURS);

        $events = BookingEvent::query()
            ->with(['booking:id,reference,client_name', 'actor:id,name'])
            ->where('happened_at', '>=', $from)
            ->orderByDesc('happened_at')
            ->limit(self::MILESTONE_LIMIT)
            ->get();

        $rows = [];

        foreach ($events as $event) {
            if ($event->happened_at === null) {
                // A milestone without a moment cannot be placed on a time scale,
                // and it must not be the reason the desk will not render.
                continue;
            }

            $happened = CarbonImmutable::instance($event->happened_at);

            $rows[] = [
                'kind' => $event->type,
                'label' => __('admin-dashboard.milestone.'.$event->type),
                'summary' => $event->summary,
                'reference' => $event->booking?->displayReference() ?? '—',
                'who' => $event->booking?->client_name,
                'actor' => $event->actor?->name,
                'when' => $this->availability->formatTime($happened),
                'moment' => $happened->toIso8601String(),
                'ago' => $this->measureAge($this->minutesBetween($happened, $now)),
                'href' => Gate::allows('view bookings') && $event->booking !== null
                    ? route('admin.bookings.show', $event->booking)
                    : null,
            ];
        }

        return [
            'rows' => $rows,
            'window' => self::ACTIVITY_WINDOW_HOURS,
            'from_label' => $this->availability->formatTime($from),
            'count' => $events->count(),
        ];
    }

    /**
     * Totals, each with the honest comparison beside it.
     *
     * Each row is one query: the total and both windows come back in the same
     * pass, because three counts per figure would be nine queries for numbers
     * that describe one table.
     *
     * @return array<int, array<string, mixed>>
     */
    private function ledger(CarbonImmutable $now): array
    {
        $weekStart = $now->subDays(self::LEDGER_WINDOW_DAYS);
        $fortnight = $now->subDays(self::LEDGER_WINDOW_DAYS * 2);
        $current = [$weekStart, $now];
        $previous = [$fortnight, $weekStart];

        $rows = [];

        [$total, $count, $prior] = $this->triple(Booking::query(), 'created_at', $current, $previous);
        $rows[] = $this->figure(
            'bookings',
            __('admin-dashboard.total_bookings'),
            $total,
            $count,
            $prior,
            Gate::allows('view bookings') ? route('admin.bookings.index') : null,
        );

        [$total, $count, $prior] = $this->triple(Quote::query(), 'created_at', $current, $previous);
        $rows[] = $this->figure(
            'quotes',
            __('admin-dashboard.quotes'),
            $total,
            $count,
            $prior,
            Gate::allows('view bookings') ? route('admin.quotes.index') : null,
        );

        [$total, $count, $prior] = $this->triple(
            Inspection::query()->whereNotNull('ended_at'),
            'ended_at',
            $current,
            $previous
        );
        $rows[] = $this->figure(
            'fitted',
            __('admin-dashboard.fitted'),
            $total,
            $count,
            $prior,
            Gate::allows('view inspections') ? route('admin.inspections.index') : null,
        );

        if (Gate::allows('manage users')) {
            [$total, $count, $prior] = $this->triple(User::query(), 'created_at', $current, $previous);

            $rows[] = $this->figure('users', __('admin-dashboard.active_users'), $total, $count, $prior, route('admin.users.index'));
        }

        return $rows;
    }

    /**
     * A total plus two windows, in one pass, using portable conditional counts.
     *
     * @param Builder<Booking|Quote|Inspection|User>        $query
     * @param array{0: CarbonImmutable, 1: CarbonImmutable} $current
     * @param array{0: CarbonImmutable, 1: CarbonImmutable} $previous
     *
     * @return array{0: int, 1: int, 2: int}
     */
    private function triple(Builder $query, string $column, array $current, array $previous): array
    {
        $row = $query->selectRaw(
            sprintf(
                'count(*) as aggregate_total,'
                .' sum(case when %s between ? and ? then 1 else 0 end) as aggregate_current,'
                .' sum(case when %s between ? and ? then 1 else 0 end) as aggregate_previous',
                $column,
                $column
            ),
            [$current[0], $current[1], $previous[0], $previous[1]]
        )->first();

        return [
            (int) ($row->aggregate_total ?? 0),
            (int) ($row->aggregate_current ?? 0),
            (int) ($row->aggregate_previous ?? 0),
        ];
    }

    /**
     * One total, its week, and the difference against the week before it.
     *
     * @return array<string, mixed>
     */
    private function figure(string $key, string $label, int $total, int $current, int $previous, ?string $href): array
    {
        $delta = $current - $previous;

        return [
            'key' => $key,
            'label' => $label,
            'value' => $total,
            'current' => $current,
            'previous' => $previous,
            'delta' => $delta,
            'delta_label' => $current === 0 && $previous === 0
                ? __('admin-dashboard.no_change')
                : sprintf('%+d', $delta),
            'trend' => $delta > 0 ? 'up' : ($delta < 0 ? 'down' : 'flat'),
            'window' => __('admin-dashboard.per_week'),
            'href' => $href,
        ];
    }

    /**
     * The first open day after today, when the week reaches one.
     */
    private function nextOpenDay(CarbonImmutable $today): ?CarbonImmutable
    {
        for ($offset = 1; $offset <= 7; $offset++) {
            $candidate = $today->addDays($offset);

            if ($this->availability->isOpenDay($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * The day's opening and closing moments, in the application timezone.
     *
     * @param array{open: string, close: string} $hours
     *
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}
     */
    private function bounds(CarbonImmutable $today, array $hours): array
    {
        $date = $today->toDateString();

        return [
            CarbonImmutable::parse($date.' '.$hours['open']),
            CarbonImmutable::parse($date.' '.$hours['close']),
        ];
    }

    /**
     * Whole minutes from one moment to the other, signed so a future moment reads negative.
     */
    private function minutesBetween(DateTimeInterface $from, DateTimeInterface $to): int
    {
        return (int) round(($to->getTimestamp() - $from->getTimestamp()) / 60);
    }

    /**
     * A duration in minutes, said the way the shop says it.
     */
    private function durationLabel(int $minutes): string
    {
        $sign = $minutes < 0 ? '−' : '';
        $minutes = abs($minutes);

        if ($minutes < 60) {
            return $sign.$minutes.'m';
        }

        $hours = intdiv($minutes, 60);
        $rest = $minutes % 60;

        return $rest === 0 ? $sign.$hours.'h' : sprintf('%s%dh %02dm', $sign, $hours, $rest);
    }

    /**
     * How long something has been waiting, as a measured pair rather than prose.
     *
     * Waiting is counted in days once it passes a day, because "1467h" is a true
     * number that nobody can act on and "61d" is the same fact at the size of the
     * problem.
     */
    private function measureAge(int $minutes): string
    {
        $minutes = max(0, $minutes);

        if ($minutes < 1440) {
            return $this->durationLabel($minutes);
        }

        return sprintf('%dd %02dh', intdiv($minutes, 1440), intdiv($minutes % 1440, 60));
    }

    /**
     * A weekday with its date, used wherever a single day is named.
     */
    private function dayLabel(CarbonImmutable $moment): string
    {
        return $moment->translatedFormat('D j M');
    }
}
