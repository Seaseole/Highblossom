<x-layouts::admin title="Edit Role">
    <div class="max-w-xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Edit Role</h1>
                <p class="text-gray-500 dark:text-gray-400">Modify role details and permissions.</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Back to Roles
            </a>
        </div>

        <form method="POST" action="{{ route('admin.roles.update', $role) }}" 
              class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role Name</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Permissions</label>
                <div class="space-y-2 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-100 dark:border-white/5 max-h-64 overflow-y-auto">
                    @foreach($permissions as $permission)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 cursor-pointer transition-colors">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-white/5 flex items-center justify-between">
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:opacity-75 transition-opacity">Delete Role</button>
                </form>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                    <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                        Update Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>