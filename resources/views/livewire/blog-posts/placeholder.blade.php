<div class="space-y-12">
    {{-- Featured skeleton --}}
    <div class="animate-pulse">
        <div class="relative h-[26rem] w-full overflow-hidden rounded-3xl border border-site-line-strong bg-site-fill sm:h-[30rem]">
            <div class="absolute inset-0 bg-gradient-to-t from-white/10 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 space-y-4 p-6 sm:p-10">
                <div class="h-9 w-2/3 rounded bg-site-fill-hover"></div>
                <div class="h-4 w-1/2 rounded bg-site-fill-hover"></div>
                <div class="h-4 w-40 rounded bg-site-fill-hover"></div>
            </div>
        </div>
    </div>

    {{-- Tabs + search skeleton --}}
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-site-line-strong pb-4">
        <div class="flex animate-pulse gap-4">
            @for ($i = 1; $i <= 4; $i++)
                <div class="h-4 w-20 rounded bg-site-fill-hover"></div>
            @endfor
        </div>
        <div class="h-10 w-72 animate-pulse rounded-full bg-site-fill-hover"></div>
    </div>

    {{-- Card grid skeleton --}}
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        @for ($i = 1; $i <= 6; $i++)
            <div class="animate-pulse overflow-hidden rounded-3xl border border-site-line-strong bg-site-fill">
                <div class="aspect-[16/10] bg-gradient-to-br from-white/10 to-white/5"></div>
                <div class="space-y-3 p-6">
                    <div class="h-3 w-24 rounded bg-site-fill-hover"></div>
                    <div class="h-6 w-5/6 rounded bg-site-fill-hover"></div>
                    <div class="h-4 w-full rounded bg-site-fill-hover"></div>
                    <div class="h-4 w-4/6 rounded bg-site-fill-hover"></div>
                    <div class="flex items-center gap-3 border-t border-site-line pt-4">
                        <div class="h-8 w-8 rounded-full bg-site-fill-hover"></div>
                        <div class="h-4 w-24 rounded bg-site-fill-hover"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
