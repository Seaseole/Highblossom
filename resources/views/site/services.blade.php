<x-layouts::site title="Our Services">
    <!-- Hero Section -->
    <section class="relative bg-[#0A0A0F] pt-32 pb-20">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                        {{ __('services.label') }}
                    </div>
                    <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                        {!! __('services.title') !!}
                    </h1>
                    <p class="max-w-lg text-lg leading-relaxed text-[#A1A1AA]">{{ __('services.description') }}</p>
                </div>
                <div class="relative">
                    <div class="glass-card rounded-2xl p-8">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="font-headline text-3xl font-bold text-[#DC2626]">6+</div>
                                <div class="mt-1 text-sm text-[#71717A]">{{ __('services.service_types') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-headline text-3xl font-bold text-[#DC2626]">24h</div>
                                <div class="mt-1 text-sm text-[#71717A]">{{ __('services.emergency_response') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-headline text-3xl font-bold text-[#DC2626]">Same</div>
                                <div class="mt-1 text-sm text-[#71717A]">{{ __('services.day_service') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-headline text-3xl font-bold text-[#DC2626]">OEM</div>
                                <div class="mt-1 text-sm text-[#71717A]">{{ __('services.quality_parts') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="bg-[#0A0A0F] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            @forelse ($services as $index => $service)
                @if ($index % 2 === 0)
                    <!-- Image Left, Content Right -->
                    <div class="mb-20 grid items-center gap-12 last:mb-0 lg:grid-cols-2 lg:gap-20">
                        <div class="group relative overflow-hidden rounded-2xl">
                            <img
                                src="{{ $service->full_image_url ?? asset('placeholder.gif') }}"
                                alt="{{ $service->title }}"
                                class="h-80 w-full object-cover transition-transform duration-700 group-hover:scale-105 lg:h-96"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/80 to-transparent"></div>
                            <div class="absolute bottom-6 left-6">
                                <div class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/30 bg-[#DC2626]/20 px-3 py-1">
                                    <span class="text-xs font-semibold text-[#DC2626] uppercase">{{ __('services.service_label') }} {{ sprintf('%02d', $index + 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="font-headline mb-4 text-3xl font-bold text-[#FAFAFA] md:text-4xl">
                                {{ $service->title }}
                            </h2>
                            <p class="mb-6 text-lg leading-relaxed text-[#A1A1AA]">{{ $service->short_description }}</p>
                            @if ($service->features && count($service->features) > 0)
                                <ul class="mb-8 space-y-3">
                                    @foreach ($service->features as $feature)
                                        <li class="flex items-center gap-3 text-[#FAFAFA]">
                                            <svg class="h-5 w-5 flex-shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <a href="{{ route('quote') }}" class="btn-premium">
                                <span>{{ __('services.get_quote_service') }}</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Content Left, Image Right -->
                    <div class="mb-20 grid items-center gap-12 last:mb-0 lg:grid-cols-2 lg:gap-20">
                        <div class="order-2 lg:order-1">
                            <h2 class="font-headline mb-4 text-3xl font-bold text-[#FAFAFA] md:text-4xl">
                                {{ $service->title }}
                            </h2>
                            <p class="mb-6 text-lg leading-relaxed text-[#A1A1AA]">{{ $service->short_description }}</p>
                            @if ($service->features && count($service->features) > 0)
                                <ul class="mb-8 space-y-3">
                                    @foreach ($service->features as $feature)
                                        <li class="flex items-center gap-3 text-[#FAFAFA]">
                                            <svg class="h-5 w-5 flex-shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <a href="{{ route('quote') }}" class="btn-premium">
                                <span>{{ __('services.get_quote_service') }}</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="group relative order-1 overflow-hidden rounded-2xl lg:order-2">
                            <img
                                src="{{ $service->full_image_url ?? asset('placeholder.gif') }}"
                                alt="{{ $service->title }}"
                                class="h-80 w-full object-cover transition-transform duration-700 group-hover:scale-105 lg:h-96"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/80 to-transparent"></div>
                            <div class="absolute bottom-6 left-6">
                                <div class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/30 bg-[#DC2626]/20 px-3 py-1">
                                    <span class="text-xs font-semibold text-[#DC2626] uppercase">{{ __('services.service_label') }} {{ sprintf('%02d', $index + 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-1 flex flex-col items-center justify-center py-20 md:col-span-2 lg:col-span-3">
                    <img
                        src="{{ asset('placeholder.gif') }}"
                        alt="Services placeholder"
                        class="mb-8 h-auto w-full max-w-md rounded-2xl"
                    />
                    <div class="text-center">
                        <h3 class="font-headline mb-2 text-2xl font-bold text-[#FAFAFA]">
                            {{ __('services.no_services') }}
                        </h3>
                        <p class="text-[#A1A1AA]">{{ __('services.no_services_desc') }}</p>
                    </div>
                </div>
            @endforelse

            <!-- Load More -->
            @if (isset($services) && $services->hasMorePages())
                <div class="mt-16 text-center">
                    <a
                        href="{{ route('services', ['page' => $services->currentPage() + 1]) }}"
                        class="btn-glass px-8 py-4 text-lg"
                    >
                        <span>{{ __('services.load_more') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Process Section -->
    <section class="bg-[#121218] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto mb-16 max-w-2xl text-center">
                <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    {{ __('services.how_it_works') }}
                </div>
                <h2 class="font-headline text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                    {{ __('services.process_title') }}
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="relative">
                    <div class="glass-card relative z-10 rounded-2xl p-8 text-center">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#DC2626]/10">
                            <span class="font-headline text-2xl font-bold text-[#DC2626]">1</span>
                        </div>
                        <h3 class="font-headline mb-3 text-xl font-bold text-[#FAFAFA]">
                            {{ __('services.step_1_title') }}
                        </h3>
                        <p class="text-sm leading-relaxed text-[#71717A]">{{ __('services.step_1_desc') }}</p>
                    </div>
                    <div class="absolute top-1/2 -right-4 hidden h-[2px] w-8 bg-[#DC2626]/30 md:block"></div>
                </div>

                <div class="relative">
                    <div class="glass-card relative z-10 rounded-2xl p-8 text-center">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#DC2626]/10">
                            <span class="font-headline text-2xl font-bold text-[#DC2626]">2</span>
                        </div>
                        <h3 class="font-headline mb-3 text-xl font-bold text-[#FAFAFA]">
                            {{ __('services.step_2_title') }}
                        </h3>
                        <p class="text-sm leading-relaxed text-[#71717A]">{{ __('services.step_2_desc') }}</p>
                    </div>
                    <div class="absolute top-1/2 -right-4 hidden h-[2px] w-8 bg-[#DC2626]/30 md:block"></div>
                </div>

                <div>
                    <div class="glass-card rounded-2xl p-8 text-center">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#DC2626]/10">
                            <span class="font-headline text-2xl font-bold text-[#DC2626]">3</span>
                        </div>
                        <h3 class="font-headline mb-3 text-xl font-bold text-[#FAFAFA]">
                            {{ __('services.step_3_title') }}
                        </h3>
                        <p class="text-sm leading-relaxed text-[#71717A]">{{ __('services.step_3_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-b from-[#121218] to-[#0A0A0F] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="glass-card relative overflow-hidden rounded-3xl p-12 text-center lg:p-16">
                <div class="absolute top-1/2 left-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#DC2626]/10 blur-[100px]"></div>

                <div class="relative z-10">
                    <h2 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                        {{ __('services.cta_title') }}
                    </h2>
                    <p class="mx-auto mb-10 max-w-2xl text-lg text-[#A1A1AA]">{{ __('services.cta_description') }}</p>

                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <a href="{{ route('quote') }}" class="btn-premium glow-red px-8 py-4 text-lg">
                            <span>{{ __('services.request_quote') }}</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        @if ($primaryPhone)
                            <a
                                href="tel:{{ str_replace([' ', '-', '(', ')'], '', $primaryPhone) }}"
                                class="btn-glass px-8 py-4 text-lg"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
