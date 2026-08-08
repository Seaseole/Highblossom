@props([
    'status' => 500,
    'title' => 'Server Error',
    'description' => 'Something went wrong on our end. Please try again later.',
    'actionText' => 'Go Home',
    'actionUrl' => '/',
])

<x-error-layout :title="$title">
    <div class="mx-auto w-full max-w-lg">
        <!-- Glass Card -->
        <div class="animate-error-entrance rounded-2xl border border-white/10 bg-[#16161D]/80 p-8 text-center shadow-2xl shadow-[#0A0A0F]/50 backdrop-blur-xl md:p-12">
            @php
                $businessLogo = $settings->get('business_logo', '');
                $logoText = $settings->get('logo_text', 'Highblossom');
            @endphp

            @if ($businessLogo)
                <div class="mb-6 flex justify-center">
                    <img
                        src="{{ Storage::url($businessLogo) }}"
                        alt="{{ $logoText }}"
                        class="h-16 w-auto rounded-lg object-contain shadow-lg"
                    />
                </div>
            @endif

            <!-- Status Code -->
            <h1 class="font-headline mb-4 text-7xl font-bold text-[#DC2626] md:text-8xl">{{ $status }}</h1>

            <!-- Title -->
            <h2 class="font-headline mb-4 text-2xl font-semibold text-[#FAFAFA] md:text-3xl">{{ $title }}</h2>

            <!-- Description -->
            <p class="mb-8 text-base leading-relaxed text-[#eeeef3] md:text-lg">{{ $description }}</p>

            <!-- Action Button -->
            <a
                href="{{ $actionUrl }}"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#DC2626] px-8 py-3 font-semibold text-white shadow-lg shadow-[#DC2626]/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#B91C1C] hover:shadow-xl hover:shadow-[#DC2626]/30 active:scale-[0.97]"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ $actionText }}
            </a>
        </div>
    </div>
</x-error-layout>
