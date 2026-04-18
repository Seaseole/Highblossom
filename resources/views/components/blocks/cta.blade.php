@props([
    'heading' => '',
    'text' => '',
    'button_text' => '',
    'button_url' => '#',
    'style' => 'primary'
])

@php
$containerClass = match($style) {
    'dark' => 'bg-gray-900 text-white',
    'primary' => 'bg-indigo-600 text-white',
    default => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
};

$buttonClass = match($style) {
    'dark' => 'bg-white text-gray-900 hover:bg-gray-100',
    'primary' => 'bg-white text-indigo-600 hover:bg-gray-100',
    default => 'bg-indigo-600 text-white hover:bg-indigo-700',
};

$textClass = $style === 'default' ? 'text-gray-600 dark:text-gray-300' : 'text-white/90';
@endphp

<section class="{{ $containerClass }} py-12 px-6 rounded-xl my-8">
    <div class="max-w-3xl mx-auto text-center">
        <h3 class="text-2xl md:text-3xl font-bold mb-4">{{ $heading }}</h3>

        @if($text)
            <p class="text-lg {{ $textClass }} mb-6">{{ $text }}</p>
        @endif

        <a href="{{ $button_url }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-lg shadow-sm {{ $buttonClass }} transition-colors">
            {{ $button_text }}
        </a>
    </div>
</section>
