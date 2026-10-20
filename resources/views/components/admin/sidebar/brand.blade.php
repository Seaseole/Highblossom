@props([
    'logoUrl' => null,
    'companyName' => '',
    'mobile' => false,
])

<div class="flex h-16 shrink-0 items-center border-b border-gray-100 px-6 {{ $mobile ? '' : 'sidebar-collapsed:lg:px-4' }} dark:border-white/5">
    <a href="/" class="flex items-center gap-3" aria-label="Go to homepage">
        <div class="flex aspect-square size-8 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gray-900 text-white dark:bg-white dark:text-gray-900">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="size-full object-cover" />
            @else
                <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            @endif
        </div>
        <span class="font-semibold text-gray-900 {{ $mobile ? '' : 'sidebar-collapsed:lg:hidden' }} dark:text-white">{{ $companyName }}</span>
    </a>

    @if ($mobile)
        {{-- The id is what the layout's store focuses when the drawer opens and returns to on
             close; the desktop half of the shell must not mint a second copy of it. --}}
        <button
            id="sidebar-close-btn"
            @click="$store.mobileMenu.close()"
            x-ref="closeBtn"
            class="ml-auto flex size-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 lg:hidden dark:hover:bg-white/5 dark:hover:text-white"
            aria-label="Close navigation"
        >
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    @endif
</div>
