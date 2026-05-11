@props([
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'id' => null,
    'name' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
])

@php
$id = $id ?? 'input-' . md5((string) $name);
$name = $name ?? $id;
$baseClasses = 'block w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#FAFAFA] text-sm placeholder-[#71717A] transition-all duration-300 ease-out-expo focus:outline-none focus:border-[#DC2626]/50 focus:ring-4 focus:ring-[#DC2626]/10 focus:bg-white/8';
$disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
$errorClasses = $error ? 'border-red-500 focus:border-red-500 focus:ring-red-500/10' : '';
@endphp

<div {{ $attributes->except(['type', 'id', 'name', 'value', 'placeholder', 'required', 'disabled'])->class('space-y-2') }}>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-[#FAFAFA]/70 ml-1">
            {{ $label }}
            @if($required) <span class="text-[#DC2626] ml-0.5">*</span> @endif
        </label>
    @endif
    
    <div class="relative">
        <input
            {{ $attributes->only(['type', 'id', 'name', 'value', 'placeholder', 'required', 'disabled'])->merge([
                'type' => $type,
                'id' => $id,
                'name' => $name,
                'value' => $value,
                'placeholder' => $placeholder,
                'required' => $required,
                'disabled' => $disabled,
                'class' => "$baseClasses $disabledClasses $errorClasses"
            ]) }}
        >
        
        @if($error)
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>
    
    @if($error)
        <p class="mt-1.5 text-xs font-medium text-red-500 ml-1 animate-in fade-in slide-in-from-top-1 duration-200">
            {{ $error }}
        </p>
    @endif
</div>
