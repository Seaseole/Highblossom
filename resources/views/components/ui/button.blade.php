@props([
    'variant' => 'default',
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
    'disabled' => false,
])

@php
$variantClasses = match($variant) {
    'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500',
    'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-900 focus:ring-gray-500',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700 focus:ring-gray-500',
    default => 'bg-white hover:bg-gray-50 text-gray-900 border border-gray-300 focus:ring-gray-500',
};

$sizeClasses = match($size) {
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    default => 'px-4 py-2 text-sm',
};

$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

<button {{ $attributes->merge([
    'type' => $type,
    'disabled' => $disabled,
    'class' => "$baseClasses $variantClasses $sizeClasses"
]) }}>
    @if($icon)
        <x-ui.icon :name="$icon" class="mr-2" />
    @endif
    {{ $slot }}
</button>
