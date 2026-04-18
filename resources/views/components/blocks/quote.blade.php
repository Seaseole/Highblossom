@props([
    'quote' => '',
    'author' => '',
    'title' => '',
    'avatar' => null,
    'style' => 'default'
])

@php
$containerClass = match($style) {
    'large' => 'py-12 border-l-4 border-indigo-500 pl-8',
    'testimonial' => 'bg-gray-50 dark:bg-gray-800 p-8 rounded-xl shadow-sm',
    default => 'py-8 border-l-4 border-gray-300 dark:border-gray-600 pl-6',
};

$quoteClass = match($style) {
    'large' => 'text-2xl md:text-3xl font-light text-gray-900 dark:text-white italic',
    'testimonial' => 'text-lg text-gray-700 dark:text-gray-300',
    default => 'text-xl text-gray-700 dark:text-gray-300 italic',
};
@endphp

<blockquote class="{{ $containerClass }}">
    <p class="{{ $quoteClass }}">
        "{{ $quote }}"
    </p>

    @if($author || $title || $avatar)
        <footer class="mt-6 flex items-center gap-3">
            @if($avatar)
                <img src="{{ $avatar }}" alt="{{ $author }}" class="w-12 h-12 rounded-full object-cover">
            @endif

            <div>
                @if($author)
                    <cite class="not-italic font-semibold text-gray-900 dark:text-white">
                        {{ $author }}
                    </cite>
                @endif

                @if($title)
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $title }}
                    </p>
                @endif
            </div>
        </footer>
    @endif
</blockquote>
