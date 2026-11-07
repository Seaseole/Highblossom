@php
    $lines = __('site.home.tagline_lines');
@endphp

{{-- The mandatory tagline moment: separate from the hero, large type, and each
     word wakes from a muted tone in reading order as the section crosses the
     trigger line. Reduced motion shows the finished text immediately. --}}
<section
    id="tagline"
    class="border-t border-site-line bg-site-surface py-24 lg:py-32"
    aria-labelledby="tagline-heading"
    data-home-reveal
>
    <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
        <div class="mb-8 text-sm font-semibold tracking-wider text-site-link uppercase">
            {{ __('site.home.tagline_label') }}
        </div>

        <h2
            id="tagline-heading"
            class="hb-tagline font-headline text-4xl font-semibold leading-tight tracking-tight text-site-text md:text-5xl"
        >
            {{-- One readable sentence for assistive technology; the visual words
                 carry the animation and are hidden from the accessible name. --}}
            <span class="sr-only">{{ implode(' ', $lines) }}</span>
            <span aria-hidden="true">
                @foreach ($lines as $line)
                    <span class="block text-wrap-balance">
                        @foreach (preg_split('/\s+/', trim($line)) as $word)
                            <span class="hb-tagline-word" data-tagline-word>{{ $word }}</span>
                        @endforeach
                    </span>
                @endforeach
            </span>
        </h2>
    </div>
</section>
