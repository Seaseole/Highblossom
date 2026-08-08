<x-layouts::site title="About Us">
    <section class="bg-[#0A0A0F] pt-32 pb-24 lg:pb-32">
        <div class="mx-auto max-w-350 px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-10">
                <!-- Left Column (70%) - Title, Hero Image, Body -->
                <div class="lg:col-span-7">
                    @if ($content->hero_image)
                        <div class="about-hero js-scroll-with-image relative mb-8 overflow-hidden rounded-[2.5rem]">
                            <img
                                src="{{ Storage::url($content->hero_image) }}"
                                alt="About Highblossom"
                                class="h-[420px] w-full object-cover transition-transform duration-1000 ease-out hover:scale-[1.02]"
                            />
                            <div class="absolute inset-0 bg-linear-to-t from-[#0A0A0F]/90 via-[#0A0A0F]/20 to-transparent"></div>
                            <div class="feather-overlay pointer-events-none absolute inset-0"></div>
                            <div class="absolute inset-x-0 bottom-[2px] px-6 lg:px-12">
                                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-45 bg-linear-to-t from-white/91 via-white/40 to-transparent"></div>
                                <div class="relative max-w-3xl transform space-y-5 text-[#0B0B0F] transition-transform duration-300 ease-out">
                                    <div class="text-admin-accent text-sm font-semibold tracking-wider uppercase">
                                        About Us
                                    </div>
                                    <h1 class="font-headline text-4xl font-bold tracking-tight text-[#0B0B0F] md:text-5xl lg:text-6xl">
                                        {!! nl2br(e($content->title)) !!}
                                    </h1>
                                    @if ($content->subtitle)
                                        <p class="max-w-2xl text-lg leading-relaxed text-[#0a0a0f]">
                                            {{ $content->subtitle }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-admin-accent mb-4 text-sm font-semibold tracking-wider uppercase">
                            About Us
                        </div>
                        <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                            {!! nl2br(e($content->title)) !!}
                        </h1>
                        @if ($content->subtitle)
                            <p class="mb-8 text-lg leading-relaxed text-[#A1A1AA]">{{ $content->subtitle }}</p>
                        @endif
                    @endif

                    <div class="prose prose-invert prose-lg mb-20 max-w-none leading-relaxed text-[#A1A1AA]">
                        {!! $content->body !!}
                    </div>

                    {{-- Team Section --}}
                    @if ($staff->isNotEmpty())
                        <div class="mt-20 border-t border-white/5 pt-20">
                            <div class="mb-12">
                                <div class="mb-3 text-sm font-semibold tracking-[0.2em] text-[#DC2626] uppercase">
                                    Excellence in Motion
                                </div>
                                <h2 class="font-headline text-3xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                                    Our Master Craftsmen
                                </h2>
                                <p class="mt-4 max-w-xl text-[#A1A1AA]">
                                    Meet the dedicated professionals who bring precision and care to every installation,
                                    ensuring your vehicle remains safe and beautiful.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($staff as $member)
                                    <div class="group relative overflow-hidden rounded-[2.5rem] border border-white/5 bg-[#121218] shadow-2xl transition-all duration-500 hover:border-[#DC2626]/20">
                                        {{-- Image Container --}}
                                        <div class="relative h-[400px] overflow-hidden">
                                            <img
                                                src="{{ $member->photo_url }}"
                                                alt="{{ $member->name }}"
                                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            />
                                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent opacity-60 transition-opacity group-hover:opacity-40"></div>
                                        </div>

                                        {{-- Content --}}
                                        <div class="relative p-8">
                                            <div class="mb-4">
                                                <h3 class="font-headline text-2xl font-bold text-[#FAFAFA] transition-colors group-hover:text-[#DC2626]">
                                                    {{ $member->name }}
                                                </h3>
                                                <div class="mt-1 text-xs font-bold tracking-widest text-[#DC2626] uppercase">
                                                    {{ $member->role }}
                                                </div>
                                            </div>

                                            @if ($member->bio)
                                                <p class="line-clamp-3 text-sm leading-relaxed text-[#A1A1AA] transition-all duration-300 group-hover:line-clamp-none">
                                                    {{ $member->bio }}
                                                </p>
                                            @endif

                                            <div class="mt-6 flex gap-3">
                                                <div class="h-[2px] w-8 rounded-full bg-[#DC2626] transition-all group-hover:w-12"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column (30%) - Vision & Mission -->
                @if ($content->vision || $content->mission)
                    <div class="space-y-6 lg:col-span-3">
                        @if ($content->vision)
                            <div class="glass-card rounded-2xl p-8">
                                <div class="text-admin-accent mb-4 text-sm font-semibold tracking-wider uppercase">
                                    Our Vision
                                </div>
                                <div class="leading-relaxed text-[#A1A1AA]">{!! $content->vision !!}</div>
                            </div>
                        @endif

                        @if ($content->mission)
                            <div class="glass-card rounded-2xl p-8">
                                <div class="text-admin-accent mb-4 text-sm font-semibold tracking-wider uppercase">
                                    Our Mission
                                </div>
                                <div class="leading-relaxed text-[#A1A1AA]">{!! $content->mission !!}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts::site>
