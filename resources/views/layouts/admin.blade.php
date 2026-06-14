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

    {{-- Seed localStorage BEFORE @fluxAppearance reads it, so Flux honours the backend preference --}}
    <script>
        (function() {
            const theme = @json($theme);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            // Apply class immediately to prevent FOUT
            if (theme === 'dark' || (theme === 'auto' && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            // Sync both our key and Flux's key so they stay in agreement
            localStorage.setItem('theme', theme);
            localStorage.setItem('flux.appearance', theme === 'auto' ? 'system' : theme);
        })();
    </script>

    @fluxAppearance
    @livewireStyles
    @include('partials.cloak')
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/admin.js'])
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    <style>
        /* Base background colors to prevent flash */
        html {
            background-color: #FFFFFF; /* White */
        }
        html.dark {
            background-color: #0A0A0F; /* Dark Background */
        }
    </style>
</head>
<body class="min-h-[100dvh] bg-white dark:bg-[#0A0A0F] text-gray-900 dark:text-gray-100 font-sans antialiased">
    <div class="flex min-h-[100dvh]">
        <!-- Livewire Admin Sidebar -->
        <livewire:admin-sidebar />

        <!-- Main Content -->
        <main class="flex-1 overflow-auto admin-main bg-white dark:bg-[#0A0A0F]">
            <div class="lg:hidden p-4">
                <button @click="Alpine.store('mobileMenu').toggle()" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Environment Badge --}}
    @php
        $environment = config('app.env');
        $envColors = [
            'production' => 'bg-red-500/20 border-red-500/30 text-red-600',
            'staging' => 'bg-yellow-500/20 border-yellow-500/30 text-yellow-600',
            'development' => 'bg-blue-500/20 border-blue-500/30 text-blue-600',
            'local' => 'bg-emerald-500/20 border-emerald-500/30 text-emerald-600',
        ];
        $envColor = $envColors[$environment] ?? $envColors['development'];
        $envDotColors = [
            'production' => 'bg-red-500',
            'staging' => 'bg-yellow-500',
            'development' => 'bg-blue-500',
            'local' => 'bg-emerald-500',
        ];
        $envDotColor = $envDotColors[$environment] ?? $envDotColors['development'];
    @endphp

    <div class="fixed top-6 right-6 z-[8000]">
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border backdrop-blur-sm {{ $envColor }}">
            <span class="relative flex items-center justify-center">
                <span class="absolute inline-flex h-2 w-2 rounded-full {{ $envDotColor }} animate-pulse-dot"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full {{ $envDotColor }} opacity-75"></span>
            </span>
            <span class="text-xs font-semibold uppercase tracking-wide">{{ $environment }}</span>
        </div>
    </div>

    <style>
        @keyframes pulse-dot {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.25);
            }
        }

        .animate-pulse-dot {
            animation: pulse-dot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    {{-- Global Toaster --}}
    <x-ui.toaster />

    @livewireScripts
    @fluxScripts
    @stack('scripts')
</body>
</html>
