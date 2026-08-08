<div class="flex flex-col gap-8 lg:flex-row lg:gap-12">
    {{-- Main Content Skeleton --}}
    <div class="min-w-0 flex-1">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @for ($i = 1; $i <= 6; $i++)
                <div class="animate-pulse overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                    {{-- Featured Image Skeleton --}}
                    <div class="aspect-[16/10] bg-gradient-to-br from-white/10 to-white/5"></div>

                    {{-- Content Skeleton --}}
                    <div class="p-6">
                        {{-- Date and Category Skeleton --}}
                        <div class="mb-4 flex items-center gap-3">
                            <div class="h-4 w-20 animate-pulse rounded bg-white/10"></div>
                            <div class="h-1 w-1 rounded-full bg-white/20"></div>
                            <div class="h-4 w-16 animate-pulse rounded bg-white/10"></div>
                        </div>

                        {{-- Title Skeleton --}}
                        <div class="mb-3 h-6 animate-pulse rounded bg-white/10"></div>
                        <div class="mb-4 h-6 w-4/5 animate-pulse rounded bg-white/10"></div>

                        {{-- Description Skeleton --}}
                        <div class="mb-4 space-y-2">
                            <div class="h-4 animate-pulse rounded bg-white/10"></div>
                            <div class="h-4 w-5/6 animate-pulse rounded bg-white/10"></div>
                            <div class="h-4 w-4/6 animate-pulse rounded bg-white/10"></div>
                        </div>

                        {{-- Read More Skeleton --}}
                        <div class="mb-6 h-4 w-20 animate-pulse rounded bg-white/10"></div>

                        {{-- Author Section Skeleton --}}
                        <div class="flex items-center gap-3 border-t border-white/5 pt-4">
                            <div class="h-10 w-10 animate-pulse rounded-full bg-white/10"></div>
                            <div class="flex-1">
                                <div class="mb-1 h-4 w-24 animate-pulse rounded bg-white/10"></div>
                                <div class="h-3 w-12 animate-pulse rounded bg-white/10"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    {{-- Sidebar Skeleton --}}
    <aside class="w-full flex-shrink-0 space-y-6 lg:w-80">
        {{-- Categories Panel Skeleton --}}
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="mb-5 flex items-center gap-3">
                <div class="h-10 w-10 animate-pulse rounded-xl bg-gradient-to-br from-white/10 to-white/5"></div>
                <div class="h-6 w-24 animate-pulse rounded bg-white/10"></div>
            </div>
            <div class="space-y-2">
                @for ($i = 1; $i <= 5; $i++)
                    <div
                        class="h-10 animate-pulse rounded-lg bg-white/10"
                        style="animation-delay: {{ $i * 0.1 }}s"
                    ></div>
                @endfor
            </div>
        </div>

        {{-- Tags Panel Skeleton --}}
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
            <div class="mb-5 flex items-center gap-3">
                <div class="h-10 w-10 animate-pulse rounded-xl bg-gradient-to-br from-white/10 to-white/5"></div>
                <div class="h-6 w-16 animate-pulse rounded bg-white/10"></div>
            </div>
            <div class="flex flex-wrap gap-2">
                @for ($i = 1; $i <= 8; $i++)
                    <div
                        class="h-8 w-20 animate-pulse rounded-full bg-white/10"
                        style="animation-delay: {{ $i * 0.05 }}s"
                    ></div>
                @endfor
            </div>
        </div>
    </aside>
</div>
