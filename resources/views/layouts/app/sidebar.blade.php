<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="grid min-h-screen w-full lg:grid-cols-[auto_1fr]">
            <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                <flux:sidebar.group heading="{{ __('Bookings') }}" expandable :expanded="request()->routeIs('admin.bookings.*') || request()->routeIs('admin.inspections.*') || request()->routeIs('admin.absences.*')">
                    <flux:sidebar.item icon="calendar" :href="route('admin.bookings.index')" :current="request()->routeIs('admin.bookings.*')" wire:navigate>
                        {{ __('Manage Bookings') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="check-circle" :href="route('admin.inspections.index')" :current="request()->routeIs('admin.inspections.*')" wire:navigate>
                        {{ __('Inspections') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('admin.absences.index')" :current="request()->routeIs('admin.absences.*')" wire:navigate>
                        {{ __('Staff Absences') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group heading="{{ __('Content') }}" expandable :expanded="request()->routeIs('admin.pages.*') || request()->routeIs('admin.blog.*') || request()->routeIs('admin.testimonials.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.gallery.*')">
                    <flux:sidebar.item icon="document-text" :href="route('admin.pages.index')" :current="request()->routeIs('admin.pages.*')" wire:navigate>
                        {{ __('Pages') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="newspaper" :href="route('admin.blog.index')" :current="request()->routeIs('admin.blog.*')" wire:navigate>
                        {{ __('Blog') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('admin.testimonials.index')" :current="request()->routeIs('admin.testimonials.*')" wire:navigate>
                        {{ __('Testimonials') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="wrench" :href="route('admin.services.index')" :current="request()->routeIs('admin.services.*')" wire:navigate>
                        {{ __('Services') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="photo" :href="route('admin.gallery.index')" :current="request()->routeIs('admin.gallery.*')" wire:navigate>
                        {{ __('Gallery') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group heading="{{ __('Contact') }}" expandable :expanded="request()->routeIs('admin.contact-numbers.*') || request()->routeIs('admin.contact-messages.*')">
                    <flux:sidebar.item icon="phone" :href="route('admin.contact-numbers.index')" :current="request()->routeIs('admin.contact-numbers.*')" wire:navigate>
                        {{ __('Numbers') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="inbox" :href="route('admin.contact-messages.index')" :current="request()->routeIs('admin.contact-messages.*')" wire:navigate>
                        {{ __('Messages') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group heading="{{ __('Settings') }}" expandable :expanded="request()->routeIs('admin.settings.*') || request()->routeIs('admin.seo.*')">
                    <flux:sidebar.item icon="building-office" :href="route('admin.settings.company')" :current="request()->routeIs('admin.settings.company')" wire:navigate>
                        {{ __('Company') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="envelope" :href="route('admin.settings.smtp')" :current="request()->routeIs('admin.settings.smtp')" wire:navigate>
                        {{ __('SMTP') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="globe-alt" :href="route('admin.seo.static-routes')" :current="request()->routeIs('admin.seo.*')" wire:navigate>
                        {{ __('SEO') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        </div>
    </body>
</html>
