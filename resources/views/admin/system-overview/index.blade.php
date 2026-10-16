<x-layouts::admin title="System Overview">
    @php
        $attention = collect($sections)->filter(
            fn ($section) => in_array($section->status->value, ['warn', 'fail'], true)
        );
    @endphp

    <div class="mx-auto max-w-5xl space-y-8 py-6 sm:py-10">
        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="circle-stack" size="sm" class="text-gray-500 dark:text-gray-400" />
                    <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">System Overview</h1>
                </div>
                <p class="text-gray-500 dark:text-gray-400">
                    A snapshot of the runtime, stores, and scheduled work. Secrets are never listed here.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <form method="POST" action="{{ route('admin.system-overview.refresh') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-white/10 dark:bg-transparent dark:text-gray-200 dark:hover:bg-white/5"
                    >
                        <x-ui.icon name="arrow-path" size="sm" />
                        Re-measure
                    </button>
                </form>
                @can('export system overview')
                    <form method="POST" action="{{ route('admin.system-overview.export') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-white/10 dark:bg-transparent dark:text-gray-200 dark:hover:bg-white/5"
                        >
                            <x-ui.icon name="arrow-down-tray" size="sm" />
                            {{ __('messages.system_overview_export') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        {{-- Attention summary --}}
        <div
            @class([
                'rounded-3xl border p-6',
                'border-emerald-100 bg-emerald-50/50 dark:border-emerald-900/40 dark:bg-emerald-950/20' => $attention->isEmpty(),
                'border-amber-200 bg-amber-50/60 dark:border-amber-900/40 dark:bg-amber-950/20' => $attention->isNotEmpty(),
            ])
        >
            @if ($attention->isEmpty())
                <p class="text-sm text-emerald-800 dark:text-emerald-300">
                    {{ __('messages.system_overview_all_ok') }}
                </p>
            @else
                <p class="mb-3 text-sm font-semibold text-amber-900 dark:text-amber-300">
                    {{ trans_choice(__('messages.system_overview_attention'), $attention->count(), ['count' => $attention->count()]) }}
                </p>
                <ul class="flex flex-wrap gap-2">
                    @foreach ($attention as $section)
                        <li>
                            <a
                                href="#system-overview-{{ $section->key }}"
                                @class([
                                    'rounded-full px-3 py-1 text-xs font-medium transition-colors',
                                    'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-950/40 dark:text-red-300' => $section->status->value === 'fail',
                                    'bg-amber-100 text-amber-800 hover:bg-amber-200 dark:bg-amber-950/40 dark:text-amber-300' => $section->status->value === 'warn',
                                ])
                            >{{ $section->label }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Sections --}}
        <div class="grid gap-6 lg:grid-cols-2">
            @foreach ($sections as $section)
                @if (in_array($section->key, $heavyProbes, true))
                    <livewire:system-overview-section
                        :probe="$section->key"
                        :key="'system-overview-section-'.$section->key"
                    />
                @else
                    @include('admin.system-overview.card', ['section' => $section])
                @endif
            @endforeach
        </div>

        {{-- Notes --}}
        <div class="rounded-3xl border border-blue-100 bg-blue-50/50 p-6 dark:border-blue-900/40 dark:bg-blue-950/20">
            <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">How this page works</h2>
            <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-300">
                <li>&bull; Each card is measured and cached on its own, so one unavailable service cannot blank the page.</li>
                <li>&bull; Cards report how long they took to measure; anything that touches the file system is warmed in the background instead of slowing a request.</li>
                <li>&bull; Re-measuring clears every cached card and reads the services again.</li>
                <li>&bull; Configuration values are read from the running app, never from <code>.env</code>, so secrets stay out of the browser.</li>
                <li>&bull; Exporting writes a redacted copy: lines a probe marks sensitive, such as the database endpoint, are left out and every download is recorded in the audit trail.</li>
            </ul>
        </div>
    </div>
</x-layouts::admin>
