@php
    /* Drawn from the rail's own view model, so the navigation is still declared exactly once:
       AdminMenu::groups() writes the entries, AdminMenu::visible() applies the abilities, and
       AdminSidebar::render() resolves the paths, the badge counts and which row owns this page.
       This is a third place those rows are printed — never a second menu to keep in step, which
       is the only reason the two layouts cannot disagree about what an account may open.

       Every Alpine expression below is a bare call with an integer key, written on a plain
       element. A `$store` token inside a static attribute on a Blade component tag compiles as
       PHP rather than as Alpine, so the rows stay behind the one component that already solved
       that problem, and this file never reaches into their scope. */
    $groups = \App\Support\AdminSidebar::render(request());
@endphp

<div
    {{-- min-width:0 stays: this element is the one allowed to be narrower than what is inside it,
         which is how the fit maths gets a real budget to compare against. `lg:ml-2 lg:mr-1` sits on
         top of the bar's own gap so the row starts clear of the truncating context chip and, when
         the bar is saturated, ends clear of the search field. --}}
    class="admin-bar-menu min-w-0 flex-1 lg:ml-2 lg:mr-1"
    x-ref="menu"
    x-data="adminMenuBar"
    @click.outside="close(false)"
    @keydown.left.prevent="moveTrigger(-1)"
    @keydown.right.prevent="moveTrigger(1)"
    @keydown.home.prevent="moveTrigger('first')"
    @keydown.end.prevent="moveTrigger('last')"
    @keydown.escape.prevent="close(true)"
    role="menubar"
    aria-label="Admin navigation"
>
    @foreach ($groups as $index => $group)
        @php $items = $group['items']; @endphp

        {{-- The group's own label, and nothing else: the bar decides which branch holds the page
             by asking the shell which section the lit row belongs to, so this row and the rail
             cannot answer that question with two different rules. Paths are deliberately not read
             out of the DOM here — this island is restored from Livewire's persist store, and a
             subtree query made during that restore sees nothing. --}}
        <div class="admin-bar-menu-slot" data-menu-slot="{{ $index }}" data-menu-group="{{ $group['label'] }}" x-show="shows({{ $index }})">
            @if (count($items) === 1)
                {{-- A group with one destination is a link, not a menu. Opening a dropdown to
                     show the single row inside it would be a click for nothing. It is still a top
                     level item, so it carries the trigger marker: Left and Right have to stop at
                     every item in the bar, not only at the ones that open something. --}}
                {{-- The group's icon travels with it: a row that loses its drawing while every
                     neighbour keeps one makes the bar look like two lists joined together. `xs`
                     matches the branches, which are the only other thing in this row. --}}
                <x-admin.sidebar.item
                    :label="$items[0]['label']"
                    :url="$items[0]['url']"
                    :icon="$group['icon']"
                    icon-size="xs"
                    :server-active="$items[0]['active']"
                    :badge-count="$items[0]['badge']"
                    data-menu-trigger="{{ $index }}"
                    role="menuitem"
                />
            @else
                {{-- Hover is claimed by the branch, not the trigger: the panel below is the
                     trigger's sibling, so a leave read off the trigger fired on the way down into
                     the submenu and closed it under the pointer. See `hoverIn` in admin.js. --}}
                <div
                    class="admin-bar-menu-branch"
                    @pointerenter="hoverIn({{ $index }}, $event)"
                    @pointerleave="hoverOut($event)"
                >
                    <button
                        type="button"
                        role="menuitem"
                        aria-haspopup="menu"
                        class="admin-bar-menu-item"
                        data-menu-trigger="{{ $index }}"
                        :aria-expanded="isOpen({{ $index }}).toString()"
                        :class="{ 'admin-bar-menu-lit': holdsPage({{ $index }}) }"
                        @click="toggle({{ $index }})"
                        @keydown.down.prevent="openAndEnter({{ $index }})"
                        @keydown.up.prevent="openAndEnter({{ $index }})"
                    >
                        <x-ui.icon :name="$group['icon']" size="xs" class="shrink-0" />

                        <span class="truncate">{{ $group['label'] }}</span>

                        @if (collect($items)->contains(fn (array $item) => filled($item['badge']) && $item['badge'] > 0))
                            {{-- A closed branch would hide its waiting work entirely. The dot says
                                 "there is something in here"; the number itself is printed on the
                                 row, so it is never stated twice and never stale by a second. --}}
                            <span class="admin-bar-menu-flag" aria-hidden="true"></span>
                        @endif

                        {{-- Rotation on a plain span: :class written on a component tag is compiled
                             as PHP, not read by Alpine. --}}
                        <span class="admin-bar-menu-caret" :class="isOpen({{ $index }}) ? 'rotate-180' : ''">
                            <x-ui.icon name="chevron-down" size="xs" />
                        </span>
                    </button>

                    <div
                        class="admin-nav-pop admin-bar-pop absolute left-0 top-full z-40 mt-2 w-60 space-y-1"
                        data-menu-panel="{{ $index }}"
                        x-cloak
                        x-show="isOpen({{ $index }})"
                        @click="close(false)"
                        @keydown.down.prevent="walk(1)"
                        @keydown.up.prevent="walk(-1)"
                        role="menu"
                        aria-label="{{ $group['label'] }}"
                    >
                        <p class="admin-bar-heading">{{ $group['label'] }}</p>

                        @foreach ($items as $item)
                            <x-admin.sidebar.item
                                :label="$item['label']"
                                :url="$item['url']"
                                :server-active="$item['active']"
                                :badge-count="$item['badge']"
                                role="menuitem"
                            />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    {{-- The overflow. One pill holds every group the measured width could not, so the row never
         wraps and never scrolls: the two failures a bar of fixed height cannot survive. --}}
    <div class="admin-bar-menu-slot" data-menu-more x-show="hasOverflow()">
        <div
            class="admin-bar-menu-branch"
            @pointerenter="hoverIn('more', $event)"
            @pointerleave="hoverOut($event)"
        >
            <button
                type="button"
                role="menuitem"
                aria-haspopup="menu"
                class="admin-bar-menu-item"
                data-menu-trigger="more"
                :aria-expanded="isOpen('more').toString()"
                @click="toggle('more')"
                @keydown.down.prevent="openAndEnter('more')"
                @keydown.up.prevent="openAndEnter('more')"
            >
                <span class="px-0.5">More</span>

                <span class="admin-bar-menu-caret" :class="isOpen('more') ? 'rotate-180' : ''">
                    <x-ui.icon name="chevron-down" size="xs" />
                </span>
            </button>

            <div
                class="admin-nav-pop admin-bar-pop absolute right-0 top-full z-40 mt-2 w-64 space-y-2"
                data-menu-panel="more"
                x-cloak
                x-show="isOpen('more')"
                @click="close(false)"
                @keydown.down.prevent="walk(1)"
                @keydown.up.prevent="walk(-1)"
                role="menu"
                aria-label="More sections"
            >
                @foreach ($groups as $index => $group)
                    {{-- x-if rather than x-show: a branch that fits in the bar has no reason to
                         also exist in here, and every row carries its own Alpine scope. This keeps
                         the duplicated rows to exactly the ones that are actually off-screen. --}}
                    <template x-if="overflows({{ $index }})">
                        <div class="space-y-1">
                            @if (count($group['items']) === 1)
                                <x-admin.sidebar.item
                                    :label="$group['items'][0]['label']"
                                    :url="$group['items'][0]['url']"
                                    :server-active="$group['items'][0]['active']"
                                    :badge-count="$group['items'][0]['badge']"
                                    role="menuitem"
                                />
                            @else
                                <p class="admin-bar-heading">{{ $group['label'] }}</p>

                                @foreach ($group['items'] as $item)
                                    <x-admin.sidebar.item
                                        :label="$item['label']"
                                        :url="$item['url']"
                                        :server-active="$item['active']"
                                        :badge-count="$item['badge']"
                                        role="menuitem"
                                    />
                                @endforeach
                            @endif
                        </div>
                    </template>
                @endforeach
            </div>
        </div>
    </div>
</div>
