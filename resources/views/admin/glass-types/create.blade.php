<x-layouts::admin title="Create Glass Type">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Create Glass Type</h1>
            <a href="{{ route('admin.glass-types.index') }}" class="admin-action-btn admin-action-btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.glass-types.store') }}" class="max-w-2xl">
            @csrf

            <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-admin-text-muted mb-2">Name</label>
                    <input type="text" name="name" id="name" required class="w-full admin-form-input" placeholder="e.g. Windshield">
                    @error('name')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-admin-text-muted mb-2">Sort Order <span class="text-admin-text-muted">(Optional)</span></label>
                    <input type="number" name="sort_order" id="sort_order" class="w-full admin-form-input" placeholder="Lower numbers appear first">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 p-4 bg-admin-surface-alt rounded-xl border border-admin-border">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-5 w-5 bg-admin-input-bg border-admin-input-border rounded focus:ring-2 focus:ring-[#DC2626] cursor-pointer">
                    <label for="is_active" class="text-sm font-medium text-admin-text cursor-pointer select-none">Active</label>
                    <span class="text-xs text-admin-text-muted ml-auto">Visible in quote forms</span>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.glass-types.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Create Glass Type
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
