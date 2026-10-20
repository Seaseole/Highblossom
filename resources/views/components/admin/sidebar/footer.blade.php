@props([
    'theme' => 'auto',
    'role' => null,
    'mobile' => false,
])

@php
    $user = auth()->user();
    $hide = $mobile ? '' : 'sidebar-collapsed:lg:hidden';
    $center = $mobile ? '' : 'sidebar-collapsed:lg:mx-auto';

    $profileUrl = \App\Support\AdminSidebar::path('admin.profile.index');
    $sessionsUrl = \App\Support\AdminSidebar::path('admin.sessions.index');
    $canSessions = $user !== null && $user->can('manage user sessions');

    // Landing on your own profile should not show a card that looks closed.
    $startOpen = request()->is(ltrim($profileUrl, '/'));
@endphp

<div class="shrink-0 space-y-2 border-t border-gray-100 p-4 {{ $mobile ? '' : 'sidebar-collapsed:lg:p-2' }} dark:border-white/5">
    {{-- One Alpine scope for the card and its panel: two scopes hand-signalling each other
         across a Blade branch is how a menu ends up opening somewhere nobody can see it. --}}
    <div
        class="admin-nav-card relative"
        x-data="{ open: @if ($startOpen) true @else false @endif }"
        @if (! $mobile)
            x-on:click.outside="open = false"
            x-on:keydown.escape.window="open = false"
        @endif
    >
        @if ($mobile)
            {{-- The drawer is already a narrow, scrolling, off-canvas surface. Nesting a popover
                 inside it would need its own trap and its own scroll, and there is no rail to
                 save space for — so the account actions simply sit in the list. --}}
            <a
                href="{{ $profileUrl }}"
                wire:navigate.hover
                @click="$store.mobileMenu.close()"
                class="flex min-h-[44px] w-full items-center gap-3 rounded-xl px-2 py-1.5 text-left text-sm font-medium text-gray-900 hover:bg-white dark:text-white dark:hover:bg-white/10"
            >
                <x-user-avatar :user="$user" size="sm" />
                <span class="min-w-0 flex-1">
                    <span class="block truncate">{{ $user?->name ?? 'Guest' }}</span>
                    @if ($role)
                        <span class="block truncate text-xs font-normal text-gray-400 dark:text-gray-500">{{ $role }}</span>
                    @endif
                </span>
            </a>

            <div class="mt-1 space-y-1">
                @if ($canSessions)
                    <a
                        href="{{ $sessionsUrl }}"
                        wire:navigate.hover
                        @click="$store.mobileMenu.close()"
                        class="admin-nav-item"
                    >
                        <x-ui.icon name="shield-check" size="xs" class="shrink-0" />
                        <span class="flex-1">Sessions</span>
                    </a>
                @endif

                <button
                    type="button"
                    @click="$store.adminNav.cycleTheme()"
                    class="admin-nav-item w-full"
                >
                    <x-ui.icon name="bolt" size="xs" class="shrink-0" />
                    <span class="flex-1">Theme</span>
                    <span class="admin-nav-chip" data-admin-theme-chip="{{ $theme }}" x-text="$store.adminNav.theme">{{ $theme }}</span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="admin-nav-item w-full text-[var(--color-admin-accent)]">
                        <x-ui.icon name="power" size="xs" class="shrink-0" />
                        <span class="flex-1">Logout</span>
                    </button>
                </form>
            </div>
        @else
            <button
                type="button"
                @click="open = ! open"
                :aria-expanded="open.toString()"
                aria-haspopup="menu"
                aria-controls="account-menu"
                aria-label="Account menu"
                class="flex min-h-[44px] w-full items-center gap-3 rounded-xl px-2 py-1.5 text-left text-sm font-medium text-gray-900 hover:bg-white dark:text-white dark:hover:bg-white/10"
            >
                <x-user-avatar :user="$user" size="sm" class="{{ $center }}" />

                <span class="min-w-0 flex-1 {{ $hide }}">
                    <span class="block truncate">{{ $user?->name ?? 'Guest' }}</span>
                    @if ($role)
                        <span class="block truncate text-xs font-normal text-gray-400 dark:text-gray-500">{{ $role }}</span>
                    @endif
                </span>

                <svg
                    class="size-4 shrink-0 text-gray-400 transition-transform duration-200 {{ $hide }}"
                    :class="open ? 'rotate-180' : ''"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                ><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>
            </button>

            {{-- Floating, not in flow: the footer is pinned to the bottom of a viewport-height
                 column, so a panel that grew downward would push past the bottom edge of the
                 screen — the menu appearing outside the sidebar. Anchoring it above the trigger
                 keeps the footer exactly one card tall whatever happens. At the 80px rail width
                 the panel cannot hold a label either, so it breaks out to the right at a fixed
                 width instead of clipping. --}}
            <div
                id="account-menu"
                x-cloak
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-1"
                role="menu"
                aria-label="Account"
                class="admin-nav-pop absolute bottom-full left-0 right-0 z-30 mb-2 space-y-1 sidebar-collapsed:lg:right-auto sidebar-collapsed:lg:w-56"
            >
                <a
                    href="{{ $profileUrl }}"
                    wire:navigate.hover
                    @click="open = false"
                    role="menuitem"
                    class="admin-nav-item"
                >
                    <x-ui.icon name="users" size="xs" class="shrink-0" />
                    <span class="flex-1">Profile</span>
                </a>

                @if ($canSessions)
                    <a
                        href="{{ $sessionsUrl }}"
                        wire:navigate.hover
                        @click="open = false"
                        role="menuitem"
                        class="admin-nav-item"
                    >
                        <x-ui.icon name="shield-check" size="xs" class="shrink-0" />
                        <span class="flex-1">Sessions</span>
                    </a>
                @endif

                <button
                    type="button"
                    @click="$store.adminNav.cycleTheme()"
                    role="menuitem"
                    class="admin-nav-item w-full"
                >
                    <x-ui.icon name="bolt" size="xs" class="shrink-0" />
                    <span class="flex-1">Theme</span>
                    <span class="admin-nav-chip" data-admin-theme-chip="{{ $theme }}" x-text="$store.adminNav.theme">{{ $theme }}</span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    {{-- Deliberately not wire:navigate: logout has to tear the page down and
                         re-read the session. An SPA swap would leave the persisted rail — with
                         its lit items and cached counts — standing over a page nobody is signed
                         in to. --}}
                    <button type="submit" role="menuitem" class="admin-nav-item w-full text-[var(--color-admin-accent)]">
                        <x-ui.icon name="power" size="xs" class="shrink-0" />
                        <span class="flex-1">Logout</span>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
