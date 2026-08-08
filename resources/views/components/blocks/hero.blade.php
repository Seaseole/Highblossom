@props([
    'heading' => '',
    'subheading' => '',
    'image' => null,
    'cta_text' => '',
    'cta_url' => '#',
    'alignment' => 'center',
])

@php
    $alignClass = match ($alignment) {
        'left' => 'text-left items-start',
        'right' => 'text-right items-end',
        default => 'text-center items-center',
    };
@endphp

<section class="relative py-16 lg:py-24">
    @if ($image)
        <div class="absolute inset-0 z-0">
            <img src="{{ $image }}" alt="" class="h-full w-full object-cover" />
            <div class="absolute inset-0 bg-black/50"></div>
        </div>
    @endif

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col {{ $alignClass }} max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold {{ $image ? 'text-white' : 'text-gray-900 dark:text-white' }}">
                {{ $heading }}
            </h2>

            @if ($subheading)
                <p class="mt-4 text-xl {{ $image ? 'text-gray-200' : 'text-gray-600 dark:text-gray-300' }}">
                    {{ $subheading }}
                </p>
            @endif

            @if ($cta_text)
                <div class="mt-8">
                    <a
                        href="{{ $cta_url }}"
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    >
                        {{ $cta_text }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
