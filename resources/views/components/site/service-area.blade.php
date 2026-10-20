@props([
    'area' => null,
    'coverage' => null,
])

@php
    $area = trim((string) $area);
    $coverage = trim((string) $coverage);
    $address = trim($contact->address);
@endphp

@if ($area !== '' || $coverage !== '' || $address !== '')
    <section id="service-area" class="bg-site-surface py-20 lg:py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mb-3 text-sm font-semibold tracking-wider text-site-link uppercase">
                {{ __('site.home.service_area_label') }}
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                @if ($area !== '')
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="font-headline mb-2 text-lg font-bold text-site-text">{{ __('site.home.service_area_label') }}</h3>
                        <p class="text-sm leading-relaxed text-site-muted">{{ $area }}</p>
                    </div>
                @endif

                @if ($coverage !== '')
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="font-headline mb-2 text-lg font-bold text-site-text">{{ __('site.home.service_area_mobile') }}</h3>
                        <p class="text-sm leading-relaxed text-site-muted">{{ $coverage }}</p>
                    </div>
                @endif

                @if ($address !== '')
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="font-headline mb-2 text-lg font-bold text-site-text">{{ __('site.home.service_area_workshop') }}</h3>
                        <p class="text-sm leading-relaxed text-site-muted">{{ $address }}</p>
                        @if ($mapDirectionsLink)
                            <a
                                href="{{ $mapDirectionsLink }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-4 inline-flex min-h-[44px] items-center gap-2 text-sm font-semibold text-site-link transition-colors hover:text-site-link-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60"
                            >
                                <span>{{ __('site.home.service_area_directions') }}</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
