@php
    $currentRoute = request()->route()?->getName() ?? '';
    $user = auth()->user();
    $userCount = \App\Models\User::count();

    $group = [
        'dashboard' => ['label' => 'Overview', 'icon' => 'home', 'routes' => ['dashboard']],
        'bookings' => ['label' => 'Bookings', 'icon' => 'calendar', 'routes' => ['admin.bookings', 'admin.inspections', 'admin.quotes']],
        'content' => ['label' => 'Content', 'icon' => 'document', 'routes' => ['admin.about-us', 'admin.testimonials', 'admin.services', 'admin.gallery', 'admin.gallery-categories', 'admin.partners', 'admin.staff', 'admin.glass-types', 'admin.service-types', 'admin.contact-messages']],
        'blog' => ['label' => 'Blog', 'icon' => 'newspaper', 'routes' => ['admin.posts', 'admin.categories', 'admin.tags']],
        'access' => ['label' => 'Team & Access', 'icon' => 'users', 'routes' => ['admin.users', 'admin.roles']],
        'media' => ['label' => 'Media', 'icon' => 'image', 'routes' => ['admin.media-library']],
        'system' => ['label' => 'System', 'icon' => 'cog', 'routes' => ['admin.settings', 'admin.seo']]
    ];

    $isRouteActive = fn($route) => str_starts_with($currentRoute, $route . '.') || $currentRoute === $route || ($route === 'dashboard' && $currentRoute === 'dashboard');
    $isGroupActive = fn($groupRoutes) => collect($groupRoutes)->contains(fn($r) => $isRouteActive($r));
    $getRouteName = fn($route) => route($route === 'dashboard' ? 'dashboard' : ($route === 'admin.about-us' ? 'admin.about-us.edit' : ($route === 'admin.seo' ? 'admin.seo.static-routes' : $route . '.index')));
    $getRouteLabel = fn($route) => [
            'dashboard' => 'Dashboard', 'admin.bookings' => 'Bookings', 'admin.inspections' => 'Inspections', 'admin.quotes' => 'Quotes',
            'admin.about-us' => 'About Us', 'admin.testimonials' => 'Testimonials', 'admin.services' => 'Services', 'admin.gallery' => 'Gallery',
            'admin.gallery-categories' => 'Gallery Categories', 'admin.partners' => 'Partners', 'admin.staff' => 'Staff',
            'admin.glass-types' => 'Glass Types', 'admin.service-types' => 'Service Types', 'admin.contact-messages' => 'Messages',
            'admin.posts' => 'Posts', 'admin.categories' => 'Categories', 'admin.tags' => 'Tags', 'admin.users' => 'Users',
            'admin.roles' => 'Roles', 'admin.media-library' => 'Media', 'admin.settings' => 'Settings', 'admin.seo' => 'SEO'
        ][$route] ?? ucfirst(str_replace(['admin.', '-'], ['', ' '], $route));
@endphp

<div class="h-full flex flex-col bg-white dark:bg-[#0A0A0F] border-r border-gray-200 dark:border-white/5 w-0 min-w-0 overflow-hidden lg:w-auto lg:overflow-visible"
     x-data="{ touchStartX: 0 }">
    
    {{-- Overlay for mobile --}}
    <div x-show="mobileOpen" 
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 lg:hidden" 
         @click="mobileOpen = false"
         style="display: none;"
         aria-hidden="true"></div>

    {{-- Sidebar Panel --}}
    <div id="sidebar-panel" class="relative z-50 flex h-full flex-col bg-white dark:bg-[#0A0A0F] border-r border-gray-200 dark:border-white/5 
                transition-transform duration-200 ease-in-out
                w-64 fixed lg:relative shadow-2xl lg:shadow-none"
                x-ref="sidebarPanel"
                x-on:touchstart="touchStartX = $event.touches[0].clientX"
                x-on:touchend="if(mobileOpen && touchStartX - $event.changedTouches[0].clientX > 80) mobileOpen = false"
                :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    
    {{-- Brand --}}
    <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-white/5">
        <a href="/" class="flex items-center gap-3" aria-label="Go to homepage">
            <div class="flex aspect-square size-8 items-center justify-center rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 overflow-hidden">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="size-full object-cover">
                @else
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                @endif
            </div>
            <span class="font-semibold text-gray-900 dark:text-white">{{ $companyName }}</span>
        </a>
        <button @click="mobileOpen = false" 
                x-ref="closeBtn"
                class="lg:hidden ml-auto flex items-center justify-center size-9 text-gray-400 hover:text-gray-700 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-white/5"
                aria-label="Close navigation">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto p-4 space-y-2" role="navigation" aria-label="Admin navigation">
        @forelse($group as $groupKey => $groupData)
            @php $active = $isGroupActive($groupData['routes']); @endphp
            <div x-data="{ open: @js($active) }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2 text-[0.7rem] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider hover:text-gray-900 dark:hover:text-white"
                        :aria-expanded="open.toString()">
                    {{ $groupData['label'] }}
                    <svg class="size-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse.duration.200ms class="space-y-1">
                    @foreach($groupData['routes'] as $route)
                        <a href="{{ $getRouteName($route) }}" 
                           @click="mobileOpen = false"
                           class="flex items-center gap-3 px-3 py-2 min-h-[44px] rounded-xl text-sm font-medium transition-all {{ $isRouteActive($route) ? 'bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                            {{ $getRouteLabel($route) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="px-3 py-6 text-sm text-gray-400 dark:text-gray-500 text-center">No navigation items available.</p>
        @endforelse
    </nav>

    {{-- User Section --}}
    <div class="p-4 border-t border-gray-100 dark:border-white/5 space-y-2">
        <button wire:click="toggleTheme" class="w-full flex items-center justify-between px-3 py-2 min-h-[44px] text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl">
            Theme
            <span class="capitalize text-xs px-2 py-0.5 bg-gray-100 dark:bg-white/10 rounded-full">{{ $theme }}</span>
        </button>
        <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-3 py-2 min-h-[44px] rounded-xl text-sm font-medium text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5">
            <div class="size-8 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-300">
                {{ $user?->initials() ?? '?' }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="truncate">{{ $user?->name ?? 'Guest' }}</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 min-h-[44px] text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl">
                Logout
            </button>
        </form>
    </div>
    </div>
</div>
