@props([
    'heading' => null,
    'text' => null,
    'size' => 'md',
])

@php
    $heading = trim((string) $heading);
    $text = trim((string) $text);
@endphp

{{-- Reused after the services grid and after the process steps. Renders nothing
     when the admin has cleared both fields so the page never shows an empty band. --}}
@if ($heading !== '' || $text !== '')
    <section class="bg-[#0A0A0F] py-12 lg:py-16" aria-label="{{ $heading !== '' ? $heading : __('site.home.cta_band_quote') }}">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="glass-card relative overflow-hidden rounded-3xl px-6 py-10 text-center lg:px-12 lg:py-12">
                <div class="absolute top-1/2 left-1/2 h-[420px] w-[420px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#DC2626]/10 blur-[90px]" aria-hidden="true"></div>

                <div class="relative z-10 mx-auto max-w-3xl">
                    @if ($heading !== '')
                        <h2 class="font-headline mb-3 text-2xl font-bold tracking-tight text-[#FAFAFA] sm:text-3xl lg:text-4xl">
                            {{ $heading }}
                        </h2>
                    @endif

                    @if ($text !== '')
                        <p class="mx-auto mb-8 max-w-2xl text-base leading-relaxed text-[#A1A1AA] lg:text-lg">
                            {{ $text }}
                        </p>
                    @endif

                    <x-site.contact-buttons :size="$size" />
                </div>
            </div>
        </div>
    </section>
@endif
