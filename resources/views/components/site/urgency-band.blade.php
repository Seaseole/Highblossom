@props([
    'text' => null,
])

@php
    $text = trim((string) $text);
@endphp

@if ($text !== '')
    <section class="border-y border-site-accent/20 bg-gradient-to-r from-[#73081d]/25 via-site to-[#73081d]/25 py-8" aria-label="{{ __('site.home.urgency_call') }}">
        <div class="mx-auto flex max-w-[1400px] flex-col items-center gap-5 px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3 flex-shrink-0" aria-hidden="true">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-site-accent opacity-75"></span>
                    <span class="relative inline-flex h-3 w-3 rounded-full bg-site-accent"></span>
                </span>
                <h2 class="font-headline text-center text-xl font-bold tracking-tight text-site-text sm:text-left sm:text-2xl">
                    {{ $text }}
                </h2>
            </div>

            <x-site.contact-buttons
                :quote-label="null"
                :call-label="__('site.home.urgency_call')"
                :whatsapp-label="__('site.home.urgency_whatsapp')"
                :show-quote="false"
                class="w-full sm:w-auto"
            />
        </div>
    </section>
@endif
