@props([
    'title' => null,
    'heading' => null,
    'subheading' => null,
    'brandLine' => null,
    'alpine' => false,
])

<x-layouts::base :title="$title" :alpine="$alpine">
    <div class="grid min-h-[100dvh] grid-cols-1 lg:grid-cols-2">
        {{-- Brand panel. Deliberately quiet graphite: the accent stays reserved
             for the things you can click, which is what makes it read as premium
             rather than decorated. --}}
        <aside class="ui-panel relative hidden flex-col justify-between overflow-hidden p-10 lg:flex xl:p-14">
            <div class="ui-panel-grid absolute inset-0" aria-hidden="true"></div>
            <div class="ui-panel-tint absolute inset-0" aria-hidden="true"></div>

            <a
                href="{{ route('home') }}"
                class="relative inline-flex items-center gap-3 self-start rounded-ui-md text-ui-on-panel transition-opacity hover:opacity-80 focus:outline-none"
            >
                <x-ui.brand-mark size="md" class="rounded-ui-sm" />
                <span class="text-ui-md font-semibold tracking-tight">{{ $companyName ?? config('app.name') }}</span>
            </a>

            <div class="relative max-w-sm">
                <span class="mb-6 block h-px w-10 bg-ui-accent" aria-hidden="true"></span>
                <p class="text-ui-xl leading-snug font-medium text-balance">
                    {{ $brandLine ?? __('auth.login.welcome_back') }}
                </p>
            </div>

            <p class="relative text-ui-xs tracking-[0.06em] text-ui-on-panel-muted uppercase">
                &copy; {{ date('Y') }} {{ $settings->get('company_name', config('app.name')) }}
            </p>
        </aside>

        {{-- Form column. Single column below lg, where the panel is dropped and
             the mark moves into the header row instead. --}}
        <main class="relative flex items-center justify-center px-5 py-10 sm:px-8 lg:px-12">
            <div class="w-full max-w-[26rem]">
                <div class="mb-7 flex items-center gap-3 lg:hidden">
                    <x-ui.brand-mark size="md" class="rounded-ui-sm" />
                    <span class="text-ui-lg font-semibold tracking-tight text-ui-text">{{ $companyName ?? config('app.name') }}</span>
                </div>

                <x-ui.card padding="lg" class="ui-rise">
                    @if ($heading)
                        <div class="mb-6">
                            <h1 class="text-ui-2xl font-semibold tracking-tight text-ui-text">{{ $heading }}</h1>
                            @if ($subheading)
                                <p class="mt-1.5 text-ui-sm text-ui-muted">{{ $subheading }}</p>
                            @endif
                        </div>
                    @endif

                    {{ $slot }}
                </x-ui.card>

                <div class="mt-7 flex flex-wrap items-center justify-between gap-x-4 gap-y-3">
                    <x-ui.link :href="route('home')" tone="muted" size="sm">
                        <x-ui.icon name="arrow-left" size="xs" class="shrink-0" />
                        {{ __('auth.ui.back_to_site') }}
                    </x-ui.link>

                    <nav aria-label="Legal" class="flex items-center gap-4">
                        <x-ui.link :href="route('privacy')" tone="muted" size="xs">{{ __('auth.ui.privacy') }}</x-ui.link>
                        <x-ui.link :href="route('terms')" tone="muted" size="xs">{{ __('auth.ui.terms') }}</x-ui.link>
                    </nav>
                </div>
            </div>
        </main>
    </div>
</x-layouts::base>
