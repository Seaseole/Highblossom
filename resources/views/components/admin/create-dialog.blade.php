@props([
    'actions' => [],
])

{{-- The bar's Create control, as a dialog rather than a drop-down.

     It is built the way the command palette is: the open flag lives in an Alpine store, so the
     bar's `+`, the account menu's phone row and this panel can never disagree about whether it is
     showing, and the whole thing survives a `wire:navigate` because it sits in the persisted bar.
     The list is the same one the old popover printed — AdminTopbar::permittedQuickActions, filtered
     by the ability each create route enforces — so a tile appears here exactly when its page would
     answer with something other than a 403. --}}
<div
    x-data="adminCreateDialog"
    x-show="$store.adminCreate.open"
    x-cloak
    @keydown.tab="trap($event)"
    @keydown.escape.window="$store.adminCreate.hide()"
    @keydown.down.prevent="move(1)"
    @keydown.up.prevent="move(-1)"
    @keydown.right.prevent="move(1)"
    @keydown.left.prevent="move(-1)"
    x-on:livewire:navigated.document="$store.adminCreate.hide(false)"
    x-effect="$store.adminCreate.open && $nextTick(() => focusFirst())"
>
    <div
        class="fixed inset-0 z-[9990] bg-gray-900/40 backdrop-blur-sm dark:bg-black/70"
        @click="$store.adminCreate.hide()"
        aria-hidden="true"
    ></div>

    <div
        id="admin-create-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="admin-create-title"
        aria-describedby="admin-create-lede"
        class="fixed inset-0 z-[9991] overflow-y-auto p-4"
        @click.self="$store.adminCreate.hide()"
    >
        <div class="mx-auto flex min-h-full items-start justify-center pt-[8vh]">
            <div class="admin-palette-panel w-full max-w-2xl">
                <div class="admin-create-head">
                    <div class="min-w-0 flex-1">
                        <h2 id="admin-create-title" class="admin-create-title">Create</h2>
                        <p id="admin-create-lede" class="admin-create-lede">
                            Start something new. Only what you are allowed to make is listed.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="$store.adminCreate.hide()"
                        class="admin-bar-btn inline-flex size-8 shrink-0"
                        aria-label="Close"
                    >
                        <x-ui.icon name="x-mark" size="xs" />
                    </button>
                </div>

                <div class="admin-create-grid" role="menu" aria-label="Things you can create">
                    @foreach ($actions as $action)
                        <a
                            href="{{ $action['url'] }}"
                            wire:navigate.hover
                            @click="$store.adminCreate.hide(false)"
                            role="menuitem"
                            class="admin-create-tile"
                        >
                            <span class="admin-create-tile-icon" aria-hidden="true">
                                <x-ui.icon :name="$action['icon']" size="sm" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="admin-create-tile-label">{{ $action['label'] }}</span>
                                <span class="admin-create-tile-sub">{{ $action['description'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>

                <div class="admin-palette-foot">
                    <span><span class="admin-palette-keys">↑</span><span class="admin-palette-keys">↓</span> to move</span>
                    <span><span class="admin-palette-keys">Enter</span> to open</span>
                    <span><span class="admin-palette-keys">Esc</span> to close</span>
                </div>
            </div>
        </div>
    </div>
</div>
