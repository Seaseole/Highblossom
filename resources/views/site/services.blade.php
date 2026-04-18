<x-layouts::site title="Our Services">
    <!-- Hero Section -->
    <section class="py-16 bg-surface">
        <div class="max-w-7xl mx-auto px-8 text-center">
            <span class="inline-block py-1 px-3 mb-6 rounded-full bg-primary-container/10 text-primary font-bold text-sm tracking-widest uppercase">What We Do</span>
            <h1 class="text-5xl md:text-6xl font-headline font-extrabold text-on-surface tracking-tighter leading-none mb-6">
                Precision Services for <span class="text-primary">Every Pane</span>
            </h1>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto leading-relaxed">
                From luxury vehicles to heavy industrial machinery, we deliver expert glass solutions with unmatched quality and on-time service.
            </p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-24 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="services-grid">
                @forelse ($services as $service)
                <div class="group p-10 bg-surface rounded-2xl hover:bg-surface-container-lowest transition-colors duration-500 relative overflow-hidden border border-outline-variant/10 shadow-lg">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-primary">{{ $service->icon }}</span>
                        </div>
                        <h3 class="text-2xl font-headline font-extrabold text-on-surface mb-4">{{ $service->title }}</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6">
                            {{ $service->short_description }}
                        </p>
                        @if ($service->features && count($service->features) > 0)
                        <ul class="space-y-2 text-sm text-on-surface-variant">
                            @foreach ($service->features as $feature)
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-primary/5 -mr-8 -mb-8 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                </div>
                @empty
                <!-- Static fallback services when no dynamic services exist -->
                <div class="group p-10 bg-surface rounded-2xl hover:bg-surface-container-lowest transition-colors duration-500 relative overflow-hidden border border-outline-variant/10 shadow-lg">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-primary">directions_car</span>
                        </div>
                        <h3 class="text-2xl font-headline font-extrabold text-on-surface mb-4">Windscreen Repair & Replacement</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6">
                            OEM-quality windscreen installations for all vehicle makes and models. From minor chip repairs to complete replacements with ADAS calibration.
                        </p>
                        <ul class="space-y-2 text-sm text-on-surface-variant">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Same-day service available
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                OEM and aftermarket options
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                ADAS calibration included
                            </li>
                        </ul>
                    </div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-primary/5 -mr-8 -mb-8 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                </div>

                <!-- Side & Rear Glass -->
                <div class="group p-10 bg-surface rounded-2xl hover:bg-surface-container-lowest transition-colors duration-500 relative overflow-hidden border border-outline-variant/10 shadow-lg">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-primary">sensor_window</span>
                        </div>
                        <h3 class="text-2xl font-headline font-extrabold text-on-surface mb-4">Side & Rear Window Glass</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6">
                            Full replacement of tempered and laminated side windows, heated rear screens, and quarter glass with precision alignment.
                        </p>
                        <ul class="space-y-2 text-sm text-on-surface-variant">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Tempered safety glass
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Heated rear screen options
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Privacy tinting available
                            </li>
                        </ul>
                    </div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-primary/5 -mr-8 -mb-8 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                </div>

                <!-- Heavy Machinery -->
                <div class="group p-10 bg-surface rounded-2xl hover:bg-surface-container-lowest transition-colors duration-500 relative overflow-hidden border border-outline-variant/10 shadow-lg">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-primary">agriculture</span>
                        </div>
                        <h3 class="text-2xl font-headline font-extrabold text-on-surface mb-4">Heavy Machinery Glass</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6">
                            Specialized toughened glass solutions for mining, construction, and agricultural equipment. Custom cuts for any cabin or enclosure.
                        </p>
                        <ul class="space-y-2 text-sm text-on-surface-variant">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Excavators & bulldozers
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Cranes & lift equipment
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Agricultural machinery
                            </li>
                        </ul>
                    </div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-primary/5 -mr-8 -mb-8 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                </div>

                <!-- Fleet Services -->
                <div class="group p-10 bg-surface rounded-2xl hover:bg-surface-container-lowest transition-colors duration-500 relative overflow-hidden border border-outline-variant/10 shadow-lg">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-primary">local_shipping</span>
                        </div>
                        <h3 class="text-2xl font-headline font-extrabold text-on-surface mb-4">Fleet Services</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6">
                            Dedicated fleet maintenance programs for commercial vehicle operators. Volume discounts and priority scheduling available.
                        </p>
                        <ul class="space-y-2 text-sm text-on-surface-variant">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Volume pricing
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Priority scheduling
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Account management
                            </li>
                        </ul>
                    </div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-primary/5 -mr-8 -mb-8 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                </div>

                <!-- Mobile Service -->
                <div class="group p-10 bg-surface rounded-2xl hover:bg-surface-container-lowest transition-colors duration-500 relative overflow-hidden border border-outline-variant/10 shadow-lg">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-primary">home_repair_service</span>
                        </div>
                        <h3 class="text-2xl font-headline font-extrabold text-on-surface mb-4">Mobile Glass Service</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6">
                            Can't come to us? We'll come to you. Our mobile units serve Gaborone and surrounding areas with full replacement capabilities.
                        </p>
                        <ul class="space-y-2 text-sm text-on-surface-variant">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                On-site replacement
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Gaborone metro area
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Same-day service
                            </li>
                        </ul>
                    </div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-primary/5 -mr-8 -mb-8 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                </div>

                <!-- Emergency Repairs -->
                <div class="group p-10 bg-surface rounded-2xl hover:bg-surface-container-lowest transition-colors duration-500 relative overflow-hidden border border-outline-variant/10 shadow-lg">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-primary">emergency</span>
                        </div>
                        <h3 class="text-2xl font-headline font-extrabold text-on-surface mb-4">Emergency Repairs</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6">
                            Broken glass can't wait. Our emergency response team provides rapid assessment and temporary or permanent solutions.
                        </p>
                        <ul class="space-y-2 text-sm text-on-surface-variant">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                24/7 availability
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Rapid response
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                Temporary solutions
                            </li>
                        </ul>
                    </div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-primary/5 -mr-8 -mb-8 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                </div>
                @endforelse
            </div>

            <!-- Load More -->
            @if ($services->hasMorePages())
            <div class="text-center mt-12">
                <a href="{{ route('services', ['page' => $services->currentPage() + 1]) }}" class="glass-card text-on-surface px-8 py-4 rounded-lg font-headline font-bold border border-outline-variant/20 hover:bg-white/80 transition-colors inline-flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    Load More Services
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-surface">
        <div class="max-w-4xl mx-auto px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-headline font-extrabold text-on-surface mb-6">
                Ready to Get Started?
            </h2>
            <p class="text-on-surface-variant text-lg mb-10">
                Contact us today for a free quote on your glass replacement needs.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('quote') }}" class="primary-gradient text-on-primary px-8 py-4 rounded-lg font-headline font-bold text-lg shadow-xl shadow-primary/20 hover:opacity-90 transition-opacity">
                    Request a Quote
                </a>
                @if ($primaryPhone)
                <a href="tel:{{ $primaryPhone->phone_number }}" class="glass-card text-on-surface px-8 py-4 rounded-lg font-headline font-bold text-lg border border-outline-variant/20 flex items-center gap-2 hover:bg-white/80 transition-colors">
                    <span class="material-symbols-outlined">phone</span>
                    Call Now: {{ $primaryPhone->formatted_number }}
                </a>
                @endif
            </div>
        </div>
    </section>
</x-layouts::site>
