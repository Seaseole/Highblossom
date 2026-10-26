@props([
    'label' => '',
    'url' => '#',
    'icon' => null,
    'iconSize' => 'sm',
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

     The store is the only source once JavaScript is running, and the static pair below is the
     only source before it: they carry the same fact, the server's, for the page being rendered.
     An earlier draft let the static seed keep answering after boot as well, which made it
     permanent — the island is persisted, so the row lit on the first load stayed lit beside the
     row the next navigation lit, and the navigation grew a second current page. --}}
{{-- The bag is echoed because the top bar decorates its own rows out of it: `role="menuitem"` is
     what the arrow-key walker collects, and `data-menu-trigger` is what Left and Right stop at.
     Written here rather than at the call sites, because a row that is not told about its caller's
     needs silently loses them — which is what happened until this line existed. The rail passes
     nothing, so its markup is byte for byte what it was. --}}
<a
    href="{{ e($url, false) }}"
    wire:navigate.hover
    data-nav-href="{{ $url }}"
    data-nav-label="{{ $label }}"
    {{-- The static pair below mirrors what Alpine will compute, so the correct row is already
         lit in the first paint and to a browser running without JavaScript. --}}
    @if ($serverActive)
        data-admin-nav-active="true"
        aria-current="page"
    @endif
    x-data="{ get lit() { return $store.adminNav.lit === $el.dataset.navHref } }"
    :data-admin-nav-active="lit"
    :aria-current="lit ? 'page' : null"
    @click="$store.mobileMenu.close()"
    class="admin-nav-item {{ $child ? 'admin-nav-child' : '' }} {{ $serverActive ? 'admin-nav-active' : '' }}"
    {{ $attributes }}
>
    @if ($icon)
        {{-- Both drawings are emitted and one is switched off, because the swap is a navigation
           outcome rather than a render one: the rail is persisted and cannot ask the server for
           the other variant when the page changes underneath it. --}}
        <x-ui.icon :name="$icon" :size="$iconSize" class="shrink-0" variant="solid" x-show="lit" />
        <x-ui.icon :name="$icon" :size="$iconSize" class="shrink-0" variant="outline" x-show="! lit" />
    @endif

    <span data-nav-text class="flex-1 truncate">{{ $label }}</span>

    @if ($count !== null)
        <span class="admin-nav-badge" aria-hidden="true">{{ $count }}</span>
        <span class="sr-only">({{ $count }} waiting)</span>
    @endif
</a>
