@php
    $currentRoute = request()->route()->getName();

    $group = [
        'dashboard' => [
            'label' => 'Dashboard',
            'icon' => 'home',
            'routes' => ['dashboard']
        ],
        'bookings' => [
            'label' => 'Bookings',
            'icon' => 'calendar',
            'routes' => ['admin.bookings', 'admin.inspections', 'admin.quotes']
        ],
        'content' => [
            'label' => 'Content',
            'icon' => 'document',
            'routes' => ['admin.about-us', 'admin.testimonials', 'admin.services', 'admin.gallery', 'admin.gallery-categories', 'admin.glass-types', 'admin.service-types']
        ],
        'blog' => [
            'label' => 'Blog',
            'icon' => 'newspaper',
            'routes' => ['admin.posts', 'admin.categories', 'admin.tags']
        ],
        'access' => [
            'label' => 'Access',
            'icon' => 'users',
            'routes' => ['admin.users', 'admin.roles']
        ],
        'media' => [
            'label' => 'Media',
            'icon' => 'image',
            'routes' => ['admin.media-library']
        ],
        'system' => [
            'label' => 'System',
            'icon' => 'cog',
            'routes' => ['admin.settings', 'admin.smtp', 'admin.seo', 'admin.profile']
        ]
    ];

    // Helper function to check if route is active in a group
    $isRouteActive = function($route) use ($currentRoute) {
        if ($route === 'dashboard') {
            return $currentRoute === 'dashboard';
        }
        return str_starts_with($currentRoute, $route . '.') || $currentRoute === $route;
    };

    $isGroupActive = function($groupRoutes) use ($isRouteActive) {
        foreach ($groupRoutes as $route) {
            if ($isRouteActive($route)) {
                return true;
            }
        }
        return false;
    };

    // Helper to get route name for a specific item
    $getRouteName = function($route) {
        if ($route === 'dashboard') {
            return route('dashboard');
        }
        if ($route === 'admin.about-us') {
            return route('admin.about-us.edit');
        }
        if ($route === 'admin.profile') {
            return route('admin.profile.index');
        }
        if ($route === 'admin.seo') {
            return route('admin.seo.static-routes');
        }
        return route($route . '.index');
    };

    // Helper to get icon for specific routes
    $getRouteIcon = function($route) {
        $icons = [
            'dashboard' => 'home',
            'admin.bookings' => 'calendar',
            'admin.inspections' => 'check-circle',
            'admin.quotes' => 'document',
            'admin.about-us' => 'information-circle',
            'admin.testimonials' => 'chat-bubble-left',
            'admin.services' => 'cube',
            'admin.gallery' => 'photo',
            'admin.gallery-categories' => 'folder',
            'admin.glass-types' => 'squares-2x2',
            'admin.service-types' => 'tag',
            'admin.posts' => 'document',
            'admin.categories' => 'tag',
            'admin.tags' => 'hash',
            'admin.users' => 'users',
            'admin.roles' => 'shield-check',
            'admin.media-library' => 'image',
            'admin.settings' => 'cog',
            'admin.smtp' => 'envelope',
            'admin.seo' => 'magnifying-glass',
            'admin.profile' => 'user',
        ];
        return $icons[$route] ?? 'document';
    };

    // Helper to get SVG icon for mobile sidebar
    $getMobileIcon = function($route) {
        $icons = [
            'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'admin.bookings' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'admin.inspections' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            'admin.quotes' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'admin.about-us' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'admin.testimonials' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            'admin.services' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
            'admin.gallery' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            'admin.gallery-categories' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
            'admin.glass-types' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
            'admin.service-types' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'admin.posts' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'admin.categories' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
            'admin.tags' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
            'admin.users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            'admin.roles' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'admin.media-library' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12',
            'admin.settings' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'admin.smtp' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'admin.seo' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
            'admin.profile' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        ];
        return $icons[$route] ?? 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
    };

    // Helper to get label for specific routes
    $getRouteLabel = function($route) {
        $labels = [
            'dashboard' => 'Dashboard',
            'admin.bookings' => 'Bookings',
            'admin.inspections' => 'Inspections',
            'admin.quotes' => 'Quotes',
            'admin.about-us' => 'About Us',
            'admin.testimonials' => 'Testimonials',
            'admin.services' => 'Services',
            'admin.gallery' => 'Gallery',
            'admin.gallery-categories' => 'Gallery Categories',
            'admin.glass-types' => 'Glass Types',
            'admin.service-types' => 'Service Types',
            'admin.posts' => 'Posts',
            'admin.categories' => 'Categories',
            'admin.tags' => 'Tags',
            'admin.users' => 'Users',
            'admin.roles' => 'Roles',
            'admin.media-library' => 'Media Library',
            'admin.settings' => 'Settings',
            'admin.smtp' => 'SMTP',
            'admin.seo' => 'SEO',
            'admin.profile' => 'Profile',
        ];
        return $labels[$route] ?? ucfirst(str_replace(['admin.', '-'], ['', ' '], $route));
    };
@endphp

<div x-data="{ theme: @entangle('theme'), mobileMenuOpen: @entangle('mobileMenuOpen') }" x-init="
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        const updateTheme = () => {
            if (theme === 'dark' || (theme === 'auto' && prefersDark.matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            localStorage.setItem('theme', theme);
        };
        $watch('theme', updateTheme);
        updateTheme();
        prefersDark.addEventListener('change', () => { if (theme === 'auto') updateTheme(); });
    " class="relative">
    
    {{-- Mobile Menu Button --}}
    <button @click="mobileMenuOpen = true" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-admin-surface border border-admin-border rounded-xl shadow-2xl hover:bg-admin-surface-alt transition-all duration-200 ease-[cubic-bezier(0.32,0.72,0,1)]">
        <svg class="w-6 h-6 text-admin-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- Mobile Overlay --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity duration-200 ease-[cubic-bezier(0.32,0.72,0,1)]" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200 ease-[cubic-bezier(0.32,0.72,0,1)]" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false" class="lg:hidden fixed inset-0 bg-admin-bg/95 backdrop-blur-3xl z-40" style="display: none;"></div>

    {{-- Desktop Sidebar --}}
    <flux:sidebar collapsible class="hidden lg:flex">
        {{-- Brand --}}
        <flux:sidebar.brand name="{{ config('app.name') }} Admin" {{ $attributes }}>
            <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-[#DC2626] text-white shadow-lg shadow-[#DC2626]/20">
                <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </x-slot>
        </flux:sidebar.brand>

        {{-- Navigation --}}
        <flux:sidebar.nav>
            @foreach($group as $groupKey => $groupData)
                @if(count($groupData['routes']) === 1)
                    {{-- Single item group - render as standalone item --}}
                    @php
                        $route = $groupData['routes'][0];
                        $isActive = $isRouteActive($route);
                    @endphp
                    <flux:sidebar.item 
                        :href="$getRouteName($route)"
                        :icon="$getRouteIcon($route)"
                        :active="$isActive"
                        wire:navigate
                    >
                        {{ $getRouteLabel($route) }}
                    </flux:sidebar.item>
                @else
                    {{-- Multi-item group - render as collapsible group --}}
                    <flux:sidebar.group label="{{ $groupData['label'] }}" :collapsed="!$isGroupActive($groupData['routes'])">
                        @foreach($groupData['routes'] as $route)
                            @php
                                $isActive = $isRouteActive($route);
                            @endphp
                            <flux:sidebar.item 
                                :href="$getRouteName($route)"
                                :icon="$getRouteIcon($route)"
                                :active="$isActive"
                                wire:navigate
                            >
                                {{ $getRouteLabel($route) }}
                            </flux:sidebar.item>
                        @endforeach
                    </flux:sidebar.group>
                @endif
            @endforeach
        </flux:sidebar.nav>

        {{-- Bottom Section: Theme Toggle & Profile --}}
        <div class="flex flex-col gap-2 p-4 border-t border-admin-border">
            {{-- Theme Toggle --}}
            <button 
                wire:click="toggleTheme" 
                class="flex items-center gap-3 w-full py-2 px-4 rounded-xl bg-admin-surface-alt border border-admin-border hover:bg-admin-surface hover:border-[#DC2626]/30 transition-all duration-200 ease-[cubic-bezier(0.32,0.72,0,1)] active:scale-[0.98]"
            >
                @if($theme === 'light')
                    <svg class="w-5 h-5 text-admin-text flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-admin-text whitespace-nowrap">Light Mode</span>
                @elseif($theme === 'dark')
                    <svg class="w-5 h-5 text-admin-text flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span class="text-admin-text whitespace-nowrap">Dark Mode</span>
                @else
                    <svg class="w-5 h-5 text-admin-text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-admin-text-muted whitespace-nowrap">Auto Mode</span>
                @endif
            </button>

            {{-- Profile Dropdown --}}
            <flux:dropdown position="top" align="start">
                <flux:sidebar.profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu>
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <flux:avatar
                            :name="auth()->user()->name"
                            :initials="auth()->user()->initials()"
                        />
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <flux:heading class="truncate text-admin-text">{{ auth()->user()->name }}</flux:heading>
                            <flux:text class="truncate text-admin-text-muted">{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('admin.profile.index')" icon="cog" wire:navigate class="text-admin-text-muted hover:text-admin-text">
                            Settings
                        </flux:menu.item>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer text-admin-text-muted hover:text-admin-text"
                            >
                                Log out
                            </flux:menu.item>
                        </form>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:sidebar>

    {{-- Mobile Sidebar --}}
    <aside 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition-transform duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]" 
        x-transition:enter-start="-translate-x-full" 
        x-transition:enter-end="translate-x-0" 
        x-transition:leave="transition-transform duration-200 ease-[cubic-bezier(0.32,0.72,0,1)]" 
        x-transition:leave-start="translate-x-0" 
        x-transition:leave-end="-translate-x-full" 
        class="lg:hidden fixed inset-y-0 left-0 w-72 bg-admin-surface border-r border-admin-border shadow-2xl flex flex-col z-50 backdrop-blur-3xl"
        style="display: none;"
    >
        {{-- Logo --}}
        <div class="p-4 border-b border-admin-border flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-[#DC2626] text-white shadow-lg shadow-[#DC2626]/20">
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-admin-text">Highblossom Admin</h1>
            </div>
            <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-admin-surface-alt transition-colors">
                <svg class="w-5 h-5 text-admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="p-4 flex-1 overflow-y-auto space-y-1">
            @foreach($group as $groupKey => $groupData)
                @if(count($groupData['routes']) === 1)
                    {{-- Single item group - render as standalone item --}}
                    @php
                        $route = $groupData['routes'][0];
                        $isActive = $isRouteActive($route);
                    @endphp
                    <a href="{{ $getRouteName($route) }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 py-2.5 px-4 rounded-xl {{ $isActive ? 'bg-admin-accent/10 border border-admin-accent/30 text-admin-accent font-semibold' : 'text-admin-text-muted hover:bg-admin-surface-alt hover:text-admin-text' }} transition-all duration-200">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $getMobileIcon($route) }}" />
                        </svg>
                        <span class="whitespace-nowrap">{{ $getRouteLabel($route) }}</span>
                    </a>
                @else
                    {{-- Multi-item group - render with header --}}
                    <div class="mt-4 mb-2 px-4">
                        <span class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">{{ $groupData['label'] }}</span>
                    </div>
                    @foreach($groupData['routes'] as $route)
                        @php
                            $isActive = $isRouteActive($route);
                        @endphp
                        <a href="{{ $getRouteName($route) }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 py-2.5 px-4 rounded-xl {{ $isActive ? 'bg-admin-accent/10 border border-admin-accent/30 text-admin-accent font-semibold' : 'text-admin-text-muted hover:bg-admin-surface-alt hover:text-admin-text' }} transition-all duration-200">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $getMobileIcon($route) }}" />
                            </svg>
                            <span class="whitespace-nowrap">{{ $getRouteLabel($route) }}</span>
                        </a>
                    @endforeach
                @endif
            @endforeach
        </nav>

        {{-- Bottom Section: Theme Toggle & Profile --}}
        <div class="flex flex-col gap-2 p-4 border-t border-admin-border">
            {{-- Theme Toggle --}}
            <button 
                wire:click="toggleTheme" 
                class="flex items-center gap-3 w-full py-2.5 px-4 rounded-xl bg-admin-surface-alt border border-admin-border hover:bg-admin-surface hover:border-[#DC2626]/30 transition-all duration-200 active:scale-[0.98]"
            >
                @if($theme === 'light')
                    <svg class="w-5 h-5 text-admin-text flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-admin-text whitespace-nowrap">Light Mode</span>
                @elseif($theme === 'dark')
                    <svg class="w-5 h-5 text-admin-text flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span class="text-admin-text whitespace-nowrap">Dark Mode</span>
                @else
                    <svg class="w-5 h-5 text-admin-text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-admin-text-muted whitespace-nowrap">Auto Mode</span>
                @endif
            </button>

            {{-- Profile --}}
            <div class="flex items-center gap-3 py-2.5 px-4 rounded-xl bg-admin-surface-alt border border-admin-border">
                <div class="flex aspect-square size-8 items-center justify-center rounded-full bg-[#DC2626] text-white text-sm font-semibold">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-admin-text truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-admin-text-muted truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full py-2.5 px-4 rounded-xl bg-[#DC2626] text-white hover:bg-[#B91C1C] shadow-lg shadow-[#DC2626]/20 transition-all duration-200 active:scale-[0.98]">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </aside>
</div>

@script
<script>
    // Listen for theme changes from Livewire
    document.addEventListener('theme-changed', (event) => {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        const isDark = event.detail.theme === 'dark' || (event.detail.theme === 'auto' && prefersDark.matches);

        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Store in localStorage for persistence across page loads
        localStorage.setItem('theme', event.detail.theme);
    });
</script>
@endscript
