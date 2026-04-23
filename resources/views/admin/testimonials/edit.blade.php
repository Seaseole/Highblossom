<x-layouts::admin title="Edit Testimonial">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Edit Testimonial</h1>
            <a href="{{ route('admin.testimonials.index') }}" class="admin-action-btn admin-action-btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" class="max-w-2xl">
            @csrf
            @method('PUT')

            <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-admin-text-muted mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name) }}" required class="w-full admin-form-input" placeholder="Customer name">
                    @error('name')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-admin-text-muted mb-2">Role <span class="text-admin-text-muted">(Optional)</span></label>
                    <input type="text" name="role" id="role" value="{{ old('role', $testimonial->role) }}" class="w-full admin-form-input" placeholder="e.g. CEO, Customer">
                    @error('role')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-admin-text-muted mb-2">Content</label>
                    <textarea name="content" id="content" rows="5" required class="w-full admin-form-input resize-none" placeholder="Customer testimonial">{{ old('content', $testimonial->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rating" class="block text-sm font-medium text-admin-text-muted mb-2">Rating</label>
                    <select name="rating" id="rating" class="w-full admin-form-input">
                        <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>1 Star</option>
                    </select>
                    @error('rating')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 p-4 bg-admin-surface-alt rounded-xl border border-admin-border">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }} class="h-5 w-5 bg-admin-input-bg border-admin-input-border rounded focus:ring-2 focus:ring-[#DC2626] cursor-pointer">
                    <label for="is_featured" class="text-sm font-medium text-admin-text cursor-pointer select-none">Featured</label>
                    <span class="text-xs text-admin-text-muted ml-auto">Highlighted on public site</span>
                </div>

                <div class="flex items-center gap-3 p-4 bg-admin-surface-alt rounded-xl border border-admin-border">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $testimonial->is_published) ? 'checked' : '' }} class="h-5 w-5 bg-admin-input-bg border-admin-input-border rounded focus:ring-2 focus:ring-[#DC2626] cursor-pointer">
                    <label for="is_published" class="text-sm font-medium text-admin-text cursor-pointer select-none">Published</label>
                    <span class="text-xs text-admin-text-muted ml-auto">Visible on public site</span>
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-admin-text-muted mb-2">Sort Order <span class="text-admin-text-muted">(Optional)</span></label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" class="w-full admin-form-input" placeholder="Lower numbers appear first">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.testimonials.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Update Testimonial
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
