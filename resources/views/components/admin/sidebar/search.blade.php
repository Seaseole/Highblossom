@props([
    'query' => null,
    'mobile' => false,
])

@php
    // The rail hides its own text at 80px; the drawer is always full width and never does.
    $hide = $mobile ? '' : 'sidebar-collapsed:lg:hidden';

    // Submitting has to land on the page you are on, path only: the current URL may already
    // carry ?nav_search=, and reusing it verbatim would nest one query inside another.
    $action = \App\Support\AdminMenu::normalize(url()->current());
@endphp

<div class="px-4 pb-2 pt-4 {{ $hide }}">
    {{-- A real GET form, not a decorative one: the field filters the rail in the browser as
         you type, and submitting writes the same words into the URL so the result survives a
         reload, a back button and a pasted link. Without it, a search would live only as long
         as the page it was typed on. --}}
    <form method="GET" action="{{ e($action, false) }}" class="admin-nav-field">
        <x-ui.icon name="magnifying-glass" size="sm" class="shrink-0 text-gray-400" aria-hidden="true" />

        <input
            type="search"
            name="nav_search"
            value="{{ $query }}"
            placeholder="Search"
            aria-label="Search the admin menu"
            autocomplete="off"
            class="admin-nav-search-field"
            x-data
            x-model="$store.adminNav.query"
            @keydown.escape="$store.adminNav.query = ''"
        />

        <button
            type="button"
            x-show="$store.adminNav.searching"
            @click="$store.adminNav.query = ''"
            aria-label="Clear search"
            class="flex size-5 shrink-0 items-center justify-center rounded-full text-gray-400 hover:text-gray-900 dark:hover:text-white"
        >
            <x-ui.icon name="x-mark" size="xs" aria-hidden="true" />
        </button>

        {{-- Two spellings, and CSS picks one: macOS reports its key, Windows and Linux report
             theirs. The media query is the only honest way to tell them apart without reading
             the user agent. --}}
        <kbd class="hidden font-sans text-[0.65rem] font-semibold text-gray-400 md:inline dark:text-gray-500">
            <span class="[method:keyboard]:hidden">Ctrl </span><span class="hidden [method:keyboard]:inline">⌘ </span>K
        </kbd>
    </form>
</div>
