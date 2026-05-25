<x-layouts::admin title="Staff">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-admin-text font-headline">Manage Staff</h1>
            <a href="{{ route('admin.staff.create') }}" class="admin-action-btn admin-action-btn-primary">Add Staff Member</a>
        </div>

        <div class="admin-glass-card overflow-x-auto">
            <table class="w-full">
                <thead class="bg-admin-surface-alt">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-admin-text-muted">Photo</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-admin-text-muted">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-admin-text-muted">Role</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-admin-text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @foreach($staff as $member)
                        <tr>
                            <td class="px-6 py-4"><img src="{{ $member->photo_url }}" class="w-12 h-12 rounded-full object-cover"></td>
                            <td class="px-6 py-4 text-admin-text font-medium">{{ $member->name }}</td>
                            <td class="px-6 py-4 text-admin-text-muted">{{ $member->role }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.staff.edit', $member) }}" class="text-admin-accent">Edit</a>
                                <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::admin>