<x-layouts::admin title="Create User">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-4">
                <a href="{{ route('admin.users.index') }}" class="hover:text-zinc-700 transition-colors">Users</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-zinc-400">Create</span>
            </nav>
            <h1 class="text-4xl font-bold text-[#39393c] tracking-tight">Create User</h1>
            <p class="mt-2 text-zinc-500">Add a new user account and assign roles</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="max-w-3xl">
            @csrf

            <div class="space-y-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-zinc-700 mb-2">Name</label>
                        <input type="text" name="name" id="name" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Full name">
                        @error('name')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-zinc-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="email@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-zinc-700 mb-2">Password</label>
                        <input type="password" name="password" id="password" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Secure password">
                        @error('password')
                            <p class="mt-2 text-sm text-[#dc2626]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">Roles</label>
                        <div class="grid grid-cols-1 gap-2 p-4 bg-zinc-50 rounded-xl border border-zinc-200 max-h-48 overflow-y-auto">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-3 p-2 hover:bg-zinc-100 rounded-lg cursor-pointer transition-colors">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        class="h-5 w-5 text-[#dc2626] focus:ring-[#dc2626] focus:ring-offset-0 border-zinc-300 rounded transition-all duration-200 cursor-pointer"
                                    >
                                    <span class="text-sm text-zinc-700">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-200">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-300 transition-all duration-200 active:scale-[0.98]">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-[#dc2626] text-white text-sm font-medium shadow-lg shadow-[#dc2626]/20 hover:bg-[#b91c1c] hover:shadow-xl hover:shadow-[#dc2626]/30 transition-all duration-200 active:scale-[0.98]">
                        Create User
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
