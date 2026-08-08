<x-layouts::site title="Gallery">
    <!-- Hero Section -->
    <section class="relative bg-[#0A0A0F] pt-32 pb-20">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    {{ __('gallery.label') }}
                </div>
                <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                    {{ __('gallery.title') }}
                </h1>
                <p class="text-lg leading-relaxed text-[#A1A1AA]">{{ __('gallery.description') }}</p>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="bg-[#0A0A0F] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <!-- Category Filters -->
            <div class="mb-16 flex flex-wrap justify-center gap-3">
                <a
                    href="{{ route('gallery') }}"
                    class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all {{ !$category ? 'bg-[#DC2626] text-white' : 'glass-card text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/[0.06]' }}"
                >
                    {{ __('gallery.all_projects') }}
                </a>
                @foreach ($categories as $cat)
                    <a
                        href="{{ route('gallery', ['category' => $cat->slug]) }}"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all {{ $category === $cat->slug ? 'bg-[#DC2626] text-white' : 'glass-card text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/[0.06]' }}"
                    >
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Masonry Grid -->
            <div class="grid auto-rows-[300px] grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($images as $index => $image)
                    @php
                        $isFeatured = $image->is_featured || $index === 0;
                        $rowSpan = $isFeatured ? 'md:row-span-2' : '';
                    @endphp
                    <div class="group relative overflow-hidden rounded-2xl cursor-pointer {{ $rowSpan }} {{ $index % 4 === 0 ? 'lg:row-span-2' : '' }}">
                        <img
                            alt="{{ $image->title }}"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            src="{{ $image->image_url }}"
                            loading="lazy"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/90 via-[#0A0A0F]/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <div class="absolute right-0 bottom-0 left-0 translate-y-4 p-6 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                            <span class="text-xs font-semibold tracking-wider text-[#DC2626] uppercase">{{ $image->category->name ?? '-' }}</span>
                            <h3 class="font-headline mt-1 text-xl font-bold text-[#FAFAFA]">{{ $image->title }}</h3>
                            @if ($image->description)
                                <p class="mt-2 line-clamp-2 text-sm text-[#A1A1AA]">{{ $image->description }}</p>
                            @endif
                            <div class="mt-4 flex items-center gap-3">
                                <button
                                    onclick="openLightbox({{ $index }})"
                                    class="rounded-full bg-[#DC2626] px-4 py-2 text-xs font-bold text-white transition-colors hover:bg-[#B91C1C]"
                                >
                                    {{ __('gallery.quick_view') }}
                                </button>
                                <a
                                    href="{{ route('gallery.show', $image) }}"
                                    class="rounded-full bg-white/10 px-4 py-2 text-xs font-bold text-[#FAFAFA] backdrop-blur-md transition-colors hover:bg-white/20"
                                >
                                    Project Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Placeholder fallback -->
                    <div class="col-span-1 flex flex-col items-center justify-center pt-16 pb-20 md:col-span-2 md:pt-32 lg:col-span-3">
                        <img
                            src="{{ asset('placeholder.gif') }}"
                            alt="Gallery placeholder"
                            class="mb-8 h-auto w-full max-w-md rounded-2xl"
                        />
                        <div class="text-center">
                            <h3 class="font-headline mb-2 text-2xl font-bold text-[#FAFAFA]">No Gallery Items Yet</h3>
                            <p class="text-[#A1A1AA]">Check back soon to see our latest projects</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Load More -->
            @if ($images->hasMorePages())
                <div class="mt-16 text-center">
                    <a
                        href="{{ route('gallery', array_merge(request()->all(), ['page' => $images->currentPage() + 1])) }}"
                        class="btn-glass px-8 py-4 text-lg"
                    >
                        <span>Load More Projects</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Stats Section -->
    @if (! empty($galleryMetrics))
        <section class="border-t border-white/5 bg-gradient-to-b from-[#0A0A0F] to-[#121218] py-24">
            <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
                <div class="mb-12 text-center">
                    <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                        Performance Metrics
                    </div>
                </div>
                <div class="grid grid-cols-1 {{ count($galleryMetrics) >= 3 ? 'md:grid-cols-3' : (count($galleryMetrics) === 2 ? 'md:grid-cols-2' : '') }} gap-6">
                    @foreach ($galleryMetrics as $metric)
                        <div class="glass-card rounded-2xl p-8 text-center">
                            <div class="mb-2 text-sm font-semibold tracking-wider text-[#A1A1AA] uppercase">
                                {{ $metric['label'] }}
                            </div>
                            <div class="font-headline text-5xl font-bold text-[#FAFAFA]">
                                {{ $metric['value'] }}<span class="text-[#DC2626]">{{ $metric['suffix'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Lightbox -->
    <div id="lightbox" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0A0A0F]/95 backdrop-blur-xl" onclick="closeLightbox()"></div>
        <button
            onclick="closeLightbox()"
            class="glass-card absolute top-6 right-6 z-10 flex h-12 w-12 items-center justify-center rounded-full transition-colors hover:bg-white/[0.1]"
        >
            <svg class="h-6 w-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <button
            onclick="prevImage()"
            class="glass-card absolute top-1/2 left-6 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full transition-colors hover:bg-white/[0.1]"
        >
            <svg class="h-6 w-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button
            onclick="nextImage()"
            class="glass-card absolute top-1/2 right-6 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full transition-colors hover:bg-white/[0.1]"
        >
            <svg class="h-6 w-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        <div class="relative flex h-full items-center justify-center p-20">
            <div class="flex max-w-6xl flex-col items-center gap-8 lg:flex-row">
                <img id="lightbox-img" src="" alt="" class="max-h-[70vh] max-w-full rounded-2xl object-contain" />
                <div id="lightbox-info" class="text-center lg:max-w-md lg:text-left"></div>
            </div>
        </div>
    </div>
</x-layouts::site>

@push('scripts')
    <script>
        @php
            $galleryData = $images->map(fn ($img) => [
                'src' => $img->image_url,
                'title' => $img->title,
                'category' => $img->category->name ?? '-',
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
                let infoHtml =
                    '<div class="text-[#DC2626] text-xs font-semibold uppercase tracking-wider">' +
                    galleryImages[index].category +
                    '</div>';
                infoHtml +=
                    '<h3 class="text-[#FAFAFA] text-2xl font-bold font-headline mt-1">' + galleryImages[index].title + '</h3>';

                if (galleryImages[index].description) {
                    infoHtml += '<p class="text-[#A1A1AA] mt-2">' + galleryImages[index].description + '</p>';
                }

                if (galleryImages[index].location_address && galleryImages[index].google_maps_url) {
                    infoHtml += '<div class="mt-4 flex items-center">';
                    infoHtml +=
                        '<svg class="w-5 h-5 text-[#DC2626] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                    infoHtml +=
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>';
                    infoHtml +=
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>';
                    infoHtml += '</svg>';
                    infoHtml +=
                        '<a href="' +
                        galleryImages[index].google_maps_url +
                        '" target="_blank" rel="noopener noreferrer" class="text-[#FAFAFA] hover:text-[#DC2626] transition-colors">';
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
                    { src: 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=600&q=80' },
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

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
        });
    </script>
@endpush
