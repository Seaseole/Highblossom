<x-layouts::site :title="$galleryImage->title . ' | Project Details'">
    <!-- Project Hero Section -->
    <section class="relative pt-32 pb-20 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Image Column -->
                <div class="lg:w-2/3">
                    <div class="relative group overflow-hidden rounded-3xl glass-card border border-white/5">
                        <img 
                            src="{{ $galleryImage->image_url }}" 
                            alt="{{ $galleryImage->title }}" 
                            class="w-full aspect-[4/3] object-cover transition-transform duration-700 group-hover:scale-105"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/60 via-transparent to-transparent"></div>
                    </div>
                </div>

                <!-- Info Column -->
                <div class="lg:w-1/3 flex flex-col justify-center">
                    <nav class="flex items-center gap-2 text-sm text-[#A1A1AA] mb-8">
                        <a href="{{ route('gallery') }}" class="hover:text-[#DC2626] transition-colors">Gallery</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-[#DC2626]">{{ str_replace('_', ' ', ucfirst($galleryImage->category)) }}</span>
                    </nav>

                    <h1 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                        {{ $galleryImage->title }}
                    </h1>

                    @if($galleryImage->description)
                        <div class="prose prose-invert max-w-none mb-10">
                            <p class="text-lg text-[#A1A1AA] leading-relaxed">
                                {{ $galleryImage->description }}
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 mb-10">
                        <div class="glass-card rounded-2xl p-6 border border-white/5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-[#DC2626] uppercase tracking-wider mb-1">Installation Site</div>
                                    <div class="text-[#FAFAFA] font-medium">{{ $galleryImage->location_address ?? 'Precision On-Site Fitment' }}</div>
                                    @if($galleryImage->google_maps_url)
                                        <a href="{{ $galleryImage->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-sm text-[#A1A1AA] hover:text-[#DC2626] mt-2 transition-colors">
                                            <span>Open in Google Maps</span>
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="glass-card rounded-2xl p-6 border border-white/5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#DC2626]/10 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-[#DC2626] uppercase tracking-wider mb-1">Quality Assurance</div>
                                    <div class="text-[#FAFAFA] font-medium">SABS Approved & Lifetime Workmanship Warranty</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('quote') }}" class="btn-premium w-full justify-center">
                        <span>Request Similar Service</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Projects Section -->
    @if($relatedImages->count() > 0)
        <section class="py-24 bg-[#0A0A0F] border-t border-white/5">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Portfolio</div>
                        <h2 class="text-3xl md:text-4xl font-bold text-[#FAFAFA] font-headline tracking-tight">
                            Related Projects
                        </h2>
                    </div>
                    <a href="{{ route('gallery', ['category' => $galleryImage->category]) }}" class="hidden md:flex items-center gap-2 text-[#A1A1AA] hover:text-[#DC2626] transition-colors font-semibold">
                        <span>View All {{ str_replace('_', ' ', ucfirst($galleryImage->category)) }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedImages as $related)
                        <a href="{{ route('gallery.show', $related) }}" class="group block">
                            <div class="relative overflow-hidden rounded-2xl aspect-[4/3] glass-card border border-white/5 mb-4">
                                <img 
                                    src="{{ $related->image_url }}" 
                                    alt="{{ $related->title }}" 
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <h3 class="text-[#FAFAFA] font-bold font-headline group-hover:text-[#DC2626] transition-colors">
                                {{ $related->title }}
                            </h3>
                            <p class="text-sm text-[#A1A1AA] mt-1">{{ str_replace('_', ' ', ucfirst($related->category)) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-b from-[#0A0A0F] to-[#121218] border-t border-white/5">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="glass-card rounded-[2.5rem] p-12 md:p-20 text-center relative overflow-hidden border border-white/5">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,rgba(220,38,38,0.05)_0,transparent_70%)]"></div>
                
                <div class="relative z-10 max-w-3xl mx-auto">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-8">
                        Ready for a <span class="text-[#DC2626]">Professional</span> Fitment?
                    </h2>
                    <p class="text-xl text-[#A1A1AA] mb-12 leading-relaxed">
                        Whether it's a luxury sedan or heavy mining equipment, our expert team provides precision installation anywhere in Botswana.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                        <a href="{{ route('quote') }}" class="btn-premium w-full sm:w-auto">
                            <span>Get a Free Quote</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="{{ route('contact') }}" class="btn-ghost w-full sm:w-auto">
                            <span>Contact Our Team</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts::site>
