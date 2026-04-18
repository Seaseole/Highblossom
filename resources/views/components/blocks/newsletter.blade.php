@props([
    'heading' => 'Stay Updated',
    'description' => '',
    'button_text' => 'Subscribe',
    'style' => 'inline',
    'background' => 'primary'
])

@php
$containerClass = match($background) {
    'dark' => 'bg-gray-900 text-white',
    'primary' => 'bg-indigo-600 text-white',
    default => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
};

$formClass = $style === 'inline'
    ? 'flex flex-col sm:flex-row gap-3'
    : 'flex flex-col gap-3';

$inputClass = match($background) {
    'dark', 'primary' => 'bg-white/10 border-white/20 text-white placeholder:text-white/60 focus:bg-white/20',
    default => 'border-gray-300 dark:border-gray-600 dark:bg-gray-700',
};

$buttonClass = match($background) {
    'dark' => 'bg-white text-gray-900 hover:bg-gray-100',
    'primary' => 'bg-white text-indigo-600 hover:bg-gray-100',
    default => 'bg-indigo-600 text-white hover:bg-indigo-700',
};
@endphp

<section class="{{ $containerClass }} py-12 px-6 rounded-xl my-8">
    <div class="max-w-2xl mx-auto text-center">
        <h3 class="text-2xl font-bold mb-3">{{ $heading }}</h3>
        @if($description)
            <p class="text-lg opacity-90 mb-6">{{ $description }}</p>
        @endif

        <form
            action="{{ route('contact') }}"
            method="GET"
            class="{{ $formClass }}"
        >
            <input
                type="email"
                name="email"
                placeholder="{{ __('Your email address') }}"
                required
                class="flex-1 px-4 py-3 rounded-lg border {{ $inputClass }} focus:outline-none focus:ring-2 focus:ring-white/30 transition-all"
            >
            <button
                type="submit"
                class="px-6 py-3 rounded-lg font-semibold {{ $buttonClass }} transition-all hover:shadow-lg"
            >
                {{ $button_text }}
            </button>
        </form>

        <p class="text-xs opacity-70 mt-4">
            {{ __('We respect your privacy. Unsubscribe at any time.') }}
        </p>
    </div>
</section>
