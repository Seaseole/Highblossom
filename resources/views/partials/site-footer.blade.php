<footer class="w-full border-t border-white/5 bg-[#0A0A0F]">
    {{-- Main Footer Content --}}
    <div class="mx-auto max-w-[1400px] px-6 py-16 lg:px-8 lg:py-20">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4 lg:gap-12">
            {{-- Brand Column --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="transition-opacity hover:opacity-80">
                    <x-logo-trust-badge
                        :business-name="$logoText"
                        :icon-src="$businessLogo ? media_url($businessLogo) : null"
                        font-size="text-xl font-bold font-headline"
                        :show-badge="false"
                    />
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-[#A1A1AA]">
                    Premium automotive glass repair, restoration, and replacement for Gaborone's vehicle owners and
                    commercial fleets.
                </p>
                <div class="mt-6 flex items-center gap-4">
                    @if ($contact->hasPhone())
                        <a
                            href="{{ $contact->telHref($contact->primaryPhone) }}"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full text-[#A1A1AA] transition-colors hover:text-[#DC2626] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                            aria-label="{{ __('site.home.cta_band_call') }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </a>
                    @endif
                    @if ($contact->hasWhatsapp())
                        <a
                            href="{{ $contact->whatsappHrefBare() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full text-[#A1A1AA] transition-colors hover:text-[#25D366] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                            aria-label="{{ __('site.home.cta_band_whatsapp') }}"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.182-.008-.391-.011-.6-.011-.207 0-.544.079-.829.396-.286.319-1.095 1.068-1.095 2.617 0 1.55 1.095 3.05 1.248 3.26.153.21 2.153 3.3 5.223 4.63.73.315 1.3.483 1.743.618.753.225 1.438.193 1.983.117.604-.09 1.84-.752 2.103-1.479.262-.727.262-1.349.183-1.479-.079-.13-.296-.203-.594-.351m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.398 0 .16 5.237.157 11.716c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.504 0 11.79-5.286 11.793-11.793a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                    @endif
                    @if ($contact->emailHref())
                        <a
                            href="{{ $contact->emailHref() }}"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full text-[#A1A1AA] transition-colors hover:text-[#DC2626] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                            aria-label="{{ __('site.home.cta_band_email') }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="mt-4 flex items-center gap-3">
                    @if ($facebookUrl)
                        <a
                            href="{{ $facebookUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
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
                            class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
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
                            class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                            title="LinkedIn"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Services Column: links point at each service's own block on the
                 services page instead of the top of /services for all five. --}}
            <div>
                <h4 class="font-headline mb-6 text-sm font-semibold tracking-wide text-[#FAFAFA] uppercase">
                    Services
                </h4>
                @if ($footerServices->isNotEmpty())
                    <ul class="space-y-3">
                        @foreach ($footerServices as $footerService)
                            <li>
                                <a
                                    href="{{ route('services') }}#service-{{ $footerService->slug }}"
                                    class="inline-flex min-h-[44px] items-center text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                                >
                                    {{ $footerService->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <a
                        href="{{ route('services') }}"
                        class="inline-flex min-h-[44px] items-center text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                    >
                        {{ __('site.services') }}
                    </a>
                @endif
            </div>

            {{-- Company Column --}}
            <div>
                <h4 class="font-headline mb-6 text-sm font-semibold tracking-wide text-[#FAFAFA] uppercase">Company</h4>
                <ul class="space-y-3">
                    <li>
                        <a
                            href="{{ route('home') }}"
                            class="inline-flex min-h-[44px] items-center text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                        >
                            {{ __('site.nav_home') }}
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('about-us') }}"
                            class="inline-flex min-h-[44px] items-center text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                        >
                            {{ __('site.about') }}
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('gallery') }}"
                            class="inline-flex min-h-[44px] items-center text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                        >
                            Our Work
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('blog') }}"
                            class="inline-flex min-h-[44px] items-center text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                        >
                            {{ __('site.blog') }}
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('quote') }}"
                            class="inline-flex min-h-[44px] items-center text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                        >
                            {{ __('site.get_quote') }}
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('contact') }}"
                            class="inline-flex min-h-[44px] items-center text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                        >
                            {{ __('site.contact') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Contact Column --}}
            <div>
                <h4 class="font-headline mb-6 text-sm font-semibold tracking-wide text-[#FAFAFA] uppercase">Contact</h4>
                <div class="space-y-4">
                    @if ($contact->hasPhone())
                        <div class="glass-card rounded-xl p-4">
                            <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">Phone</div>
                            <a
                                href="{{ $contact->telHref($contact->primaryPhone) }}"
                                class="font-headline inline-flex min-h-[44px] items-center font-semibold text-[#FAFAFA] transition-colors hover:text-[#DC2626] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                            >
                                {{ $contact->primaryPhone }}
                            </a>
                            @if (filled($contact->secondaryPhone))
                                <a
                                    href="{{ $contact->telHref($contact->secondaryPhone) }}"
                                    class="font-headline mt-1 inline-flex min-h-[44px] items-center text-sm font-medium text-[#A1A1AA] transition-colors hover:text-[#DC2626] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                                >
                                    {{ $contact->secondaryPhone }}
                                </a>
                            @endif
                        </div>
                    @endif

                    @if (filled($contact->address))
                        <div class="glass-card rounded-xl p-4">
                            <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">Location</div>
                            <p class="text-sm leading-relaxed text-[#FAFAFA]">{{ $contact->address }}</p>
                        </div>
                    @endif

                    @if ($contact->hasWhatsapp())
                        <div class="glass-card rounded-xl p-4">
                            <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">WhatsApp</div>
                            <a
                                href="{{ $contact->whatsappHref() }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex min-h-[44px] items-center gap-2 text-sm font-medium text-[#FAFAFA] transition-colors hover:text-[#25D366] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                            >
                                <svg class="h-5 w-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.182-.008-.391-.011-.6-.011-.207 0-.544.079-.829.396-.286.319-1.095 1.068-1.095 2.617 0 1.55 1.095 3.05 1.248 3.26.153.21 2.153 3.3 5.223 4.63.73.315 1.3.483 1.743.618.753.225 1.438.193 1.983.117.604-.09 1.84-.752 2.103-1.479.262-.727.262-1.349.183-1.479-.079-.13-.296-.203-.594-.351m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.398 0 .16 5.237.157 11.716c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.504 0 11.79-5.286 11.793-11.793a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span>{{ __('site.home.cta_band_whatsapp') }}</span>
                            </a>
                        </div>
                    @endif

                    @if ($contact->openingHours()->isNotEmpty())
                        <div class="glass-card rounded-xl p-4">
                            <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">Hours</div>
                            <ul class="space-y-1 text-sm text-[#FAFAFA]">
                                @foreach ($contact->openingHours() as $line)
                                    <li>{{ $line }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-white/5">
        <div class="mx-auto max-w-[1400px] px-6 py-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                <p class="text-sm text-[#71717A]">© {{ date('Y') }} {{ $companyName }}. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a
                        href="{{ route('privacy') }}"
                        class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                    >
                        Privacy Policy
                    </a>
                    <a
                        href="{{ route('terms') }}"
                        class="text-sm text-[#71717A] transition-colors hover:text-[#FAFAFA]"
                    >
                        Terms of Service
                    </a>
                </div>
            </div>
            <div class="mt-6 flex justify-center border-t border-white/5 pt-6">
                <span class="flex items-center gap-2 text-sm text-[#71717A]">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                    Crafted with care by
                    <a
                        href="{{ route('home') }}"
                        class="font-medium text-[#A1A1AA] transition-colors duration-200 hover:text-[#DC2626]"
                    >Eugene Seaseole</a>
                </span>
            </div>
        </div>
    </div>
</footer>
