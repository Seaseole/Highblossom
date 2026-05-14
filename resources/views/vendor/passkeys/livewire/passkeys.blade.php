<div class="space-y-6">
    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6">
        <h3 class="text-lg font-bold text-admin-text mb-2">{{ __('passkeys::passkeys.passkeys') }}</h3>
        <p class="text-sm text-admin-text-muted mb-6">Passkeys allow you to sign in securely without a password using your device's biometric sensors or security keys.</p>

        <form id="passkeyForm" wire:submit="validatePasskeyProperties" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 space-y-2">
                    <label for="name" class="text-sm font-medium text-admin-text-muted">{{ __('passkeys::passkeys.name') }}</label>
                    <input autocomplete="off" type="text" wire:model="name" placeholder="e.g. MacBook TouchID, YubiKey" class="w-full admin-form-input">
                    @error('name')
                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-admin-accent/20 transition-all active:scale-[0.97]">
                    {{ __('passkeys::passkeys.create') }}
                </button>
            </div>
        </form>
    </div>

    @if($passkeys->count() > 0)
        <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle overflow-hidden">
            <div class="px-6 py-4 border-b border-admin-border-subtle bg-admin-surface-alt/30">
                <h4 class="text-sm font-bold text-admin-text uppercase tracking-wider">Registered Passkeys</h4>
            </div>
            <div class="divide-y divide-admin-border-subtle">
                @foreach($passkeys as $passkey)
                    <div class="flex justify-between items-center p-6 hover:bg-admin-surface-alt/20 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-admin-accent/10 flex items-center justify-center text-admin-accent">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-base font-bold text-admin-text">
                                    {{ $passkey->name }}
                                </div>
                                <div class="text-xs text-admin-text-muted">
                                    {{ __('passkeys::passkeys.last_used') }}: {{ $passkey->last_used_at?->diffForHumans() ?? __('passkeys::passkeys.not_used_yet') }}
                                </div>
                            </div>
                        </div>

                        <button wire:click="deletePasskey({{ $passkey->id }})" 
                                wire:confirm="Are you sure you want to delete this passkey?"
                                class="text-red-500 hover:text-red-600 p-2 hover:bg-red-500/10 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@include('passkeys::livewire.partials.createScript')
