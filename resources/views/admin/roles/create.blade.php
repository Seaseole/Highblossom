<x-layouts::admin title="Create Role">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Create Role</h1>
            <a href="{{ route('admin.roles.index') }}" class="admin-action-btn admin-action-btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.roles.store') }}" class="max-w-2xl">
            @csrf

            <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-admin-text-muted mb-2">Role Name</label>
                    <input type="text" name="name" id="name" required class="w-full admin-form-input" placeholder="e.g. Editor">
                    @error('name')
                        <p class="mt-1 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-admin-text-muted mb-2">Permissions</label>
                    <div class="grid grid-cols-1 gap-2 p-4 bg-admin-surface-alt rounded-xl border border-admin-border max-h-64 overflow-y-auto">
                        @foreach($permissions as $permission)
                            <label class="flex items-center gap-3 p-2 hover:bg-admin-surface rounded-lg cursor-pointer transition-colors">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->name }}"
                                    class="h-5 w-5 bg-admin-input-bg border-admin-border rounded focus:ring-2 focus:ring-[#DC2626] cursor-pointer"
                                >
                                <span class="text-sm text-admin-text">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.roles.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Create Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
