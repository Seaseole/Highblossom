@props([
    'label' => '',
    'icon' => null,
    'open' => false,
    'hasActive' => false,
    'mobile' => false,
    'urls' => [],
])

@php
    // The rail collapses to an 80px column, and only the desktop half of the shell ever does:
    // the drawer is a fixed 256px. These two rules are the whole of that difference, applied
    // from the `mobile` prop rather than baked into the shared markup.
    $hideOnRail = $mobile ? '' : 'sidebar-collapsed:lg:hidden';
    $centerOnRail = $mobile ? '' : 'sidebar-collapsed:lg:mx-auto';

    /* A branch is lit when it owns the page. Spelled out per child URL rather than looked up
       through a helper, because a `$store` or `$current` token written into a static attribute
       is compiled as a PHP variable — the same trap that shapes the row component. Bare
       identifiers resolve through Alpine's scope; `this.` depends on how Alpine happened to
       bind the receiver for this particular directive. */
    $litExpression = trim(
        ($urls === [] ? '' : 'serverHasActive || ').
        implode(' || ', array_map(
            fn (string $url) => "litPaths.includes('{$url}')",
            $urls,
        )),
    ) ?: 'serverHasActive';
@endphp

{{-- The key is the group label, interpolated as a plain string rather than through a bound
   expression: an x-data attribute written on a Blade component tag is compiled as PHP, not
   read as Alpine. Nothing else in this attribute reaches into Blade. --}}
<div
    class="space-y-1"
    x-data="adminNavGroup({ key: '{{ $label }}', open: {{ $open ? 'true' : 'false' }}, serverHasActive: {{ $hasActive ? 'true' : 'false' }}, rail: {{ $mobile ? 'false' : 'true' }} })"
    x-show="anyMatches()"
>
    <button
        type="button"
        @click="toggle()"
        :aria-expanded="isOpen().toString()"
        aria-label="{{ $label }} section"
        title="{{ $label }}"
        class="admin-nav-heading"
        :class="{ 'admin-nav-active': {{ $litExpression }}, 'text-gray-900': {{ $litExpression }} }"
    >
        {{-- A branch that holds the page is drawn filled even when the page is only one of many
           rows inside it, so the open branch can be found without reading labels. --}}
        <x-ui.icon :name="$icon" size="sm" class="shrink-0 {{ $centerOnRail }}" variant="solid" x-show="{{ $litExpression }}" />
        <x-ui.icon :name="$icon" size="sm" class="shrink-0 {{ $centerOnRail }}" variant="outline" x-show="! ({{ $litExpression }})" />

        <span class="flex-1 text-left {{ $hideOnRail }}">{{ $label }}</span>

        <svg
            class="size-3 shrink-0 transition-transform duration-200 {{ $hideOnRail }}"
            :class="isOpen() ? 'rotate-180' : ''"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true"
        ><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>

    {{-- The rail's collapsed state is owned by CSS, not by an expression: `sidebar-collapsed` is
         already seeded onto <html> before paint, and a bound x-show is the one hiding rule that
         silently does nothing if the expression ever throws — which would dump all 32 rows into
         an 80px column. x-show still owns open/closed and search, so the two mechanisms stack
         instead of competing: Alpine removes display:none when a branch opens, and the class
         keeps it hidden until the rail itself is widened. --}}
    <div
        x-show="isOpen()"
        x-collapse.duration.200ms
        class="{{ $hideOnRail }}"
    >
        <div class="admin-nav-tree space-y-1 pb-1">
            {{ $slot }}
        </div>
    </div>
</div>
