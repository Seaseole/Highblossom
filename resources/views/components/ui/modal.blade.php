@props([
    'show' => false,
    'maxWidth' => '2xl',
])

@php
$maxWidthClasses = match($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    default => 'max-w-2xl',
};
@endphp

<div 
    x-data="{ show: @js($show) }" 
    x-show="show" 
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    class="relative z-[100]" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
    style="display: none;"
>
    <!-- Backdrop with advanced blur -->
    <div 
        x-show="show" 
        x-transition:enter="ease-out duration-500" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-300" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 bg-[#0A0A0F]/80 backdrop-blur-md transition-opacity" 
        aria-hidden="true"
    ></div>

    <!-- Modal panel -->
    <div class="fixed inset-0 z-[101] overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div 
                x-show="show" 
                x-on:click.away="show = false"
                x-transition:enter="ease-out-expo duration-500" 
                x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave="ease-in-out-quint duration-300" 
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                class="relative transform overflow-hidden rounded-[2rem] bg-[#16161D] border border-white/10 text-left shadow-[0_32px_64px_-16px_rgba(0,0,0,0.6)] transition-all sm:my-8 sm:w-full {{ $maxWidthClasses }}"
            >
                <!-- Feather Overlay Effect -->
                <div class="feather-overlay absolute inset-0 rounded-[2rem]"></div>
                
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
