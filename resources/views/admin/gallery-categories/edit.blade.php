<x-layouts::admin title="Edit Gallery Category">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-4">
                <a href="{{ route('admin.gallery-categories.index') }}" class="hover:text-zinc-700 transition-colors">Gallery Categories</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-zinc-400">Edit</span>
            </nav>
            <h1 class="text-4xl font-bold text-[#39393c] tracking-tight">Edit Gallery Category</h1>
            <p class="mt-2 text-zinc-500">Update gallery category information</p>
        </div>

        <form method="POST" action="{{ route('admin.gallery-categories.update', $galleryCategory) }}" class="max-w-3xl">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-zinc-700 mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $galleryCategory->name) }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="e.g. Automotive">
                        @error('name')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-zinc-700 mb-2">Sort Order <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $galleryCategory->sort_order) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Lower numbers appear first">
                        @error('sort_order')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-200">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $galleryCategory->is_active ? 'checked' : '' }} class="h-5 w-5 text-[#dc2626] focus:ring-[#dc2626] focus:ring-offset-0 border-zinc-300 rounded transition-all duration-200 cursor-pointer">
                        <label for="is_active" class="text-sm font-medium text-zinc-700 cursor-pointer select-none">Active</label>
                        <span class="text-xs text-zinc-500 ml-auto">Visible in gallery filters</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-200">
                    <a href="{{ route('admin.gallery-categories.index') }}" class="px-6 py-3 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-300 transition-all duration-200 active:scale-[0.98]">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-[#dc2626] text-white text-sm font-medium shadow-lg shadow-[#dc2626]/20 hover:bg-[#b91c1c] hover:shadow-xl hover:shadow-[#dc2626]/30 transition-all duration-200 active:scale-[0.98]">
                        Update Gallery Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
