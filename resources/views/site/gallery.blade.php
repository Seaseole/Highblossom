<x-layouts::site title="Gallery">
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Portfolio</div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                    Our Work
                </h1>
                <p class="text-lg text-[#A1A1AA] leading-relaxed">
                    Browse our portfolio of precision installations across automotive, heavy machinery, and fleet sectors.
                </p>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="py-24 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <!-- Category Filters -->
            <div class="flex flex-wrap justify-center gap-3 mb-16">
                <a href="{{ route('gallery') }}" class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all {{ !$category ? 'bg-[#DC2626] text-white' : 'glass-card text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/[0.06]' }}">
                    All Projects
                </a>
                @foreach ($categories as $cat)
                <a href="{{ route('gallery', ['category' => $cat]) }}" class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all {{ $category === $cat ? 'bg-[#DC2626] text-white' : 'glass-card text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/[0.06]' }}">
                    {{ str_replace('_', ' ', ucfirst($cat)) }}
                </a>
                @endforeach
            </div>

            <!-- Masonry Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-[300px]">
                @forelse ($images as $index => $image)
                    @php
                        $isFeatured = $image->is_featured || $index === 0;
                        $rowSpan = $isFeatured ? 'md:row-span-2' : '';
                    @endphp
                    <div class="group relative overflow-hidden rounded-2xl cursor-pointer {{ $rowSpan }} {{ $index % 4 === 0 ? 'lg:row-span-2' : '' }}">
                        <img alt="{{ $image->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $image->image_url }}" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/90 via-[#0A0A0F]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="text-[#DC2626] text-xs font-semibold uppercase tracking-wider">{{ str_replace('_', ' ', ucfirst($image->category)) }}</span>
                            <h3 class="text-[#FAFAFA] text-xl font-bold font-headline mt-1">{{ $image->title }}</h3>
                            @if ($image->description)
                                <p class="text-[#A1A1AA] text-sm mt-2 line-clamp-2">{{ $image->description }}</p>
                            @endif
                            <div class="mt-4 flex items-center gap-3">
                                <button onclick="openLightbox({{ $index }})" class="text-xs font-bold text-white bg-[#DC2626] px-4 py-2 rounded-full hover:bg-[#B91C1C] transition-colors">
                                    Quick View
                                </button>
                                <a href="{{ route('gallery.show', $image) }}" class="text-xs font-bold text-[#FAFAFA] bg-white/10 backdrop-blur-md px-4 py-2 rounded-full hover:bg-white/20 transition-colors">
                                    Project Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Placeholder fallback -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 flex flex-col items-center justify-center py-20">
                        <img src="{{ asset('storage/placeholder.gif') }}" alt="Gallery placeholder" class="max-w-md w-full h-auto rounded-2xl mb-8">
                        <div class="text-center">
                            <h3 class="text-[#FAFAFA] text-2xl font-bold font-headline mb-2">No Gallery Items Yet</h3>
                            <p class="text-[#A1A1AA]">Check back soon to see our latest projects</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Load More -->
            @if ($images->hasMorePages())
            <div class="text-center mt-16">
                <a href="{{ route('gallery', array_merge(request()->all(), ['page' => $images->currentPage() + 1])) }}" class="btn-premium">
                    <span>Load More Projects</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 bg-gradient-to-b from-[#0A0A0F] to-[#121218] border-t border-white/5">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Performance Metrics</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-2">Vehicles Serviced</div>
                    <div class="text-5xl font-bold text-[#FAFAFA] font-headline">2,500<span class="text-[#DC2626]">+</span></div>
                </div>
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="text-[#A1A1AA] text-sm font-semibold uppercase tracking-wider mb-2">Heavy Machines</div>
                    <div class="text-5xl font-bold text-[#FAFAFA] font-headline">150<span class="text-[#DC2626]">+</span></div>
                </div>
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="text-[#A1A1AA] text-sm font-semibold uppercase tracking-wider mb-2">Fleet Accounts</div>
                    <div class="text-5xl font-bold text-[#FAFAFA] font-headline">45<span class="text-[#DC2626]">+</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox -->
    <div id="lightbox" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0A0A0F]/95 backdrop-blur-xl" onclick="closeLightbox()"></div>
        <button onclick="closeLightbox()" class="absolute top-6 right-6 z-10 w-12 h-12 rounded-full glass-card flex items-center justify-center hover:bg-white/[0.1] transition-colors">
            <svg class="w-6 h-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <button onclick="prevImage()" class="absolute left-6 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full glass-card flex items-center justify-center hover:bg-white/[0.1] transition-colors">
            <svg class="w-6 h-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button onclick="nextImage()" class="absolute right-6 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full glass-card flex items-center justify-center hover:bg-white/[0.1] transition-colors">
            <svg class="w-6 h-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        <div class="relative h-full flex items-center justify-center p-20">
            <div class="flex flex-col lg:flex-row items-center gap-8 max-w-6xl">
                <img id="lightbox-img" src="" alt="" class="max-h-[70vh] max-w-full object-contain rounded-2xl">
                <div id="lightbox-info" class="lg:max-w-md text-center lg:text-left"></div>
            </div>
        </div>
    </div>
</x-layouts::site>

@push('scripts')
<script>
    @php
        $galleryData = $images->map(fn($img) => [
            'src' => $img->image_url,
            'title' => $img->title,
            'category' => $img->category,
            'description' => $img->description,
            'location_address' => $img->location_address,
            'google_maps_url' => $img->google_maps_url,
        ])->toArray();
    @endphp

    const galleryImages = @json($galleryData);

    let currentImageIndex = 0;

    function openLightbox(index) {
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        const infoDiv = document.getElementById('lightbox-info');
        currentImageIndex = index;

        if (galleryImages.length > 0 && index < galleryImages.length) {
            img.src = galleryImages[index].src;
            img.alt = galleryImages[index].title;

            // Update info section
            let infoHtml = '<div class="text-[#DC2626] text-xs font-semibold uppercase tracking-wider">' + galleryImages[index].category + '</div>';
            infoHtml += '<h3 class="text-[#FAFAFA] text-2xl font-bold font-headline mt-1">' + galleryImages[index].title + '</h3>';

            if (galleryImages[index].description) {
                infoHtml += '<p class="text-[#A1A1AA] mt-2">' + galleryImages[index].description + '</p>';
            }

            if (galleryImages[index].location_address && galleryImages[index].google_maps_url) {
                infoHtml += '<div class="mt-4 flex items-center">';
                infoHtml += '<svg class="w-5 h-5 text-[#DC2626] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                infoHtml += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>';
                infoHtml += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>';
                infoHtml += '</svg>';
                infoHtml += '<a href="' + galleryImages[index].google_maps_url + '" target="_blank" rel="noopener noreferrer" class="text-[#FAFAFA] hover:text-[#DC2626] transition-colors">';
                infoHtml += galleryImages[index].location_address;
                infoHtml += '</a>';
                infoHtml += '</div>';
            }

            infoDiv.innerHTML = infoHtml;
        } else {
            // Fallback for static images
            const fallbackImages = [
                { src: 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=80' },
                { src: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&q=80' },
                { src: 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600&q=80' },
                { src: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&q=80' },
                { src: 'https://images.unsplash.com/photo-1617788138017-80ad40651399?w=600&q=80' },
                { src: 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=600&q=80' }
            ];
            img.src = fallbackImages[index].src;
            infoDiv.innerHTML = '';
        }

        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function nextImage() {
        const total = Math.max(galleryImages.length, 6);
        currentImageIndex = (currentImageIndex + 1) % total;
        openLightbox(currentImageIndex);
    }

    function prevImage() {
        const total = Math.max(galleryImages.length, 6);
        currentImageIndex = (currentImageIndex - 1 + total) % total;
        openLightbox(currentImageIndex);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
    });
</script>
@endpush
