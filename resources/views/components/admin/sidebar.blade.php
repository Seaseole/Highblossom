@php
    $request = request();
    $query = \App\Support\AdminSidebar::query($request);
    $logoUrl = \App\Support\AdminSidebar::logoUrl();
    $companyName = \App\Support\AdminSidebar::companyName();
    $theme = \App\Support\AdminSidebar::theme($request);
    $role = \App\Support\AdminSidebar::roleLabel();
    $client = \App\Support\AdminSidebar::clientData($request);
@endphp

{{-- @persist emits its own <div x-persist> wrapper, which becomes the flex item of the
     layout row and is what gets stretched to viewport height. This div is a plain block child
     of it, so without h-full here, #desktop-sidebar's lg:h-full resolves against an
     auto-height ancestor — which CSS treats as auto, leaving the nav column exactly as tall as
     its content and nothing to scroll. The drawer is unaffected because it is fixed h-dvh. --}}
<div class="lg:h-full lg:shrink-0">
    {{-- Everything below lives inside one persisted island, so exactly one of these two columns
         is ever in the document: each mints its own ids and Alpine roots, and a second copy
         would give the drawer a duplicate "sidebar-close-btn" to focus. --}}
    <div
        id="sidebar-panel"
        x-data="adminDrawer"
        x-show="$store.mobileMenu.open"
        x-transition:enter="transition-transform ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 h-dvh w-64 lg:hidden"
        role="dialog"
        aria-modal="true"
        aria-label="Site menu"
        @click.outside="$store.mobileMenu.close()"
        @keydown.tab="trap($event)"
    >
        <div class="safe-area-top safe-area-bottom flex h-full flex-col border-r border-gray-200 bg-white shadow-2xl dark:border-white/5 dark:bg-[#0A0A0F]">
            <x-admin.sidebar.brand :logo-url="$logoUrl" :company-name="$companyName" mobile />
            <x-admin.sidebar.search :query="$query" mobile />
            <x-admin.sidebar.nav mobile />
            <x-admin.sidebar.footer :theme="$theme" :role="$role" mobile />
        </div>
    </div>

    <div id="desktop-sidebar" class="relative hidden border-r border-gray-200 bg-white transition-[width] duration-200 ease-out lg:flex lg:h-full lg:w-64 lg:shrink-0 lg:flex-col sidebar-collapsed:lg:w-20 dark:border-white/5 dark:bg-[#0A0A0F]">
        {{-- Straddles the border so it stays put at both widths; the store writes the rail's
             state to <html> and localStorage, and the layout's pre-paint seed reads it back
             so a full reload never shows the wide rail first and narrow second. --}}
        <button
            type="button"
            @click="$store.sidebar.toggle()"
            class="absolute -right-3 top-20 z-10 hidden size-6 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-400 shadow-sm transition-colors hover:text-gray-900 lg:flex dark:border-white/10 dark:bg-[#0A0A0F] dark:text-gray-500 dark:hover:text-white"
            aria-controls="desktop-sidebar"
            :aria-expanded="(! $store.sidebar.collapsed).toString()"
            :aria-label="$store.sidebar.collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
        >
            <span class="inline-flex transition-transform duration-200" :class="$store.sidebar.collapsed ? 'rotate-180' : ''">
                <x-ui.icon name="chevron-double-left" size="xs" />
            </span>
        </button>

        <x-admin.sidebar.brand :logo-url="$logoUrl" :company-name="$companyName" />
        <x-admin.sidebar.search :query="$query" />
        <x-admin.sidebar.nav />

        <x-admin.sidebar.footer :theme="$theme" :role="$role" />
    </div>

    {{-- The rail publishes what it can reach and what each row answers to, in one go. This is
         deliberately not data-navigate-once: a full page reload re-renders it with that page's
         permissions and counts, and it must be current before the store initialises underneath.
         Inside a persisted island it never re-runs on SPA navigation, which is correct — the
         whole point is that nothing about the rail changes except the location. --}}
    <script data-navigate-once>
        window.initAdminNav({
            path: @json($request->getPathInfo()),
            active: @json($client['active']),
            theme: @json($theme),
            themeUrl: @json(route('admin.theme.store')),
            text: @json($client['text'], JSON_HEX_TAG | JSON_HEX_AMP),
            badges: @json($client['badges'], JSON_HEX_TAG | JSON_HEX_AMP),
        });
    </script>
</div>
