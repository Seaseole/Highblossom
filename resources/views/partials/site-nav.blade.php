<nav class="fixed top-0 w-full z-50 bg-[#0A0A0F]/80 backdrop-blur-xl border-b border-white/5">
    <div class="flex justify-between items-center px-6 lg:px-8 py-4 max-w-[1400px] mx-auto">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
            @if($businessLogo)
                <img src="{{ Storage::url($businessLogo) }}" alt="{{ $logoText }}" class="h-10 w-auto object-contain">
            @else
                <span class="text-lg lg:text-xl font-bold text-[#FAFAFA] font-headline tracking-tight hover:text-[#DC2626] transition-colors">
                    {{ $logoText }}
                </span>
            @endif
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

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="mobile-menu fixed top-0 right-0 w-full max-w-sm h-screen bg-[#121218] border-l border-white/10 z-50 hidden md:hidden">
        <div class="flex flex-col h-full">
            {{-- Mobile Header --}}
            <div class="flex justify-between items-center px-6 py-4 border-b border-white/10">
                <span class="text-lg font-bold text-[#FAFAFA] font-headline">Menu</span>
                <button id="mobile-menu-close" class="p-2 text-[#A1A1AA] hover:text-[#FAFAFA] transition-colors" aria-label="Close menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Mobile Links --}}
            <div class="flex-1 flex flex-col px-6 py-8 gap-6">
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
                {{-- <a href="{{ route('blog.index') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('blog.*') ? 'text-[#DC2626]' : '' }}">
                    Blog
                </a> --}}
                <a href="{{ route('quote') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('quote') ? 'text-[#DC2626]' : '' }}">
                    Get Quote
                </a>
                <a href="{{ route('contact') }}" onclick="closeMobileMenu()" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('contact') ? 'text-[#DC2626]' : '' }}">
                    Contact
                </a>
            </div>

            {{-- Mobile Footer --}}
            <div class="px-6 py-6 border-t border-white/10">
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
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden"></div>
</nav>

@push('scripts')
<script>
    function closeMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        if (mobileMenu) {
            mobileMenu.classList.remove('open');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        }
        if (mobileMenuOverlay) {
            setTimeout(() => {
                mobileMenuOverlay.classList.add('hidden');
            }, 300);
        }
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

        function openMobileMenu() {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => {
                mobileMenu.classList.add('open');
            }, 10);
            mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMobileMenu);
        }

        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', closeMobileMenu);
        }
    });
</script>
@endpush
