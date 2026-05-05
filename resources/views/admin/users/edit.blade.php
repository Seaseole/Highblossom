<x-layouts::admin title="Edit User">
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm text-admin-text-muted hover:text-admin-accent transition-colors duration-200 group">
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Users
            </a>
        </div>

        {{-- Title Section --}}
        <div class="mb-8">
            <h1 class="font-headline text-3xl font-bold text-admin-text tracking-tight">Edit User</h1>
            <p class="text-admin-text-muted text-sm mt-2">Update user account details</p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-2xl">
            @csrf
            @method('PUT')

            <div class="admin-glass-card rounded-3xl shadow-black/20 p-6 space-y-5">
                <div>
                    <label for="name" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="admin-form-input w-full" placeholder="Full name">
                    @error('name')
                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="admin-form-input w-full" placeholder="email@example.com">
                    @error('email')
                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">
                        Password <span class="text-admin-text-muted font-normal normal-case">(leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password" id="password" placeholder="New password" class="admin-form-input w-full">
                    @error('password')
                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-3">Roles</label>
                    <div class="grid grid-cols-1 gap-2 p-4 bg-admin-surface-alt/50 rounded-2xl border border-admin-border-subtle max-h-48 overflow-y-auto">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-3 p-2 hover:bg-admin-surface rounded-xl cursor-pointer transition-colors">
                                <input
                                    type="checkbox"
                                    name="roles[]"
                                    value="{{ $role->name }}"
                                    {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}
                                    class="h-5 w-5 bg-admin-input-bg border-admin-border rounded focus:ring-2 focus:ring-admin-accent cursor-pointer"
                                >
                                <span class="text-sm text-admin-text">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-admin-border-subtle">
                    <a href="{{ route('admin.users.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
