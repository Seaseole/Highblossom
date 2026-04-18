<x-layouts::site :title="$companyName.' | Precision Automotive Glass'">
    <!-- Hero Section -->
    <header class="relative pb-24 overflow-hidden bg-surface">
        <div class="max-w-7xl mx-auto px-8 grid lg:grid-cols-2 gap-16 items-center">
            <div class="relative z-10">
                <span class="inline-block py-1.5 px-4 mb-6 rounded-full bg-primary-container/10 text-primary font-semibold text-sm tracking-wide">
                    Precision Refraction
                </span>
                <h1 class="text-6xl md:text-7xl font-headline font-semibold text-on-surface tracking-tight leading-[0.95] mb-8 text-wrap-balance">
                    Quality Glass.<br>
                    <span class="text-primary">On Time.</span><br>
                    For Your Peace of Mind
                </h1>
                <p class="text-on-surface-variant text-lg max-w-lg mb-10 leading-relaxed">
                    Premium automotive glass solutions and heavy machinery glazing for Gaborone's most discerning vehicle owners and commercial fleets.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('quote') }}" class="primary-gradient text-on-primary px-8 py-4 rounded-lg font-headline font-semibold text-lg shadow-[0_8px_32px_-8px_rgba(115,8,29,0.4)] hover:shadow-[0_12px_40px_-8px_rgba(115,8,29,0.5)] hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all duration-300 ease-out">
                        Get a Quote
                    </a>
                    <a href="{{ route('gallery') }}" class="glass-card text-on-surface px-8 py-4 rounded-lg font-headline font-semibold text-lg border border-outline-variant/20 flex items-center gap-2 hover:bg-white/90 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all duration-300 ease-out shadow-lg shadow-black/5">
                        <span class="material-symbols-outlined">visibility</span>
                        View Our Work
                    </a>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl relative z-0 bg-surface-container-high">
                    <img alt="Premium Car Windshield Installation" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80" loading="lazy">
                </div>
                <div class="absolute -bottom-10 -left-10 glass-card p-8 rounded-2xl shadow-xl max-w-xs border border-white/20 z-20">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-secondary-container p-3 rounded-full">
                            <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">verified</span>
                        </div>
                        <div class="font-headline font-bold text-on-surface">Certified Safety Standards</div>
                    </div>
                    <p class="text-sm text-on-surface-variant leading-tight">Every installation meets or exceeds OEM specifications for maximum structural integrity.</p>
                </div>
            </div>
        </div>
        <div class="absolute top-0 right-0 -z-10 w-1/2 h-full opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100">
                <defs>
                    <linearGradient id="glass-grad" x1="0%" x2="100%" y1="0%" y2="100%">
                        <stop offset="0%" style="stop-color:var(--color-primary);stop-opacity:1"></stop>
                        <stop offset="100%" style="stop-color:var(--color-primary-container);stop-opacity:1"></stop>
                    </linearGradient>
                </defs>
                <path d="M0,0 L100,0 L100,100 L50,80 L0,100 Z" fill="url(#glass-grad)"></path>
            </svg>
        </div>
    </header>

    <!-- Trust Bar -->
    <section class="bg-surface-container-low py-14">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center md:text-left">
                <div class="flex flex-col md:flex-row items-center gap-6 px-8 border-outline-variant/10 md:border-r last:border-0">
                    <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">history</span>
                    <div>
                        <div class="text-2xl font-headline font-semibold text-on-surface">Since 2003</div>
                        <div class="text-on-surface-variant text-sm font-medium">22 Years of Excellence</div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-6 px-8 border-outline-variant/10 md:border-r last:border-0">
                    <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">schedule</span>
                    <div>
                        <div class="text-2xl font-headline font-semibold text-on-surface">98.7% On Time</div>
                        <div class="text-on-surface-variant text-sm font-medium">Precise Scheduling</div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-6 px-8">
                    <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">location_on</span>
                    <div>
                        <div class="text-2xl font-headline font-semibold text-on-surface">Gaborone-based</div>
                        <div class="text-on-surface-variant text-sm font-medium">Local Expertise</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Teaser -->
    <section id="services" class="py-28 bg-surface">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-20 gap-8">
                <div class="max-w-2xl">
                    <span class="text-primary font-semibold text-sm tracking-wide mb-4 block">What We Do</span>
                    <h2 class="text-4xl md:text-5xl font-headline font-semibold text-on-surface mb-6 leading-[1.1] text-wrap-balance">
                        Precision Services for Every Pane
                    </h2>
                    <p class="text-on-surface-variant text-lg leading-relaxed">
                        We specialize in more than just cars. From heavy industrial machinery to luxury sedans, our precision refraction standards remain consistent.
                    </p>
                </div>
                <a href="#" class="text-primary font-semibold flex items-center gap-2 hover:gap-3 transition-all duration-300 group">
                    View All Services
                    <span class="material-symbols-outlined text-xl transition-transform group-hover:translate-x-1">arrow_forward</span>
                </a>
            </div>
            <!-- Asymmetric 2+1 Grid Layout -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="group p-10 bg-surface-container-low rounded-2xl hover:bg-surface-container-lowest transition-all duration-300 relative overflow-hidden border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:bg-primary/15 transition-all duration-300">
                            <span class="material-symbols-outlined text-2xl text-primary">directions_car</span>
                        </div>
                        <h3 class="text-xl font-headline font-semibold text-on-surface mb-3">Windscreens</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6 text-wrap-pretty">Shatterproof protection with OEM-quality glass installations for all major vehicle brands.</p>
                        <div class="text-primary font-semibold flex items-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity duration-300">
                            Learn more <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5">arrow_outward</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 w-40 h-40 bg-primary/5 -mr-10 -mb-10 rounded-full blur-3xl group-hover:bg-primary/8 transition-colors duration-500"></div>
                </div>
                <div class="group p-10 bg-surface-container-low rounded-2xl hover:bg-surface-container-lowest transition-all duration-300 relative overflow-hidden border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:bg-primary/15 transition-all duration-300">
                            <span class="material-symbols-outlined text-2xl text-primary">sensor_window</span>
                        </div>
                        <h3 class="text-xl font-headline font-semibold text-on-surface mb-3">Side & Rear Glass</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6 text-wrap-pretty">Full replacement of tempered side windows and heated rear screens with precision alignment.</p>
                        <div class="text-primary font-semibold flex items-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity duration-300">
                            Learn more <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5">arrow_outward</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 w-40 h-40 bg-primary/5 -mr-10 -mb-10 rounded-full blur-3xl group-hover:bg-primary/8 transition-colors duration-500"></div>
                </div>
            </div>
            <div class="md:max-w-md md:ml-auto">
                <div class="group p-10 bg-surface-container-low rounded-2xl hover:bg-surface-container-lowest transition-all duration-300 relative overflow-hidden border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:bg-primary/15 transition-all duration-300">
                            <span class="material-symbols-outlined text-2xl text-primary">agriculture</span>
                        </div>
                        <h3 class="text-xl font-headline font-semibold text-on-surface mb-3">Heavy Machinery</h3>
                        <p class="text-on-surface-variant leading-relaxed mb-6 text-wrap-pretty">Specialized toughened glass for mining, construction, and agricultural equipment.</p>
                        <div class="text-primary font-semibold flex items-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity duration-300">
                            Learn more <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5">arrow_outward</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 w-40 h-40 bg-primary/5 -mr-10 -mb-10 rounded-full blur-3xl group-hover:bg-primary/8 transition-colors duration-500"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Preview -->
    <section id="gallery" class="py-28 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-8">
            <div class="text-center mb-20">
                <span class="text-primary font-semibold text-sm tracking-wide mb-4 block">Our Portfolio</span>
                <h2 class="text-4xl md:text-5xl font-headline font-semibold text-on-surface mb-6 leading-[1.1] text-wrap-balance">Mastery in Motion</h2>
                <p class="text-on-surface-variant max-w-xl mx-auto text-lg leading-relaxed">Browse our recent projects showcasing high-end installations across diverse sectors.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-[800px] md:h-[600px]">
                <div class="md:col-span-8 group relative overflow-hidden rounded-2xl cursor-pointer">
                    <img alt="Heavy Machinery Installation" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-out" src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=80" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-on-surface/70 via-on-surface/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-8">
                        <h4 class="text-white font-headline font-semibold text-xl translate-y-2 group-hover:translate-y-0 transition-transform duration-300">Mining Excavator Cabin</h4>
                        <p class="text-white/70 text-sm translate-y-2 group-hover:translate-y-0 transition-transform duration-300 delay-75">Custom Toughened Glass Fitment</p>
                    </div>
                </div>
                <div class="md:col-span-4 grid grid-rows-2 gap-6">
                    <div class="group relative overflow-hidden rounded-2xl cursor-pointer">
                        <img alt="Luxury Car Service" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-out" src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600&q=80" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-on-surface/70 via-on-surface/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                            <h4 class="text-white font-headline font-semibold text-lg translate-y-2 group-hover:translate-y-0 transition-transform duration-300">Luxury Fleet Maintenance</h4>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl cursor-pointer">
                        <img alt="Windshield Detail" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-out" src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=600&q=80" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-on-surface/70 via-on-surface/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                            <h4 class="text-white font-headline font-semibold text-lg translate-y-2 group-hover:translate-y-0 transition-transform duration-300">OEM Fitment Excellence</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-28 bg-surface">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div>
                    <span class="text-primary font-semibold text-sm tracking-wide mb-4 block">Testimonials</span>
                    <h2 class="text-4xl md:text-5xl font-headline font-semibold text-on-surface mb-8 leading-[1.1] text-wrap-balance">
                        What our clients say about the precision.
                    </h2>
                    <div class="flex gap-4 items-center">
                        <div class="flex -space-x-3">
                            @foreach ($testimonials->take(3) as $testimonial)
                                <div class="w-11 h-11 rounded-full border-2 border-surface bg-secondary-container flex items-center justify-center shadow-sm">
                                    <span class="material-symbols-outlined text-secondary text-sm">person</span>
                                </div>
                            @endforeach
                        </div>
                        <span class="text-on-surface-variant font-medium">Join {{ $testimonials->count() }}+ satisfied customers in Gaborone</span>
                    </div>
                </div>
                <div class="relative">
                    @if ($testimonials->count() > 0)
                        <div id="testimonial-carousel" class="swiper">
                            <div class="swiper-wrapper">
                                @foreach ($testimonials as $testimonial)
                                    <div class="swiper-slide">
                                        <div class="glass-card p-10 rounded-2xl border border-white/30 shadow-[0_8px_32px_-12px_rgba(0,0,0,0.15)] relative z-10 backdrop-blur-xl">
                                            <div class="flex gap-1 text-secondary mb-6">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="material-symbols-outlined {{ $i <= $testimonial->rating ? '' : 'text-surface-container-highest' }}" style="font-variation-settings: 'FILL' {{ $i <= $testimonial->rating ? '1' : '0' }};">star</span>
                                                @endfor
                                            </div>
                                            <blockquote class="text-xl text-on-surface leading-relaxed mb-8 text-wrap-pretty">
                                                "{{ $testimonial->comment }}"
                                            </blockquote>
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-on-surface-variant text-sm">person</span>
                                                </div>
                                                <div>
                                                    <div class="font-headline font-semibold text-on-surface text-sm">{{ $testimonial->name }}</div>
                                                    <div class="text-on-surface-variant text-xs">{{ $testimonial->company }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    @else
                        <div class="glass-card p-10 rounded-2xl border border-white/30 shadow-[0_8px_32px_-12px_rgba(0,0,0,0.15)] relative z-10 backdrop-blur-xl">
                            <div class="text-center">
                                <span class="material-symbols-outlined text-4xl text-surface-container-highest mb-4 block">rate_review</span>
                                <p class="text-on-surface-variant">No testimonials yet. Be the first to share your experience.</p>
                            </div>
                        </div>
                    @endif
                    <div class="absolute -top-8 -right-8 w-40 h-40 bg-primary/8 rounded-full blur-3xl -z-10"></div>
                    <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-secondary/8 rounded-full blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Maps & Contact Strip -->
    <section id="contact" class="bg-surface-container-low pt-28">
        <div class="max-w-7xl mx-auto px-8 mb-[-4rem] relative z-20">
            <div class="bg-on-surface p-10 md:p-12 rounded-2xl flex flex-col md:flex-row justify-between items-center gap-8 shadow-[0_24px_60px_-16px_rgba(0,0,0,0.3)]">
                <div class="text-center md:text-left">
                    <h3 class="text-white text-3xl md:text-4xl font-headline font-semibold mb-3 leading-tight text-wrap-balance">Ready to restore your view?</h3>
                    <p class="text-white/60 text-lg">Visit our Gaborone workshop or we'll come to you.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <a href="#" class="primary-gradient text-on-primary px-8 py-4 rounded-lg font-headline font-semibold text-center hover:shadow-[0_12px_40px_-8px_rgba(115,8,29,0.5)] hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all duration-300 ease-out">Book Mobile Unit</a>
                    <a href="tel:+26712345678" class="bg-white/10 text-white hover:bg-white/20 px-8 py-4 rounded-lg font-headline font-semibold text-center hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all duration-300 ease-out">Call Support</a>
                </div>
            </div>
        </div>
        <div class="w-full h-[450px] grayscale opacity-80">
            <div class="w-full h-full bg-surface-container-highest flex items-center justify-center relative overflow-hidden">
                <img alt="Map of Gaborone" class="absolute inset-0 w-full h-full object-cover opacity-30" src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1200&q=80" loading="lazy">
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white shadow-[0_8px_24px_-6px_rgba(115,8,29,0.5)] animate-bounce">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">location_on</span>
                    </div>
                    <div class="mt-4 glass-card px-5 py-2.5 rounded-lg shadow-lg font-semibold text-on-surface text-sm">Find us in Gaborone</div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('#testimonial-carousel')) {
                new Swiper('#testimonial-carousel', {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                });
            }
        });
    </script>
    @endpush
</x-layouts::site>
