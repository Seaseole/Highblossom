<nav class="fixed top-0 w-full z-50 bg-[#0A0A0F]/80 backdrop-blur-xl border-b border-white/5">
    <div class="flex justify-between items-center px-6 lg:px-8 py-4 max-w-[1400px] mx-auto">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="text-lg lg:text-xl font-bold text-[#FAFAFA] font-headline tracking-tight hover:text-[#DC2626] transition-colors">
            {{ $logoText }}
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                Home
            </a>
            <a href="{{ route('services') }}" class="nav-link {{ request()->routeIs('services') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                Services
            </a>
            <a href="{{ route('gallery') }}" class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                Gallery
            </a>
            <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }} font-headline font-semibold text-sm tracking-tight">
                Blog
            </a>
            <a href="{{ route('quote') }}" class="btn-premium text-sm py-2.5 px-5">
                Get Quote
            </a>
        </div>

        {{-- Right Side Actions --}}
        <div class="flex items-center gap-4">
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
    <div id="mobile-menu" class="mobile-menu fixed top-0 right-0 w-full max-w-sm h-screen bg-[#121218] border-l border-white/10 z-50 md:hidden">
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
                <a href="{{ route('home') }}" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('home') ? 'text-[#DC2626]' : '' }}">
                    Home
                </a>
                <a href="{{ route('services') }}" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('services') ? 'text-[#DC2626]' : '' }}">
                    Services
                </a>
                <a href="{{ route('gallery') }}" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('gallery') ? 'text-[#DC2626]' : '' }}">
                    Gallery
                </a>
                <a href="{{ route('blog.index') }}" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('blog.*') ? 'text-[#DC2626]' : '' }}">
                    Blog
                </a>
                <a href="{{ route('quote') }}" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('quote') ? 'text-[#DC2626]' : '' }}">
                    Get Quote
                </a>
                <a href="{{ route('contact') }}" class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors {{ request()->routeIs('contact') ? 'text-[#DC2626]' : '' }}">
                    Contact
                </a>
            </div>

            {{-- Mobile Footer --}}
            <div class="px-6 py-6 border-t border-white/10">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-[#A1A1AA] hover:text-[#FAFAFA] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-headline font-semibold">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-3 text-[#A1A1AA] hover:text-[#FAFAFA] transition-colors">
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
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

        function openMobileMenu() {
            mobileMenu.classList.add('open');
            mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('open');
            mobileMenuOverlay.classList.add('hidden');
            document.body.style.overflow = '';
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
