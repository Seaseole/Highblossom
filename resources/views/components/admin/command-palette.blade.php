{{-- The admin command palette: one box that answers "go to a screen" and "find a record".

     Pages are matched in the browser against the same index the sidebar rail filters itself
     with (window.__ADMIN_NAV__, published by the rail's boot script), so a destination cannot be
     made unsearchable by a second implementation drifting from the first, and the permission
     filter is inherited rather than repeated. Records come from admin.search, which asks the
     same ability questions its own routes ask.

     It lives in the admin layout, not the site one, so it never meets the public blog palette
     on the same page; both bind Ctrl/⌘K, but only one of each is ever mounted. --}}
<div
    x-data="adminPaletteDialog"
    x-show="$store.adminPalette.open"
    x-cloak
    @keydown.tab="trap($event)"
    @keydown.escape.window="$store.adminPalette.hide()"
    x-effect="$store.adminPalette.open && $nextTick(() => $refs.input?.focus())"
>
    {{-- Backdrop --}}
    <div
        class="fixed inset-0 z-[9990] bg-gray-900/40 backdrop-blur-sm dark:bg-black/70"
        @click="$store.adminPalette.hide()"
        aria-hidden="true"
    ></div>

    {{-- Dialog --}}
    <div
        role="dialog"
        aria-modal="true"
        aria-labelledby="admin-palette-title"
        class="fixed inset-0 z-[9991] overflow-y-auto p-4"
        @click.self="$store.adminPalette.hide()"
    >
        <div class="mx-auto flex min-h-full items-start justify-center">
            <div class="admin-palette-panel w-full max-w-xl">
                <h2 id="admin-palette-title" class="sr-only">Search the admin panel</h2>

                <div class="admin-palette-field">
                    <x-ui.icon name="magnifying-glass" size="sm" class="shrink-0 text-gray-400" aria-hidden="true" />

                    <input
                        type="text"
                        x-ref="input"
                        x-model="$store.adminPalette.query"
                        @keydown.down.prevent="$store.adminPalette.move(1)"
                        @keydown.up.prevent="$store.adminPalette.move(-1)"
                        @keydown.enter.prevent="$store.adminPalette.select()"
                        autocomplete="off"
                        autocorrect="off"
                        spellcheck="false"
                        role="combobox"
                        aria-expanded="true"
                        aria-controls="admin-palette-results"
                        aria-label="Search pages and records"
                        placeholder="Search pages, bookings, messages, posts…"
                        class="admin-palette-input"
                    />

                    <x-admin.topbar.kbd class="hidden sm:inline-flex" />

                    <button
                        type="button"
                        @click="$store.adminPalette.hide()"
                        class="admin-bar-btn inline-flex size-8 shrink-0"
                        aria-label="Close search"
                    >
                        <x-ui.icon name="x-mark" size="xs" />
                    </button>
                </div>

                <p class="sr-only" role="status" aria-live="polite">
                    <span x-text="$store.adminPalette.status()"></span>
                </p>

                <div id="admin-palette-results" class="admin-palette-scroll">
                    {{-- Empty query: whatever was looked for recently, in this browser. --}}
                    <template x-if="$store.adminPalette.isEmpty()">
                        <div>
                            <template x-if="$store.adminPalette.recent.length > 0">
                                <div class="admin-palette-group">
                                    <p class="admin-palette-heading">Recent</p>
                                    <template x-for="term in $store.adminPalette.recent" :key="term">
                                        <button type="button" @click="$store.adminPalette.query = term" class="admin-palette-row">
                                            <x-ui.icon name="arrow-path" size="xs" class="shrink-0" />
                                            <span class="flex-1 truncate text-left" x-text="term"></span>
                                        </button>
                                    </template>
                                </div>
                            </template>

                            <p class="admin-palette-empty" x-show="$store.adminPalette.recent.length === 0">
                                Type to search the screens you can open, and the records in them.
                            </p>
                        </div>
                    </template>

                    {{-- Results: one group per section, pages first, then the modules the server
                         answered for. x-for over a computed list keeps the flat index the arrow
                         keys move through in exact step with what is on screen. --}}
                    <template x-if="! $store.adminPalette.isEmpty()">
                        <div>
                            <template x-for="group in $store.adminPalette.groups()" :key="group.label">
                                <div class="admin-palette-group">
                                    <p class="admin-palette-heading" x-text="group.label"></p>

                                    <template x-for="item in group.items" :key="item.url">
                                        <a
                                            :href="item.url"
                                            wire:navigate.hover
                                            @click="$store.adminPalette.choose(item)"
                                            @mouseenter="$store.adminPalette.cursor = item.idx"
                                            :class="{ 'admin-palette-row-active': $store.adminPalette.cursor === item.idx }"
                                            class="admin-palette-row"
                                        >
                                            <span class="min-w-0 flex-1">
                                                <span class="block truncate text-sm" x-text="item.label"></span>
                                                <span
                                                    x-show="item.sub"
                                                    x-text="item.sub"
                                                    class="block truncate text-xs text-[var(--admin-nav-label)]"
                                                ></span>
                                            </span>

                                            <span class="admin-nav-badge" x-show="item.badge" x-text="item.badge" aria-hidden="true"></span>
                                            <span class="sr-only" x-show="item.badge" x-text="'(' + item.badge + ' waiting)'"></span>
                                        </a>
                                    </template>
                                </div>
                            </template>

                            <p class="admin-palette-empty" x-show="$store.adminPalette.groups().length === 0">
                                Nothing matches “<span x-text="$store.adminPalette.query"></span>”.
                            </p>

                            <p class="admin-palette-empty" x-show="$store.adminPalette.loading">
                                Searching records…
                            </p>
                        </div>
                    </template>
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
