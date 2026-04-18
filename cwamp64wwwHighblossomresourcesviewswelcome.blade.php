<x-layouts::site :title="$companyName.' | Precision Automotive Glass'">
    <!-- Hero Section - Swiss Industrial Brutalist -->
    <header class="brutalist relative overflow-hidden" style="background: #F4F4F0;">
        <!-- Technical Metadata Strip -->
        <div class="brutalist-border-b">
            <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
                <span class="brutalist-mono">
                    <span style="color: #E61919;">[</span> HIGHBLOSSOM PTY LTD <span style="color: #E61919;">]</span>
                </span>
                <div class="flex gap-8">
                    <span class="brutalist-mono">[ SINCE 2003 ]</span>
                    <span class="brutalist-mono">[ GABORONE ]</span>
                    <span class="brutalist-mono">[ ISO 9001 ]</span>
                </div>
            </div>
        </div>

        <!-- Main Hero Grid -->
        <div class="brutalist-grid brutalist-grid-2" style="background: #111111;">
            <!-- Left: Typography + CTA -->
            <div class="brutalist-cell-lg flex flex-col justify-between min-h-[70vh]">
                <div>
                    <div class="brutalist-section-label mb-8">/// AUTOMOTIVE GLASS SOLUTIONS</div>
                    <h1 class="brutalist-headline mb-8" style="color: #111111;">
                        PRECISION<br>
                        <span style="color: #E61919;">GLASS</span><br>
                        WORKS
                    </h1>
                    <p class="brutalist-mono-lg mb-12 max-w-md" style="color: #111111; text-transform: none; font-weight: 400;">
                        Premium automotive glass repair, restoration, and replacement for Gaborone's vehicle owners and commercial fleets.
                    </p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('quote') }}" class="brutalist-btn brutalist-btn-accent">
                        [ GET QUOTE ]
                    </a>
                    <a href="{{ route('gallery') }}" class="brutalist-btn">
                        [ VIEW WORK ]
                    </a>
                </div>
            </div>

            <!-- Right: Image -->
            <div class="relative overflow-hidden">
                <img 
                    alt="Premium Car Windshield Installation" 
                    class="brutalist-img w-full h-full absolute inset-0" 
                    src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80" 
                    loading="lazy"
                    style="min-height: 100%; object-fit: cover;"
                >
                <div class="absolute bottom-0 left-0 right-0 p-6" style="background: linear-gradient(to top, rgba(244,244,240,1) 0%, rgba(244,244,240,0) 100%);">
                    <div class="flex items-center gap-4">
                        <span class="brutalist-mono" style="color: #E61919;">+</span>
                        <span class="brutalist-mono-lg">CERTIFIED SAFETY STANDARDS</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Accent Line -->
        <div style="height: 4px; background: #E61919;"></div>
    </header>

    <!-- Trust Bar - Stats Grid -->
    <section class="brutalist brutalist-border-b" style="background: #F4F4F0;">
        <div class="brutalist-grid brutalist-grid-3" style="background: #111111;">
            <div class="brutalist-cell text-center brutalist-border-r" style="border-color: #111111;">
                <div class="brutalist-mono mb-2" style="color: #E61919;">[ YEARS OPERATIONAL ]</div>
                <div class="brutalist-stat brutalist-stat-accent">22</div>
                <div class="brutalist-mono mt-2" style="color: #666;">SINCE 2003</div>
            </div>
            <div class="brutalist-cell text-center brutalist-border-r" style="border-color: #111111;">
                <div class="brutalist-mono mb-2" style="color: #E61919;">[ ON-TIME RATE ]</div>
                <div class="brutalist-stat">98.7<span style="font-size: 0.5em;">%</span></div>
                <div class="brutalist-mono mt-2" style="color: #666;">PRECISION SCHEDULING</div>
            </div>
            <div class="brutalist-cell text-center">
                <div class="brutalist-mono mb-2" style="color: #E61919;">[ LOCATION ]</div>
                <div class="brutalist-stat">GBE</div>
                <div class="brutalist-mono mt-2" style="color: #666;">GABORONE, BOTSWANA</div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="brutalist py-24" style="background: #F4F4F0;">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-16 pb-8 brutalist-border-b gap-8">
                <div>
                    <div class="brutalist-section-label mb-4">[ SERVICE CATALOG ]</div>
                    <h2 class="brutalist-headline-md" style="color: #111111;">
                        WHAT WE<br>DO <span style="color: #E61919;">®</span>
                    </h2>
                </div>
                <a href="{{ route('services') }}" class="brutalist-btn brutalist-arrow">
                    [ VIEW ALL SERVICES ]
                </a>
            </div>

            <!-- Services Grid - Asymmetric 2+1 -->
            <div class="brutalist-grid brutalist-grid-2 mb-1" style="background: #111111;">
                <!-- Windscreens -->
                <div class="brutalist-cell relative group">
                    <div class="brutalist-mono mb-6" style="color: #E61919;">[ CATEGORY: WINDSCREEN ]</div>
                    <h3 class="brutalist-headline-sm mb-4" style="font-size: clamp(1.5rem, 3vw, 2.5rem);">WINDSCREENS</h3>
                    <p class="brutalist-mono-lg mb-8" style="color: #333; text-transform: none;">
                        OEM-quality glass installations for all major vehicle brands. Shatterproof protection with precision fitment.
                    </p>
                    <div class="flex items-center gap-2 brutalist-mono group-hover:gap-4 transition-all">
                        <span style="color: #E61919;">>>>></span>
                        <span>LEARN MORE</span>
                    </div>
                    <div class="absolute top-4 right-4 brutalist-mono" style="color: #ccc;">01</div>
                </div>

                <!-- Side & Rear -->
                <div class="brutalist-cell brutalist-border-l relative group" style="border-color: #111111;">
                    <div class="brutalist-mono mb-6" style="color: #E61919;">[ CATEGORY: SIDE/REAR ]</div>
                    <h3 class="brutalist-headline-sm mb-4" style="font-size: clamp(1.5rem, 3vw, 2.5rem);">SIDE & REAR</h3>
                    <p class="brutalist-mono-lg mb-8" style="color: #333; text-transform: none;">
                        Full replacement of tempered side windows and heated rear screens with precision alignment.
                    </p>
                    <div class="flex items-center gap-2 brutalist-mono group-hover:gap-4 transition-all">
                        <span style="color: #E61919;">>>>></span>
                        <span>LEARN MORE</span>
                    </div>
                    <div class="absolute top-4 right-4 brutalist-mono" style="color: #ccc;">02</div>
                </div>
            </div>

            <!-- Heavy Machinery - Full Width -->
            <div class="brutalist-border-t brutalist-border-b" style="border-color: #111111;">
                <div class="brutalist-cell relative group">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <div class="brutalist-mono mb-6" style="color: #E61919;">[ CATEGORY: INDUSTRIAL ]</div>
                            <h3 class="brutalist-headline-sm mb-4">HEAVY MACHINERY</h3>
                            <p class="brutalist-mono-lg mb-8" style="color: #333; text-transform: none;">
                                Specialized toughened glass for mining, construction, and agricultural equipment. Custom fabricated to specification.
                            </p>
                            <div class="flex items-center gap-2 brutalist-mono group-hover:gap-4 transition-all">
                                <span style="color: #E61919;">>>>></span>
                                <span>LEARN MORE</span>
                            </div>
                        </div>
                        <div class="relative h-64 lg:h-full min-h-[200px]">
                            <img 
                                alt="Heavy Machinery Glass" 
                                class="brutalist-img w-full h-full" 
                                src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80" 
                                loading="lazy"
                            >
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 brutalist-mono" style="color: #ccc;">03</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="brutalist py-24 brutalist-border-t" style="background: #F4F4F0; border-color: #111111;">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-16 pb-8 brutalist-border-b gap-8" style="border-color: #111111;">
                <div>
                    <div class="brutalist-section-label mb-4">[ PORTFOLIO ]</div>
                    <h2 class="brutalist-headline-md" style="color: #111111;">
                        RECENT <span style="color: #E61919;">WORK</span>
                    </h2>
                </div>
                <a href="{{ route('gallery') }}" class="brutalist-btn brutalist-arrow">
                    [ VIEW GALLERY ]
                </a>
            </div>

            <!-- Gallery Grid -->
            <div class="brutalist-grid brutalist-grid-12" style="background: #111111; grid-auto-rows: 200px;">
                <!-- Large Feature - spans 8 columns, 2 rows -->
                <div class="relative overflow-hidden lg:col-span-8 lg:row-span-2 group" style="background: #F4F4F0;">
                    <img 
                        alt="Heavy Machinery Installation" 
                        class="brutalist-img w-full h-full absolute inset-0 transition-transform duration-500 group-hover:scale-105" 
                        src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=80" 
                        loading="lazy"
                    >
                    <div class="absolute bottom-0 left-0 right-0 p-6" style="background: linear-gradient(to top, rgba(17,17,17,0.9) 0%, transparent 100%);">
                        <div class="brutalist-mono" style="color: #E61919;">[ PROJECT: MINING EXCAVATOR ]</div>
                        <div class="text-white brutalist-mono-lg">CUSTOM TOUGHENED GLASS FITMENT</div>
                    </div>
                </div>

                <!-- Top Right - spans 4 columns -->
                <div class="relative overflow-hidden lg:col-span-4 group" style="background: #F4F4F0;">
                    <img 
                        alt="Luxury Car Service" 
                        class="brutalist-img w-full h-full absolute inset-0 transition-transform duration-500 group-hover:scale-105" 
                        src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600&q=80" 
                        loading="lazy"
                    >
                    <div class="absolute bottom-0 left-0 right-0 p-4" style="background: linear-gradient(to top, rgba(17,17,17,0.9) 0%, transparent 100%);">
                        <div class="text-white brutalist-mono">[ LUXURY FLEET ]</div>
                    </div>
                </div>

                <!-- Bottom Right - spans 4 columns -->
                <div class="relative overflow-hidden lg:col-span-4 group" style="background: #F4F4F0;">
                    <img 
                        alt="Windshield Detail" 
                        class="brutalist-img w-full h-full absolute inset-0 transition-transform duration-500 group-hover:scale-105" 
                        src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=600&q=80" 
                        loading="lazy"
                    >
                    <div class="absolute bottom-0 left-0 right-0 p-4" style="background: linear-gradient(to top, rgba(17,17,17,0.9) 0%, transparent 100%);">
                        <div class="text-white brutalist-mono">[ OEM FITMENT ]</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="brutalist py-24 brutalist-border-t" style="background: #F4F4F0; border-color: #111111;">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-0 brutalist-border" style="border-color: #111111; background: #111111;">
                <!-- Left: Header -->
                <div class="brutalist-cell-lg brutalist-border-r flex flex-col justify-between" style="border-color: #111111; background: #F4F4F0;">
                    <div>
                        <div class="brutalist-section-label mb-4">[ CLIENT FEEDBACK ]</div>
                        <h2 class="brutalist-headline-sm mb-8" style="color: #111111;">
                            WHAT THEY<br>SAY <span style="color: #E61919;">©</span>
                        </h2>
                    </div>
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex -space-x-2">
                                @foreach ($testimonials->take(3) as $testimonial)
                                    <div class="w-10 h-10 border-2 flex items-center justify-center" style="background: #F4F4F0; border-color: #111111;">
                                        <span class="brutalist-mono" style="font-size: 0.6rem;">USR</span>
                                    </div>
                                @endforeach
                            </div>
                            <span class="brutalist-mono">{{ $testimonials->count() }}+ CLIENTS</span>
                        </div>
                        <div style="height: 2px; background: #E61919; width: 60px;"></div>
                    </div>
                </div>

                <!-- Right: Testimonial Carousel -->
                <div class="brutalist-cell" style="background: #F4F4F0;">
                    @if ($testimonials->count() > 0)
                        <div id="testimonial-carousel" class="swiper">
                            <div class="swiper-wrapper">
                                @foreach ($testimonials as $testimonial)
                                    <div class="swiper-slide">
                                        <div class="brutalist-border p-8" style="border-color: #111111; background: white;">
                                            <div class="flex gap-1 mb-6">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="brutalist-mono" style="color: {{ $i <= $testimonial->rating ? '#E61919' : '#ccc' }};">[{{ $i <= $testimonial->rating ? '★' : ' ' }}]</span>
                                                @endfor
                                            </div>
                                            <blockquote class="brutalist-mono-lg mb-8" style="color: #111; text-transform: none; line-height: 1.6;">
                                                "{{ $testimonial->comment }}"
                                            </blockquote>
                                            <div class="brutalist-border-t pt-4" style="border-color: #111111;">
                                                <div class="brutalist-mono" style="color: #111;">{{ $testimonial->name }}</div>
                                                <div class="brutalist-mono" style="color: #666;">{{ $testimonial->company }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    @else
                        <div class="brutalist-border p-8 text-center" style="border-color: #111111;">
                            <span class="brutalist-mono-xl block mb-4" style="color: #ccc;">[ NO DATA ]</span>
                            <p class="brutalist-mono">Be the first to submit feedback.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section id="contact" class="brutalist-dark">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="brutalist-section-label mb-4" style="color: #E61919;">[ CONTACT ]</div>
                    <h2 class="brutalist-headline-sm mb-6" style="color: #F4F4F0;">
                        READY TO<br>RESTORE<br>YOUR VIEW?
                    </h2>
                    <p class="brutalist-mono-lg mb-8" style="color: #aaa; text-transform: none;">
                        Visit our Gaborone workshop or book our mobile unit for on-site service.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('bookings.create') }}" class="brutalist-btn" style="border-color: #E61919; background: #E61919; color: white; text-align: center;">
                        [ BOOK MOBILE UNIT ]
                    </a>
                    <a href="tel:+26712345678" class="brutalist-btn" style="text-align: center;">
                        [ CALL SUPPORT ]
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Strip -->
    <section class="brutalist relative" style="background: #F4F4F0; height: 300px;">
        <div class="absolute inset-0 brutalist-img" style="background: url('https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1200&q=80') center/cover; filter: grayscale(100%) contrast(0.8) brightness(1.1); opacity: 0.4;"></div>
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="brutalist-border p-6 text-center" style="background: #F4F4F0; border-color: #111111;">
                <div class="brutalist-mono mb-2" style="color: #E61919;">[ LOCATION ]</div>
                <div class="brutalist-mono-xl" style="color: #111;">PLOT 123, BROADHURST</div>
                <div class="brutalist-mono" style="color: #666;">GABORONE, BOTSWANA</div>
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
