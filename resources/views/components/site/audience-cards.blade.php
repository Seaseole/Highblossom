@php
    // One card per audience, one quote route per card. The audience key is
    // whitelisted in SiteController::quote() and only frames the form copy.
    $cards = [
        ['key' => 'drivers', 'audience' => 'driver', 'icon' => 'windscreen'],
        ['key' => 'fleets', 'audience' => 'fleet', 'icon' => 'fleet'],
        ['key' => 'machinery', 'audience' => 'machinery', 'icon' => 'plant'],
    ];
@endphp

{{-- Question 2, made visible: three audiences with three separate entry
     points and proof that belongs to each one. Copy lives in lang/en/site.php
     so the section is editable without touching markup. --}}
<section
    id="audiences"
    class="border-t border-site-line bg-site py-24 lg:py-32"
    aria-labelledby="audience-heading"
    data-home-reveal
>
    <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
        <div class="mb-12 max-w-[680px]">
            <div class="mb-3 text-sm font-semibold tracking-wider text-site-link uppercase">
                {{ __('site.home.audience_label') }}
            </div>
            <h2
                id="audience-heading"
                class="font-headline text-wrap-balance text-4xl font-semibold tracking-tight text-site-text md:text-5xl"
            >
                {{ __('site.home.audience_title') }}
            </h2>
            <p class="text-wrap-pretty mt-4 text-lg leading-relaxed text-site-muted">
                {{ __('site.home.audience_subtitle') }}
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ($cards as $card)
                @php
                    $base = 'site.home.audience.'.$card['key'].'.';
                    $url = route('quote').'?audience='.$card['audience'];
                @endphp
                <article
                    class="glass-card flex h-full flex-col border border-site-line p-6 transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:-translate-y-1 sm:p-8"
                    style="border-radius: 16px"
                >
                    <div class="mb-6 flex h-12 w-12 items-center justify-center bg-site-accent-soft text-site-link">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @if ($card['icon'] === 'windscreen')
                                <path d="M4 5h16l-1.2 11.4A2 2 0 0 1 16.8 18H7.2a2 2 0 0 1-2-1.6L4 5Z" />
                                <path d="M12.5 7.5 10.5 11l2.2 1.8-1.4 3.2" />
                            @elseif ($card['icon'] === 'fleet')
                                <path d="M2.5 16.5V8.5h10v8" />
                                <path d="M12.5 11.5h3.8l3.2 3v2h-7z" />
                                <path d="M2.5 16.5h17" />
                                <circle cx="7" cy="16.5" r="1.6" />
                                <circle cx="16.2" cy="16.5" r="1.6" />
                                <path d="M5.5 5.5h4M7.5 3.5v4" />
                            @else
                                <path d="M2.5 18h19" />
                                <path d="M4.5 18V8h9v10" />
                                <path d="M4.5 12h9" />
                                <path d="M13.5 8h4l2.5 5v5" />
                                <path d="M13.5 13h6.5" />
                                <path d="M6 16.5h1.5M10 16.5h1.5M15.5 16.5H17" />
                            @endif
                        </svg>
                    </div>

                    <div class="mb-2 text-xs font-semibold tracking-wider text-site-link uppercase">
                        {{ __($base.'label') }}
                    </div>
                    <h3 class="font-headline mb-3 text-2xl font-semibold text-site-text">
                        {{ __($base.'title') }}
                    </h3>
                    <p class="text-wrap-pretty mb-6 text-base leading-relaxed text-site-muted">
                        {{ __($base.'text') }}
                    </p>

                    {{-- Proof sits beside the audience it supports, not in a shared strip. --}}
                    <ul class="mb-8 space-y-3" aria-label="{{ __($base.'label') }} proof">
                        @foreach (__($base.'proof') as $proof)
                            <li class="flex items-start gap-3 text-sm leading-relaxed text-site-text">
                                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-site-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ $proof }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        <a
                            href="{{ $url }}"
                            wire:navigate
                            class="btn-premium inline-flex min-h-[48px] w-full items-center justify-center gap-2 px-5 py-3 text-sm transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent focus-visible:ring-offset-2 focus-visible:ring-offset-site sm:text-base"
                        >
                            <span>{{ __($base.'cta') }}</span>
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>

                        @if ($card['key'] === 'drivers' && ($contact->hasPhone() || $contact->hasWhatsapp()))
                            <p class="mt-4 text-center text-sm text-site-muted">
                                {{ __('site.home.audience.drivers.urgent') }}
                                @if ($contact->hasPhone())
                                    <a
                                        href="{{ $contact->telHref($contact->primaryPhone) }}"
                                        class="font-semibold text-site-link underline underline-offset-4 transition-colors hover:text-site-text focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60"
                                    >{{ __('site.home.sticky_call') }}</a>
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
                                    >{{ __('site.home.sticky_whatsapp') }}</a>
                                @endif
                            </p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
