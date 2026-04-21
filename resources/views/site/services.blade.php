<x-layouts::site title="Our Services">
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">{{ __('services.label') }}</div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                        {!! __('services.title') !!}
                    </h1>
                    <p class="text-lg text-[#A1A1AA] leading-relaxed max-w-lg">
                        {{ __('services.description') }}
                    </p>
                </div>
                <div class="relative">
                    <div class="glass-card rounded-2xl p-8">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-[#DC2626] font-headline">6+</div>
                                <div class="text-[#71717A] text-sm mt-1">{{ __('services.service_types') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-[#DC2626] font-headline">24h</div>
                                <div class="text-[#71717A] text-sm mt-1">{{ __('services.emergency_response') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-[#DC2626] font-headline">Same</div>
                                <div class="text-[#71717A] text-sm mt-1">{{ __('services.day_service') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-[#DC2626] font-headline">OEM</div>
                                <div class="text-[#71717A] text-sm mt-1">{{ __('services.quality_parts') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-24 lg:py-32 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            @forelse ($services as $index => $service)
                @if ($index % 2 === 0)
                    <!-- Image Left, Content Right -->
                    <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center mb-20 last:mb-0">
                        <div class="relative group overflow-hidden rounded-2xl">
                            <img
                                src="{{ $service->full_image_url ?? asset('storage/placeholder.gif') }}"
                                alt="{{ $service->title }}"
                                class="w-full h-80 lg:h-96 object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/80 to-transparent"></div>
                            <div class="absolute bottom-6 left-6">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/20 border border-[#DC2626]/30">
                                    <span class="text-[#DC2626] text-xs font-semibold uppercase">{{ __('services.service_label') }} {{ sprintf('%02d', $index + 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline mb-4">{{ $service->title }}</h2>
                            <p class="text-[#A1A1AA] text-lg leading-relaxed mb-6">{{ $service->short_description }}</p>
                            @if ($service->features && count($service->features) > 0)
                            <ul class="space-y-3 mb-8">
                                @foreach ($service->features as $feature)
                                <li class="flex items-center gap-3 text-[#FAFAFA]">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            <a href="{{ route('quote') }}" class="btn-premium">
                                <span>{{ __('services.get_quote_service') }}</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Content Left, Image Right -->
                    <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center mb-20 last:mb-0">
                        <div class="order-2 lg:order-1">
                            <h2 class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline mb-4">{{ $service->title }}</h2>
                            <p class="text-[#A1A1AA] text-lg leading-relaxed mb-6">{{ $service->short_description }}</p>
                            @if ($service->features && count($service->features) > 0)
                            <ul class="space-y-3 mb-8">
                                @foreach ($service->features as $feature)
                                <li class="flex items-center gap-3 text-[#FAFAFA]">
                                    <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            <a href="{{ route('quote') }}" class="btn-premium">
                                <span>{{ __('services.get_quote_service') }}</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="relative group overflow-hidden rounded-2xl order-1 lg:order-2">
                            <img
                                src="{{ $service->full_image_url ?? asset('storage/placeholder.gif') }}"
                                alt="{{ $service->title }}"
                                class="w-full h-80 lg:h-96 object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/80 to-transparent"></div>
                            <div class="absolute bottom-6 left-6">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/20 border border-[#DC2626]/30">
                                    <span class="text-[#DC2626] text-xs font-semibold uppercase">{{ __('services.service_label') }} {{ sprintf('%02d', $index + 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 flex flex-col items-center justify-center py-20">
                    <img src="{{ asset('storage/placeholder.gif') }}" alt="Services placeholder" class="max-w-md w-full h-auto rounded-2xl mb-8">
                    <div class="text-center">
                        <h3 class="text-[#FAFAFA] text-2xl font-bold font-headline mb-2">{{ __('services.no_services') }}</h3>
                        <p class="text-[#A1A1AA]">{{ __('services.no_services_desc') }}</p>
                    </div>
                </div>
            @endforelse

            <!-- Load More -->
            @if (isset($services) && $services->hasMorePages())
            <div class="text-center mt-16">
                <a href="{{ route('services', ['page' => $services->currentPage() + 1]) }}" class="btn-ghost">
                    <span>{{ __('services.load_more') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-24 lg:py-32 bg-[#121218]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">{{ __('services.how_it_works') }}</div>
                <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight">
                    {{ __('services.process_title') }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="relative">
                    <div class="glass-card rounded-2xl p-8 text-center relative z-10">
                        <div class="w-16 h-16 rounded-full bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6">
                            <span class="text-2xl font-bold text-[#DC2626] font-headline">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">{{ __('services.step_1_title') }}</h3>
                        <p class="text-[#71717A] text-sm leading-relaxed">
                            {{ __('services.step_1_desc') }}
                        </p>
                    </div>
                    <div class="hidden md:block absolute top-1/2 -right-4 w-8 h-[2px] bg-[#DC2626]/30"></div>
                </div>

                <div class="relative">
                    <div class="glass-card rounded-2xl p-8 text-center relative z-10">
                        <div class="w-16 h-16 rounded-full bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6">
                            <span class="text-2xl font-bold text-[#DC2626] font-headline">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">{{ __('services.step_2_title') }}</h3>
                        <p class="text-[#71717A] text-sm leading-relaxed">
                            {{ __('services.step_2_desc') }}
                        </p>
                    </div>
                    <div class="hidden md:block absolute top-1/2 -right-4 w-8 h-[2px] bg-[#DC2626]/30"></div>
                </div>

                <div>
                    <div class="glass-card rounded-2xl p-8 text-center">
                        <div class="w-16 h-16 rounded-full bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6">
                            <span class="text-2xl font-bold text-[#DC2626] font-headline">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">{{ __('services.step_3_title') }}</h3>
                        <p class="text-[#71717A] text-sm leading-relaxed">
                            {{ __('services.step_3_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 lg:py-32 bg-gradient-to-b from-[#121218] to-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-12 lg:p-16 text-center relative overflow-hidden">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#DC2626]/10 rounded-full blur-[100px]"></div>

                <div class="relative z-10">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                        {{ __('services.cta_title') }}
                    </h2>
                    <p class="text-lg text-[#A1A1AA] max-w-2xl mx-auto mb-10">
                        {{ __('services.cta_description') }}
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('quote') }}" class="btn-premium glow-red text-lg px-8 py-4">
                            <span>{{ __('services.request_quote') }}</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        @if ($primaryPhone)
                        <a href="tel:{{ str_replace([' ', '-', '(', ')'], '', $primaryPhone) }}" class="btn-ghost text-lg px-8 py-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ $primaryPhone }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts::site>
