@php
    /* Decided on the server and published below as a class on <html> before the first byte
       paints, which is the only way a chosen layout is never visible as the other one. The two
       shells are not two templates: the rail and the bar both print, and this class decides
       which of the two is standing — so nothing about a mode switch asks the server for a new
       page, and a `wire:navigate` cannot leave the shell disagreeing with the content. */
    $navLayout = \App\Support\AdminNav::layout();
    $navTop = $navLayout === \App\Enums\AdminNavLayout::Top;

    // What the shell can reach, what each row answers to, the counts beside them and which row
    // owns this page — published once here, in the layout, rather than by the rail. See the
    // script below for why that distinction turned out to matter.
    $client = \App\Support\AdminSidebar::clientData(request());
@endphp
<!DOCTYPE html>
{{-- The class is written here as well as seeded by the script below, so the served document is
     already in the layout the server chose: the script exists to put the *browser's* facts
     (theme, rail width, which keyboard glyph) on the same element before paint, and the head is
     not the place to decide a preference that came back from the database with the page. --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['admin-nav-top' => $navTop])>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>
        {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
    </title>
    @include('partials.favicons')

    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet"
    />

    {{-- Safe area insets for notched mobile devices --}}
    <style>
        .safe-area-top {
            padding-top: env(safe-area-inset-top, 0px);
        }
        .safe-area-bottom {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        .safe-area-left {
            padding-left: env(safe-area-inset-left, 0px);
        }
        .safe-area-right {
            padding-right: env(safe-area-inset-right, 0px);
        }
    </style>

    {{-- Seed localStorage BEFORE @fluxAppearance reads it, so Flux honours the backend preference --}}
    <script>
        (function () {
            /* Every class the shell puts on <html> before paint, in one function that both ends of
               the document can call — because a `wire:navigate` takes those classes away with the
               body it replaces, and only <head> survives to be re-run, which it never is. So after
               the first navigation in a session the panel came back light under a dark theme and
               the rail came back wide. The body twin below calls this again on every navigation:
               the same arithmetic, not a second rule to keep in step.

               `theme` and the layout are passed in rather than captured, because the body script
               re-runs with the values of the page that has just arrived. */
            window.adminShellSeed = function (theme, navTop) {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                // Apply class immediately to prevent FOUT
                document.documentElement.classList.toggle(
                    'dark',
                    theme === 'dark' || (theme === 'auto' && prefersDark)
                );

                // Restore the desktop sidebar rail width before paint, so the rail
                // does not re-widen on every full-page admin navigation.
                document.documentElement.classList.toggle(
                    'sidebar-collapsed',
                    localStorage.getItem('sidebarCollapsed') === 'true'
                );

                // Same reason, different key: the keyboard badge has to know whether it is
                // writing ⌘ or Ctrl before the first frame, or every Mac user watches the
                // wrong glyph swap itself out a beat after the bar appears.
                document.documentElement.classList.toggle(
                    'os-mac',
                    /Mac|iPhone|iPad|iPod/.test(navigator.userAgentData?.platform || navigator.platform || '')
                );

                // Which navigation the wide screen wears. Absent means the sidebar, so the default
                // layout needs no class of its own and nothing here has to know how many layouts
                // exist.
                document.documentElement.classList.toggle('admin-nav-top', navTop === true);
            };

            const theme = @json($theme);

            window.adminShellSeed(theme, @json($navTop));

            // Sync both our key and Flux's key so they stay in agreement
            localStorage.setItem('theme', theme);
            localStorage.setItem('flux.appearance', theme === 'auto' ? 'system' : theme);
        })();
    </script>

    @include('partials.cloak')
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/admin.js'])

    <noscript>
        {{-- Without script a top-mode dropdown cannot open, so the navigation a person can
             actually use wins over the layout they asked for: the rail comes back, the bar's menu
             row goes away, and the toggle keeps its two jobs. These rules mirror the mode block in
             resources/css/admin.css in the same order it is written there, which makes the plain
             override the whole of the difference rather than a second set of selectors to keep up. --}}
        <style>
            html.admin-nav-top #desktop-sidebar {
                display: flex;
            }

            html.admin-nav-top .admin-bar-menu {
                display: none;
            }

            @media (min-width: 1024px) {
                html.admin-nav-top #mobile-menu-btn {
                    display: inline-flex;
                }
            }
        </style>
    </noscript>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    <style>
        /* Base background colors to prevent flash */
        html {
            background-color: #ffffff; /* White */
        }
        html.dark {
            background-color: #0a0a0f; /* Dark Background */
        }
    </style>
</head>
<body
    class="admin-panel h-dvh overflow-hidden bg-white font-sans text-gray-900 antialiased dark:bg-[#0A0A0F] dark:text-gray-100"
    x-data
    @keydown.escape.window="$store.mobileMenu.close()"
>
    {{-- The bar is a sibling of the scroller rather than an overlay above it. Nothing in this
         shell is position:fixed on desktop — the sidebar is a flex child too — so a bar in the
         flow needs no width maths to clear the rail, follows its collapse transition for free,
         and cannot be scrolled over by an anchor. A fixed bar would need all three handled by
         hand, and would be the reason page anchors started landing behind it. --}}
    {{-- The row is viewport-tall rather than content-tall: with a min-height here the flex
         line grows with the page, the sidebar's lg:h-full resolves against the whole
         document instead of the screen, and the window becomes the scroll area. --}}
    <div class="flex h-dvh">
        {{-- Persisted so a wire:navigate between admin pages swaps the content and leaves the
             rail standing. It is plain Blade rather than a Livewire component for the same
             reason: a persisted component keeps answering to the snapshot of the page that
             created it, and every later page has since replaced those bindings. The rail does
             not need to talk to the server — it needs to stop talking to it. --}}
        @persist('admin-sidebar')
            <x-admin.sidebar />
        @endpersist

        {{-- The wrapper is deliberately outside the persist: it is the flex column that holds
             the bar above the scroller, and it is re-rendered with every page. Only the bar
             inside it is kept, and only because everything it prints is either a browser
             preference or a snapshot the labels map can recompute. --}}
        <div class="flex min-w-0 flex-1 flex-col">
            @persist('admin-topbar')
                <x-admin.topbar />
            @endpersist

            <!-- Main Content -->
            <main class="admin-main min-h-0 flex-1 overflow-y-auto bg-white dark:bg-[#0A0A0F]">
                <div class="p-4 sm:p-8">
                    @if ($errors->any())
                        <div
                            class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300"
                            role="alert"
                        >
                            <p class="font-medium">
                                The form could not be saved — {{ $errors->count() }}
                                {{ Str::plural('issue', $errors->count()) }}
                                need{{ $errors->count() === 1 ? 's' : '' }} attention:
                            </p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach (array_slice($errors->all(), 0, 10) as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                            @if ($errors->count() > 10)
                                <p class="mt-2">{{ $errors->count() - 10 }} more not shown.</p>
                            @endif
                        </div>
                    @endif

                    @livewire('passkey-recovery-banner')

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    {{-- Global Toaster --}}
    <x-ui.toaster />

    {{-- The shell publishes what it can reach, in one blob: the destinations, the words each row
         answers to, the counts beside them, the row that owns this page, and the three URLs the
         bar's controls POST to. It lives here rather than inside the rail because a classic inline
         script is parsed before the deferred `admin.js` module has defined anything — a call into
         that module from the island was silently taking the whole blob with it, which left the
         palette without a page index and the theme control posting to the current URL. Publishing
         data and letting the module pull it works in either order, and this script is deliberately
         not `data-navigate-once`: a `wire:navigate` re-runs it with the page that just arrived. --}}
    <script>
        window.__ADMIN_NAV_BOOT__ = {
            path: @json(request()->getPathInfo()),
            active: @json($client['active']),
            theme: @json($client['theme']),
            themeUrl: @json(route('admin.theme.store')),
            navUrl: @json(route('admin.nav-layout.store')),
            searchUrl: @json(route('admin.search')),
            text: @json($client['text'], JSON_HEX_TAG | JSON_HEX_AMP),
            labels: @json($client['labels'], JSON_HEX_TAG | JSON_HEX_AMP),
            badges: @json($client['badges'], JSON_HEX_TAG | JSON_HEX_AMP),
        };

        window.initAdminNav?.(window.__ADMIN_NAV_BOOT__);
    </script>

    {{-- The seed in <head> above never runs again — Livewire keeps head scripts as permanent
         assets, and the navigation replaces the body that carried the classes off. This body-level
         twin re-asserts them with the values of the page that just arrived, which does two jobs: a
         layout choice made on another device lands without waiting for a reload, and the theme and
         the rail width stop un-setting themselves on the first SPA navigation. --}}
    <script>
        (function () {
            window.adminShellSeed?.(@json($theme), @json($navTop));

            // On a first load the store has not been registered yet, and it seeds itself from the
            // class above. On a navigation it is already standing, so it is told to re-read it.
            if (window.Alpine?.store('adminNav')?.syncLayout) {
                window.Alpine.store('adminNav').syncLayout();
            }
        })();
    </script>

    {{-- Alpine stores — must be registered before Alpine initialises --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('mobileMenu', {
                open: false,

                toggle() {
                    this.open ? this.close() : this.show();
                },

                show() {
                    this.open = true;
                    this.lock();
                    document.getElementById('sidebar-close-btn')?.focus();
                },

                close() {
                    if (! this.open) {
                        return;
                    }

                    this.open = false;
                    this.lock(false);

                    // Hand focus back to the control that opened the drawer, so Tab does
                    // not restart at the top of the document.
                    document.getElementById('mobile-menu-btn')?.focus();
                },

                // Content scrolls inside .admin-main, not the window, so the backdrop has
                // to suspend that scroller — locking <body> would do nothing at all.
                lock(locked = true) {
                    document.querySelector('.admin-main')?.classList.toggle('is-scroll-locked', locked);
                },
            });

            Alpine.store('sidebar', {
                // Seeded from the <html> class applied before paint, so the two
                // can never disagree on first render.
                collapsed: document.documentElement.classList.contains('sidebar-collapsed'),
                toggle() {
                    this.collapsed = !this.collapsed;
                    document.documentElement.classList.toggle('sidebar-collapsed', this.collapsed);
                    localStorage.setItem('sidebarCollapsed', String(this.collapsed));
                },
            });
        });
    </script>

    <x-media-picker />

    @livewireScripts
    @fluxScripts
    @stack('scripts')
</body>
</html>
