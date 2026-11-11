<x-layouts::admin title="System Overview">
    @php
        $attention = collect($sections)->filter(
            fn ($section) => in_array($section->status->value, ['warn', 'fail'], true)
        );

        // Only the Sessions card carries an action, and only for a reader who may
        // already revoke sessions from the list it links to.
        $pruneAction = Gate::allows('manage user sessions') ? [
            'route' => 'admin.sessions.prune',
            'label' => __('messages.system_overview_prune_now'),
            'confirm' => __('messages.system_overview_prune_confirm'),
        ] : null;
    @endphp

    <div class="hb-desk mx-auto max-w-[80rem] py-2 sm:py-4" data-dash>
        <header class="hb-desk-intro">
            <div class="min-w-0">
                <h1 class="hb-h1">System Overview</h1>
                <p class="hb-lede">
                    A snapshot of the runtime, stores, and scheduled work. Passwords, keys and tokens
                    are never listed here; an address the app itself is configured to send from is.
                </p>
            </div>

            <div class="hb-actions">
                <form method="POST" action="{{ route('admin.system-overview.refresh') }}">
                    @csrf
                    <button type="submit" class="hb-btn">
                        <x-ui.icon name="arrow-path" size="xs" />
                        Re-measure
                    </button>
                </form>
                @can('export system overview')
                    <form method="POST" action="{{ route('admin.system-overview.export') }}">
                        @csrf
                        <button type="submit" class="hb-btn">
                            <x-ui.icon name="arrow-down-tray" size="xs" />
                            {{ __('messages.system_overview_export') }}
                        </button>
                    </form>
                @endcan
            </div>
        </header>

        {{-- The strip is the whole page read at once: one mark per section, at the
             height its verdict earns, and each mark is a way down to the card. It is
             not a summary of a measurement, so it is not a chart; it is the scale the
             cards below hang on. The verdict is also written in words, because a
             height and a colour are not something to read a page by. --}}
        <section class="hb-band" aria-labelledby="strip-title" data-dash-reveal>
            <h2 class="hb-subhead" id="strip-title">Measured</h2>
            <ul class="hb-strip">
                @foreach ($sections as $section)
                    <li class="hb-strip-item">
                        <a
                            class="hb-strip-link hb-strip-link--{{ $section->status->value }}"
                            href="#system-overview-{{ $section->key }}"
                        >
                            <span class="hb-strip-tick" aria-hidden="true"></span>
                            <span>{{ $section->label }}</span>
                            <span class="sr-only">{{ ucfirst($section->status->value) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            @if ($attention->isEmpty())
                <p class="hb-strip-note">
                    <span class="hb-num">{{ $sections->count() }}</span>
                    <span>{{ __('messages.system_overview_all_ok') }}</span>
                    <span class="hb-muted">A taller mark is a worse verdict. Each card below names its own.</span>
                </p>
            @else
                {{-- The sentence already carries the number, so it is not printed twice. --}}
                <p class="hb-strip-note">
                    <span>{{ trans_choice(__('messages.system_overview_attention'), $attention->count(), ['count' => $attention->count()]) }}:</span>
                    @foreach ($attention as $section)
                        <a class="hb-link" href="#system-overview-{{ $section->key }}">{{ $section->label }}</a>
                    @endforeach
                    <span class="hb-muted">A taller mark is a worse verdict. Each card below names its own.</span>
                </p>
            @endif
        </section>

        @include('admin.nudges.band', ['nudges' => $nudges])

        @php $gridSections = collect($sections)->reject(fn ($section) => $section->key === 'packages'); @endphp
        <div class="hb-desk-grid" data-dash-reveal>
            @foreach ($gridSections as $section)
                @if (in_array($section->key, $heavyProbes, true))
                    <livewire:system-overview-section
                        :probe="$section->key"
                        :key="'system-overview-section-'.$section->key"
                    />
                @else
                    @include('admin.system-overview.card', [
                        'section' => $section,
                        'action' => $section->key === 'sessions' ? $pruneAction : null,
                    ])
                @endif
            @endforeach
        </div>

        {{-- Dependencies carries a searchable inventory under its card. Held to one
             column it squeezed the table and left the other half of the row empty,
             so it stands on the full width of the page instead. --}}
        @if (in_array('packages', $heavyProbes, true))
            <livewire:system-overview-section
                probe="packages"
                key="system-overview-section-packages"
            />
        @endif

        <section class="hb-band hb-notes" aria-labelledby="notes-title" data-dash-reveal>
            <h2 class="hb-subhead" id="notes-title">How this page works</h2>
            <ul>
                <li>Each card is measured and cached on its own, so one unavailable service cannot blank the page.</li>
                <li>Cards report how long they took to measure; anything that touches the file system is warmed in the background instead of slowing a request.</li>
                <li>Re-measuring clears every cached card and reads the services again.</li>
                <li>Configuration values are read from the running app, never from <code>.env</code>, so secrets stay out of the browser.</li>
                <li>Exporting writes a redacted copy: lines a probe marks sensitive, such as the database endpoint, are left out and every download is recorded in the audit trail.</li>
            </ul>
        </section>
    </div>
</x-layouts::admin>
