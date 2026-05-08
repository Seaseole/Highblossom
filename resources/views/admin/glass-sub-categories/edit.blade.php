<x-layouts::admin title="Edit Glass Sub-Category">
    <div class="p-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-admin-text-muted hover:text-admin-text">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-admin-text-muted" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('admin.glass-sub-categories.index') }}" class="ml-1 text-sm font-medium text-admin-text-muted hover:text-admin-text md:ml-2">Glass Sub-Categories</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-admin-text-muted" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-admin-text md:ml-2">Edit: {{ $glassSubCategory->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="admin-glass-card rounded-2xl shadow-2xl shadow-black/20">
            <div class="px-8 py-6 border-b border-admin-border-subtle">
                <h2 class="text-2xl font-bold text-admin-text font-headline">Edit Glass Sub-Category</h2>
                <p class="text-admin-text-muted text-sm mt-1">Update the details for this glass sub-category.</p>
            </div>

            <form action="{{ route('admin.glass-sub-categories.update', $glassSubCategory) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Glass Type Selection -->
                <div class="space-y-3">
                    <label for="glass_type_id" class="block text-sm font-medium text-admin-text">
                        Glass Type <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="glass_type_id"
                        name="glass_type_id"
                        required
                        class="admin-form-input w-full"
                    >
                        <option value="">Select a glass type...</option>
                        @foreach($glassTypes as $glassType)
                            <option value="{{ $glassType->id }}" {{ $glassSubCategory->glass_type_id == $glassType->id ? 'selected' : '' }}>
                                {{ $glassType->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('glass_type_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sub-Category Name -->
                <div class="space-y-3">
                    <label for="name" class="block text-sm font-medium text-admin-text">
                        Sub-Category Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        value="{{ old('name', $glassSubCategory->name) }}"
                        placeholder="e.g., Left Hand Side - Rear Door Glass"
                        class="admin-form-input w-full"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-admin-text-muted">
                        Be specific with location and type of glass for accurate quote requests.
                    </p>
                </div>

                <!-- Slug -->
                <div class="space-y-3">
                    <label for="slug" class="block text-sm font-medium text-admin-text">
                        URL Slug
                    </label>
                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        value="{{ old('slug', $glassSubCategory->slug) }}"
                        placeholder="e.g., left-hand-side-rear-door-glass"
                        class="admin-form-input w-full font-mono text-sm"
                    >
                    @error('slug')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-admin-text-muted">
                        Leave empty to auto-generate from name. Must be unique.
                    </p>
                </div>

                <!-- Sort Order -->
                <div class="space-y-3">
                    <label for="sort_order" class="block text-sm font-medium text-admin-text">
                        Sort Order
                    </label>
                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        value="{{ old('sort_order', $glassSubCategory->sort_order) }}"
                        min="0"
                        class="admin-form-input w-full"
                    >
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-admin-text-muted">
                        Lower numbers appear first. Default is 0.
                    </p>
                </div>

                <!-- Active Status -->
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $glassSubCategory->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-admin-accent bg-admin-surface border-admin-border rounded focus:ring-admin-accent focus:ring-2"
                        >
                        <label for="is_active" class="ml-3 text-sm font-medium text-admin-text">
                            Active
                        </label>
                    </div>
                    <p class="text-xs text-admin-text-muted">
                        Inactive sub-categories won't appear in public quote form.
                    </p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-start gap-4 pt-6 border-t border-admin-border-subtle">
                    <a href="{{ route('admin.glass-sub-categories.index') }}" class="admin-action-btn admin-action-btn-ghost">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Update Sub-Category</span>
                    </button>
                </div>
            </form>

            <!-- Delete Form (separate to avoid nested form issue) -->
            <div class="px-8 pb-8">
                @if($glassSubCategory->quotes()->count() === 0)
                    <form action="{{ route('admin.glass-sub-categories.destroy', $glassSubCategory) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this sub-category? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-action-btn admin-action-btn-danger">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Delete Sub-Category</span>
                        </button>
                    </form>
                @else
                    <div class="text-sm text-admin-text-muted flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        Cannot delete - has {{ $glassSubCategory->quotes()->count() }} quote(s)
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::admin>
