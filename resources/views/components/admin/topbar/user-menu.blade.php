@props([
    'user' => null,
    'quickActions' => [],
])

@php
    $role = \App\Support\AdminSidebar::roleLabel();
    $profilePath = \App\Support\AdminSidebar::path('admin.profile.index');
    $sessionsPath = \App\Support\AdminSidebar::path('admin.sessions.index');
    $canSessions = $user !== null && $user->can('manage user sessions');
    $lastSignIn = \App\Support\AdminTopbar::lastSignIn($user);
    $environment = \App\Support\AdminTopbar::environment();
    $version = $environment === null ? null : \App\Support\AdminTopbar::version();
    $twoFactor = $user?->hasEnabledTwoFactorAuthentication() ?? false;
    // The layout this account is actually looking at, which is the site default until they
    // choose — not whatever their row happens to hold.
    $navLayout = \App\Support\AdminNav::for($user);
@endphp

{{-- This card moved from the bottom of the sidebar, where it was rendered twice and shared one
     id with its other copy. It grew the details the old one could not hold: the account's own
     identity (email, username, role), the security posture worth checking before you do
     something irreversible (two-factor, and the sign-in before this one), and the environment
     the badge used to float in the top-right corner and cover these very buttons.

     Logout is a plain POST with no wire:navigate, for the reason the old footer spelled out:
     signing out has to tear the page down and re-read the session, and an SPA swap would leave
     this bar — with its persisted menus and cached counts — standing over a page nobody is
     signed in to. --}}
<div class="relative ml-1 shrink-0 sm:ml-2" x-data="adminPopover">
    <button
        type="button"
        x-ref="trigger"
        @click="toggle()"
        @keydown.down.prevent="show()"
        @keydown.up.prevent="show(true)"
        :aria-expanded="open.toString()"
        aria-haspopup="menu"
        aria-controls="account-menu"
        class="admin-bar-user"
    >
        <x-user-avatar :user="$user" size="sm" />

        <span class="hidden min-w-0 flex-1 text-left lg:block">
            <span class="block truncate">{{ $user?->name ?? 'Guest' }}<span class="sr-only">, account menu</span></span>
        </span>

        {{-- The rotation lives on a plain span rather than on the icon component: :class is a
             bound attribute, and a bound attribute written on a component tag is compiled as
             PHP rather than read by Alpine. --}}
        <span class="hidden shrink-0 text-gray-400 transition-transform duration-200 lg:inline-flex" :class="open ? 'rotate-180' : ''">
            <x-ui.icon name="chevron-down" size="xs" />
        </span>
    </button>

    <div
        id="account-menu"
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
        aria-label="Account"
        class="admin-nav-pop admin-bar-pop absolute right-0 top-full z-40 mt-2 w-72 space-y-1"
    >
        {{-- Header: who this account is. --}}
        <div class="admin-bar-user-header">
            <x-user-avatar :user="$user" size="md" />

            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $user?->name ?? 'Guest' }}</p>
                <p class="truncate text-xs text-[var(--admin-nav-label)]">{{ $user?->email }}</p>

                <div class="mt-1.5 flex flex-wrap items-center gap-1">
                    @if ($role)
                        <span class="admin-bar-tag">{{ $role }}</span>
                    @endif

                    @if (filled($user?->username))
                        <span class="admin-bar-tag admin-bar-tag-muted">{{ '@'.$user->username }}</span>
                    @endif

                    <span class="admin-bar-tag {{ $twoFactor ? 'admin-bar-tag-ok' : 'admin-bar-tag-warn' }}">
                        {{ $twoFactor ? '2FA on' : '2FA off' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="admin-bar-rule" role="separator"></div>

        {{-- The profile page's own tabs, addressed by the query they already answer to. --}}
        <a href="{{ $profilePath }}" wire:navigate.hover @click="close(false)" role="menuitem" class="admin-nav-item">
            <x-ui.icon name="identification" size="xs" class="shrink-0" />
            <span class="flex-1">My profile</span>
        </a>

        <a href="{{ $profilePath }}?tab=appearance" wire:navigate.hover @click="close(false)" role="menuitem" class="admin-nav-item">
            <x-ui.icon name="computer-desktop" size="xs" class="shrink-0" />
            <span class="flex-1">Appearance</span>
        </a>

        <a href="{{ $profilePath }}?tab=security" wire:navigate.hover @click="close(false)" role="menuitem" class="admin-nav-item">
            <x-ui.icon name="lock-closed" size="xs" class="shrink-0" />
            <span class="flex-1">Password &amp; two-factor</span>
        </a>

        <a href="{{ $profilePath }}?tab=passkeys" wire:navigate.hover @click="close(false)" role="menuitem" class="admin-nav-item">
            <x-ui.icon name="key" size="xs" class="shrink-0" />
            <span class="flex-1">Passkeys</span>
        </a>

        <a href="{{ $profilePath }}?tab=sessions" wire:navigate.hover @click="close(false)" role="menuitem" class="admin-nav-item">
            <x-ui.icon name="circle-stack" size="xs" class="shrink-0" />
            <span class="flex-1">Your devices</span>
        </a>

        @if ($canSessions)
            <a href="{{ $sessionsPath }}" wire:navigate.hover @click="close(false)" role="menuitem" class="admin-nav-item">
                <x-ui.icon name="shield-check" size="xs" class="shrink-0" />
                <span class="flex-1">All sessions</span>
            </a>
        @endif

        {{-- The same choice the Appearance tab offers, one click nearer. Two rows rather than a
             cycle, because a person should be able to see which one they are on before pressing
             it — and because a cycle cannot show the other option exists.

             They are not hidden on a phone, and that is deliberate: the setting is about the desk,
             so changing it from a phone is a legitimate thing to do. The word "Desktop" in the
             heading is what says so, and the toast repeats it. Hiding the row behind a class would
             not have worked either — `.admin-nav-item` is unlayered and would beat `hidden`. --}}
        <div class="admin-bar-rule" role="separator"></div>

        <p class="admin-bar-heading">Desktop navigation</p>

        @foreach (\App\Enums\AdminNavLayout::options() as $layout)
            <button
                type="button"
                @click="$store.adminNav.setNavLayout('{{ $layout->value }}'); close(false)"
                role="menuitemradio"
                aria-checked="{{ $navLayout === $layout ? 'true' : 'false' }}"
                :aria-checked="($store.adminNav.navLayout === '{{ $layout->value }}').toString()"
                class="admin-nav-item w-full"
            >
                <x-ui.icon :name="$layout === \App\Enums\AdminNavLayout::Top ? 'computer-desktop' : 'bars-3'" size="xs" class="shrink-0" />
                <span class="flex-1">{{ $layout->label() }}</span>
                <x-ui.icon name="check" size="xs" class="shrink-0" x-show="$store.adminNav.navLayout === '{{ $layout->value }}'" />
            </button>
        @endforeach

        {{-- The two controls the narrow bar has no room for. Same handlers, same store, one
             extra placement — the alternative was a second set of actions that only exists on
             phones, which is the kind of drift that starts a redesign. --}}
        @if ($quickActions !== [])
            {{-- Opens the same dialog the bar's `+` does, so the list of what can be created is
                 printed once. The menu closes first, or the dialog would open under it. --}}
            <button
                type="button"
                @click="close(false); $store.adminCreate.show($refs.trigger)"
                role="menuitem"
                aria-haspopup="dialog"
                aria-controls="admin-create-dialog"
                class="admin-nav-item admin-nav-item-narrow w-full"
            >
                <x-ui.icon name="plus" size="xs" class="shrink-0" />
                <span class="flex-1">Create…</span>
            </button>
        @endif

        <button
            type="button"
            @click="$store.fullscreen.toggle(); close(false)"
            role="menuitem"
            class="admin-nav-item admin-nav-item-narrow w-full"
        >
            <x-ui.icon name="arrows-pointing-out" size="xs" class="shrink-0" />
            <span class="flex-1">Full screen</span>
        </button>

        <div class="admin-bar-rule" role="separator"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" role="menuitem" class="admin-nav-item admin-nav-item-danger w-full">
                <x-ui.icon name="power" size="xs" class="shrink-0" />
                <span class="flex-1">Log out</span>
            </button>
        </form>

        @if ($lastSignIn || $environment)
            <div class="admin-bar-foot">
                @if ($lastSignIn)
                    <p>Last signed in {{ $lastSignIn->diffForHumans() }}</p>
                @endif

                @if ($environment)
                    {{-- The environment badge lived in a fixed pill over the top-right corner of
                         every admin page. Here it is where it can actually be read, and it is
                         absent in production rather than green. --}}
                    <p class="mt-1 flex items-center gap-1.5">
                        <span class="admin-bar-env admin-bar-env-{{ $environment }}" aria-hidden="true"></span>
                        <span class="uppercase">{{ $environment }}</span>
                        @if ($version)
                            <span class="font-mono">v{{ $version }}</span>
                        @endif
                    </p>
                @endif
            </div>
        @endif
    </div>
</div>
