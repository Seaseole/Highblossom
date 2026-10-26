<div class="flex shrink-0 items-center">
    {{-- A button shaped like a field, not a field: the typing happens in the palette's own input,
         and a real input here would either steal keystrokes from the dialog or need to forward
         them, which is two sources of truth for one string. --}}
    <button
        type="button"
        @click="$store.adminPalette.show()"
        class="admin-bar-search hidden lg:flex"
        aria-keyshortcuts="Meta+K Control+K /"
        title="Search the admin panel"
    >
        <x-ui.icon name="magnifying-glass" size="sm" class="shrink-0 text-gray-400" aria-hidden="true" />
        <span class="flex-1 text-left">Search</span>
        <x-admin.topbar.kbd />
    </button>

    <button
        type="button"
        @click="$store.adminPalette.show()"
        class="admin-bar-btn lg:hidden"
        aria-label="Search the admin panel"
        aria-keyshortcuts="Meta+K Control+K /"
    >
        <x-ui.icon name="magnifying-glass" size="sm" />
    </button>
</div>
