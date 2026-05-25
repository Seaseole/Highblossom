<x-layouts::admin title="Partners">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-admin-text font-headline">Manage Partners</h1>
            <a href="{{ route('admin.partners.create') }}" class="admin-action-btn admin-action-btn-primary">Add Partner</a>
        </div>

        <div class="admin-glass-card overflow-x-auto">
            <table class="w-full">
                <thead class="bg-admin-surface-alt">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-admin-text-muted">Logo</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-admin-text-muted">Name</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-admin-text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @foreach($partners as $partner)
                        <tr>
                            <td class="px-6 py-4"><img src="{{ $partner->logo_url }}" class="w-16 h-10 object-contain"></td>
                            <td class="px-6 py-4 text-admin-text font-medium">{{ $partner->name }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.partners.edit', $partner) }}" class="text-admin-accent">Edit</a>
                                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="inline">
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