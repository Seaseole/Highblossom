<input
    type="text"
    name="search"
    placeholder="{{ __('Search images...') }}"
    hx-get="{{ route('admin.media-library.index') }}"
    hx-trigger="keyup changed delay:300ms"
    hx-target="#image-grid"
    hx-indicator="#search-loading"
    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
    value="{{ request()->search }}"
>
<div id="search-loading" class="hidden">Searching...</div>

<div id="image-grid" class="grid grid-cols-2 md:grid-cols-4 gap-6 p-2">
    @foreach($images as $image)
        <button
            type="button"
            @click="window.dispatchEvent(new CustomEvent('image-selected', { detail: { url: '{{ $image->image_url }}' }}))"
            class="group relative aspect-square rounded-xl overflow-hidden border-2 border-gray-200 dark:border-gray-800 hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/25 transition-all active:scale-95"
        >
            <img src="{{ $image->image_url }}" alt="{{ $image->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="bg-indigo-600 text-white rounded-full p-2 transform scale-0 group-hover:scale-100 transition-transform duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-2 text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                {{ \Illuminate\Support\Str::limit($image->title, 20) }}
            </div>
        </button>
    @endforeach
</div>

<div class="flex justify-center pt-4">
    {{ $images->links() }}
</div>
