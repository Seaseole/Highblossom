<x-layouts::admin title="Edit Testimonial">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-4">
                <a href="{{ route('admin.testimonials.index') }}" class="hover:text-zinc-700 transition-colors">Testimonials</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-zinc-400">Edit</span>
            </nav>
            <h1 class="text-4xl font-bold text-[#39393c] tracking-tight">Edit Testimonial</h1>
            <p class="mt-2 text-zinc-500">Update testimonial details and manage content</p>
        </div>

        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" class="max-w-3xl">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-zinc-700 mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name) }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Customer name">
                        @error('name')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-zinc-700 mb-2">Role <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <input type="text" name="role" id="role" value="{{ old('role', $testimonial->role) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="e.g. CEO, Customer">
                        @error('role')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-semibold text-zinc-700 mb-2">Content</label>
                        <textarea name="content" id="content" rows="5" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 resize-none" placeholder="Customer testimonial">{{ old('content', $testimonial->content) }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rating" class="block text-sm font-semibold text-zinc-700 mb-2">Rating</label>
                        <select name="rating" id="rating" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200">
                            <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>1 Star</option>
                        </select>
                        @error('rating')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-200">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }} class="h-5 w-5 text-[#dc2626] focus:ring-[#dc2626] focus:ring-offset-0 border-zinc-300 rounded transition-all duration-200 cursor-pointer">
                        <label for="is_featured" class="text-sm font-medium text-zinc-700 cursor-pointer select-none">Featured</label>
                        <span class="text-xs text-zinc-500 ml-auto">Highlighted on public site</span>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-200">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $testimonial->is_published) ? 'checked' : '' }} class="h-5 w-5 text-[#dc2626] focus:ring-[#dc2626] focus:ring-offset-0 border-zinc-300 rounded transition-all duration-200 cursor-pointer">
                        <label for="is_published" class="text-sm font-medium text-zinc-700 cursor-pointer select-none">Published</label>
                        <span class="text-xs text-zinc-500 ml-auto">Visible on public site</span>
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-zinc-700 mb-2">Sort Order <span class="font-normal text-zinc-400">(Optional)</span></label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Lower numbers appear first">
                        @error('sort_order')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-200">
                    <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-3 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-300 transition-all duration-200 active:scale-[0.98]">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-[#dc2626] text-white text-sm font-medium shadow-lg shadow-[#dc2626]/20 hover:bg-[#b91c1c] hover:shadow-xl hover:shadow-[#dc2626]/30 transition-all duration-200 active:scale-[0.98]">
                        Update Testimonial
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
