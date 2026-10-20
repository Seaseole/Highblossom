@props([
    'stats' => null,
    'tiles' => null,
])

@php
    $cells = collect($stats ?? [])
        ->map(fn (array $stat) => ['value' => $stat['value'], 'label' => $stat['label'], 'suffix' => ''])
        ->concat(
            collect($tiles ?? [])
                ->take(4)
                ->map(fn (array $tile) => [
                    'value' => $tile['value'],
                    'label' => $tile['label'],
                    'suffix' => (string) ($tile['suffix'] ?? ''),
                ])
        )
        ->take(4)
        ->values();
@endphp

{{-- Counters backed by real records lead the row; admin-editable fact tiles fill
     the rest, so nothing here ever renders a "0+" or an invented percentage. --}}
@if ($cells->isNotEmpty())
    <section class="border-t border-site-line bg-site py-10" aria-label="{{ __('site.home.why_choose_label') }}">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                @foreach ($cells as $cell)
                    <div class="glass-card rounded-2xl px-4 py-6 text-center">
                        <div class="font-headline mb-1 text-3xl leading-none font-bold tracking-tight text-site-text lg:text-4xl">
                            {{ $cell['value'] }}{{ $cell['suffix'] }}
                        </div>
                        <div class="text-xs font-medium tracking-wider text-site-muted uppercase">
                            {{ $cell['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
