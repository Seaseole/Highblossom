<x-layouts::admin title="Edit Category">
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 text-sm text-admin-text-muted hover:text-admin-accent transition-colors duration-200 group">
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Categories
            </a>
        </div>

        {{-- Title Section --}}
        <div class="mb-8">
            <h1 class="font-headline text-3xl font-bold text-admin-text tracking-tight">Edit Category</h1>
            <p class="text-admin-text-muted text-sm mt-2">Update category details</p>
        </div>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="max-w-2xl">
            @csrf
            @method('PUT')

            <div class="admin-glass-card rounded-3xl shadow-black/20 p-6 space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        required
                        class="admin-form-input w-full"
                        placeholder="Enter category name..."
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Description</label>
                    <textarea
                        name="description"
                        rows="3"
                        class="admin-form-input w-full resize-none"
                        placeholder="Category description..."
                    >{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-admin-border-subtle">
                    <a href="{{ route('admin.categories.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
