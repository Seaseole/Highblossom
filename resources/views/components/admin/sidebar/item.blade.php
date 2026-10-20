@props([
    'label' => '',
    'url' => '#',
    'icon' => null,
    'badgeCount' => null,
    'child' => false,
    'serverActive' => false,
])

@php
    $count = $badgeCount === null ? null : \Illuminate\Support\Number::abbreviate($badgeCount, maxPrecision: 1);
@endphp

{{-- Every Alpine expression below is written without a single $-prefixed token that Blade
     could claim: `$store` and `$el` in a static attribute are compiled as PHP variables, not
     read as Alpine, so the row's answer is parked in data attributes and pulled back through
     this component's own x-data scope. `lit` is the only name the rest of the markup needs.
     The server seeds it for the page being rendered; after wire:navigate the rail is not
     re-rendered, so the store's own value takes over — and a row that was not lit on the
     server can still become lit in the browser, which is what a navigation is for. --}}
<a
    href="{{ e($url, false) }}"
    wire:navigate.hover
    data-nav-href="{{ $url }}"
    data-nav-label="{{ $label }}"
    data-nav-server-lit="{{ $serverActive ? '1' : '0' }}"
    {{-- The static pair below mirrors what Alpine will compute, so the correct row is already
         lit in the first paint and to a browser running without JavaScript. The bindings then
         keep it true through a navigation; they never contradict the seed for this page. --}}
    @if ($serverActive)
        data-admin-nav-active="true"
        aria-current="page"
    @endif
    x-data="{ get lit() { return $el.dataset.navServerLit === '1' || $store.adminNav.lit === $el.dataset.navHref } }"
    :data-admin-nav-active="lit"
    :aria-current="lit ? 'page' : null"
    x-show="$store.adminNav.matches($el.dataset.navHref)"
    @click="$store.mobileMenu.close()"
    class="admin-nav-item {{ $child ? 'admin-nav-child' : '' }} {{ $serverActive ? 'admin-nav-active' : '' }}"
>
    @if ($icon)
        {{-- Both drawings are emitted and one is switched off, because the swap is a navigation
           outcome rather than a render one: the rail is persisted and cannot ask the server for
           the other variant when the page changes underneath it. --}}
        <x-ui.icon :name="$icon" size="sm" class="shrink-0" variant="solid" x-show="lit" />
        <x-ui.icon :name="$icon" size="sm" class="shrink-0" variant="outline" x-show="! lit" />
    @endif

    <span data-nav-text class="flex-1 truncate">{{ $label }}</span>

    @if ($count !== null)
        <span class="admin-nav-badge" aria-hidden="true">{{ $count }}</span>
        <span class="sr-only">({{ $count }} waiting)</span>
    @endif
</a>
