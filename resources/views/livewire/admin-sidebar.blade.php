@php
    $currentRoute = request()->route()->getName();
    $user = auth()->user();
    $userCount = \App\Models\User::count();

    // Grouping configuration
    $group = [
        'dashboard' => ['label' => 'Overview', 'icon' => 'home', 'routes' => ['dashboard']],
        'bookings' => ['label' => 'Bookings', 'icon' => 'calendar', 'routes' => ['admin.bookings', 'admin.inspections', 'admin.quotes']],
        'content' => ['label' => 'Content', 'icon' => 'document', 'routes' => ['admin.about-us', 'admin.testimonials', 'admin.services', 'admin.gallery', 'admin.gallery-categories', 'admin.partners', 'admin.staff', 'admin.glass-types', 'admin.service-types', 'admin.contact-messages']],
        'blog' => ['label' => 'Blog', 'icon' => 'newspaper', 'routes' => ['admin.posts', 'admin.categories', 'admin.tags']],
        'access' => ['label' => 'Team & Access', 'icon' => 'users', 'routes' => ['admin.users', 'admin.roles']],
        'media' => ['label' => 'Media', 'icon' => 'image', 'routes' => ['admin.media-library']],
        'system' => ['label' => 'System', 'icon' => 'cog', 'routes' => ['admin.settings', 'admin.smtp', 'admin.seo']]
    ];

    // Helper functions (simplified)
    $isRouteActive = fn($route) => str_starts_with($currentRoute, $route . '.') || $currentRoute === $route || ($route === 'dashboard' && $currentRoute === 'dashboard');
    $isGroupActive = fn($groupRoutes) => collect($groupRoutes)->contains(fn($r) => $isRouteActive($r));
    $getRouteName = fn($route) => route($route === 'dashboard' ? 'dashboard' : ($route === 'admin.about-us' ? 'admin.about-us.edit' : ($route === 'admin.seo' ? 'admin.seo.static-routes' : $route . '.index')));
    $getRouteLabel = fn($route) => [
            'dashboard' => 'Dashboard', 'admin.bookings' => 'Bookings', 'admin.inspections' => 'Inspections', 'admin.quotes' => 'Quotes',
            'admin.about-us' => 'About Us', 'admin.testimonials' => 'Testimonials', 'admin.services' => 'Services', 'admin.gallery' => 'Gallery',
            'admin.gallery-categories' => 'Gallery Categories', 'admin.partners' => 'Partners', 'admin.staff' => 'Staff',
            'admin.glass-types' => 'Glass Types', 'admin.service-types' => 'Service Types', 'admin.contact-messages' => 'Messages',
            'admin.posts' => 'Posts', 'admin.categories' => 'Categories', 'admin.tags' => 'Tags', 'admin.users' => 'Users',
            'admin.roles' => 'Roles', 'admin.media-library' => 'Media', 'admin.settings' => 'Settings', 'admin.smtp' => 'SMTP', 'admin.seo' => 'SEO'
        ][$route] ?? ucfirst(str_replace(['admin.', '-'], ['', ' '], $route));
@endphp

<div class="flex h-full flex-col bg-white dark:bg-[#0A0A0F] border-r border-gray-200 dark:border-white/5">
    
    {{-- Brand --}}
    <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-white/5">
        <div class="flex items-center gap-3">
            <div class="flex aspect-square size-8 items-center justify-center rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 overflow-hidden">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" class="size-full object-cover">
                @else
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                @endif
            </div>
            <span class="font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto p-4 space-y-2" x-data="{ activeGroup: null }">
        @foreach($group as $groupKey => $groupData)
            @php $active = $isGroupActive($groupData['routes']); @endphp
            <div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-[0.7rem] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider hover:text-gray-900 dark:hover:text-white">
                    {{ $groupData['label'] }}
                    <svg class="size-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse class="space-y-1">
                    @foreach($groupData['routes'] as $route)
                        <a href="{{ $getRouteName($route) }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ $isRouteActive($route) ? 'bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                            {{ $getRouteLabel($route) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    {{-- User Section --}}
    <div class="p-4 border-t border-gray-100 dark:border-white/5 space-y-2">
        <button wire:click="toggleTheme" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl">
            Theme
            <span class="capitalize text-xs px-2 py-0.5 bg-gray-100 dark:bg-white/10 rounded-full">{{ $theme }}</span>
        </button>
        <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5">
            <div class="size-8 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-300">
                {{ $user->initials() }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="truncate">{{ $user->name }}</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl">
                Logout
            </button>
        </form>
    </div>
</div>
