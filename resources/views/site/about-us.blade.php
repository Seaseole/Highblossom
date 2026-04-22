<x-layouts::site title="About Us">
    <section class="pt-32 pb-24 lg:pb-32 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <!-- Left Column (70%) - Title, Hero Image, Body -->
                <div class="lg:col-span-7">
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">About Us</div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                        {!! nl2br(e($content->title)) !!}
                    </h1>
                    @if($content->subtitle)
                        <p class="text-lg text-[#A1A1AA] leading-relaxed mb-8">
                            {{ $content->subtitle }}
                        </p>
                    @endif

                    @if($content->hero_image)
                        <div class="relative rounded-2xl overflow-hidden mb-8">
                            <img src="{{ Storage::url($content->hero_image) }}" alt="About Highblossom" class="w-full h-[400px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/80 to-transparent"></div>
                        </div>
                    @endif

                    <div class="prose prose-invert prose-lg max-w-none text-[#A1A1AA] leading-relaxed">
                        {!! $content->body !!}
                    </div>
                </div>

                <!-- Right Column (30%) - Vision & Mission -->
                @if($content->vision || $content->mission)
                <div class="lg:col-span-3 space-y-6">
                    @if($content->vision)
                    <div class="glass-card rounded-2xl p-8">
                        <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Our Vision</div>
                        <p class="text-[#A1A1AA] leading-relaxed">{!! nl2br(e($content->vision)) !!}</p>
                    </div>
                    @endif

                    @if($content->mission)
                    <div class="glass-card rounded-2xl p-8">
                        <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-4">Our Mission</div>
                        <p class="text-[#A1A1AA] leading-relaxed">{!! nl2br(e($content->mission)) !!}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts::site>
