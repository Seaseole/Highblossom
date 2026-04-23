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
    @fluxAppearance
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
</head>
<body class="min-h-[100dvh] bg-admin-bg text-admin-text font-body antialiased" x-data="{ darkMode: localStorage.getItem('theme') || 'auto' }" x-init="
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        const updateDark = () => {
            if (darkMode === 'dark' || (darkMode === 'auto' && prefersDark.matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        };
        updateDark();
        prefersDark.addEventListener('change', () => { if (darkMode === 'auto') updateDark(); });
    ">
    <div class="flex min-h-[100dvh]">
        <!-- Livewire Admin Sidebar -->
        <livewire:admin-sidebar />

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
                    <button @click="dismissToast(toast.id)" class="flex-shrink-0 text-admin-text-muted hover:text-admin-text transition-colors">
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
    @fluxScripts
    @stack('scripts')
</body>
</html>
