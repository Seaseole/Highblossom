<nav class="fixed top-0 w-full z-50 bg-white/70 dark:bg-surface/80 backdrop-blur-xl border-b border-outline-variant/10">
    <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="text-xl font-black text-on-surface font-headline tracking-tight">
            {{ $logoText }}
        </a>
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }} pb-1 font-headline font-bold tracking-tight transition-colors">Home</a>
            <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }} pb-1 font-headline font-bold tracking-tight transition-colors">Services</a>
            <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }} pb-1 font-headline font-bold tracking-tight transition-colors">Gallery</a>
            <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }} pb-1 font-headline font-bold tracking-tight transition-colors">Blog</a>
            <a href="{{ route('quote') }}" class="{{ request()->routeIs('quote') ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }} pb-1 font-headline font-bold tracking-tight transition-colors">Quote</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }} pb-1 font-headline font-bold tracking-tight transition-colors">Contact</a>
        </div>
        <div class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden lg:block text-on-surface-variant hover:text-primary transition-colors font-headline font-bold">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:block text-on-surface-variant hover:text-primary transition-colors font-headline font-bold">
                        Log in
                    </a>
                    {{-- @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="primary-gradient text-on-primary px-6 py-2.5 rounded-lg font-headline font-bold scale-95 active:scale-90 duration-200 hover:opacity-90">
                            Register
                        </a>
                    @endif --}}
                @endauth
            @endif
        </div>
    </div>
</nav>
