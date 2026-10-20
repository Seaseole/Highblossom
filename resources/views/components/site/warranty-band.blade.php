@props([
    'heading' => null,
    'text' => null,
    'highlights' => null,
])

@php
    $heading = trim((string) $heading);
    $text = trim((string) $text);
    $highlights = collect($highlights ?? [])->filter(fn ($row) => filled($row['title'] ?? null))->values();
@endphp

@if ($heading !== '' || $text !== '' || $highlights->isNotEmpty())
    <section id="warranty" class="bg-site py-20 lg:py-28">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="glass-card overflow-hidden rounded-3xl border-site-accent/15 p-8 lg:p-14">
                <div class="mb-3 text-sm font-semibold tracking-wider text-site-link uppercase">
                    {{ __('site.home.warranty_label') }}
                </div>

                @if ($heading !== '')
                    <h2 class="font-headline mb-5 max-w-3xl text-3xl leading-tight font-bold tracking-tight text-site-text md:text-4xl lg:text-5xl">
                        {{ $heading }}
                    </h2>
                @endif

                @if ($text !== '')
                    <p class="mb-10 max-w-3xl text-base leading-relaxed text-site-muted md:text-lg">
                        {{ $text }}
                    </p>
                @endif

                @if ($highlights->isNotEmpty())
                    <div class="grid gap-4 {{ $highlights->count() > 2 ? 'md:grid-cols-3' : 'md:grid-cols-2' }}">
                        @foreach ($highlights as $highlight)
                            <div class="rounded-2xl border border-site-line bg-site-fill p-6">
                                <h3 class="font-headline mb-2 text-lg font-bold text-site-text">
                                    {{ $highlight['title'] }}
                                </h3>
                                <p class="text-sm leading-relaxed text-site-muted">
                                    {{ $highlight['text'] ?? '' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
