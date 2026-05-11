@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
    'disabled' => false,
])

@php
$variantClasses = match($variant) {
    'primary' => 'bg-[#DC2626] hover:bg-[#B91C1C] text-white shadow-lg shadow-[#DC2626]/20 hover:shadow-xl hover:shadow-[#DC2626]/30',
    'secondary' => 'bg-[#16161D] hover:bg-[#1a1a24] text-[#FAFAFA] border border-white/10',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    'ghost' => 'bg-transparent hover:bg-white/5 text-[#FAFAFA] border border-white/10',
    default => 'bg-[#DC2626] hover:bg-[#B91C1C] text-white shadow-lg shadow-[#DC2626]/20 hover:shadow-xl hover:shadow-[#DC2626]/30',
};

$sizeClasses = match($size) {
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-6 py-2.5 text-sm',
    'lg' => 'px-8 py-3.5 text-base',
    default => 'px-6 py-2.5 text-sm',
};

$baseClasses = 'inline-flex items-center justify-center font-semibold rounded-full transition-all duration-300 ease-out-expo hover:-translate-y-0.5 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:active:scale-100';
@endphp

<button {{ $attributes->merge([
    'type' => $type,
    'disabled' => $disabled,
    'class' => "$baseClasses $variantClasses $sizeClasses"
]) }}>
    @if($icon)
        <x-ui.icon :name="$icon" class="mr-2 w-4 h-4" />
    @endif
    {{ $slot }}
</button>
