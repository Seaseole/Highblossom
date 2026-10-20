{{-- Public service copy: the summary always shows, the detailed description expands in
     place. Plain text from the admin textarea, so it is escaped and only line breaks are
     turned into markup — never an unescaped echo of stored content. --}}
@php
    $summary = trim((string) $service->short_description);
    $detailed = trim((string) $service->full_description);
    $expandable = $detailed !== '' && $detailed !== $summary;
@endphp

<p class="mb-6 text-lg leading-relaxed text-site-muted">{{ $service->short_description }}</p>

@if ($expandable)
    <div x-data="{ open: false }" class="-mt-2 mb-6">
        <div
            x-show="open"
            x-collapse
            x-cloak
            id="service-detail-{{ $service->id }}"
            class="text-base leading-relaxed text-site-muted"
        >{!! nl2br(e($detailed)) !!}</div>

        <button
            type="button"
            x-on:click="open = ! open"
            :aria-expanded="open ? 'true' : 'false'"
            aria-controls="service-detail-{{ $service->id }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-site-link transition-opacity hover:opacity-80"
        >
            <span x-text="open ? '{{ __('services.show_less') }}' : '{{ __('services.read_more') }}'"></span>
            <svg
                class="h-4 w-4 transition-transform duration-300"
                :class="open ? 'rotate-180' : ''"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>
@endif
