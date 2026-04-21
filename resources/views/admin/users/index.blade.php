<x-layouts::admin title="Users">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Users & Roles</h1>
            <div class="flex items-center gap-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="w-64">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search users..."
                        class="admin-form-input"
                    >
                </form>
                <a href="{{ route('admin.users.create') }}" class="admin-action-btn admin-action-btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Create User</span>
                </a>
            </div>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-white/5">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($users as $user)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-[#DC2626] flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-[#DC2626]/20">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-[#FAFAFA]">{{ $user->name }}</div>
                                        <div class="text-sm text-[#A1A1AA]">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA]">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="admin-badge" style="background: rgba(220, 38, 38, 0.2); color: #DC2626; border: 1px solid rgba(220, 38, 38, 0.3);">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-[#71717A] italic">No roles</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <label for="edit-modal-toggle" class="admin-action-btn admin-action-btn-secondary cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    <x-modal id="edit" title="Edit User" maxWidth="2xl">
        <form method="POST" action="{{ route('admin.users.update', $user ?? '') }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-[#A1A1AA] mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" required class="admin-form-input @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-[#A1A1AA] mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" required class="admin-form-input @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#A1A1AA] mb-2">Password</label>
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep current" class="admin-form-input @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#A1A1AA] mb-2">Roles</label>
                    <div class="grid grid-cols-1 gap-2 p-3 border border-white/10 rounded-xl max-h-48 overflow-y-auto bg-white/5">
                        @foreach($roles ?? [] as $role)
                            <label class="flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="roles[]"
                                    value="{{ $role->name }}"
                                    {{ isset($user) && $user->roles->contains('name', $role->name) ? 'checked' : '' }}
                                    class="h-4 w-4 text-[#DC2626] focus:ring-[#DC2626] border-white/20 rounded bg-white/5"
                                >
                                <span class="ml-2 text-sm text-[#FAFAFA]">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <x-slot:footer>
                <div class="flex justify-end gap-3">
                    <label for="edit-modal-toggle" class="admin-action-btn admin-action-btn-ghost cursor-pointer inline-block">
                        Cancel
                    </label>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Update User
                    </button>
                </div>
            </x-slot:footer>
        </form>
    </x-modal>
</x-layouts::admin>
