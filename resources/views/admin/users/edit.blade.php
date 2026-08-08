<x-layouts::admin title="Edit User">
    <div
        class="mx-auto max-w-xl space-y-8 py-10"
        x-data="{
            showPassword: false,
            minLen: 8,
            init() {
                const passInput = $refs.passwordInput;
                if (passInput && passInput.dataset.rules) {
                    const minMatch = passInput.dataset.rules.match(/min:(\d+)/);
                    if (minMatch) this.minLen = parseInt(minMatch[1]);
                }
            },
        }"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit User</h1>
                <p class="text-gray-500 dark:text-gray-400">Update user account details.</p>
            </div>
            <a
                href="{{ route('admin.users.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Users
            </a>
        </div>

        <form
            action="{{ route('admin.users.update', $user) }}"
            method="POST"
            class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
        >
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Password
                        <span class="text-xs font-normal text-gray-500">(leave blank to keep current)</span></label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            x-ref="passwordInput"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-10 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            placeholder="New password"
                            data-rules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        />
                        <button
                            type="button"
                            @click="showPassword = ! showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        >
                            <svg x-show="! showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.77 9.77 0 012.804-3.704M15.48 15.48l2.58 2.58M12 9a3 3 0 013 3m-3-3a3 3 0 00-3 3m0 0a3 3 0 013-3m0 0l-2.58-2.58M21 21l-9-9m0 0L3 3" /></svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500" x-text="`Min ${minLen} characters`"></p>
                </div>

                <div>
                    <label class="mb-4 block text-sm font-medium text-gray-700 dark:text-gray-300">Roles</label>
                    <div class="max-h-64 space-y-2 overflow-y-auto rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                        @foreach ($roles as $role)
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg p-2 transition-colors hover:bg-gray-100 dark:hover:bg-white/5">
                                <input
                                    type="checkbox"
                                    name="roles[]"
                                    value="{{ $role->name }}"
                                    {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                                />
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6 dark:border-white/5">
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >Cancel</a>
                    <button
                        type="submit"
                        class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
