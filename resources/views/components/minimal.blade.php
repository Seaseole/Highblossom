@props([
    'status' => 500,
    'title' => 'Server Error',
    'description' => 'Something went wrong on our end. Please try again later.',
    'actionText' => 'Go Home',
    'actionUrl' => '/',
])

<x-error-layout :title="$title">
    <div class="w-full max-w-lg mx-auto">
        <!-- Glass Card -->
        <div 
            class="bg-[#16161D]/80 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl shadow-[#0A0A0F]/50 p-8 md:p-12 text-center animate-error-entrance"
        >
            @php
                $businessLogo = App\Models\CompanySetting::get('business_logo', '');
                $logoText = App\Models\CompanySetting::get('logo_text', 'Highblossom');
            @endphp

            @if($businessLogo)
                <div class="flex justify-center mb-6">
                    <img 
                        src="{{ Storage::url($businessLogo) }}" 
                        alt="{{ $logoText }}" 
                        class="h-16 w-auto object-contain rounded-lg shadow-lg"
                    >
                </div>
            @endif

            <!-- Status Code -->
            <h1 class="font-headline text-7xl md:text-8xl font-bold text-[#DC2626] mb-4">
                {{ $status }}
            </h1>

            <!-- Title -->
            <h2 class="font-headline text-2xl md:text-3xl font-semibold text-[#FAFAFA] mb-4">
                {{ $title }}
            </h2>

            <!-- Description -->
            <p class="text-[#eeeef3] text-base md:text-lg mb-8 leading-relaxed">
                {{ $description }}
            </p>

            <!-- Action Button -->
            <a 
                href="{{ $actionUrl }}"
                class="inline-flex items-center justify-center gap-2 bg-[#DC2626] hover:bg-[#B91C1C] text-white font-semibold px-8 py-3 rounded-full transition-all duration-200 shadow-lg shadow-[#DC2626]/20 hover:shadow-xl hover:shadow-[#DC2626]/30 hover:-translate-y-0.5 active:scale-[0.97]"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ $actionText }}
            </a>
        </div>
    </div>
</x-error-layout>
