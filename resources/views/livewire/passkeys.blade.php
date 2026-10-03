<div
    class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 md:p-8 dark:border-white/10 dark:bg-[#0A0A0F]"
    x-data="{
        async register() {
            const name = prompt('Give this passkey a name:', 'My Security Key');
            if (! name || ! name.trim()) return;

            try {
                // Show a loading state if needed by UI
                await window.Passkeys.register({ name: name.trim() });

                // Dispatch a Livewire event to handle the refresh reliably
                Livewire.dispatch('passkeyRegistered');

                window.dispatchEvent(
                    new CustomEvent('toast', {
                        detail: { type: 'success', message: 'Passkey registered successfully.' },
                    }),
                );
            } catch (e) {
                console.error('Passkey registration failed', e);
                if (! ['UserCancelledError', 'NotAllowedError', 'AbortError'].includes(e.name)) {
                    window.dispatchEvent(
                        new CustomEvent('toast', {
                            detail: { type: 'error', message: e.message || 'Failed to register passkey. Please try again.' },
                        }),
                    );
                }
            }
        },
        rename(id, currentName) {
            const newName = prompt('Enter new name for passkey:', currentName);
            if (newName && newName.trim() && newName !== currentName) {
                Livewire.dispatch('renamePasskeyRequest', { id: id, name: newName.trim() });
            }
        },
    }"
>
    <div class="mb-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Security Keys</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage the devices you use to securely sign in.</p>
        </div>
        <button
            @click="register()"
            class="rounded-full bg-gray-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] disabled:opacity-50 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
        >
            <span wire:loading.remove wire:target="register">Register Device</span>
            <span wire:loading wire:target="register">Registering...</span>
        </button>
    </div>

    @if ($passkeys->isEmpty())
        <div class="rounded-2xl border-2 border-dashed border-gray-100 py-12 text-center dark:border-white/5">
            <p class="text-sm text-gray-500 dark:text-gray-400">No security keys registered yet.</p>
        </div>
    @else
        <ul class="space-y-3">
            @foreach ($passkeys as $passkey)
                <li
                    class="group flex items-center justify-between rounded-2xl border border-gray-100 bg-gray-50 p-5 transition-colors hover:border-gray-200 dark:border-white/5 dark:bg-white/5 dark:hover:border-white/10"
                    data-passkey-id="{{ $passkey->id }}"
                    data-passkey-name="{{ $passkey->name }}"
                >
                    <div class="flex min-w-0 items-center gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-100 bg-white text-gray-400 dark:border-white/10 dark:bg-[#16161D]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <span class="truncate font-medium text-gray-900 dark:text-gray-100">{{ $passkey->name }}</span>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <button
                            @click="rename($el.closest('li').dataset.passkeyId, $el.closest('li').dataset.passkeyName)"
                            class="text-sm font-medium text-gray-400 transition-colors hover:text-blue-500 sm:opacity-0 sm:group-hover:opacity-100"
                        >
                            Rename
                        </button>
                        <button
                            wire:click="deletePasskey({{ $passkey->id }})"
                            wire:confirm="Are you sure you want to delete this passkey?"
                            class="text-sm font-medium text-gray-400 transition-colors hover:text-red-500 disabled:opacity-50 sm:opacity-0 sm:group-hover:opacity-100"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove wire:target="deletePasskey({{ $passkey->id }})">Remove</span>
                            <span wire:loading wire:target="deletePasskey({{ $passkey->id }})">Removing...</span>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
