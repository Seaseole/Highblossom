@props([
    'heroImage' => null,
    'tagline' => null,
    'years' => null,
])

{{--
    The hero the homepage has always opened with: one photograph, the animated headline, the
    trust copy. This is also the fallback the cinematic hero degrades to — a style set to
    cinematic with no usable slide never reaches its own component — so it is the block that
    has to keep working exactly as it did.

    The headline animation lives in welcome.blade.php and reads #hero-headline-container,
    [data-hero-seed] and #hero-heading. Those names are contracts, not styling.
--}}

{{-- Background Image with Overlay --}}
<div class="absolute inset-0 z-0">
    @if (filled($heroImage))
        <img
            alt="{{ $tagline }}"
            class="h-full w-full object-cover opacity-40"
            src="{{ $heroImage }}"
            width="1920"
            height="1080"
            fetchpriority="high"
            loading="eager"
            decoding="async"
        />
    @endif
    <div class="absolute inset-0 bg-gradient-to-b from-site via-site/80 to-site"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-site via-transparent to-site/50"></div>
    <div class="grain-overlay pointer-events-none absolute inset-0" aria-hidden="true"></div>
</div>

{{-- Hero Content --}}
<div class="relative z-10 mx-auto flex w-full max-w-[1400px] flex-grow items-center justify-center px-4 pt-32 pb-12 text-center sm:px-6 sm:pt-40 lg:px-8">
    <div class="mx-auto max-w-4xl">
        {{-- Trust Badge --}}
        <div class="animate-fade-up mb-6 flex flex-wrap items-center justify-center gap-2 sm:mb-8">
            <span class="inline-flex items-center gap-2 rounded-full border border-site-line-strong bg-site-fill px-4 py-2">
                <span class="h-2 w-2 animate-pulse rounded-full bg-site-accent" aria-hidden="true"></span>
                <span class="text-xs font-medium text-site-text sm:text-sm">{{ __('site.home.hero_trust_badge') }}</span>
            </span>
            @if (filled($years))
                <span class="inline-flex items-center gap-2 rounded-full border border-site-accent/25 bg-site-accent-soft px-4 py-2">
                    <span class="text-xs font-bold text-site-text sm:text-sm">
                        {{ __('site.home.hero_years_badge', ['years' => $years]) }}
                    </span>
                </span>
            @endif
        </div>

        {{-- Animated Headline - Focus Blur Resolve --}}
        <h1 id="hero-heading" class="sr-only">{{ $tagline ?: __('site.home.hero_headline_fallback') }}</h1>
        <div
            id="hero-headline-container"
            aria-labelledby="hero-heading"
            class="font-headline mx-auto mb-4 text-2xl leading-[1.2] font-bold tracking-tight break-words hyphens-auto text-site-text sm:mb-6 sm:text-4xl sm:leading-[1.1] md:text-5xl lg:text-6xl"
            style="perspective: 900px; min-height: 2.4em"
        ><span data-hero-seed>{{ $tagline ?: __('site.home.hero_headline_animated')[0] }}</span></div>

        {{-- Subhead --}}
        <p
            class="animate-fade-up mx-auto mb-4 max-w-[680px] text-base leading-relaxed text-site-text sm:text-lg md:text-xl"
            style="animation-delay: 200ms"
        >
            {{ __('site.home.hero_subheadline') }}
        </p>

        {{-- The three audiences are named above the fold, not left for a later
             section to imply. This line stays static while the headline animates. --}}
        <p
            class="animate-fade-up mx-auto mb-8 max-w-[680px] text-sm font-medium text-site-text sm:text-base"
            style="animation-delay: 250ms"
        >
            {{ __('site.home.hero_audience_line') }}
        </p>

        {{-- One primary action. Call and WhatsApp stay as plain urgent text links
             so they never compete with the quote button. --}}
        <div
            class="animate-fade-up flex flex-col items-center justify-center gap-4"
            style="animation-delay: 300ms"
        >
            <a href="{{ route('quote') }}" wire:navigate class="btn-premium-md glow-red-subtle">
                <span>{{ __('site.home.hero_get_quote') }}</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3"
                    ></path>
                </svg>
            </a>

            <p class="text-sm text-site-muted">{{ __('site.home.hero_proof_line') }}</p>

            @if ($contact->hasPhone() || $contact->hasWhatsapp())
                <p class="text-sm text-site-muted">
                    {{ __('site.home.hero_urgent_prefix') }}
                    @if ($contact->hasPhone())
                        <a
                            href="{{ $contact->telHref($contact->primaryPhone) }}"
                            class="font-semibold text-site-link underline underline-offset-4 transition-colors hover:text-site-text focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60"
                        >{{ __('site.home.hero_urgent_call') }}</a>
                    @endif
                    @if ($contact->hasPhone() && $contact->hasWhatsapp())
                        <span> or </span>
                    @endif
                    @if ($contact->hasWhatsapp())
                        <a
                            href="{{ $contact->whatsappHref() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="font-semibold text-site-link underline underline-offset-4 transition-colors hover:text-site-text focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60"
                        >{{ __('site.home.hero_urgent_whatsapp') }}</a>
                    @endif
                </p>
            @endif
        </div>
    </div>
</div>
