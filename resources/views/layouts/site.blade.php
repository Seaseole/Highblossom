<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-seo.meta />
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <script src="/vendor/ckeditor/ckeditor.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .glass-card {
            background: #16161d;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .primary-gradient {
            background: linear-gradient(135deg, #73081d 0%, #a93440 100%);
        }
        .swiper {
            width: 100%;
            padding-bottom: 40px;
        }
        .swiper-pagination-bullet-active {
            background-color: #73081d;
        }
        .text-wrap-balance {
            text-wrap: balance;
        }
        .text-wrap-pretty {
            text-wrap: pretty;
        }
    </style>
</head>
<body class="bg-[#0A0A0F] text-[#FAFAFA] font-body selection:bg-[#DC2626] selection:text-white antialiased">
    @include('partials.site-nav')

    <main>
        {{ $slot }}
    </main>

    @include('partials.site-footer')
    @include('partials.whatsapp-fab')

    <!-- Custom Toast Notification -->
    <div
        x-data="{
            show: false,
            message: '',
            type: 'success',
            initToast() {
                @if(session('success'))
                    this.message = '{{ session('success') }}';
                    this.type = 'success';
                    this.show = true;
                    setTimeout(() => this.dismissToast(), 3000);
                @endif
                @if(session('error'))
                    this.message = '{{ session('error') }}';
                    this.type = 'error';
                    this.show = true;
                    setTimeout(() => this.dismissToast(), 3000);
                @endif
            },
            dismissToast() {
                this.show = false;
            }
        }"
        x-init="initToast()"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-4 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-4 opacity-0"
        class="fixed bottom-4 right-4 z-50 max-w-sm w-full"
        style="display: none;"
    >
        <div class="bg-[#16161D] backdrop-blur-xl border border-white/10 border-l-4 rounded-xl shadow-2xl shadow-[#0A0A0F]/50 p-4 flex items-start gap-3"
             :class="type === 'success' ? 'border-l-[#DC2626]' : 'border-l-[#EF4444]'">
            <!-- Success Icon -->
            <svg x-show="type === 'success'" class="w-5 h-5 text-[#DC2626] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <!-- Error Icon -->
            <svg x-show="type === 'error'" class="w-5 h-5 text-[#EF4444] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1 min-w-0">
                <p class="text-[#FAFAFA] font-medium text-sm" x-text="message"></p>
            </div>
            <button @click="dismissToast()" class="flex-shrink-0 text-[#71717A] hover:text-[#FAFAFA] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>
