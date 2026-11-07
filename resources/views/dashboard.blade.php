<x-layouts::admin title="{{ __('admin-dashboard.title') }}">
    <div
        class="hb-dash mx-auto max-w-[86rem]"
        data-dash
        data-render-minute="{{ $day['render_minute'] }}"
        data-render-epoch="{{ $day['render_epoch'] }}"
        data-clock-format="{{ $day['clock_format'] }}"
        @if ($day['is_open']) data-open-minute="{{ $day['open_minute'] }}" data-window-minutes="{{ $day['window_minutes'] }}" @endif
    >
        {{-- ============================================================
             MASTHEAD. The clock is the largest glyph on the page because
             the clock is what everything else on this page is measured
             against. The date sits under the page name, not above it.
             ============================================================ --}}
        <header class="hb-mast" data-dash-reveal>
            <div class="hb-mast-text">
                <h1 class="hb-h1">{{ __('admin-dashboard.title')  }}</h1>
                <p class="hb-dayline">
                    <time datetime="{{ $day['iso'] }}">{{ $day['label'] }}</time>
                    <span class="hb-sep" aria-hidden="true">·</span>
                    <span
                        class="hb-state"
                        data-dash-state
                        @if ($day['is_open'])
                            data-label-before="{{ $day['label_before'] }}"
                            data-label-in="{{ $day['label_in'] }}"
                            data-label-after="{{ $day['label_after'] }}"
                        @endif
                    >{{ $day['state_label'] }}</span>                </p>
                <p class="hb-lede">{{ __('admin-dashboard.welcome', ['name' => explode(' ', auth()->user()->name)[0]]) }}</p>
            </div>

            <dl class="hb-day">
                <div class="hb-day-cell hb-day-cell-clock">
                    <dt>{{ __('admin-dashboard.clock_label') }}</dt>
                    <dd>
                        <time class="hb-clock" datetime="{{ $day['clock_iso'] }}" data-dash-clock>{{ $day['clock'] }}</time>
                    </dd>
                </div>
                <div class="hb-day-cell">
                    <dt>{{ __('admin-dashboard.open_label') }}</dt>
                    <dd class="hb-num">{{ $day['open'] ?? __('admin-dashboard.closed_day') }}</dd>
                </div>
                <div class="hb-day-cell">
                    <dt>{{ __('admin-dashboard.close_label') }}</dt>
                    <dd class="hb-num">{{ $day['close'] ?? '—' }}</dd>
                </div>
                <div class="hb-day-cell">
                    <dt>{{ __('admin-dashboard.left_label') }}</dt>
                    <dd class="hb-num">
                        @if ($day['minutes_left'] === null)
                            —
                        @else
                            <span>{{ $day['minutes_left'] }}</span>
                            <span class="hb-unit">m</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </header>

        {{-- ============================================================
             THE RAIL. Two lanes on one scale; a span's width is the
             duration the shop itself gave that service. The now line is
             the only thing here that moves by itself, and it moves
             because the day is actually passing.
             ============================================================ --}}
        <section class="hb-band" aria-labelledby="rail-title" data-dash-reveal>
            <div class="hb-head">
                <div class="hb-head-text">
                    <h2 class="hb-h2" id="rail-title">{{ __('admin-dashboard.fittings_today') }}</h2>
                    <p class="hb-hint">{{ $rail['bounded'] ? __('admin-dashboard.rail_hint') : __('admin-dashboard.rail_closed') }}</p>
                </div>
                <ul class="hb-legend" aria-label="{{ __('admin-dashboard.state_legend') }}">
                    @foreach ($rail['legend'] as $state)
                        <li class="hb-legend-item hb-legend-item--{{ $state['key'] }}">
                            <span class="hb-mark" aria-hidden="true"></span>{{ $state['label'] }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Phone and collapsed-rail form of the same measure: a day bar with the
                 now mark on it, then the jobs listed in the order they happen. --}}
            {{-- Inside the rail's own container, because both forms of the measure
                 are chosen by the same width: this bar is the day when there is no
                 room for the scale, and it must disappear exactly when the scale
                 arrives. Outside that container the query could not see it. --}}
            <div class="hb-rail-wrap">
                <div class="hb-daybar" @if ($day['now_position'] === null) hidden @endif>
                    <div class="hb-daybar-track" data-dash-daybar style="--nowf: {{ (($day['now_position'] ?? 0) / 100) }}">
                        <span class="hb-daybar-now" aria-hidden="true"></span>
                    </div>
                    <p class="hb-daybar-meta">
                        <span class="hb-num">{{ $day['open'] ?? '—' }}</span>
                        <span class="hb-muted">{{ __('admin-dashboard.window_label') }}</span>
                        <span class="hb-num">{{ $day['close'] ?? '—' }}</span>
                    </p>
                </div>

                <div class="hb-rail" data-dash-rail style="--nowf: {{ (($day['now_position'] ?? 0) / 100) }}">
                    <div class="hb-scale" aria-hidden="true">
                        @foreach ($rail['ticks'] as $tick)
                            <span class="hb-tick" style="--at: {{ $tick['position'] }}%">{{ $tick['label'] }}</span>
                        @endforeach
                    </div>

                    <ol class="hb-lanes">
                        @foreach ($rail['lanes'] as $lane)
                            <li class="hb-lane">
                                <div class="hb-lane-side">
                                    <p class="hb-lane-label">{{ $lane['label'] }}</p>
                                    <p class="hb-lane-hint">{{ $lane['hint'] }}</p>
                                    <p class="hb-lane-meta">
                                        <span class="hb-num">{{ $lane['count'] }}</span>
                                        <span class="hb-muted">{{ $lane['count'] === 1 ? __('admin-dashboard.job') : __('admin-dashboard.jobs') }}</span>
                                        <span class="hb-sep" aria-hidden="true">·</span>
                                        <span class="hb-num">{{ $lane['busy_label'] }}</span>
                                        <span class="hb-muted">{{ __('admin-dashboard.busy') }}</span>
                                        <span class="hb-sep" aria-hidden="true">·</span>
                                        <span class="hb-num">{{ $lane['utilisation'] }}%</span>
                                        <span class="hb-muted">{{ __('admin-dashboard.utilisation') }}</span>
                                    </p>
                                </div>

                                <div class="hb-track" data-dash-track>
                                    @forelse ($lane['spans'] as $span)
                                        <a
                                            class="hb-span hb-span--{{ $span['state'] }}"
                                            style="--at: {{ $span['left'] }}%; --span: {{ $span['width'] }}%"
                                            @if ($span['href'] !== null) href="{{ $span['href'] }}" @else tabindex="-1" aria-disabled="true" @endif
                                            data-dash-span
                                        >
                                            <span class="hb-span-when">
                                                <span class="hb-num">{{ $span['start_label'] }}</span>
                                                <span class="hb-muted">{{ $span['end_label'] }}</span>
                                            </span>
                                            <span class="hb-span-who">
                                                {{ $span['who'] }}
                                                <span class="hb-span-service">{{ $span['service'] }}</span>
                                            </span>
                                            <span class="hb-span-cap">
                                                <span class="hb-num">{{ $span['duration_label'] }}</span>
                                                @if ($span['staff'] !== null)
                                                    <span class="hb-muted">{{ $span['staff'] }}</span>
                                                @endif
                                                @if ($span['vehicle'] !== '')
                                                    <span class="hb-plate">{{ $span['vehicle'] }}</span>
                                                @endif
                                                @if ($span['overrun'] > 0)
                                                    <span class="hb-warn">{{ __('admin-dashboard.runs_past_close', ['count' => $span['overrun']]) }}</span>
                                                @endif
                                                @if ($span['starts_early'])
                                                    <span class="hb-warn">{{ __('admin-dashboard.starts_before_open') }}</span>
                                                @endif
                                            </span>
                                            {{-- The name carries what the width had to leave out:
                                                 the state, who holds the job, and which pool it is in.
                                                 Not an aria-label, because the visible words have to stay
                                                 inside the name rather than be replaced by it. --}}
                                            <span class="sr-only">
                                                {{ __('admin-dashboard.state.'.$span['state']) }}
                                                <span class="hb-sep" aria-hidden="true">·</span>{{ __($span['lane'] === 'mobile' ? 'admin-dashboard.lane_mobile' : 'admin-dashboard.lane_workshop') }}
                                                @if ($span['staff'] !== null)
                                                    <span class="hb-sep" aria-hidden="true">·</span>{{ $span['staff'] }}
                                                @endif
                                            </span>
                                        </a>
                                    @empty
                                        <p class="hb-track-empty">{{ $lane['empty_label'] }}</p>
                                    @endforelse
                                </div>
                            </li>
                        @endforeach
                    </ol>

                    <div class="hb-now" data-dash-now aria-hidden="true">
                        <span class="hb-now-line"></span>
                    </div>
                </div>
            </div>

            <div class="hb-rail-foot">
                @if ($rail['next'] !== null)
                    <p class="hb-next">
                        <span class="hb-muted">{{ __('admin-dashboard.next_job') }}</span>
                        {{-- A reader who cannot open the booking still gets the fact, but not a
                             link that goes nowhere. --}}
                        @if ($rail['next']['href'] !== null)
                            <a class="hb-link" href="{{ $rail['next']['href'] }}">
                        @else
                            <span>
                        @endif
                            <span class="hb-num">{{ $rail['next']['start_label'] }}</span>
                            <span class="hb-sep" aria-hidden="true">·</span>{{ $rail['next']['who'] }}
                            <span class="hb-sep" aria-hidden="true">·</span>{{ $rail['next']['service'] }}
                            <span class="hb-sep" aria-hidden="true">·</span>{{ $rail['next']['duration_label'] }}
                            <span class="hb-sep" aria-hidden="true">·</span>{{ $rail['next']['lane'] === 'mobile' ? __('admin-dashboard.lane_mobile') : __('admin-dashboard.lane_workshop') }}
                        @if ($rail['next']['href'] !== null)
                            </a>
                        @else
                            </span>
                        @endif
                    </p>
                @else
                    <p class="hb-next">
                        <span class="hb-muted">{{ __('admin-dashboard.next_job') }}</span>
                        <span>{{ __('admin-dashboard.nothing_next') }}</span>
                    </p>
                @endif

                <p class="hb-rail-counts">
                    <span class="hb-num">{{ $rail['counts']['today'] }}</span>
                    <span class="hb-muted">{{ $rail['counts']['today'] === 1 ? __('admin-dashboard.job') : __('admin-dashboard.jobs') }}</span>
                    @if ($rail['counts']['later'] > 0)
                        <span class="hb-sep" aria-hidden="true">·</span>
                        <span class="hb-num">{{ $rail['counts']['later'] }}</span>
                        <span class="hb-muted">{{ __('admin-dashboard.still_to_start') }}</span>
                    @endif
                    @if ($rail['counts']['cancelled'] > 0)
                        <span class="hb-sep" aria-hidden="true">·</span>
                        <span class="hb-num">{{ $rail['counts']['cancelled'] }}</span>
                        <span class="hb-muted">{{ __('admin-dashboard.cancelled_today') }}</span>
                    @endif
                </p>
            </div>
        </section>

        {{-- ============================================================
             THE QUEUE AND THE PIPELINE. One list of things that need an
             answer, and the measure of how many there are.
             ============================================================ --}}
        <div class="hb-pair">
            <section class="hb-band" aria-labelledby="queue-title" data-dash-reveal>
                <div class="hb-head">
                    <div class="hb-head-text">
                        <h2 class="hb-h2" id="queue-title">{{ __('admin-dashboard.waiting_on_you') }}</h2>
                        <p class="hb-hint">{{ __('admin-dashboard.waiting_hint') }}</p>
                    </div>
                    <p class="hb-count">
                        <span class="hb-num">{{ $attention['count'] }}</span>
                    </p>
                </div>

                @if ($attention['rows'] !== [])
                    <ol class="hb-queue">
                        @foreach ($attention['rows'] as $row)
                            <li class="hb-queue-row hb-tone--{{ $row['tone'] }}">
                                <a class="hb-queue-link" href="{{ $row['href'] }}">
                                    <span class="hb-queue-kind">{{ __('admin-dashboard.'.$row['kind']) }}</span>
                                    <span class="hb-queue-title">{{ $row['title'] }}</span>
                                    <span class="hb-queue-detail">{{ $row['detail'] }}</span>
                                    <span class="hb-queue-age">
                                        <span class="hb-muted">{{ __('admin-dashboard.aged') }}</span>
                                        <time class="hb-num" datetime="{{ $row['moment'] }}">{{ $row['age'] }}</time>
                                    </span>
                                    <span class="hb-queue-go" aria-hidden="true"></span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="hb-clear">{{ __('admin-dashboard.waiting_empty') }}</p>
                @endif

                @if ($attention['groups'] !== [])
                    <ul class="hb-groups">
                        @foreach ($attention['groups'] as $group)
                            <li>
                                <a class="hb-group" href="{{ $group['href'] }}">
                                    <span class="hb-num">{{ $group['value'] }}</span>
                                    <span class="hb-muted">{{ $group['label'] }}</span>
                                    <span class="hb-queue-go" aria-hidden="true"></span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="hb-band" aria-labelledby="pipeline-title" data-dash-reveal>
                <div class="hb-head">
                    <div class="hb-head-text">
                        <h2 class="hb-h2" id="pipeline-title">{{ __('admin-dashboard.quote_pipeline') }}</h2>
                        <p class="hb-hint">
                            <span class="hb-num">{{ $pipeline['total'] }}</span>
                            <span class="hb-muted">{{ __('admin-dashboard.all_quotes') }}</span>
                            <span class="hb-sep" aria-hidden="true">·</span>
                            <span class="hb-num">{{ $pipeline['open'] }}</span>
                            <span class="hb-muted">{{ __('admin-dashboard.still_open') }}</span>
                        </p>
                    </div>
                </div>

                <div class="hb-stack" aria-hidden="true">
                    @foreach ($pipeline['statuses'] as $status)
                        <span
                            class="hb-stack-seg hb-stack-seg--{{ $status['key'] }}"
                            style="--w: {{ $status['position'] }}%"
                            title="{{ $status['label'] }}: {{ $status['value'] }}"
                        ></span>
                    @endforeach
                </div>

                <ol class="hb-figures" aria-label="{{ __('admin-dashboard.quote_pipeline') }}">
                    @foreach ($pipeline['statuses'] as $status)
                        <li class="hb-figure-item hb-figure-item--{{ $status['key'] }}">
                            @if ($status['href'] !== null)
                                <a class="hb-figure" href="{{ $status['href'] }}">
                                    <span class="hb-figure-value hb-num">{{ $status['value'] }}</span>
                                    <span class="hb-muted">{{ $status['label'] }}</span>
                                </a>
                            @else
                                <span class="hb-figure">
                                    <span class="hb-figure-value hb-num">{{ $status['value'] }}</span>
                                    <span class="hb-muted">{{ $status['label'] }}</span>
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ol>

                @php $arrivals = $pipeline['arrivals']; @endphp
                <div class="hb-arrivals">
                    <p class="hb-subhead">{{ __('admin-dashboard.arrivals', ['days' => $arrivals['window']]) }}</p>
                    <p class="hb-arrivals-note">
                        {{ __('admin-dashboard.arrivals_hint', ['total' => $arrivals['total'], 'days' => $arrivals['window'], 'peak' => $arrivals['peak']]) }}
                    </p>
                    <ul class="hb-bars hb-bars--14" aria-hidden="true">
                        @foreach ($arrivals['days'] as $daybar)
                            <li class="hb-bar" style="--h: {{ $daybar['position'] }}%">
                                <span class="hb-bar-fill"></span>
                                <span class="hb-bar-foot">{{ $daybar['label'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="hb-arrivals-meta">
                        <span class="hb-num">{{ $arrivals['average'] }}</span>
                        <span class="hb-muted">{{ __('admin-dashboard.average_per_day') }}</span>
                        @if ($arrivals['peak_label'] !== null)
                            <span class="hb-sep" aria-hidden="true">·</span>
                            <span class="hb-muted">{{ __('admin-dashboard.busiest_day') }}</span>
                            <span>{{ $arrivals['peak_label'] }}</span>
                            <span class="hb-muted">(</span><span class="hb-num">{{ $arrivals['peak'] }}</span><span class="hb-muted">)</span>
                        @endif
                        <span class="hb-sep" aria-hidden="true">·</span>
                        <span class="hb-num">{{ $arrivals['quiet'] }}</span>
                        <span class="hb-muted">{{ __('admin-dashboard.quiet_days') }}</span>
                    </p>
                </div>
            </section>
        </div>

        {{-- ============================================================
             SEVEN DAYS AHEAD. The same measure stretched across a week:
             a bar per day, the shop's own closed days marked.
             ============================================================ --}}
        <section class="hb-band" aria-labelledby="week-title" data-dash-reveal>
            <div class="hb-head">
                <div class="hb-head-text">
                    <h2 class="hb-h2" id="week-title">{{ __('admin-dashboard.seven_days') }}</h2>
                    <p class="hb-hint">{{ __('admin-dashboard.week_hint') }}</p>
                </div>
                <p class="hb-rail-counts">
                    <span class="hb-num">{{ $week['total'] }}</span>
                    <span class="hb-muted">{{ $week['total'] === 1 ? __('admin-dashboard.job') : __('admin-dashboard.jobs') }}</span>
                    @if ($week['busiest'] !== null)
                        <span class="hb-sep" aria-hidden="true">·</span>
                        <span class="hb-muted">{{ __('admin-dashboard.busiest_day') }}</span>
                        <span>{{ $week['busiest'] }}</span>
                    @endif
                </p>
            </div>

            <ul class="hb-bars hb-bars--7">
                @foreach ($week['days'] as $daybar)
                    <li
                        @class([
                            'hb-bar',
                            'hb-bar--today' => $daybar['is_today'],
                            'hb-bar--closed' => $daybar['is_closed'],
                        ])
                        style="--h: {{ $daybar['position'] }}%"
                    >
                        @if ($daybar['href'] !== null)
                            <a class="hb-bar-link" href="{{ $daybar['href'] }}" aria-label="{{ $daybar['label'] }}: {{ $daybar['value'] }} {{ $daybar['value'] === 1 ? __('admin-dashboard.job') : __('admin-dashboard.jobs') }}@if ($daybar['is_today']), today @endif @if ($daybar['is_closed']), the shop is closed @endif">
                        @else
                            <span class="hb-bar-link">
                        @endif
                                <span class="hb-bar-fill" aria-hidden="true"></span>
                                <span class="hb-bar-value hb-num">{{ $daybar['value'] }}</span>
                                <span class="hb-bar-foot">
                                    {{ $daybar['weekday'] }}
                                    <span class="hb-bar-day hb-num">{{ $daybar['day'] }}</span>
                                    @if ($daybar['is_today'])
                                        <span class="hb-flag">{{ __('admin-dashboard.today_flag') }}</span>
                                    @elseif ($daybar['is_closed'])
                                        <span class="hb-flag hb-flag--closed">{{ __('admin-dashboard.closed_day') }}</span>
                                    @endif
                                </span>
                        @if ($daybar['href'] !== null)
                            </a>
                        @else
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>

            <dl class="hb-ledger">
                @foreach ($ledger as $figure)
                    <div class="hb-ledger-cell">
                        <dt>{{ $figure['label'] }}</dt>
                        <dd>
                            @if ($figure['href'] !== null)
                                <a class="hb-link" href="{{ $figure['href'] }}">
                                    <span class="hb-num hb-ledger-value">{{ $figure['value'] }}</span>
                                </a>
                            @else
                                <span class="hb-num hb-ledger-value">{{ $figure['value'] }}</span>
                            @endif
                            <span class="hb-ledger-delta hb-num hb-trend--{{ $figure['trend'] }}">
                                {{ $figure['delta_label'] }}
                                <span class="hb-muted">{{ __('admin-dashboard.per_week') }}</span>
                            </span>
                            <span class="hb-muted">{{ $figure['current'] }} {{ __('admin-dashboard.this_week') }}</span>
                        </dd>
                    </div>
                @endforeach
            </dl>
        </section>

        {{-- ============================================================
             WHAT WE FIT AND WHO FITS IT. Two distributions, same bar
             language, read across instead of down.
             ============================================================ --}}
        <div class="hb-pair">
            <section class="hb-band" aria-labelledby="mix-title" data-dash-reveal>
                <div class="hb-head">
                    <div class="hb-head-text">
                        <h2 class="hb-h2" id="mix-title">{{ __('admin-dashboard.what_we_fit') }}</h2>
                        <p class="hb-hint">{{ __('admin-dashboard.mix_hint', ['days' => $mix['window']]) }}</p>
                    </div>
                </div>

                @forelse ($mix['rows'] as $row)
                    <div class="hb-dist">
                        <p class="hb-dist-head">
                            @if ($row['href'] !== null)
                                <a class="hb-link" href="{{ $row['href'] }}">{{ $row['label'] }}</a>
                            @else
                                <span>{{ $row['label'] }}</span>
                            @endif
                            <span class="hb-dist-figures">
                                <span class="hb-num">{{ $row['value'] }}</span>
                                <span class="hb-muted">{{ round($row['position']) }}%</span>
                                <span class="hb-sep" aria-hidden="true">·</span>
                                <span class="hb-muted">{{ $row['duration'] }}</span>
                            </span>
                        </p>
                        <div class="hb-dist-track" aria-hidden="true" style="--w: {{ $row['position'] }}%">
                            <span class="hb-dist-fill"></span>
                        </div>
                    </div>
                @empty
                    <p class="hb-clear">{{ __('admin-dashboard.mix_empty', ['days' => $mix['window']]) }}</p>
                @endforelse

                @if ($mix['rows'] !== [])
                    <p class="hb-dist-total">
                        <span class="hb-num">{{ $mix['total'] }}</span>
                        <span class="hb-muted">{{ __('admin-dashboard.bookings_window', ['days' => $mix['window']]) }}</span>
                        <span class="hb-sep" aria-hidden="true">·</span>
                        <span class="hb-num">{{ $mix['on_glass'] }}</span>
                        <span class="hb-muted">{{ __('admin-dashboard.on_glass') }}</span>
                    </p>
                @endif
            </section>

            <section class="hb-band" aria-labelledby="team-title" data-dash-reveal>
                <div class="hb-head">
                    <div class="hb-head-text">
                        <h2 class="hb-h2" id="team-title">{{ __('admin-dashboard.who_carried_the_week') }}</h2>
                        <p class="hb-hint">{{ __('admin-dashboard.team_hint', ['days' => $team['window']]) }}</p>
                    </div>
                    <p class="hb-rail-counts">
                        <span class="hb-num">{{ $team['total'] }}</span>
                        <span class="hb-muted">{{ $team['total'] === 1 ? __('admin-dashboard.job') : __('admin-dashboard.jobs') }}</span>
                        <span class="hb-sep" aria-hidden="true">·</span>
                        <span class="hb-num">{{ $team['finished'] }}</span>
                        <span class="hb-muted">{{ __('admin-dashboard.finished') }}</span>
                        @if ($team['open_jobs'] > 0)
                            <span class="hb-sep" aria-hidden="true">·</span>
                            <span class="hb-num">{{ $team['open_jobs'] }}</span>
                            <span class="hb-muted">{{ __('admin-dashboard.open_jobs') }}</span>
                        @endif
                    </p>
                </div>

                @forelse ($team['rows'] as $row)
                    <div class="hb-dist @if($row['key'] === 'unassigned') hb-dist--unassigned @endif">
                        <p class="hb-dist-head">
                            @if ($row['href'] !== null)
                                <a class="hb-link" href="{{ $row['href'] }}">{{ $row['label'] }}</a>
                            @else
                                <span>{{ $row['label'] }}</span>
                            @endif
                            <span class="hb-dist-figures">
                                <span class="hb-num">{{ $row['value'] }}</span>
                                <span class="hb-muted">{{ $row['share'] }}% {{ __('admin-dashboard.share') }}</span>
                            </span>
                        </p>
                        <div class="hb-dist-track" aria-hidden="true" style="--w: {{ $row['position'] }}%">
                            <span class="hb-dist-fill"></span>
                        </div>
                    </div>
                @empty
                    <p class="hb-clear">{{ __('admin-dashboard.team_empty', ['days' => $team['window']]) }}</p>
                @endforelse
            </section>
        </div>

        {{-- ============================================================
             MILESTONES. The same clock as the rail, turned on its side:
             every row is a real change to a real booking, placed by how
             long ago it happened.
             ============================================================ --}}
        <section class="hb-band" aria-labelledby="milestone-title" data-dash-reveal>
            <div class="hb-head">
                <div class="hb-head-text">
                    <h2 class="hb-h2" id="milestone-title">{{ __('admin-dashboard.milestones', ['hours' => $milestones['window']]) }}</h2>
                    <p class="hb-hint">{{ __('admin-dashboard.milestones_hint') }}</p>
                </div>
                <p class="hb-rail-counts">
                    <span class="hb-muted">{{ __('admin-dashboard.since') }}</span>
                    <span class="hb-num">{{ $milestones['from_label'] }}</span>
                </p>
            </div>

            @if ($milestones['rows'] !== [])
                <ol class="hb-timeline">
                    @foreach ($milestones['rows'] as $row)
                        <li class="hb-milestone hb-milestone--{{ $row['kind'] }}">
                            <span class="hb-milestone-mark" aria-hidden="true"></span>
                            <p class="hb-milestone-when">
                                <time class="hb-num" datetime="{{ $row['moment'] }}">{{ $row['when'] }}</time>
                                <span class="hb-muted">{{ $row['ago'] }}</span>
                            </p>
                            <p class="hb-milestone-what">
                                <span class="hb-milestone-kind">{{ $row['label'] }}</span>
                                @if ($row['href'] !== null)
                                    <a class="hb-link" href="{{ $row['href'] }}">{{ $row['reference'] }}</a>
                                @else
                                    <span class="hb-num">{{ $row['reference'] }}</span>
                                @endif
                                @if ($row['who'] !== null)
                                    <span class="hb-muted">{{ $row['who'] }}</span>
                                @endif
                            </p>
                            @if ($row['summary'] !== null && $row['summary'] !== '')
                                <p class="hb-milestone-summary">{{ $row['summary'] }}</p>
                            @endif
                            @if ($row['actor'] !== null)
                                <p class="hb-milestone-actor">{{ $row['actor'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="hb-clear">{{ __('admin-dashboard.no_milestones', ['hours' => $milestones['window']]) }}</p>
            @endif
        </section>

        {{-- ============================================================
             COLOPHON. Where the numbers came from, and when.
             ============================================================ --}}
        <footer class="hb-colophon" data-dash-reveal>
            <p>
                <span class="hb-muted">{{ __('admin-dashboard.measured_at') }}</span>
                <time class="hb-num" datetime="{{ $colophon['measured_iso'] }}">{{ $colophon['measured_at'] }}</time>
                <span class="hb-sep" aria-hidden="true">·</span>
                <span class="hb-muted">{{ $colophon['timezone'] }}</span>
                @if ($colophon['window_label'] !== null)
                    <span class="hb-sep" aria-hidden="true">·</span>
                    <span class="hb-muted">{{ __('admin-dashboard.window_label') }}</span>
                    <span class="hb-num">{{ $colophon['window_label'] }}</span>
                @endif
            </p>
            <p class="hb-muted">{{ __('admin-dashboard.sources', ['sources' => implode(', ', $colophon['references'])]) }}</p>
            <p class="hb-muted">{{ __('admin-dashboard.not_cached') }}</p>
            @if (\Illuminate\Support\Facades\Gate::allows('view system overview'))
                <a class="hb-link hb-link--utility" href="{{ route('admin.system-overview.index') }}">
                    {{ __('admin-dashboard.system_overview') }}
                </a>
            @endif
        </footer>
    </div>
</x-layouts::admin>
