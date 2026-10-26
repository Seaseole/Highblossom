@php
    $logoUrl = \App\Support\AdminSidebar::logoUrl();
    $companyName = \App\Support\AdminSidebar::companyName();
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
        x-data="adminFocusTrap"
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
            <x-admin.sidebar.nav mobile />
        </div>
    </div>

    <div id="desktop-sidebar" class="relative hidden border-r border-gray-200 bg-white transition-[width] duration-200 ease-out lg:flex lg:h-full lg:w-64 lg:shrink-0 lg:flex-col sidebar-collapsed:lg:w-20 dark:border-white/5 dark:bg-[#0A0A0F]">
        {{-- The floating pill that used to straddle this border is gone: collapsing the rail is
             now the top bar's toggle, which is the control a person can find whether or not the
             rail is wide enough to show one. --}}
        <x-admin.sidebar.brand :logo-url="$logoUrl" :company-name="$companyName" />
        <x-admin.sidebar.nav />
    </div>
</div>
