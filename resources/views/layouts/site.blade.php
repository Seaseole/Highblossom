<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google-site-verification" content="wp55DtE3P11yp5FAs8JlenWM70Bl8bVAyLNHt5MIDuk" />
    <x-seo.meta />
    @include('partials.favicons')
    @include('partials.theme-script')
    {{-- Announcement dismissal, decided before first paint. One attribute on <html> both
         hides the bar and releases the space it took, so a returning visitor never sees the
         bar or a gap where it was. The value is a fingerprint of the live messages, so an
         edited announcement shows again while the same one stays hidden. --}}
    <script>
        (function () {
            var STORAGE_KEY = 'hb-announcement';
            var root = document.documentElement;
            var current = @js($announcementKey ?? '');

            function apply() {
                var dismissed = null;

                try {
                    dismissed = localStorage.getItem(STORAGE_KEY);
                } catch (e) {
                    /* private mode: the bar simply stays visible for this visit */
                }

                root.dataset.announcement = dismissed !== null && dismissed !== '' && dismissed === current ? 'dismissed' : 'visible';
            }

            // Read by the dismiss handler in partials/site-nav so the two can never
            // disagree about what the current announcement is.
            root.dataset.announcementKey = current;

            apply();

            // <html> survives a wire:navigate body swap, so this only has to catch the
            // announcement changing mid-session without a full load.
            document.addEventListener('livewire:navigated', apply);
        })();
    </script>
    <script src="/vendor/ckeditor/ckeditor.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/site-booking.js', 'resources/js/site-alpine.js'])
    @include('partials.cloak')

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .glass-card {
            background: var(--color-site-raised, #16161d);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
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
        /* Base background colors to prevent flash (theme script sets .dark before paint) */
        html {
            background-color: #f7f5f3;
        }
        html.dark {
            background-color: #0a0a0f;
        }
    </style>

    @stack('styles')
</head>
<body class="font-body bg-site text-site-text antialiased selection:bg-[#DC2626] selection:text-white">
    @include('partials.site-nav')

    <main>{{ $slot }}</main>

    @include('partials.site-footer')
    @include('partials.whatsapp-fab')
    @include('partials.cookie-consent')

    {{-- Structured data lives in the body so each navigation carries only its own page's schema. --}}
    <x-seo.schema />

    <!-- Custom Toast Notification -->
    <x-ui.toaster />

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- wire:navigate is a Livewire directive, and Livewire only auto-injects its bundle on
         pages that render a component. Most public pages render none, so load it explicitly. --}}
    @livewireScripts

    @stack('scripts')
</body>
</html>
