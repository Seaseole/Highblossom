@props([
    'companyName' => '',
    'group' => null,
    'label' => null,
])

{{-- Not a breadcrumb trail: nothing here is a link, because the section heading in the rail is a
     branch, not a page, and linking it somewhere it does not point would be a lie. It answers
     one question — which row of the navigation owns this screen — in the same two words the rail
     uses. The text is seeded server-side for the first paint and then recomputed by the store on
     every wire:navigate, because a persisted bar that only ever printed the server's answer would
     keep naming whichever page was loaded first. --}}
<p class="admin-bar-context flex min-w-0 items-center gap-2 pl-1 sm:pl-2">
    <span class="sr-only">Current page</span>

    <span class="hidden min-w-0 shrink items-center gap-2 lg:flex dark:text-gray-100">
        <span class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $companyName }}</span>
        <span class="admin-bar-sep" aria-hidden="true"></span>
    </span>

    <span
        x-show="$store.adminNav.context.group"
        class="hidden shrink-0 items-center gap-2 text-sm text-gray-500 md:flex dark:text-gray-400"
    >
        {{-- The class sits on the two children rather than on the span above, because that span is
             owned by `x-show` and a second writer to the same `display` property is a race. Top
             mode hides the group word: the lit menu trigger already says it. --}}
        <span class="admin-bar-context-group admin-bar-context-page" x-text="$store.adminNav.context.group">{{ $group }}</span>
        <span aria-hidden="true" class="admin-bar-context-page">/</span>
    </span>

    <span class="truncate text-sm font-semibold text-gray-900 dark:text-white" aria-current="page" x-text="$store.adminNav.context.label">{{ $label }}</span>
</p>
