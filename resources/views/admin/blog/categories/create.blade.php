<x-layouts::admin title="Create Category">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Create Category</h1>
            <a href="{{ route('admin.categories.index') }}" class="admin-action-btn admin-action-btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-2xl">
            @csrf

            <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-4 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
                <div>
                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full bg-admin-surface-alt border border-admin-border rounded-xl px-4 py-3 text-admin-text placeholder-admin-text-muted focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                        placeholder="Enter category name..."
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Description</label>
                    <textarea
                        name="description"
                        rows="3"
                        class="w-full bg-admin-surface-alt border border-admin-border rounded-xl px-4 py-3 text-admin-text placeholder-admin-text-muted focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                        placeholder="Category description..."
                    >{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.categories.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Create Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
