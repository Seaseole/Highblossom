<x-layouts::site title="About Us">
    <section class="pt-32 pb-24 lg:pb-32 bg-[#0A0A0F]">
        <div class="max-w-350 mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <!-- Left Column (70%) - Title, Hero Image, Body -->
                <div class="lg:col-span-7">
                    @if($content->hero_image)
                        <div class="relative rounded-[2.5rem] overflow-hidden mb-8 about-hero js-scroll-with-image">
                            <img src="{{ Storage::url($content->hero_image) }}" alt="About Highblossom" class="w-full h-[420px] object-cover transition-transform duration-1000 ease-out hover:scale-[1.02]">
                            <div class="absolute inset-0 bg-linear-to-t from-[#0A0A0F]/90 via-[#0A0A0F]/20 to-transparent"></div>
                            <div class="absolute inset-0 pointer-events-none feather-overlay"></div>
                            <div class="absolute inset-x-0 bottom-[2px] px-6 lg:px-12">
                                <div class="absolute inset-x-0 bottom-0 h-45 bg-linear-to-t from-white/91 via-white/40 to-transparent pointer-events-none"></div>
                                <div class="relative max-w-3xl text-[#0B0B0F] space-y-5 transform transition-transform duration-300 ease-out">
                                    <div class="text-admin-accent text-sm font-semibold uppercase tracking-wider">About Us</div>
                                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#0B0B0F] font-headline tracking-tight">
                                        {!! nl2br(e($content->title)) !!}
                                    </h1>
                                    @if($content->subtitle)
                                        <p class="text-lg text-[#0a0a0f] leading-relaxed max-w-2xl">
                                            {{ $content->subtitle }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-admin-accent text-sm font-semibold uppercase tracking-wider mb-4">About Us</div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                            {!! nl2br(e($content->title)) !!}
                        </h1>
                        @if($content->subtitle)
                            <p class="text-lg text-[#A1A1AA] leading-relaxed mb-8">
                                {{ $content->subtitle }}
                            </p>
                        @endif
                    @endif

                    <div class="prose prose-invert prose-lg max-w-none text-[#A1A1AA] leading-relaxed mb-20">
                        {!! $content->body !!}
                    </div>

                    {{-- Team Section --}}
                    @if($staff->isNotEmpty())
                        <div class="mt-20 border-t border-white/5 pt-20">
                            <div class="mb-12">
                                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-[0.2em] mb-3">Excellence in Motion</div>
                                <h2 class="text-3xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight">Our Master Craftsmen</h2>
                                <p class="text-[#A1A1AA] mt-4 max-w-xl">Meet the dedicated professionals who bring precision and care to every installation, ensuring your vehicle remains safe and beautiful.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                                @foreach($staff as $member)
                                    <div class="group relative bg-[#121218] rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl transition-all duration-500 hover:border-[#DC2626]/20">
                                        {{-- Image Container --}}
                                        <div class="relative h-[400px] overflow-hidden">
                                            <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" 
                                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                                        </div>

                                        {{-- Content --}}
                                        <div class="p-8 relative">
                                            <div class="mb-4">
                                                <h3 class="text-2xl font-bold text-[#FAFAFA] font-headline group-hover:text-[#DC2626] transition-colors">{{ $member->name }}</h3>
                                                <div class="text-[#DC2626] text-xs font-bold uppercase tracking-widest mt-1">{{ $member->role }}</div>
                                            </div>
                                            
                                            @if($member->bio)
                                                <p class="text-[#A1A1AA] text-sm leading-relaxed line-clamp-3 group-hover:line-clamp-none transition-all duration-300">
                                                    {{ $member->bio }}
                                                </p>
                                            @endif

                                            <div class="mt-6 flex gap-3">
                                                <div class="w-8 h-[2px] bg-[#DC2626] rounded-full group-hover:w-12 transition-all"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column (30%) - Vision & Mission -->
                @if($content->vision || $content->mission)
                <div class="lg:col-span-3 space-y-6">
                    @if($content->vision)
                    <div class="glass-card rounded-2xl p-8">
                        <div class="text-admin-accent text-sm font-semibold uppercase tracking-wider mb-4">Our Vision</div>
                        <div class="text-[#A1A1AA] leading-relaxed">{!! $content->vision !!}</div>
                    </div>
                    @endif

                    @if($content->mission)
                    <div class="glass-card rounded-2xl p-8">
                        <div class="text-admin-accent text-sm font-semibold uppercase tracking-wider mb-4">Our Mission</div>
                        <div class="text-[#A1A1AA] leading-relaxed">{!! $content->mission !!}</div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts::site>
