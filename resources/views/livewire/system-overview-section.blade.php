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
            class="hb-desk-card"
        >
            <header class="hb-desk-card__head">
                <div class="flex min-w-0 items-start gap-2">
                    <x-ui.icon :name="$meta->icon()" size="xs" class="mt-1 shrink-0 text-gray-400 dark:text-gray-500" />
                    <div class="min-w-0">
                        <h2 class="hb-desk-card__title">{{ $meta->label() }}</h2>
                        <p class="hb-desk-card__desc">{{ $meta->description() }}</p>
                    </div>
                </div>
                <p class="hb-desk-card__verdict hb-desk-card__verdict--pending">
                    <span class="hb-mark" aria-hidden="true"></span>{{ ucfirst($section->status->value) }}
                </p>
            </header>

            <div class="hb-desk-pending">
                <p class="hb-desk-pending__line">
                    @if ($warming)
                        <span class="inline-flex items-center gap-2">
                            <x-ui.icon name="arrow-path" size="xs" class="animate-spin" />
                            {{ __('messages.system_overview_warming') }}
                        </span>
                    @else
                        {{ __('messages.system_overview_not_warmed') }}
                    @endif
                </p>

                <div class="hb-actions">
                    <button
                        type="button"
                        wire:click="measure"
                        wire:loading.attr="disabled"
                        class="hb-btn hb-btn--primary hb-btn--small"
                    >
                        <span wire:loading.remove wire:target="measure">{{ __('messages.system_overview_measure_now') }}</span>
                        <span wire:loading wire:target="measure">{{ __('messages.system_overview_measuring') }}</span>
                    </button>
                    <button
                        type="button"
                        wire:click="queueWarmup"
                        class="hb-btn hb-btn--small"
                    >
                        {{ __('messages.system_overview_warm_in_background') }}
                    </button>
                </div>
            </div>

            <footer class="hb-desk-card__foot">
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
