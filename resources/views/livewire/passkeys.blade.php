<div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm" x-data="{
    async register() {
        try {
            const name = prompt('Give this passkey a name:', 'My Security Key');
            if (!name) return;

            // Show a loading state if needed by UI
            await window.Passkeys.register({ name: name });
            
            // Dispatch a Livewire event to handle the refresh reliably
            Livewire.dispatch('passkeyRegistered');

            $dispatch('toast', {
                type: 'success',
                message: 'Passkey registered successfully.'
            });
        } catch (e) {
            console.error('Passkey registration failed', e);
            if (e.name !== 'NotAllowedError' && e.name !== 'AbortError') {
                $dispatch('toast', {
                    type: 'error',
                    message: 'Failed to register passkey. Please try again.'
                });
            }
            return;
        }
    },
    rename(id, currentName) {
        const newName = prompt('Enter new name for passkey:', currentName);
        if (newName && newName !== currentName) {
            Livewire.dispatch('renamePasskeyRequest', { id: id, name: newName });
        }
    }
}">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Security Keys</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage the devices you use to securely sign in.</p>
        </div>
        <button @click="register()" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-5 rounded-full text-sm transition-all shadow-sm active:scale-[0.98] disabled:opacity-50">
            <span wire:loading.remove wire:target="register">Register Device</span>
            <span wire:loading wire:target="register">Registering...</span>
        </button>
    </div>
    
    @if($passkeys->isEmpty())
        <div class="text-center py-12 border-2 border-dashed border-gray-100 dark:border-white/5 rounded-2xl">
            <p class="text-sm text-gray-500 dark:text-gray-400">No security keys registered yet.</p>
        </div>
    @else
        <ul class="space-y-3">
            @foreach($passkeys as $passkey)
                <li class="flex items-center justify-between p-5 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 group hover:border-gray-200 dark:hover:border-white/10 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-[#16161D] flex items-center justify-center border border-gray-100 dark:border-white/10 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $passkey->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            @click="rename({{ $passkey->id }}, '{{ $passkey->name }}')"
                            class="text-gray-400 hover:text-blue-500 font-medium text-sm transition-colors opacity-0 group-hover:opacity-100"
                        >
                            Rename
                        </button>
                        <button 
                            wire:click="deletePasskey({{ $passkey->id }})" 
                            wire:confirm="Are you sure you want to delete this passkey?"
                            class="text-gray-400 hover:text-red-500 font-medium text-sm transition-colors opacity-0 group-hover:opacity-100 disabled:opacity-50"
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
