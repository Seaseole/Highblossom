<x-layouts::site title="Gallery">
    <!-- Hero Section -->
    <section class="py-16 bg-surface">
        <div class="max-w-7xl mx-auto px-8 text-center">
            <span class="inline-block py-1 px-3 mb-6 rounded-full bg-primary-container/10 text-primary font-bold text-sm tracking-widest uppercase">Our Work</span>
            <h1 class="text-5xl md:text-6xl font-headline font-extrabold text-on-surface tracking-tighter leading-none mb-6">
                Mastery in <span class="text-primary">Motion</span>
            </h1>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto leading-relaxed">
                Browse our portfolio of recent projects showcasing precision installations across automotive, heavy machinery, and fleet sectors.
            </p>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="py-24 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-8">
            <!-- Category Filters -->
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                <a href="{{ route('gallery') }}" class="px-6 py-2 rounded-full {{ !$category ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant hover:bg-surface-container-high' }} font-bold text-sm transition-colors">All Projects</a>
                @foreach ($categories as $cat)
                <a href="{{ route('gallery', ['category' => $cat]) }}" class="px-6 py-2 rounded-full {{ $category === $cat ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface-variant hover:bg-surface-container-high' }} font-bold text-sm transition-colors">{{ ucfirst(str_replace('_', ' ', $cat)) }}</a>
                @endforeach
            </div>

            <!-- Masonry Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($images as $index => $image)
                    @php
                        $isFeatured = $image->is_featured || $index === 0;
                        $isWide = $image->is_featured || ($index + 1) % 6 === 0;
                        $aspectClass = $isWide ? 'md:col-span-2 aspect-[21/9]' : ($isFeatured ? 'md:col-span-2 lg:col-span-2 aspect-[16/9]' : (in_array($index % 5, [1, 6]) ? 'aspect-[4/5]' : 'aspect-square'));
                    @endphp
                    <div class="group relative overflow-hidden rounded-2xl {{ $aspectClass }}">
                        <img alt="{{ $image->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $image->image_url }}" loading="lazy">
                        <div class="absolute inset-0 {{ $isWide || $isFeatured ? 'bg-gradient-to-t from-on-surface/80 via-on-surface/20 to-transparent' : 'bg-gradient-to-t from-on-surface/80 to-transparent opacity-0 group-hover:opacity-100' }} transition-opacity flex flex-col justify-end p-6 md:p-8">
                            <span class="text-white/60 text-sm font-medium mb-1 md:mb-2">{{ ucfirst(str_replace('_', ' ', $image->category)) }}</span>
                            <h3 class="text-white font-headline font-bold {{ $isWide || $isFeatured ? 'text-2xl' : 'text-lg' }} mb-1">{{ $image->title }}</h3>
                            @if ($image->description)
                                <p class="text-white/80 text-sm">{{ $image->description }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Static fallback images when no gallery images exist -->
                    <div class="md:col-span-2 lg:col-span-2 group relative overflow-hidden rounded-2xl aspect-[16/9]">
                        <img alt="Mining Excavator Installation" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=80" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 via-on-surface/20 to-transparent flex flex-col justify-end p-8">
                            <span class="text-white/60 text-sm font-medium mb-2">Heavy Machinery</span>
                            <h3 class="text-white font-headline font-bold text-2xl mb-1">Mining Excavator Cabin</h3>
                            <p class="text-white/80 text-sm">Custom toughened glass fitment for CAT 320D</p>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl aspect-[4/5]">
                        <img alt="Luxury Sedan Windshield" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&q=80" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-6">
                            <span class="text-white/60 text-sm font-medium mb-1">Automotive</span>
                            <h3 class="text-white font-headline font-bold text-lg">Luxury Vehicle Windshield</h3>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Load More -->
            @if ($images->hasMorePages())
            <div class="text-center mt-16">
                <a href="{{ route('gallery', array_merge(request()->all(), ['page' => $images->currentPage() + 1])) }}" class="glass-card text-on-surface px-8 py-4 rounded-lg font-headline font-bold border border-outline-variant/20 hover:bg-white/80 transition-colors inline-flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    Load More Projects
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-5xl font-headline font-extrabold text-primary mb-2">2,500+</div>
                    <div class="text-on-surface-variant font-medium">Vehicles Serviced</div>
                </div>
                <div>
                    <div class="text-5xl font-headline font-extrabold text-primary mb-2">150+</div>
                    <div class="text-on-surface-variant font-medium">Heavy Machines</div>
                </div>
                <div>
                    <div class="text-5xl font-headline font-extrabold text-primary mb-2">45+</div>
                    <div class="text-on-surface-variant font-medium">Fleet Accounts</div>
                </div>
                <div>
                    <div class="text-5xl font-headline font-extrabold text-primary mb-2">20+</div>
                    <div class="text-on-surface-variant font-medium">Years Experience</div>
                </div>
            </div>
        </div>
    </section>
</x-layouts::site>
