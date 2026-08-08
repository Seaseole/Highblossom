<input
    type="text"
    name="search"
    placeholder="{{ __('Search images...') }}"
    hx-get="{{ route('admin.media-library.index') }}"
    hx-trigger="keyup changed delay:300ms"
    hx-target="#image-grid"
    hx-indicator="#search-loading"
    class="admin-form-input w-full"
    value="{{ request()->search }}"
/>
<div id="search-loading" class="text-admin-text-muted mt-2 hidden text-sm">Searching...</div>

<div id="image-grid" class="grid grid-cols-2 gap-6 p-2 md:grid-cols-4">
    @foreach ($images as $image)
        <button
            type="button"
            @click="$dispatch('open-image-preview', { id: {{ $image->id }} })"
            class="group border-admin-border hover:border-admin-accent hover:shadow-admin-accent/20 relative aspect-square overflow-hidden rounded-xl border-2 transition-all hover:shadow-lg active:scale-95"
        >
            <img
                src="{{ $image->image_url }}"
                alt="{{ $image->title }}"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
            />
            <div class="absolute inset-0 bg-linear-to-t from-black/60 via-black/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="bg-admin-accent scale-0 transform rounded-full p-2 text-white transition-transform duration-300 group-hover:scale-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 left-0 truncate p-2 text-xs font-medium text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                {{ \Illuminate\Support\Str::limit($image->title, 20) }}
            </div>
        </button>
    @endforeach
</div>

<div class="flex justify-center pt-4">{{ $images->links() }}</div>
