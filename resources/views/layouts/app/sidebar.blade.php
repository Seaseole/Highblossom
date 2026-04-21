<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100" x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', mobileMenuOpen: false }" @keydown.escape.window="mobileMenuOpen = false">
    <div class="flex min-h-screen">
        <!-- Mobile Menu Button -->
        <button @click="mobileMenuOpen = true" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded shadow hover:bg-gray-50 transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Mobile Overlay -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false" class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" class="hidden lg:flex bg-white shadow-lg flex-shrink-0 flex-col transition-all duration-300 ease-in-out" x-cloak>
            <!-- Logo -->
            <div class="p-4 border-b flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-800 whitespace-nowrap" x-show="!sidebarCollapsed">Highblossom Admin</h1>
                <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" class="p-1 rounded hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="w-5 h-5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="p-4 flex-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-200' : '' }}" :title="sidebarCollapsed ? 'Dashboard' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-200' : '' }}" :title="sidebarCollapsed ? 'Users' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Users</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-gray-200' : '' }}" :title="sidebarCollapsed ? 'Roles' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Roles</span>
                </a>
                <a href="{{ route('admin.seo.static-routes') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.seo.*') ? 'bg-gray-200' : '' }}" :title="sidebarCollapsed ? 'SEO' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="whitespace-nowrap" x-show="!sidebarCollapsed">SEO</span>
                </a>
                <a href="{{ route('admin.media-library.index') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.media-library.*') ? 'bg-gray-200' : '' }}" :title="sidebarCollapsed ? 'Media Library' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Media Library</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700 transition-colors group" :title="sidebarCollapsed ? 'Logout' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <aside x-show="mobileMenuOpen" x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="lg:hidden fixed inset-y-0 left-0 w-64 bg-white shadow-lg flex flex-col z-50" style="display: none;">
            <!-- Logo -->
            <div class="p-4 border-b flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-800">Highblossom Admin</h1>
                <button @click="mobileMenuOpen = false" class="p-1 rounded hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="p-4 flex-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-200' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" @click="mobileMenuOpen = false" class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-200' : '' }}">
                    Users
                </a>
                <a href="{{ route('admin.roles.index') }}" @click="mobileMenuOpen = false" class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-gray-200' : '' }}">
                    Roles
                </a>
                <a href="{{ route('admin.seo.static-routes') }}" @click="mobileMenuOpen = false" class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.seo.*') ? 'bg-gray-200' : '' }}">
                    SEO
                </a>
                <a href="{{ route('admin.media-library.index') }}" @click="mobileMenuOpen = false" class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.media-library.*') ? 'bg-gray-200' : '' }}">
                    Media Library
                </a>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
