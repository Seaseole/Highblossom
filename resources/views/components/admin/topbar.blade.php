@php
    $user = auth()->user();
    $companyName = \App\Support\AdminSidebar::companyName();
    $context = \App\Support\AdminTopbar::context(request());
    $quickActions = \App\Support\AdminTopbar::permittedQuickActions();
    $notifications = \App\Support\AdminTopbar::notifications();
    $theme = \App\Support\AdminSidebar::theme(request());
@endphp

<header
    class="admin-bar safe-area-top relative z-30 flex h-14 shrink-0 items-center gap-1 border-b border-gray-200 bg-white px-2 sm:gap-2 sm:px-4 lg:h-16 dark:border-white/10 dark:bg-[#0A0A0F]"
>
    <x-admin.topbar.toggle />
    <x-admin.topbar.context
        :company-name="$companyName"
        :group="$context['group']"
        :label="$context['label']"
    />

    {{-- The navigation joins the bar in top mode and is display:none in the sidebar mode, so it
         costs the original layout nothing but a class it never reads. It is printed here rather
         than swapped in, because this island is persisted: markup that only exists after a server
         round trip is markup that a `wire:navigate` would have to re-create, and the whole point
         of the island is that it is not re-created. --}}
    <x-admin.topbar.menu />

    {{-- ml-auto rather than justify-between on the header: the context area truncates, and a
         flex child that is allowed to shrink has to be separated from the fixed cluster it is
         pushing against. --}}
    <div class="ml-auto flex shrink-0 items-center gap-1">
        <x-admin.topbar.search />

        @if ($quickActions !== [])
            <x-admin.topbar.quick-actions :actions="$quickActions" />
        @endif

        @if ($notifications !== [])
            <x-admin.topbar.notifications :rows="$notifications" />
        @endif

        <x-admin.topbar.fullscreen />
        <x-admin.topbar.theme-toggle :theme="$theme" />
        <x-admin.topbar.view-site />
        <x-admin.topbar.user-menu :user="$user" :quick-actions="$quickActions" />
    </div>
</header>

<x-admin.command-palette />

@if ($quickActions !== [])
    <x-admin.create-dialog :actions="$quickActions" />
@endif
