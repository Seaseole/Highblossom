@props([
    'slides' => [],
    'stats' => [],
    'autoplay' => true,
    'button' => ['label' => '', 'url' => ''],
    'whatsapp' => null,
])

@php
    use App\Support\HeroSlides;

    $count = count($slides);
    $sizes = HeroSlides::SIZES;
    $interval = HeroSlides::AUTOPLAY_MS;
    $crossfade = HeroSlides::CROSSFADE_MS;
    $first = $slides[0] ?? [];

    // The text a visitor reads is Alpine's job once the script boots, but it has to be in the
    // HTML before that: the h1, the label on the call to action and the tagline all render with
    // slide one's copy so the page is complete — and searchable — with JavaScript switched off.
    $firstHeadline = ($first['headline'] ?? '') !== '' ? $first['headline'] : HeroSlides::fallbackHeadline();
    $firstTagline = $first['tagline'] ?? '';
    $firstLabel = ($first['button_label'] ?? '') !== '' ? $first['button_label'] : $button['label'];
    $firstUrl = ($first['button_url'] ?? '') !== '' ? $first['button_url'] : $button['url'];
    $hasTagline = collect($slides)->contains(fn (array $slide): bool => filled($slide['tagline']));
@endphp

{{--
    The cinematic hero: three layers per slide painted into one box — the photograph, the display
    word behind the subject, and the subject cut out of that same photograph sitting on top of the
    letters. Everything readable is in one column under one scrim, so the contrast the copy needs
    never depends on a photograph the admin swapped last week.

    Each slide carries its own copy in data attributes and the heroCinematic component in
    site-alpine.js reads them once, from the DOM. The markup is the configuration: there is no
    second list of slides to keep in step.
--}}
<section
    class="hb-hero"
    x-data="heroCinematic"
    data-hero-autoplay="{{ $autoplay ? '1' : '0' }}"
    @keydown.left.prevent="previous()"
    @keydown.right.prevent="advance()"
    @mouseenter="hovered = true"
    @mouseleave="hovered = false"
    @focusin="focused = true"
    @focusout="focused = false"
    style="--hero-interval: {{ $interval }}ms; --hero-crossfade: {{ $crossfade }}ms"
    aria-roledescription="carousel"
    aria-label="{{ __('site.home.hero_carousel_label') }}"
    tabindex="0"
>
    <style>
        /*
         * Scoped to .hb-hero, and deliberately its own values: this is the one surface in the app
         * that paints over a photograph, so it is the one exception DESIGN.md grants (see
         * "Photographic hero surface"). Nothing here is global. The copy inside stays fixed light
         * on the scrim in both themes; the space around the card still follows light/dark.
         */
        .hb-hero {
            --hero-radius: 28px;
            --hero-ease: cubic-bezier(.22, 1, .36, 1);
            --hero-on: #fafafa;
            --hero-on-muted: rgba(250, 250, 250, .74);
            --hero-line: rgba(250, 250, 250, .18);
            --hero-pad: clamp(1.25rem, 4vw, 3rem);
            --hero-word-size: clamp(4.25rem, 19vw, 20rem);

            position: relative;
            width: 100%;
        }

        /* Approximates Geist's metrics with a font that is always present, so the display word
           does not resize the card when the webfont swaps in. */
        @font-face {
            font-family: "Geist Hero Fallback";
            font-weight: 800;
            src: local("Arial");
            size-adjust: 94%;
            ascent-override: 92%;
            descent-override: 24%;
        }

        .hb-hero-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: clamp(26rem, 66dvh, 40rem);
            overflow: hidden;
            border-radius: var(--hero-radius);
            background-color: #0a0a0f;
            isolation: isolate;
        }

        @media (min-width: 768px) {
            .hb-hero-card {
                min-height: clamp(30rem, 72dvh, 46rem);
            }
        }

        .hb-hero-layers,
        .hb-hero-scrim {
            position: absolute;
            inset: 0;
        }

        .hb-hero-layers {
            z-index: 0;
        }

        /* One scrim for the whole card rather than one per slide, so its alpha never doubles up
           while two slides are mid-crossfade — and so the copy's contrast ratio is a constant
           instead of a function of whichever photograph is passing underneath. */
        .hb-hero-scrim {
            z-index: 1;
            pointer-events: none;
            background:
                linear-gradient(to top, rgba(10, 10, 15, .88) 0%, rgba(10, 10, 15, .58) 30%, rgba(10, 10, 15, .16) 62%, transparent 80%),
                radial-gradient(125% 95% at 50% 0%, transparent 52%, rgba(10, 10, 15, .34) 100%);
        }

        .hb-hero-slide {
            position: absolute;
            inset: 0;
            display: grid;
            opacity: 0;
            pointer-events: none;
            transition: opacity var(--hero-crossfade) linear;
            background-color: var(--hero-tint, #0a0a0f);
        }

        .hb-hero-slide.is-active {
            opacity: 1;
        }

        /* Every layer takes the same grid cell, which is what locks the cutout onto its own
           photograph at any width and any zoom: there is no per-layer geometry to agree about. */
        .hb-hero-slide > * {
            grid-area: 1 / 1;
        }

        .hb-hero-photo,
        .hb-hero-subject {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .hb-hero-photo {
            z-index: 0;
        }

        .hb-hero-subject {
            z-index: 2;
        }

        .hb-hero-word {
            z-index: 1;
            place-self: center;
            max-width: 100%;
            overflow: hidden;
            font-family: "Geist", "Geist Hero Fallback", ui-sans-serif, system-ui, sans-serif;
            font-weight: 800;
            font-size: var(--hero-word-size);
            line-height: .82;
            letter-spacing: -.045em;
            text-transform: uppercase;
            white-space: nowrap;
            text-align: center;
            user-select: none;
            color: transparent;
            background-image: linear-gradient(158deg, rgba(255, 255, 255, .34) 0%, rgba(255, 255, 255, .12) 46%, rgba(220, 38, 38, .26) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @supports not (background-clip: text) {
            .hb-hero-word {
                color: rgba(250, 250, 250, .18);
                -webkit-text-fill-color: currentColor;
                background-image: none;
            }
        }

        .hb-hero-body {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: clamp(1.5rem, 3vw, 2.5rem);
            margin-top: auto;
            padding: var(--hero-pad);
        }

        .hb-hero-copy {
            max-width: 34rem;
        }

        .hb-hero-headline {
            font-family: var(--font-sans);
            font-size: var(--text-ui-3xl);
            line-height: var(--text-ui-3xl--line-height);
            font-weight: 600;
            letter-spacing: -.02em;
            text-wrap: balance;
            color: var(--hero-on);
        }

        .hb-hero-tagline {
            margin-top: .75rem;
            max-width: 30rem;
            font-size: var(--text-ui-md);
            line-height: var(--text-ui-md--line-height);
            color: var(--hero-on-muted);
        }

        /* Both hero variants name the three audiences above the fold. */
        .hb-hero-audience {
            margin-top: .5rem;
            max-width: 34rem;
            font-size: 14px;
            line-height: 20px;
            color: var(--hero-on);
            text-wrap: pretty;
        }

        .hb-hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .75rem;
            margin-top: 1.5rem;
        }

        .hb-hero-cta {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            padding: .5rem .5rem .5rem 1.25rem;
            border-radius: 9999px;
            background-color: #ffffff;
            color: #0a0a0f;
            font-size: var(--text-ui-md);
            font-weight: 600;
            box-shadow: 0 18px 34px -18px rgba(10, 10, 15, .75);
            transition: transform 180ms var(--hero-ease), box-shadow 180ms var(--hero-ease);
        }

        .hb-hero-cta:hover {
            transform: translateY(-1px);
        }

        .hb-hero-cta:active {
            transform: scale(.98);
        }

        .hb-hero-cta-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            background-color: #dc2626;
            color: #ffffff;
        }

        .hb-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .75rem 1.125rem;
            border: 1px solid var(--hero-line);
            border-radius: 9999px;
            background-color: rgba(250, 250, 250, .08);
            color: var(--hero-on);
            font-size: var(--text-ui-md);
            font-weight: 500;
            transition: background-color 180ms var(--hero-ease);
        }

        .hb-hero-secondary:hover {
            background-color: rgba(250, 250, 250, .16);
        }

        .hb-hero-foot {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 1.5rem;
        }

        .hb-hero-stats {
            display: flex;
            flex-wrap: wrap;
            gap: clamp(1.25rem, 3vw, 2.75rem);
        }

        .hb-hero-stat-value {
            font-size: var(--text-ui-2xl);
            line-height: var(--text-ui-2xl--line-height);
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            color: var(--hero-on);
        }

        .hb-hero-stat-label {
            margin-top: .125rem;
            max-width: 12rem;
            font-size: var(--text-ui-xs);
            line-height: var(--text-ui-xs--line-height);
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--hero-on-muted);
        }

        .hb-hero-side {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-left: auto;
        }

        .hb-hero-nav {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border: 1px solid var(--hero-line);
            border-radius: 9999px;
            background-color: rgba(250, 250, 250, .06);
            color: var(--hero-on);
            transition: background-color 180ms var(--hero-ease), transform 180ms var(--hero-ease);
        }

        .hb-hero-nav:hover {
            background-color: rgba(250, 250, 250, .16);
        }

        .hb-hero-nav:active {
            transform: scale(.96);
        }

        .hb-hero-preview {
            position: relative;
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .5rem .875rem .5rem .5rem;
            border: 1px solid var(--hero-line);
            border-radius: 14px;
            background-color: rgba(10, 10, 15, .55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: var(--hero-on);
            font-size: var(--text-ui-xs);
            line-height: var(--text-ui-xs--line-height);
            font-weight: 500;
            font-variant-numeric: tabular-nums;
            text-align: left;
            transition: background-color 180ms var(--hero-ease);
        }

        .hb-hero-preview:hover {
            background-color: rgba(10, 10, 15, .7);
        }

        .hb-hero-preview img {
            width: 2.75rem;
            height: 2rem;
            border-radius: 8px;
            object-fit: cover;
        }

        /* The autoplay tick. It is a CSS animation rather than a timer on purpose: when the fill
           finishes, the slide changes, so the bar and the interval cannot drift apart — and
           pausing it with animation-play-state pauses the carousel with the same one flag. It
           sits on the card, not inside the preview, because a hidden element runs no animation
           and the phones would then never advance. */
        .hb-hero-progress {
            position: absolute;
            inset: auto 0 0 0;
            z-index: 3;
            height: 2px;
            transform-origin: left;
            transform: scaleX(0);
            background-color: #dc2626;
            pointer-events: none;
        }

        .hb-hero-progress.is-running {
            animation: hb-hero-fill var(--hero-interval) linear forwards;
        }

        .hb-hero-progress.is-paused {
            animation-play-state: paused;
        }

        @keyframes hb-hero-fill {
            from {
                transform: scaleX(0);
            }

            to {
                transform: scaleX(1);
            }
        }

        .hb-hero-copy.is-entering {
            animation: hb-hero-copy var(--hero-crossfade) var(--hero-ease) both;
        }

        @keyframes hb-hero-copy {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .hb-hero-dots {
            display: none;
            align-items: center;
            gap: .5rem;
        }

        .hb-hero-dot {
            width: .5rem;
            height: .5rem;
            border-radius: 9999px;
            background-color: rgba(250, 250, 250, .3);
            transition: background-color 180ms var(--hero-ease), width 180ms var(--hero-ease);
        }

        .hb-hero-dot.is-on {
            width: 1.5rem;
            background-color: #dc2626;
        }

        /* The preview card is a desktop object; a phone gets dots and the two arrows. */
        @media (max-width: 767px) {
            .hb-hero-preview {
                display: none;
            }

            .hb-hero-dots {
                display: flex;
            }
        }

        .hb-hero:focus-visible,
        .hb-hero-cta:focus-visible,
        .hb-hero-secondary:focus-visible,
        .hb-hero-nav:focus-visible,
        .hb-hero-preview:focus-visible,
        .hb-hero-dot:focus-visible {
            outline: 2px solid #f87171;
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            .hb-hero-slide {
                transition: none;
            }

            /* No fill, so no tick, so the carousel simply holds still. Reduced motion stops the
               autoplay rather than slowing it down. */
            .hb-hero-progress.is-running {
                animation: none;
            }

            .hb-hero-copy.is-entering {
                animation: none;
            }

            .hb-hero-cta,
            .hb-hero-secondary,
            .hb-hero-nav,
            .hb-hero-dot {
                transition: none;
            }
        }
    </style>

    <div
        class="hb-hero-card"
        @pointerdown="touchFrom = $event.clientX"
        @pointerup="swipe($event)"
    >
        <div class="hb-hero-layers">
            @foreach ($slides as $index => $slide)
                @php
                    // Slide one is the largest paint, so it is the only one fetched with the HTML.
                    // The rest wait for the page to go idle: six photographs on the first screen
                    // would be the hero competing with itself, and autoplay has seconds to spare.
                    $eager = $index === 0;
                @endphp
                <div
                    class="hb-hero-slide"
                    data-hero-slide
                    @class(['is-active' => $index === 0])
                    role="group"
                    aria-roledescription="slide"
                    aria-label="{{ __('site.home.hero_slide_of', ['current' => $index + 1, 'total' => $count]) }}"
                    aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                    data-label="{{ __('site.home.hero_slide_of', ['current' => $index + 1, 'total' => $count]) }}"
                    data-headline="{{ ($slide['headline'] ?? '') !== '' ? $slide['headline'] : \App\Support\HeroSlides::fallbackHeadline() }}"
                    data-tagline="{{ $slide['tagline'] }}"
                    data-button-label="{{ ($slide['button_label'] ?? '') !== '' ? $slide['button_label'] : $button['label'] }}"
                    data-button-url="{{ ($slide['button_url'] ?? '') !== '' ? $slide['button_url'] : $button['url'] }}"
                    data-thumb="{{ $slide['thumb_src'] }}"
                    @if (! empty($slide['dominant']))
                        style="--hero-tint: {{ $slide['dominant'] }}"
                    @endif
                >
                    @if (filled($slide['word']))
                        <span class="hb-hero-word" aria-hidden="true">{{ $slide['word'] }}</span>
                    @endif

                    <img
                        @if ($eager)
                            src="{{ $slide['background_src'] }}"
                            srcset="{{ $slide['background_srcset'] }}"
                            fetchpriority="high"
                            loading="eager"
                        @else
                            data-hero-src="{{ $slide['background_src'] }}"
                            data-hero-srcset="{{ $slide['background_srcset'] }}"
                            loading="lazy"
                            aria-hidden="true"
                        @endif
                        sizes="{{ $sizes }}"
                        width="{{ $slide['width'] }}"
                        height="{{ $slide['height'] }}"
                        alt="{{ $slide['alt'] }}"
                        class="hb-hero-photo"
                        decoding="async"
                    />

                    @if (filled($slide['cutout_src']))
                        <img
                            @if ($eager)
                                src="{{ $slide['cutout_src'] }}"
                                srcset="{{ $slide['cutout_srcset'] }}"
                                fetchpriority="high"
                            @else
                                data-hero-src="{{ $slide['cutout_src'] }}"
                                data-hero-srcset="{{ $slide['cutout_srcset'] }}"
                                loading="lazy"
                            @endif
                            sizes="{{ $sizes }}"
                            width="{{ $slide['width'] }}"
                            height="{{ $slide['height'] }}"
                            alt=""
                            aria-hidden="true"
                            class="hb-hero-subject"
                            decoding="async"
                        />
                    @endif
                </div>
            @endforeach
        </div>

        <div class="hb-hero-scrim" aria-hidden="true"></div>

        <div class="hb-hero-body">
            <div class="hb-hero-copy" x-ref="copy">
                <h1 id="hero-heading" class="hb-hero-headline" x-text="current.headline">{{ $firstHeadline }}</h1>

                @if ($hasTagline)
                    <p class="hb-hero-tagline" x-text="current.tagline" :hidden="! current.tagline">{{ $firstTagline }}</p>
                @endif

                <p class="hb-hero-audience">{{ __('site.home.hero_audience_line') }}</p>

                <div class="hb-hero-actions">
                    <a href="{{ $firstUrl }}" class="hb-hero-cta" :href="current.buttonUrl" wire:navigate>
                        <span x-text="current.buttonLabel">{{ $firstLabel }}</span>
                        <span class="hb-hero-cta-arrow" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </a>

                    @if (filled($whatsapp))
                        <a href="{{ $whatsapp }}" class="hb-hero-secondary" target="_blank" rel="noopener noreferrer">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5.1-1.3A10 10 0 1 0 12 2Zm5.3 14.2c-.2.6-1.2 1.2-1.7 1.2-.5.1-1 .1-1.7-.1a12 12 0 0 1-5.7-4.6c-.5-.7-.9-1.6-.9-2.4 0-.8.4-1.5.8-1.9.2-.2.4-.3.6-.3h.5c.2 0 .4 0 .5.4l.7 1.7c.1.2 0 .4-.1.5l-.4.5c-.1.2-.2.3 0 .5a8 8 0 0 0 3.6 3.1c.2.1.3 0 .5-.1l.5-.6c.2-.2.3-.2.5-.1l1.6.8c.2.1.3.2.3.4 0 .2 0 .5-.1.7Z" />
                            </svg>
                            <span>{{ __('site.home.hero_whatsapp_cta') }}</span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="hb-hero-foot">
                @if ($stats !== [])
                    <ul class="hb-hero-stats" data-hero-stats>
                        @foreach ($stats as $stat)
                            <li>
                                <p class="hb-hero-stat-value">
                                    <span
                                        data-hero-count
                                        @if (! is_null($stat['count_to']))
                                            data-count-to="{{ $stat['count_to'] }}"
                                            data-decimals="{{ $stat['decimals'] }}"
                                            data-grouped="{{ $stat['grouped'] ? '1' : '0' }}"
                                        @endif
                                    >{{ $stat['value'] }}</span><span>{{ $stat['suffix'] }}</span>
                                </p>
                                <p class="hb-hero-stat-label">{{ $stat['label'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($count > 1)
                    <div class="hb-hero-side">
                        <button type="button" class="hb-hero-nav" @click="previous()" aria-label="{{ __('site.home.hero_previous_slide') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <div class="hb-hero-dots">
                            @foreach ($slides as $index => $slide)
                                <button
                                    type="button"
                                    class="hb-hero-dot"
                                    @click="go({{ $index }})"
                                    :class="{ 'is-on': index === {{ $index }} }"
                                    :aria-current="(index === {{ $index }}) ? 'true' : 'false'"
                                    aria-label="{{ __('site.home.hero_goto_slide', ['number' => $index + 1]) }}"
                                ></button>
                            @endforeach
                        </div>

                        <button type="button" class="hb-hero-nav" @click="advance()" aria-label="{{ __('site.home.hero_next_slide') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <button type="button" class="hb-hero-preview" @click="advance()" aria-label="{{ __('site.home.hero_next_slide') }}">
                            <img :src="next.thumb" alt="" aria-hidden="true" width="800" height="450" loading="lazy" decoding="async" />
                            <span>
                                <span class="block" style="opacity: .7">{{ __('site.home.hero_next_up') }}</span>
                                <span aria-hidden="true">
                                    <span x-text="padded(index + 1)">01</span>/<span>{{ str_pad((string) $count, 2, '0', STR_PAD_LEFT) }}</span>
                                </span>
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        @if ($count > 1)
            <span
                class="hb-hero-progress"
                x-ref="bar"
                data-hero-progress
                :class="{ 'is-paused': paused }"
                @animationend.self="tick()"
            ></span>
        @endif
    </div>

    <p class="sr-only" aria-live="polite" x-text="live"></p>
</section>
