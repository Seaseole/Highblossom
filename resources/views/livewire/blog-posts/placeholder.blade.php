<div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
    {{-- Main Content Skeleton --}}
    <div class="flex-1 min-w-0">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for($i = 1; $i <= 6; $i++)
                <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden animate-pulse">
                    {{-- Featured Image Skeleton --}}
                    <div class="aspect-[16/10] bg-gradient-to-br from-white/10 to-white/5"></div>
                    
                    {{-- Content Skeleton --}}
                    <div class="p-6">
                        {{-- Date and Category Skeleton --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-4 w-20 bg-white/10 rounded animate-pulse"></div>
                            <div class="w-1 h-1 rounded-full bg-white/20"></div>
                            <div class="h-4 w-16 bg-white/10 rounded animate-pulse"></div>
                        </div>

                        {{-- Title Skeleton --}}
                        <div class="h-6 bg-white/10 rounded mb-3 animate-pulse"></div>
                        <div class="h-6 bg-white/10 rounded w-4/5 mb-4 animate-pulse"></div>

                        {{-- Description Skeleton --}}
                        <div class="space-y-2 mb-4">
                            <div class="h-4 bg-white/10 rounded animate-pulse"></div>
                            <div class="h-4 bg-white/10 rounded w-5/6 animate-pulse"></div>
                            <div class="h-4 bg-white/10 rounded w-4/6 animate-pulse"></div>
                        </div>

                        {{-- Read More Skeleton --}}
                        <div class="h-4 w-20 bg-white/10 rounded mb-6 animate-pulse"></div>

                        {{-- Author Section Skeleton --}}
                        <div class="flex items-center gap-3 pt-4 border-t border-white/5">
                            <div class="w-10 h-10 rounded-full bg-white/10 animate-pulse"></div>
                            <div class="flex-1">
                                <div class="h-4 w-24 bg-white/10 rounded mb-1 animate-pulse"></div>
                                <div class="h-3 w-12 bg-white/10 rounded animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    {{-- Sidebar Skeleton --}}
    <aside class="w-full lg:w-80 flex-shrink-0 space-y-6">
        {{-- Categories Panel Skeleton --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-white/10 to-white/5 animate-pulse"></div>
                <div class="h-6 w-24 bg-white/10 rounded animate-pulse"></div>
            </div>
            <div class="space-y-2">
                @for($i = 1; $i <= 5; $i++)
                    <div class="h-10 bg-white/10 rounded-lg animate-pulse" style="animation-delay: {{ $i * 0.1 }}s"></div>
                @endfor
            </div>
        </div>

        {{-- Tags Panel Skeleton --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-white/10 to-white/5 animate-pulse"></div>
                <div class="h-6 w-16 bg-white/10 rounded animate-pulse"></div>
            </div>
            <div class="flex flex-wrap gap-2">
                @for($i = 1; $i <= 8; $i++)
                    <div class="h-8 w-20 bg-white/10 rounded-full animate-pulse" style="animation-delay: {{ $i * 0.05 }}s"></div>
                @endfor
            </div>
        </div>
    </aside>
</div>
