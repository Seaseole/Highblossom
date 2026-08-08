@props([
    'heading' => '',
    'subheading' => '',
    'columns' => '3',
    'features' => [],
])

@php
    $features = is_array($features) ? $features : [];
@endphp

@php
    $gridClass = match ($columns) {
        '2' => 'grid-cols-1 md:grid-cols-2',
        '4' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 md:grid-cols-3',
    };
@endphp

<section class="my-8">
    @if ($heading)
        <div class="mb-8 text-center">
            <h3 class="mb-2 text-2xl font-bold text-gray-900 md:text-3xl dark:text-white">{{ $heading }}</h3>
            @if ($subheading)
                <p class="text-gray-600 dark:text-gray-300">{{ $subheading }}</p>
            @endif
        </div>
    @endif

    <div class="grid {{ $gridClass }} gap-6">
        @foreach ($features as $feature)
            <div class="rounded-xl bg-gray-50 p-6 dark:bg-gray-800">
                @if (! empty($feature['icon']))
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($feature['icon'] === 'check-circle')
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            @elseif ($feature['icon'] === 'clock')
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            @elseif ($feature['icon'] === 'shield-check')
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            @else
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            @endif
                        </svg>
                    </div>
                @endif

                <h4 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">{{ $feature['title'] ?? '' }}</h4>

                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $feature['description'] ?? '' }}</p>
            </div>
        @endforeach
    </div>
</section>
