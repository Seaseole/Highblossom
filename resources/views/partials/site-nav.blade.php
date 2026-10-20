<nav
    id="public-main-nav"
    class="sticky top-0 z-40 flex w-full flex-col border-b border-site-line bg-site-glass backdrop-blur-xl"
>
    @php
        $isAnnounceActive = $announcementActive ?? $settings->get('announcement_active', false);
        // Expiry is decided in GlobalSettingsComposer, which also produces the dismissal
        // key the layout hands to the browser. Filtering again here would let the messages
        // on screen and the fingerprint they are dismissed under disagree.
        $announceList = $announcements ?? [];
    @endphp
    @if ($isAnnounceActive && ! empty($announceList))
        <style>
            .announcement-gradient {
                background: linear-gradient(90deg, #73081d 0%, #dc2626 50%, #73081d 100%);
            }
            .ticker-container {
                position: relative;
                display: flex;
                overflow: hidden;
                width: 100%;
            }
            .ticker-wrapper {
                display: flex;
                white-space: nowrap;
                animation: marquee-scroll 30s linear infinite;
                will-change: transform;
            }
            .ticker-wrapper:hover {
                animation-play-state: paused;
            }
            .ticker-item {
                display: inline-flex;
                align-items: center;
            }
            /* The Material Symbols stylesheet declares font-size: 24px and line-height: 24px,
               which outranks the text-[15px] / text-[16px] classes on these glyphs. That is why
               the bar's content measured 47px inside a box hardcoded to 34px: the icons were
               being clipped. Sized here, the row is its own height and needs no declared one. */
            #announcement-ticker-bar .material-symbols-outlined {
                font-size: 15px;
                line-height: 1;
            }
            #close-announcement-btn .material-symbols-outlined {
                font-size: 16px;
            }
            /* Keyboard users get the pause too, not only a hovering pointer. */
            .ticker-container:focus-within .ticker-wrapper {
                animation-play-state: paused;
            }
            /* One attribute both hides the bar and releases the space it took: the bar is a
               normal-flow child of the sticky header, so nothing else reserves its height. */
            html[data-announcement="dismissed"] #announcement-ticker-bar {
                display: none;
            }
            #announcement-ticker-bar.is-closing {
                padding-top: 0;
                padding-bottom: 0;
                border-bottom-width: 0;
                opacity: 0;
            }
            @keyframes marquee-scroll {
                0% {
                    transform: translate3d(0, 0, 0);
                }
                100% {
                    transform: translate3d(-33.333%, 0, 0);
                }
            }
            .shimmer-glow::after {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08) 50%, transparent);
                transform: translateX(-100%);
                animation: shimmer-swipe 8s infinite;
            }
            @keyframes shimmer-swipe {
                0% {
                    transform: translateX(-100%);
                }
                35%,
                100% {
                    transform: translateX(100%);
                }
            }
            @media (prefers-reduced-motion: reduce) {
                .ticker-wrapper,
                .shimmer-glow::after,
                #announcement-ticker-bar .animate-pulse {
                    animation: none;
                }
                /* Static text still has to be reachable, so the row becomes scrollable. */
                .ticker-container {
                    overflow-x: auto;
                }
                #announcement-ticker-bar {
                    transition: none;
                }
            }
        </style>
        {{-- Deliberately no declared height. The bar is one line of text with fixed
             padding inside a normal-flow column, so its space is released by itself when it
             is dismissed. The inline min-height: 34px removed here is exactly what stayed
             behind as an empty gap: the old handler zeroed height, padding, opacity and the
             border, but min-height outranks height, so the box never collapsed. --}}
        <div
            id="announcement-ticker-bar"
            class="announcement-gradient shimmer-glow relative flex w-full items-center overflow-hidden border-b border-white/10 px-4 py-1.5 text-white transition-all duration-300 ease-out select-none"
            role="region"
            aria-label="Announcements"
        >
            <div class="z-10 mx-auto flex w-full max-w-[1400px] items-center justify-between gap-4">
                <!-- Label on left -->
                <div class="flex flex-shrink-0 items-center gap-1.5">
                    <span class="material-symbols-outlined animate-pulse text-[15px] text-white/90">campaign</span>
                    <span class="font-headline rounded border border-white/20 bg-white/10 px-1.5 py-0.5 text-[9px] font-black tracking-widest text-white uppercase">Notice</span>
                </div>

                <!-- Scrolling marquee container -->
                <div class="relative mx-2 flex h-5 flex-1 items-center overflow-hidden">
                    <div class="ticker-container">
                        <div class="ticker-wrapper">
                            @for ($i = 0; $i < 3; $i++)
                                @foreach ($announceList as $announcement)
                                    @php
                                        $link = $announcement['link'] ?? '';
                                        if (! empty($link) && ! str_starts_with($link, 'http') && Route::has($link)) {
                                            $link = route($link);
                                        }
                                    @endphp
                                    <div class="ticker-item mx-4 text-[11px] font-medium tracking-wide text-white" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
                                        @if (! empty($link))
                                            <x-site.link :href="$link" class="flex items-center gap-1 transition-colors hover:text-white/80">
                                                <span>{{ $announcement['text'] }}</span>
                                                <span class="material-symbols-outlined text-[12px] opacity-80">arrow_forward</span>
                                            </x-site.link>
                                        @else
                                            <span>{{ $announcement['text'] }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Close Button on right -->
                <button
                    type="button"
                    id="close-announcement-btn"
                    class="group relative flex-shrink-0 rounded p-0.5 leading-none text-white/60 transition-colors hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/80 before:absolute before:-inset-3 before:content-['']"
                    aria-label="Dismiss announcement"
                >
                    {{-- The 44px target is grown with a pseudo-element rather than padding,
                         because padding would have made the bar taller than it is today. The
                         bar clips its own overflow, so the target is 44px wide and exactly as
                         tall as the bar. --}}
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>
        </div>
    @endif

    <div class="mx-auto flex w-full max-w-[1400px] items-center justify-between px-6 py-4 lg:px-8">
        {{-- Logo --}}
        <a href="{{ route('home') }}" wire:navigate.hover class="transition-opacity hover:opacity-80">
            <x-logo-trust-badge
                :business-name="$logoText"
                :icon-src="$businessLogo ? media_url($businessLogo) : null"
                font-size="text-lg lg:text-xl font-bold font-headline"
                :badge-size="10"
                gap="gap-1.5"
                :badge-top="-2"
                :badge-right="-16"
            />
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden items-center gap-8 md:flex">
            <a
                href="{{ route('home') }}"
                wire:navigate.hover
                class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight"
            >
                Home
            </a>
            <a
                href="{{ route('about-us') }}"
                wire:navigate.hover
                class="nav-link {{ request()->routeIs('about-us') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight"
            >
                About Us
            </a>
            <a
                href="{{ route('services') }}"
                wire:navigate.hover
                class="nav-link {{ request()->routeIs('services') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight"
            >
                Services
            </a>
            <a
                href="{{ route('gallery') }}"
                wire:navigate.hover
                class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight"
            >
                Gallery
            </a>
            <a
                href="{{ route('blog') }}"
                wire:navigate.hover
                class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight"
            >
                Blog
            </a>
            <a href="{{ route('quote') }}" wire:navigate.hover class="btn-premium min-h-[44px] px-5 py-2.5 text-sm"> {{ __('site.get_quote') }} </a>
            <a href="{{ route('bookings.create') }}" wire:navigate.hover class="btn-glass min-h-[44px] px-5 py-2.5 text-sm"> {{ __('site.book_appointment') }} </a>
        </div>

        {{-- Right Side Actions --}}
        <div class="flex items-center gap-4">
            <x-site.theme-toggle />

            @if (request()->routeIs('blog', 'blog.show'))
                {{-- Blog palette trigger, below lg only: on desktop the shortcut and
                     the in-page search bar cover it, and the header has better things
                     to spend width on. --}}
                <button
                    type="button"
                    x-data="{}"
                    x-cloak
                    @click="$dispatch('blog-search:open', { trigger: $el })"
                    aria-label="Search the blog"
                    class="flex min-h-[44px] items-center justify-center rounded-full border border-site-line-strong bg-site-fill p-2.5 text-site-muted transition-colors hover:border-site-accent/40 hover:text-site-text focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60 lg:hidden"
                >
                    <svg class="h-5 w-5 text-site-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            @endif

            {{-- Call / WhatsApp: always one tap away, desktop --}}
            <div class="hidden items-center gap-3 lg:flex">
                @if ($contact->hasPhone())
                    <a
                        href="{{ $contact->telHref($contact->primaryPhone) }}"
                        class="flex min-h-[44px] items-center gap-2 text-sm font-semibold text-site-text transition-colors hover:text-site-link focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60"
                    >
                        <svg class="h-5 w-5 text-site-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ $contact->primaryPhone }}</span>
                    </a>
                @endif
                @if ($contact->hasWhatsapp())
                    <a
                        href="{{ $contact->whatsappHref() }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex min-h-[44px] items-center justify-center rounded-full border border-emerald-400/25 bg-site-fill px-3 text-site-text transition-colors hover:border-emerald-400/50 hover:bg-emerald-400/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400/60"
                        title="WhatsApp"
                    >
                        <span class="sr-only">{{ __('site.home.cta_band_whatsapp') }}</span>
                        <svg class="h-5 w-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.182-.008-.391-.011-.6-.011-.207 0-.544.079-.829.396-.286.319-1.095 1.068-1.095 2.617 0 1.55 1.095 3.05 1.248 3.26.153.21 2.153 3.3 5.223 4.63.73.315 1.3.483 1.743.618.753.225 1.438.193 1.983.117.604-.09 1.84-.752 2.103-1.479.262-.727.262-1.349.183-1.479-.079-.13-.296-.203-.594-.351m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.398 0 .16 5.237.157 11.716c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.504 0 11.79-5.286 11.793-11.793a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                @endif
            </div>

            {{-- Social Media Icons --}}
            <div class="hidden items-center gap-3 md:flex">
                @if ($facebookUrl)
                    <a
                        href="{{ $facebookUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-site-muted transition-colors hover:text-site-link"
                        title="Facebook"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                @endif
                @if ($instagramUrl)
                    <a
                        href="{{ $instagramUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-site-muted transition-colors hover:text-site-link"
                        title="Instagram"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                @endif
                @if ($linkedinUrl)
                    <a
                        href="{{ $linkedinUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-site-muted transition-colors hover:text-site-link"
                        title="LinkedIn"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>
                @endif
            </div>

            @if (Route::has('login'))
                @auth
                    <a
                        href="{{ route('dashboard') }}"
                        class="font-headline hidden text-sm font-semibold text-site-muted transition-colors hover:text-site-text lg:block"
                    >
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="font-headline hidden text-sm font-semibold text-site-muted transition-colors hover:text-site-text lg:block"
                    >
                        Log in
                    </a>
                @endauth
            @endif

            {{-- Mobile Menu Button --}}
            <button
                id="mobile-menu-btn"
                class="p-2 text-site-text transition-colors hover:text-site-link md:hidden"
                aria-label="Toggle menu"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div
    id="mobile-menu"
    class="mobile-menu fixed top-0 right-0 z-[60] h-[100dvh] w-full max-w-sm border-l border-site-line-strong bg-site-surface"
    aria-hidden="true"
    aria-label="Mobile navigation menu"
>
    <div class="flex h-full flex-col">
        {{-- Mobile Header --}}
        <div class="flex flex-shrink-0 items-center justify-between border-b border-site-line-strong px-6 py-4">
            <span class="font-headline text-lg font-bold text-site-text">Menu</span>
            <button
                id="mobile-menu-close"
                class="p-2 text-site-muted transition-colors hover:text-site-text"
                aria-label="Close menu"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Mobile Links --}}
        <div class="flex min-h-0 flex-1 flex-col gap-6 overflow-y-auto px-6 py-8">
            <a
                href="{{ route('home') }}"
                onclick="closeMobileMenu()"
                wire:navigate
                class="text-2xl font-headline font-bold text-site-text hover:text-site-link transition-colors {{ request()->routeIs('home') ? 'text-site-link' : '' }}"
            >
                Home
            </a>
            <a
                href="{{ route('about-us') }}"
                onclick="closeMobileMenu()"
                wire:navigate
                class="text-2xl font-headline font-bold text-site-text hover:text-site-link transition-colors {{ request()->routeIs('about-us') ? 'text-site-link' : '' }}"
            >
                About Us
            </a>
            <a
                href="{{ route('services') }}"
                onclick="closeMobileMenu()"
                wire:navigate
                class="text-2xl font-headline font-bold text-site-text hover:text-site-link transition-colors {{ request()->routeIs('services') ? 'text-site-link' : '' }}"
            >
                Services
            </a>
            <a
                href="{{ route('gallery') }}"
                onclick="closeMobileMenu()"
                wire:navigate
                class="text-2xl font-headline font-bold text-site-text hover:text-site-link transition-colors {{ request()->routeIs('gallery') ? 'text-site-link' : '' }}"
            >
                Gallery
            </a>
            <a
                href="{{ route('blog') }}"
                onclick="closeMobileMenu()"
                wire:navigate
                class="text-2xl font-headline font-bold text-site-text hover:text-site-link transition-colors {{ request()->routeIs('blog') ? 'text-site-link' : '' }}"
            >
                Blog
            </a>
            <a
                href="{{ route('quote') }}"
                onclick="closeMobileMenu()"
                wire:navigate
                class="mt-2 flex min-h-[48px] items-center justify-center gap-2 rounded-full bg-site-accent px-6 text-base font-bold text-white transition-all hover:bg-site-accent-hover active:scale-[0.98]"
            >
                {{ __('site.get_quote') }}
            </a>
            <a
                href="{{ route('bookings.create') }}"
                onclick="closeMobileMenu()"
                wire:navigate
                class="flex min-h-[48px] items-center justify-center gap-2 rounded-full border border-site-line-strong bg-site-fill px-6 text-base font-bold text-site-text transition-all hover:border-site-accent/40 hover:bg-site-accent-soft active:scale-[0.98]"
            >
                {{ __('site.book_appointment') }}
            </a>
            @if ($contact->hasPhone() || $contact->hasWhatsapp())
                <div class="flex gap-3">
                    @if ($contact->hasPhone())
                        <a
                            href="{{ $contact->telHref($contact->primaryPhone) }}"
                            onclick="closeMobileMenu()"
                            class="flex min-h-[48px] flex-1 items-center justify-center gap-2 rounded-full border border-site-line-strong bg-site-fill px-4 text-sm font-bold text-site-text transition-colors hover:border-site-accent/40"
                        >
                            <svg class="h-5 w-5 text-site-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ __('site.home.sticky_call') }}</span>
                        </a>
                    @endif
                    @if ($contact->hasWhatsapp())
                        <a
                            href="{{ $contact->whatsappHref() }}"
                            onclick="closeMobileMenu()"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex min-h-[48px] flex-1 items-center justify-center gap-2 rounded-full border border-emerald-400/25 bg-site-fill px-4 text-sm font-bold text-site-text transition-colors hover:border-emerald-400/50"
                        >
                            <svg class="h-5 w-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.182-.008-.391-.011-.6-.011-.207 0-.544.079-.829.396-.286.319-1.095 1.068-1.095 2.617 0 1.55 1.095 3.05 1.248 3.26.153.21 2.153 3.3 5.223 4.63.73.315 1.3.483 1.743.618.753.225 1.438.193 1.983.117.604-.09 1.84-.752 2.103-1.479.262-.727.262-1.349.183-1.479-.079-.13-.296-.203-.594-.351m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.398 0 .16 5.237.157 11.716c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.504 0 11.79-5.286 11.793-11.793a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span>{{ __('site.home.sticky_whatsapp') }}</span>
                        </a>
                    @endif
                </div>
            @endif
            <a
                href="{{ route('contact') }}"
                onclick="closeMobileMenu()"
                wire:navigate
                class="text-2xl font-headline font-bold text-site-text hover:text-site-link transition-colors {{ request()->routeIs('contact') ? 'text-site-link' : '' }}"
            >
                Contact
            </a>
        </div>

        {{-- Mobile Footer --}}
        <div class="flex-shrink-0 border-t border-site-line-strong px-6 pt-6 pb-[max(1.5rem,env(safe-area-inset-bottom))]">
            <div class="mb-5 flex items-center justify-between">
                <span class="font-headline text-sm font-semibold tracking-tight text-site-muted uppercase">Appearance</span>
                <x-site.theme-toggle />
            </div>
            @if (Route::has('login'))
                @auth
                    <a
                        href="{{ route('dashboard') }}"
                        onclick="closeMobileMenu()"
                        class="flex w-full items-center justify-center gap-3 rounded-full bg-site-accent px-6 py-3.5 text-base font-bold text-white transition-all hover:bg-site-accent-hover active:scale-[0.98]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-headline font-bold">Dashboard</span>
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        onclick="closeMobileMenu()"
                        class="flex w-full items-center justify-center gap-3 rounded-full border border-site-line-strong bg-site-fill px-6 py-3.5 text-base font-bold text-site-text transition-all hover:border-site-accent/40 hover:bg-site-accent-soft active:scale-[0.98]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="font-headline font-bold">Log in</span>
                    </a>
                    @if (\Illuminate\Support\Facades\Route::has('register') && filter_var($settings['enable_registration'] ?? '0', FILTER_VALIDATE_BOOLEAN))
                        <a
                            href="{{ route('register') }}"
                            onclick="closeMobileMenu()"
                            class="mt-3 block text-center text-sm font-medium text-site-muted transition-colors hover:text-site-text"
                        >
                            Need an account? Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</div>

{{-- Mobile Menu Overlay --}}
<div id="mobile-menu-overlay" class="mobile-menu-overlay"></div>

@push('scripts')
    <script>
        (function () {
            'use strict';

            // This push re-runs on every wire:navigate page view. The handlers below live
            // on `document`, so without this guard each navigation stacks another copy.
            if (window.siteNavBound) {
                return;
            }

            window.siteNavBound = true;

            const TRANSITION_DURATION = 400; // ms — matches CSS .mobile-menu transition

            let isOpen = false;
            let closeTimer = null;

            function openMobileMenu() {
                if (isOpen) return;
                isOpen = true;

                const mobileMenu = document.getElementById('mobile-menu');
                const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');

                if (closeTimer) {
                    clearTimeout(closeTimer);
                    closeTimer = null;
                }

                if (mobileMenu) {
                    mobileMenu.removeAttribute('aria-hidden');
                    // Slight defer so the browser paints the element before we add .open
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => mobileMenu.classList.add('open'));
                    });
                }
                if (mobileMenuOverlay) mobileMenuOverlay.classList.add('open');
                if (mobileMenuBtn) mobileMenuBtn.setAttribute('aria-expanded', 'true');

                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                if (!isOpen) return;
                isOpen = false;

                const mobileMenu = document.getElementById('mobile-menu');
                const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');

                if (mobileMenu) mobileMenu.classList.remove('open');
                if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('open');
                if (mobileMenuBtn) mobileMenuBtn.setAttribute('aria-expanded', 'false');

                document.body.style.overflow = '';

                // Re-apply aria-hidden after the slide-out transition finishes
                closeTimer = setTimeout(() => {
                    const menu = document.getElementById('mobile-menu');
                    if (menu && !isOpen) menu.setAttribute('aria-hidden', 'true');
                    closeTimer = null;
                }, TRANSITION_DURATION);
            }

            // Expose globally for onclick handlers on anchor tags
            window.closeMobileMenu = closeMobileMenu;

            /**
             * Take the announcement bar out of the layout and remember that it went.
             *
             * The dismissal is stored against the fingerprint of the live messages, so an
             * edited announcement comes back while the same one stays hidden across reloads
             * and navigations. The attribute on <html> is what actually removes the bar — the
             * collapse is only played first so the header does not snap upward.
             */
            function dismissAnnouncement() {
                const root = document.documentElement;
                const bar = document.getElementById('announcement-ticker-bar');

                try {
                    localStorage.setItem('hb-announcement', root.dataset.announcementKey || '');
                } catch (err) {
                    /* private mode: the dismissal still holds for this visit */
                }

                if (! bar || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    root.dataset.announcement = 'dismissed';

                    return;
                }

                // Height has to be pinned to a number before it can be transitioned to zero,
                // because auto is not an animatable endpoint.
                bar.style.height = `${bar.offsetHeight}px`;
                void bar.offsetHeight;
                bar.style.height = '0px';
                bar.classList.add('is-closing');

                let settled = false;

                const settle = () => {
                    if (settled) {
                        return;
                    }

                    settled = true;
                    bar.style.height = '';
                    bar.classList.remove('is-closing');
                    root.dataset.announcement = 'dismissed';
                };

                bar.addEventListener('transitionend', settle, { once: true });

                // A transition interrupted before the frame is painted never reports an end,
                // so the bar leaves the layout on a timer as well.
                setTimeout(settle, 400);
            }

            // Delegated rather than bound to the elements themselves: wire:navigate
            // replaces the whole <body>, so any direct binding points at a detached node
            // from the previous page.
            document.addEventListener('click', function (e) {
                if (e.target.closest('#mobile-menu-btn')) {
                    openMobileMenu();

                    return;
                }

                if (e.target.closest('#close-announcement-btn')) {
                    dismissAnnouncement();

                    return;
                }

                if (e.target.closest('#mobile-menu-close') || e.target.closest('#mobile-menu-overlay')) {
                    closeMobileMenu();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && isOpen) closeMobileMenu();
            });

            // The menu markup comes back closed from the next page, so the closure has to
            // agree with it; otherwise the first tap after navigating is swallowed by the
            // isOpen guard and the drawer stops opening.
            document.addEventListener('livewire:navigated', function () {
                if (closeTimer) {
                    clearTimeout(closeTimer);
                    closeTimer = null;
                }

                isOpen = false;
                document.body.style.overflow = '';
            });
        })();
    </script>
@endpush
