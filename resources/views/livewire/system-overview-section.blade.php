@php
    $section = $this->section;
@endphp

<div
    wire:key="system-overview-section-{{ $probe }}"
    @if ($section->isPending()) wire:poll.4s @endif
>
    @if ($section->isPending())
        <section
            id="system-overview-{{ $probe }}"
            class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
        >
            <header class="flex items-start justify-between gap-4 border-b border-gray-100 px-6 py-4 dark:border-white/10">
                <div class="flex items-start gap-3">
                    <x-ui.icon :name="$meta->icon()" size="sm" class="mt-0.5 text-gray-500 dark:text-gray-400" />
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ $meta->label() }}</h2>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $meta->description() }}</p>
                    </div>
                </div>
                <span class="mt-2 inline-block h-2.5 w-2.5 shrink-0 rounded-full bg-blue-500/60"></span>
            </header>

            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @if ($warming)
                        <span class="inline-flex items-center gap-2">
                            <x-ui.icon name="arrow-path" size="xs" class="animate-spin text-blue-500" />
                            {{ __('messages.system_overview_warming') }}
                        </span>
                    @else
                        {{ __('messages.system_overview_not_warmed') }}
                    @endif
                </p>

                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        wire:click="measure"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition-colors hover:bg-blue-700 disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="measure">{{ __('messages.system_overview_measure_now') }}</span>
                        <span wire:loading wire:target="measure">{{ __('messages.system_overview_measuring') }}</span>
                    </button>
                    <button
                        type="button"
                        wire:click="queueWarmup"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-3.5 py-2 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/5"
                    >
                        {{ __('messages.system_overview_warm_in_background') }}
                    </button>
                </div>
            </div>

            <footer class="border-t border-gray-100 px-6 py-3 text-xs text-gray-400 dark:border-white/10 dark:text-gray-500">
                <span class="inline-flex items-center gap-2">
                    <x-ui.icon name="exclamation-triangle" size="xs" class="shrink-0" />
                    {{ __('messages.system_overview_heavy_hint') }}
                </span>
            </footer>
        </section>
    @else
        @include('admin.system-overview.card', [
            'section' => $section,
            'recalculate' => true,
        ])

        @if ($probe === 'packages')
            <livewire:system-overview-packages lazy />
        @endif
    @endif
</div>
