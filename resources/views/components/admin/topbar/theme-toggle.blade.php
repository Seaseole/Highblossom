@props([
    'theme' => 'auto',
])

@php
    // The stored preference, not the effective appearance: under `auto` the operating system
    // decides and only the browser can know that. The labels below therefore describe the
    // promise, not the pixels.
    $options = [
        'light' => ['label' => 'Light', 'icon' => 'sun'],
        'dark' => ['label' => 'Dark', 'icon' => 'moon'],
        'auto' => ['label' => 'System', 'icon' => 'computer-desktop'],
    ];
@endphp

{{-- This control moved here from the sidebar footer, where it was one cycling button printed
     twice — once for the drawer and once for the rail, both writing to the same store. The
     store, the pre-paint seed in the layout, and the POST to admin.theme.store are unchanged,
     so there is still exactly one source of truth for the preference; only the trigger has a
     new home. Three named choices replace the cycle because a cycle cannot be pointed at. --}}
<div class="relative shrink-0" x-data="adminPopover">
    <button
        type="button"
        x-ref="trigger"
        @click="toggle()"
        :aria-expanded="open.toString()"
        aria-haspopup="menu"
        aria-controls="theme-menu"
        :aria-label="'Theme: ' + $store.adminNav.label()"
        title="Theme"
        class="admin-bar-btn inline-flex"
    >
        @foreach ($options as $value => $option)
            <x-ui.icon :name="$option['icon']" size="sm" x-show="$store.adminNav.theme === '{{ $value }}'" />
        @endforeach
    </button>

    <div
        id="theme-menu"
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.outside="close(false)"
        @keydown.escape.window="close()"
        @keydown.down.prevent="move(1)"
        @keydown.up.prevent="move(-1)"
        role="menu"
        aria-label="Theme"
        class="admin-nav-pop admin-bar-pop absolute right-0 top-full z-40 mt-2 w-44 space-y-1"
    >
        @foreach ($options as $value => $option)
            <button
                type="button"
                @click="$store.adminNav.setTheme('{{ $value }}'); close(false)"
                role="menuitemradio"
                :aria-checked="($store.adminNav.theme === '{{ $value }}').toString()"
                class="admin-nav-item w-full"
            >
                <x-ui.icon :name="$option['icon']" size="xs" class="shrink-0" />
                <span class="flex-1">{{ $option['label'] }}</span>
                <x-ui.icon name="check" size="xs" class="shrink-0" x-show="$store.adminNav.theme === '{{ $value }}'" />
            </button>
        @endforeach
    </div>
</div>
