@props([
    'rows' => [],
])

@php
    $total = array_sum(array_column($rows, 'count'));
@endphp

{{-- The bell is the rail's badges seen from the other end. Same numbers, same filter: a queue
     appears here only when AdminMenu counts it for a row this account can open, so the bell
     cannot promise a list the sidebar refused to show. It is a snapshot of a 60-second cache
     taken when this page was rendered, which is what a persisted bar can honestly be. --}}
<div class="relative shrink-0" x-data="adminPopover">
    <button
        type="button"
        x-ref="trigger"
        @click="toggle()"
        :aria-expanded="open.toString()"
        aria-haspopup="menu"
        aria-controls="notifications-menu"
        :aria-label="open ? 'Waiting queues, close' : 'Waiting queues, {{ $total }} waiting'"
        title="Waiting for someone"
        class="admin-bar-btn relative inline-flex"
    >
        <x-ui.icon name="bell" size="sm" />

        @if ($total > 0)
            {{-- Two renderings of one number: the dot is decoration for eyes that can see it,
                 the sr-only text is the whole message for the ones that cannot. --}}
            <span class="admin-bar-count" aria-hidden="true">{{ $total > 99 ? '99+' : $total }}</span>
            <span class="sr-only">{{ $total }} waiting</span>
        @endif
    </button>

    <div
        id="notifications-menu"
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
        x-on:livewire:navigated.document="close(false)"
        role="menu"
        aria-label="Waiting queues"
        class="admin-nav-pop admin-bar-pop absolute right-0 top-full z-40 mt-2 w-64 space-y-1"
    >
        @foreach ($rows as $row)
            <a
                href="{{ $row['url'] }}"
                wire:navigate.hover
                @click="close(false)"
                role="menuitem"
                class="admin-nav-item"
            >
                <span class="flex-1">{{ $row['label'] }}</span>
                <span class="admin-nav-badge" aria-hidden="true">{{ $row['count'] > 99 ? '99+' : $row['count'] }}</span>
                <span class="sr-only">({{ $row['count'] }} waiting)</span>
            </a>
        @endforeach
    </div>
</div>
