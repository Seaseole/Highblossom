@props([
    'logoUrl' => null,
    'companyName' => '',
    'mobile' => false,
])

{{-- Two small corrections, both of which the accessibility tree asked for.

   The logo is decoration beside a link that already says the company name twice, once in
   its own text and once in its `aria-label`. An `alt` that repeats the surrounding text is
   read as the same words twice, so it becomes empty and the name stays where it is written.

   The desktop brand row sat outside every landmark: the rail's own `<nav>` starts under it,
   and the mobile copy is inside a dialog, which is already a region. A named region is a
   landmark, so the desktop half gets one and the mobile half is left alone. --}}
<div
    class="flex h-16 shrink-0 items-center border-b border-gray-100 px-6 {{ $mobile ? '' : 'sidebar-collapsed:lg:px-4' }} dark:border-white/5"
    @if (! $mobile) role="region" aria-label="Company" @endif
>
    <a href="/" class="flex items-center gap-3" aria-label="{{ $companyName }}, go to homepage">
        {{-- The monogram is always in the box and the stored logo sits on top of it.
             A `business_logo` that points at a file which is no longer on disk used to
             paint a broken-image glyph on every page of the panel and cost one failed
             request per view; now the mark it falls back to is already there. --}}
        <div class="relative flex aspect-square size-8 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gray-900 text-white dark:bg-white dark:text-gray-900">
            <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
            </svg>
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="" role="presentation" class="absolute inset-0 size-full object-cover" onerror="this.remove()" />
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
