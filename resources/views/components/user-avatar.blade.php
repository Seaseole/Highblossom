@props([
    'user' => null,
    'size' => 'sm',
])

@php
    // One source of truth for a user's picture: the same avatar_url accessor the
    // profile page uses, so a disk or path change can never fix one surface and
    // leave another showing initials.
    $url = $user?->avatar_url;

    $sizes = [
        'xs' => ['box' => 'size-6', 'text' => 'text-[10px]'],
        'sm' => ['box' => 'size-8', 'text' => 'text-xs'],
        'md' => ['box' => 'size-10', 'text' => 'text-sm'],
    ];
    $size = $sizes[$size] ?? $sizes['sm'];
@endphp

<span
    {{ $attributes->class([
        'relative flex shrink-0 items-center justify-center overflow-hidden rounded-full bg-gray-200 dark:bg-white/10',
        $size['box'],
    ]) }}
>
    {{-- Underneath the image rather than replaced by it, so a broken URL falls back
         to initials without JavaScript; the img removes itself on error. --}}
    <span
        @class([$size['text'], 'font-bold text-gray-700 dark:text-gray-300'])
        @if ($url !== null) aria-hidden="true" @endif
    >{{ $user?->initials() ?? '?' }}</span>

    @if ($url !== null)
        <img
            src="{{ $url }}"
            alt="Avatar of {{ $user?->name }}"
            class="absolute inset-0 size-full rounded-full object-cover"
            onerror="this.remove()"
        />
    @endif
</span>
