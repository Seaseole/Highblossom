<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
</head>
<body class="min-h-[100dvh] bg-[#0A0A0F] text-[#FAFAFA] font-body antialiased" x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', mobileMenuOpen: false }" @keydown.escape.window="mobileMenuOpen = false">
    <div class="flex min-h-[100dvh]">
        <!-- Mobile Menu Button -->
        <button @click="mobileMenuOpen = true" class="lg:hidden fixed top-4 right-4 z-50 p-2 bg-[#16161d] rounded-xl border border-white/10 shadow-lg hover:bg-[#DC2626] transition-all duration-300 active:scale-[0.98]">
            <svg class="w-6 h-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Mobile Overlay -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false" class="lg:hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-40" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarCollapsed ? 'w-20' : 'w-72'" class="hidden lg:flex admin-sidebar flex-shrink-0 flex-col transition-all duration-300 ease-in-out" x-cloak>
            <!-- Logo -->
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#DC2626] flex items-center justify-center flex-shrink-0 shadow-lg shadow-[#DC2626]/20">
                        <span class="text-white font-bold text-lg">H</span>
                    </div>
                    <span class="text-xl font-bold text-[#FAFAFA] font-headline whitespace-nowrap" x-show="!sidebarCollapsed">{{ config('app.name') }}</span>
                </a>
                <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" class="p-2 rounded-lg hover:bg-white/5 transition-colors flex-shrink-0">
                    <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="w-5 h-5 text-[#A1A1AA] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('dashboard') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Dashboard' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Dashboard</span>
                </a>

                <!-- Bookings Section -->
                <div class="pt-4 pb-2" x-show="!sidebarCollapsed">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Bookings</span>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.bookings.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Bookings' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Bookings</span>
                </a>
                <a href="{{ route('admin.inspections.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.inspections.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Inspections' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Inspections</span>
                </a>
                <a href="{{ route('admin.quotes.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.quotes.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Quotes' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Quotes</span>
                </a>

                <!-- Content Section -->
                <div class="pt-4 pb-2" x-show="!sidebarCollapsed">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Content</span>
                </div>
                <a href="{{ route('admin.about-us.edit') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.about-us.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'About Us' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">About Us</span>
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.testimonials.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Testimonials' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Testimonials</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.services.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Services' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Services</span>
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.gallery.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Gallery' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Gallery</span>
                </a>
                <a href="{{ route('admin.gallery-categories.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.gallery-categories.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Gallery Categories' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Gallery Categories</span>
                </a>
                <a href="{{ route('admin.glass-types.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.glass-types.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Glass Types' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Glass Types</span>
                </a>
                <a href="{{ route('admin.service-types.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.service-types.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Service Types' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Service Types</span>
                </a>


                <!-- Access Section -->
                <div class="pt-4 pb-2" x-show="!sidebarCollapsed">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Access</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.users.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Users' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Users</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.roles.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Roles' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Roles</span>
                </a>

                <!-- Media Section -->
                <div class="pt-4 pb-2" x-show="!sidebarCollapsed">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Media</span>
                </div>
                <a href="{{ route('admin.media-library.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.media-library.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Media Library' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Media Library</span>
                </a>

                <!-- System Section -->
                <div class="pt-4 pb-2" x-show="!sidebarCollapsed">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">System</span>
                </div>
                <a href="{{ route('admin.settings.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.settings.index') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'Settings' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Settings</span>
                </a>
                <a href="{{ route('admin.smtp.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl group {{ request()->routeIs('admin.smtp.*') ? 'active' : 'text-[#A1A1AA]' }}" :title="sidebarCollapsed ? 'SMTP' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">SMTP</span>
                </a>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-white/5 space-y-1">
                <a href="{{ route('admin.profile.index') }}" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-[#A1A1AA] group {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" :title="sidebarCollapsed ? 'Profile' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Profile</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-[#A1A1AA] w-full group" :title="sidebarCollapsed ? 'Logout' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium whitespace-nowrap" x-show="!sidebarCollapsed">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <aside x-show="mobileMenuOpen" x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="lg:hidden fixed inset-y-0 right-0 w-72 admin-sidebar flex flex-col z-50" style="display: none;">
            <!-- Logo -->
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#DC2626] flex items-center justify-center shadow-lg shadow-[#DC2626]/20">
                        <span class="text-white font-bold text-lg">H</span>
                    </div>
                    <span class="text-xl font-bold text-[#FAFAFA] font-headline">{{ config('app.name') }}</span>
                </a>
                <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-white/5 transition-colors">
                    <svg class="w-5 h-5 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="pt-4 pb-2">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Bookings</span>
                </div>
                <a href="{{ route('admin.bookings.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.bookings.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">Bookings</span>
                </a>
                <a href="{{ route('admin.inspections.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.inspections.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span class="font-medium">Inspections</span>
                </a>
                <a href="{{ route('admin.quotes.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.quotes.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Quotes</span>
                </a>

                <div class="pt-4 pb-2">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Content</span>
                </div>
                <a href="{{ route('admin.about-us.edit') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.about-us.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">About Us</span>
                </a>
                <a href="{{ route('admin.testimonials.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.testimonials.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="font-medium">Testimonials</span>
                </a>
                <a href="{{ route('admin.services.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.services.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium">Services</span>
                </a>
                <a href="{{ route('admin.gallery.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.gallery.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">Gallery</span>
                </a>
                <a href="{{ route('admin.gallery-categories.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.gallery-categories.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium">Gallery Categories</span>
                </a>
                <a href="{{ route('admin.glass-types.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.glass-types.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium">Glass Types</span>
                </a>
                <a href="{{ route('admin.service-types.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.service-types.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium">Service Types</span>
                </a>


                <div class="pt-4 pb-2">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Access</span>
                </div>
                <a href="{{ route('admin.users.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium">Users</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="font-medium">Roles</span>
                </a>

                <div class="pt-4 pb-2">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Media</span>
                </div>
                <a href="{{ route('admin.media-library.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.media-library.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="font-medium">Media Library</span>
                </a>

                <div class="pt-4 pb-2">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">System</span>
                </div>
                <a href="{{ route('admin.settings.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.index') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium">Settings</span>
                </a>
                <a href="{{ route('admin.smtp.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.smtp.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">SMTP</span>
                </a>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-white/5 space-y-1">
                <a href="{{ route('admin.profile.index') }}" @click="mobileMenuOpen = false" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.profile.*') ? 'active' : 'text-[#A1A1AA]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">Profile</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-[#A1A1AA] transition-all duration-200 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto admin-main">
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Global Toaster --}}
    <div x-data="{
        toasts: [],
        init() {
            @if(session('success'))
                this.addToast('success', {{ json_encode(session('success')) }});
            @endif
            @if(session('error'))
                this.addToast('error', {{ json_encode(session('error')) }});
            @endif
            @if(session('info'))
                this.addToast('info', {{ json_encode(session('info')) }});
            @endif
            @if(session('warning'))
                this.addToast('warning', {{ json_encode(session('warning')) }});
            @endif
        },
        addToast(type, message) {
            const id = Date.now();
            this.toasts.push({ id, type, message, progress: 100 });
            setTimeout(() => {
                this.dismissToast(id);
            }, 3000);
        },
        dismissToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts.splice(index, 1);
            }
        }
    }" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0" class="relative overflow-hidden rounded-xl backdrop-blur-xl border shadow-2xl min-w-[320px] max-w-md"
                :class="{
                    'bg-emerald-500/10 border-emerald-500/20': toast.type === 'success',
                    'bg-red-500/10 border-red-500/20': toast.type === 'error',
                    'bg-blue-500/10 border-blue-500/20': toast.type === 'info',
                    'bg-yellow-500/10 border-yellow-500/20': toast.type === 'warning'
                }"
                @mouseenter="toast.paused = true" @mouseleave="toast.paused = false">
                <div class="flex items-start gap-3 p-4">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-0.5"
                        :class="{
                            'text-emerald-400': toast.type === 'success',
                            'text-red-400': toast.type === 'error',
                            'text-blue-400': toast.type === 'info',
                            'text-yellow-400': toast.type === 'warning'
                        }">
                        <template x-if="toast.type === 'success'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </template>
                    </div>
                    {{-- Message --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium"
                            :class="{
                                'text-emerald-400': toast.type === 'success',
                                'text-red-400': toast.type === 'error',
                                'text-blue-400': toast.type === 'info',
                                'text-yellow-400': toast.type === 'warning'
                            }" x-text="toast.message"></p>
                    </div>
                    {{-- Close Button --}}
                    <button @click="dismissToast(toast.id)" class="flex-shrink-0 text-[#71717A] hover:text-[#FAFAFA] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                {{-- Progress Bar --}}
                <div class="h-1 bg-white/5">
                    <div class="h-full transition-all duration-100 ease-linear"
                        :class="{
                            'bg-emerald-400': toast.type === 'success',
                            'bg-red-400': toast.type === 'error',
                            'bg-blue-400': toast.type === 'info',
                            'bg-yellow-400': toast.type === 'warning'
                        }"
                        :style="`width: ${toast.progress}%`"
                        x-init="setInterval(() => { if (!toast.paused) toast.progress = Math.max(0, toast.progress - 0.33); }, 10)"></div>
                </div>
            </div>
        </template>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
