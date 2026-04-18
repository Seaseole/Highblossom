<x-layouts::site :title="$companyName.' | Precision Automotive Glass'">
    <!-- Hero Section - Cinematic Dark -->
    <header class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#0A0A0F]">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img
                alt="Premium automotive glass installation"
                class="w-full h-full object-cover opacity-40"
                src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1920&q=80"
                loading="eager"
            >
            <div class="absolute inset-0 bg-gradient-to-b from-[#0A0A0F] via-[#0A0A0F]/80 to-[#0A0A0F]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0F] via-transparent to-[#0A0A0F]/50"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-[1400px] mx-auto px-6 lg:px-8 pt-32 pb-20">
            <div class="max-w-3xl">
                {{-- Trust Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-8 animate-fade-up">
                    <span class="w-2 h-2 rounded-full bg-[#DC2626] animate-pulse"></span>
                    <span class="text-[#A1A1AA] text-sm font-medium">Gaborone's Trusted Auto Glass Specialists</span>
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-[#FAFAFA] font-headline tracking-tight leading-[1.1] mb-6 animate-fade-up" style="animation-delay: 100ms;">
                    Precision<br>
                    <span class="gradient-text-accent">Glass</span> Works
                </h1>

                {{-- Subhead --}}
                <p class="text-lg md:text-xl text-[#A1A1AA] leading-relaxed max-w-xl mb-10 animate-fade-up" style="animation-delay: 200ms;">
                    Premium automotive glass repair, restoration, and replacement for Gaborone's vehicle owners and commercial fleets.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-col sm:flex-row gap-4 animate-fade-up" style="animation-delay: 300ms;">
                    <a href="{{ route('quote') }}" class="btn-premium glow-red-subtle">
                        <span>Get Your Quote</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="{{ route('gallery') }}" class="btn-ghost">
                        <span>View Our Work</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Trust Bar --}}
        <div class="absolute bottom-0 left-0 right-0 z-10 border-t border-white/5 bg-[#0A0A0F]/50 backdrop-blur-sm">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                    <div class="text-center md:text-left">
                        <div class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline">22+</div>
                        <div class="text-[#71717A] text-sm mt-1">Years Experience</div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline">2,500+</div>
                        <div class="text-[#71717A] text-sm mt-1">Vehicles Serviced</div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline">98%</div>
                        <div class="text-[#71717A] text-sm mt-1">On-Time Rate</div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline">Same</div>
                        <div class="text-[#71717A] text-sm mt-1">Day Service</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Services Preview Section -->
    <section id="services" class="py-24 lg:py-32 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-16">
                <div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">What We Do</div>
                    <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight">
                        Our Services
                    </h2>
                </div>
                <a href="{{ route('services') }}" class="btn-ghost">
                    <span>View All Services</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            {{-- Services Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Windscreens --}}
                <div class="glass-card glass-card-hover rounded-2xl p-8 group">
                    <div class="w-14 h-14 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">Windscreens</h3>
                    <p class="text-[#A1A1AA] text-sm leading-relaxed mb-6">
                        OEM-quality installations for all vehicle makes. From minor repairs to complete replacements.
                    </p>
                    <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-[#DC2626] font-semibold text-sm group-hover:gap-3 transition-all">
                        Learn more
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                {{-- Side & Rear --}}
                <div class="glass-card glass-card-hover rounded-2xl p-8 group">
                    <div class="w-14 h-14 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">Side & Rear</h3>
                    <p class="text-[#A1A1AA] text-sm leading-relaxed mb-6">
                        Full replacement of tempered side windows and heated rear screens with precision alignment.
                    </p>
                    <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-[#DC2626] font-semibold text-sm group-hover:gap-3 transition-all">
                        Learn more
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                {{-- Heavy Machinery --}}
                <div class="glass-card glass-card-hover rounded-2xl p-8 group">
                    <div class="w-14 h-14 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">Heavy Machinery</h3>
                    <p class="text-[#A1A1AA] text-sm leading-relaxed mb-6">
                        Specialized toughened glass for mining, construction, and agricultural equipment.
                    </p>
                    <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-[#DC2626] font-semibold text-sm group-hover:gap-3 transition-all">
                        Learn more
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                {{-- Fleet Services --}}
                <div class="glass-card glass-card-hover rounded-2xl p-8 group">
                    <div class="w-14 h-14 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">Fleet Services</h3>
                    <p class="text-[#A1A1AA] text-sm leading-relaxed mb-6">
                        Dedicated fleet maintenance programs with volume pricing and priority scheduling.
                    </p>
                    <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-[#DC2626] font-semibold text-sm group-hover:gap-3 transition-all">
                        Learn more
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Teaser Section -->
    <section id="gallery" class="py-24 lg:py-32 bg-[#121218]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-12">
                <div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">Portfolio</div>
                    <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight">
                        Recent Work
                    </h2>
                </div>
                <a href="{{ route('gallery') }}" class="btn-ghost">
                    <span>View Full Gallery</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            {{-- Gallery Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Featured Large Item --}}
                <div class="lg:col-span-2 lg:row-span-2 relative group overflow-hidden rounded-2xl">
                    <img
                        alt="Heavy machinery glass installation"
                        class="w-full h-full object-cover min-h-[400px] lg:min-h-full transition-transform duration-700 group-hover:scale-105"
                        src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=80"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-[#0A0A0F]/30 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/20 border border-[#DC2626]/30 mb-4">
                            <span class="text-[#DC2626] text-xs font-semibold uppercase">Heavy Machinery</span>
                        </div>
                        <h3 class="text-xl lg:text-2xl font-bold text-[#FAFAFA] font-headline mb-2">Mining Excavator Cabin</h3>
                        <p class="text-[#A1A1AA] text-sm">Custom toughened glass fitment for CAT 320D</p>
                    </div>
                </div>

                {{-- Small Item 1 --}}
                <div class="relative group overflow-hidden rounded-2xl">
                    <img
                        alt="Luxury vehicle windshield replacement"
                        class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-105"
                        src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 mb-3">
                            <span class="text-[#FAFAFA] text-xs font-semibold uppercase">Automotive</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#FAFAFA] font-headline">Luxury Fleet Service</h3>
                    </div>
                </div>

                {{-- Small Item 2 --}}
                <div class="relative group overflow-hidden rounded-2xl">
                    <img
                        alt="Commercial fleet glass service"
                        class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-105"
                        src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800&q=80"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 mb-3">
                            <span class="text-[#FAFAFA] text-xs font-semibold uppercase">Fleet</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#FAFAFA] font-headline">Commercial Fleet</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-24 lg:py-32 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">Why Highblossom</div>
                <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                    The Highblossom Difference
                </h2>
                <p class="text-[#A1A1AA] text-lg">
                    What sets us apart in automotive glass services across Gaborone
                </p>
            </div>

            {{-- Features Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Feature 1 --}}
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">Safety First</h3>
                    <p class="text-[#71717A] text-sm leading-relaxed">
                        OEM-quality glass that meets all safety standards. Your protection is our priority.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">Same-Day Service</h3>
                    <p class="text-[#71717A] text-sm leading-relaxed">
                        Quick turnaround without compromising quality. Get back on the road fast.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">Expert Craftsmanship</h3>
                    <p class="text-[#71717A] text-sm leading-relaxed">
                        22+ years of experience. Certified technicians with specialized training.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">Mobile Service</h3>
                    <p class="text-[#71717A] text-sm leading-relaxed">
                        We come to you. Mobile units serving Gaborone and surrounding areas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 lg:py-32 bg-[#121218]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                {{-- Left Content --}}
                <div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">Testimonials</div>
                    <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-8">
                        What Our Clients Say
                    </h2>

                    @if($testimonials->count() > 0)
                        @php $firstTestimonial = $testimonials->first(); @endphp
                        <div class="glass-card rounded-2xl p-8">
                            {{-- Stars --}}
                            <div class="flex gap-1 mb-6">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $firstTestimonial->rating ? 'text-[#DC2626]' : 'text-[#71717A]' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>

                            {{-- Quote --}}
                            <blockquote class="text-xl text-[#FAFAFA] leading-relaxed mb-6">
                                "{{ $firstTestimonial->comment }}"
                            </blockquote>

                            {{-- Author --}}
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-[#DC2626]/20 flex items-center justify-center">
                                    <span class="text-[#DC2626] font-bold text-lg">{{ substr($firstTestimonial->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-[#FAFAFA] font-semibold">{{ $firstTestimonial->name }}</div>
                                    @if($firstTestimonial->company)
                                        <div class="text-[#71717A] text-sm">{{ $firstTestimonial->company }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="glass-card rounded-2xl p-8 text-center">
                            <div class="text-[#71717A] mb-4">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="text-[#A1A1AA]">Be the first to share your experience with us.</p>
                        </div>
                    @endif
                </div>

                {{-- Right Stats --}}
                <div class="grid grid-cols-2 gap-6">
                    <div class="glass-card rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-[#FAFAFA] font-headline mb-2">{{ $testimonials->count() }}+</div>
                        <div class="text-[#71717A] text-sm">Happy Clients</div>
                    </div>
                    <div class="glass-card rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-[#FAFAFA] font-headline mb-2">4.9</div>
                        <div class="text-[#71717A] text-sm">Average Rating</div>
                    </div>
                    <div class="glass-card rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-[#FAFAFA] font-headline mb-2">98%</div>
                        <div class="text-[#71717A] text-sm">Recommend Us</div>
                    </div>
                    <div class="glass-card rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-[#FAFAFA] font-headline mb-2">24h</div>
                        <div class="text-[#71717A] text-sm">Response Time</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 lg:py-32 bg-gradient-to-b from-[#121218] to-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-12 lg:p-16 text-center relative overflow-hidden">
                {{-- Background Glow --}}
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#DC2626]/10 rounded-full blur-[100px]"></div>

                <div class="relative z-10">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                        Ready for Crystal<br>Clear Vision?
                    </h2>
                    <p class="text-lg text-[#A1A1AA] max-w-2xl mx-auto mb-10">
                        Get a free quote today. Our team is ready to help with all your automotive glass needs.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('quote') }}" class="btn-premium glow-red text-lg px-8 py-4">
                            <span>Get Your Free Quote</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        @if($primaryPhone)
                        <a href="tel:{{ $primaryPhone->phone_number }}" class="btn-ghost text-lg px-8 py-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ $primaryPhone->formatted_number }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Info Section -->
    <section class="py-12 border-t border-white/5 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-[#DC2626]/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[#FAFAFA] font-semibold text-sm">{{ $companyAddress }}</div>
                        <div class="text-[#71717A] text-xs">Mon-Fri: 8:00 AM - 5:30 PM</div>
                    </div>
                </div>

                @if($primaryPhone)
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-[#DC2626]/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[#FAFAFA] font-semibold text-sm">{{ $primaryPhone->formatted_number }}</div>
                        <div class="text-[#71717A] text-xs">Call for immediate assistance</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts::site>
