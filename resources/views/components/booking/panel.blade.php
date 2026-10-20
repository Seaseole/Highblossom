@props(['eyebrow' => null, 'title' => null, 'description' => null])

<section
    {{ $attributes->merge(['class' => 'rounded-3xl border border-[var(--booking-line)] bg-[var(--booking-panel)] p-5 md:p-7']) }}
>
    @if ($eyebrow)
        <p class="mb-1 text-[10px] font-semibold tracking-[0.2em] text-[var(--booking-accent)] uppercase">{{ $eyebrow }}</p>
    @endif

    @if ($title)
        <h2 class="font-headline text-xl font-bold tracking-tight text-site-text md:text-2xl">{{ $title }}</h2>
    @endif

    @if ($description)
        <p class="mt-1.5 mb-5 text-sm leading-relaxed text-[var(--booking-muted)]">{{ $description }}</p>
    @else
        <div class="mb-5"></div>
    @endif

    <div>
        {{ $slot }}
    </div>
</section>
