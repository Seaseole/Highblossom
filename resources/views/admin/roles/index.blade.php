<x-layouts::admin title="Roles">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Roles & Permissions</h1>
            <a href="{{ route('admin.roles.create') }}" class="admin-action-btn admin-action-btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Create Role</span>
            </a>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-white/5">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($roles as $role)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-[#FAFAFA]">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($role->permissions as $permission)
                                        <span class="admin-badge" style="background: rgba(220, 38, 38, 0.2); color: #DC2626; border: 1px solid rgba(220, 38, 38, 0.3);">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="admin-action-btn admin-action-btn-secondary mr-3">Edit</a>
                                @if($role->name !== 'Super Admin')
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[#DC2626] hover:text-[#FAFAFA] transition-colors">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::admin>
