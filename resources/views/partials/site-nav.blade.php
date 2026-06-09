<nav id="public-main-nav" class="sticky top-0 w-full z-40 bg-[#0A0A0F]/90 backdrop-blur-xl border-b border-white/5 flex flex-col">
    @php
        $isAnnounceActive = $announcementActive ?? \App\Models\CompanySetting::get('announcement_active', false);
        $announceList = $announcements ?? \App\Models\CompanySetting::get('announcements', []);
    @endphp
@if($isAnnounceActive && !empty($announceList))
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
            @keyframes marquee-scroll {
                0% { transform: translate3d(0, 0, 0); }
                100% { transform: translate3d(-33.333%, 0, 0); }
            }
            .shimmer-glow::after {
                content: '';
                position: absolute;
                top: 0; right: 0; bottom: 0; left: 0;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08) 50%, transparent);
                transform: translateX(-100%);
                animation: shimmer-swipe 8s infinite;
            }
            @keyframes shimmer-swipe {
                0% { transform: translateX(-100%); }
                35%, 100% { transform: translateX(100%); }
            }
        </style>
        <div id="announcement-ticker-bar" class="w-full announcement-gradient border-b border-white/10 text-white py-1.5 px-4 relative overflow-hidden transition-all duration-300 ease-out select-none flex items-center shimmer-glow" style="height: 34px; min-height: 34px;">
            <div class="max-w-[1400px] mx-auto w-full flex items-center justify-between gap-4 z-10">
                <!-- Label on left -->
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <span class="material-symbols-outlined text-[15px] text-white/90 animate-pulse">campaign</span>
                    <span class="text-[9px] tracking-widest font-black uppercase bg-white/10 border border-white/20 px-1.5 py-0.5 rounded text-white font-headline">Notice</span>
                </div>
                
                <!-- Scrolling marquee container -->
                <div class="flex-1 overflow-hidden relative mx-2 h-5 flex items-center">
                    <div class="ticker-container">
                        <div class="ticker-wrapper">
                            @for ($i = 0; $i < 3; $i++)
                                @foreach($announceList as $announcement)
                                    @php
                                        $link = $announcement['link'] ?? '';
                                        if (!empty($link) && !str_starts_with($link, 'http') && Route::has($link)) {
                                            $link = route($link);
                                        }
                                    @endphp
                                    <div class="ticker-item text-[11px] font-medium tracking-wide text-white mx-4">
                                        @if(!empty($link))
                                            <a href="{{ $link }}" class="flex items-center gap-1 hover:text-white/80 transition-colors">
                                                <span>{{ $announcement['text'] }}</span>
                                                <span class="material-symbols-outlined text-[12px] opacity-80">arrow_forward</span>
                                            </a>
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
                <button type="button" id="close-announcement-btn" class="text-white/60 hover:text-white transition-colors p-0.5 flex-shrink-0 relative group" aria-label="Close announcement">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>
        </div>
    @endif

    <div class="flex justify-between items-center px-6 lg:px-8 py-4 max-w-[1400px] mx-auto w-full">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity">
            <x-logo-trust-badge 
                :business-name="$logoText" 
                :icon-src="$businessLogo ? Storage::url($businessLogo) : null" 
                font-size="text-lg lg:text-xl font-bold font-headline" 
                :badge-size="10"
                gap="gap-1.5"
                :badge-top="-2"
                :badge-right="-16"
            />
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                Home
            </a>
            <a href="{{ route('about-us') }}" class="nav-link {{ request()->routeIs('about-us') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                About Us
            </a>
            <a href="{{ route('services') }}" class="nav-link {{ request()->routeIs('services') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                Services
            </a>
            <a href="{{ route('gallery') }}" class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                Gallery
            </a>
            <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                Blog
            </a>
            <a href="{{ route('quote') }}" class="btn-premium text-sm py-2.5 px-5">
                Get Quote
            </a>
        </div>

        {{-- Right Side Actions --}}
        <div class="flex items-center gap-4">
            {{-- Social Media Icons --}}
            <div class="hidden md:flex items-center gap-3">
                @if($facebookUrl)
                <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="text-[#A1A1AA] hover:text-[#DC2626] transition-colors" title="Facebook">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                @endif
                @if($instagramUrl)
                <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="text-[#A1A1AA] hover:text-[#DC2626] transition-colors" title="Instagram">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                @endif
                @if($linkedinUrl)
                <a href="{{ $linkedinUrl }}" target="_blank" rel="noopener noreferrer" class="text-[#A1A1AA] hover:text-[#DC2626] transition-colors" title="LinkedIn">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>
                @endif
            </div>

            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden lg:block text-[#A1A1AA] hover:text-[#FAFAFA] transition-colors font-headline font-semibold text-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:block text-[#A1A1AA] hover:text-[#FAFAFA] transition-colors font-headline font-semibold text-sm">
                        Log in
                    </a>
                @endauth
            @endif

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 text-[#FAFAFA] hover:text-[#DC2626] transition-colors" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div id="mobile-menu" class="mobile-menu fixed top-0 right-0 w-full max-w-sm h-screen h-[100dvh] bg-[#121218] border-l border-white/10 z-60 hidden md:hidden">
            <div class="flex flex-col h-full">
            {{-- Mobile Header --}}
            <div class="flex justify-between items-center px-6 py-4 border-b border-white/10 flex-shrink-0">
                <span class="text-lg font-bold text-[#FAFAFA] font-headline">Menu</span>
                <button id="mobile-menu-close" class="p-2 text-[#A1A1AA] hover:text-[#FAFAFA] transition-colors" aria-label="Close menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Mobile Links --}}
            <div class="flex-1 flex flex-col px-6 py-8 gap-6 overflow-y-auto">
                <a href="{{ route('home') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('home') ? 'text-[#DC2626]' : '' }}">
                    Home
                </a>
                <a href="{{ route('about-us') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('about-us') ? 'text-[#DC2626]' : '' }}">
                    About Us
                </a>
                <a href="{{ route('services') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('services') ? 'text-[#DC2626]' : '' }}">
                    Services
                </a>
                <a href="{{ route('gallery') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('gallery') ? 'text-[#DC2626]' : '' }}">
                    Gallery
                </a>
                <a href="{{ route('blog') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('blog') ? 'text-[#DC2626]' : '' }}">
                    Blog
                </a>
                <a href="{{ route('quote') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('quote') ? 'text-[#DC2626]' : '' }}">
                    Get Quote
                </a>
                <a href="{{ route('contact') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('contact') ? 'text-[#DC2626]' : '' }}">
                    Contact
                </a>
            </div>

            {{-- Mobile Footer --}}
            <div class="px-6 py-6 border-t border-white/10 flex-shrink-0">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" onclick="closeMobileMenu()" class="flex items-center gap-3 text-[#A1A1AA] hover:text-[#FAFAFA] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-headline font-semibold">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" onclick="closeMobileMenu()" class="flex items-center gap-3 text-[#A1A1AA] hover:text-[#FAFAFA] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="font-headline font-semibold">Log in</span>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </div>

{{-- Mobile Menu Overlay --}}
<div id="mobile-menu-overlay" class="mobile-menu-overlay fixed inset-0 bg-black/60 z-50 md:hidden"></div>

@push('scripts')
<script>
    function closeMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        
        if (mobileMenu) mobileMenu.classList.remove('open');
        if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('open');
        
        document.body.style.overflow = '';
        
        // Optional: add back hidden after transition if you want total removal from render tree
        // but visibility: hidden in CSS is usually enough.
    }

    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

        function openMobileMenu() {
            if (mobileMenu) {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => mobileMenu.classList.add('open'), 10);
            }
            if (mobileMenuOverlay) {
                mobileMenuOverlay.classList.add('open');
            }
            document.body.style.overflow = 'hidden';
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
        if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeMobileMenu);
        if (mobileMenuOverlay) mobileMenuOverlay.addEventListener('click', closeMobileMenu);

        // Close Announcement Bar
        const closeAnnouncementBtn = document.getElementById('close-announcement-btn');
        if (closeAnnouncementBtn) {
            closeAnnouncementBtn.addEventListener('click', function() {
                const bar = document.getElementById('announcement-ticker-bar');
                if (bar) {
                    bar.style.height = '0px';
                    bar.style.paddingTop = '0px';
                    bar.style.paddingBottom = '0px';
                    bar.style.opacity = '0';
                    bar.style.borderBottomWidth = '0px';
                    
                    // Set session flag to keep it closed for current session
                    sessionStorage.setItem('dismissed_announcement', 'true');
                }
            });
        }
    });
</script>
@endpush
