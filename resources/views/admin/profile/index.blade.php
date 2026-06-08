<x-layouts::admin title="Profile">
    <div class="max-w-4xl mx-auto space-y-10 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Profile Settings</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your account settings and preferences.</p>
        </div>

        <div x-data="{ 
            tab: '{{ request()->query('tab', 'profile') }}',
            showDeleteModal: false,
            showRecoveryCodesModal: false,
            recoveryCodes: @json(session('recovery_codes', [])),
            loadingCodes: false,
            confirmCode: '',

            init() {
                this.$watch('tab', value => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', value);
                    window.history.replaceState({}, '', url.toString());
                });

                if (this.recoveryCodes.length > 0) {
                    this.showRecoveryCodesModal = true;
                }
            },

            showCodes() {
                this.loadingCodes = true;
                fetch('{{ route('admin.profile.two-factor.recovery-codes') }}', {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async r => {
                    if (!r.ok) {
                        const data = await r.json().catch(() => ({}));
                        throw new Error(data.message || 'Failed to fetch recovery codes');
                    }
                    return r.json();
                })
                .then(data => {
                    if (data.recovery_codes && Array.isArray(data.recovery_codes)) {
                        this.recoveryCodes = data.recovery_codes;
                        this.showRecoveryCodesModal = true;
                    }
                })
                .catch(e => {
                    console.error('TFA Error:', e);
                    alert(e.message);
                })
                .finally(() => this.loadingCodes = false);
            },

            regenerateCodes() {
                if (!confirm('Regenerate recovery codes? Old ones will stop working.')) return;
                
                this.loadingCodes = true;
                fetch('{{ route('admin.profile.two-factor.regenerate-recovery-codes') }}', {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(async r => {
                    if (!r.ok) {
                        const data = await r.json().catch(() => ({}));
                        throw new Error(data.message || 'Failed to regenerate codes');
                    }
                    return r.json();
                })
                .then(data => {
                    if (data.recovery_codes && Array.isArray(data.recovery_codes)) {
                        this.recoveryCodes = data.recovery_codes;
                        this.showRecoveryCodesModal = true;
                    }
                })
                .catch(e => {
                    console.error('TFA Error:', e);
                    alert(e.message);
                })
                .finally(() => this.loadingCodes = false);
            }
        }">
            <!-- Tabs Navigation -->
            <div class="flex border-b border-gray-200 dark:border-white/10 mb-8 space-x-1">
                @foreach(['profile' => 'Profile', 'appearance' => 'Appearance', 'security' => 'Security', 'passkeys' => 'Passkeys'] as $key => $label)
                    <button type="button" 
                            @click="tab = '{{ $key }}'" 
                            :class="tab === '{{ $key }}' ? 'border-gray-900 dark:border-white text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'" 
                            class="pb-4 px-1 border-b-2 font-medium transition-colors text-sm">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Tab Contents -->
            <div class="space-y-8">
                <!-- Profile Tab -->
                <div x-show="tab === 'profile'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-8">
                    
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Profile Information</h3>
                        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-gray-900 dark:focus:ring-white outline-none transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-gray-900 dark:focus:ring-white outline-none transition-all">
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                                    Save Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-red-50 dark:bg-red-950/10 rounded-3xl border border-red-100 dark:border-red-900/30 p-8">
                        <h3 class="text-lg font-semibold text-red-700 dark:text-red-400 mb-2">Delete Account</h3>
                        <p class="text-sm text-red-600/80 dark:text-red-400/70 mb-6 max-w-lg">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                        <button type="button" @click="showDeleteModal = true" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-5 rounded-full text-sm transition-all active:scale-[0.98]">
                            Delete Account
                        </button>
                    </div>
                </div>

                <!-- Appearance Tab -->
                <div x-show="tab === 'appearance'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-8" style="display: none;">
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Appearance Settings</h3>
                        <form action="{{ route('admin.profile.appearance.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Theme Preference</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    @foreach(['light' => 'Light', 'dark' => 'Dark', 'auto' => 'Auto'] as $value => $label)
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="theme" value="{{ $value }}" {{ $user->theme?->value === $value || ($value === 'auto' && !$user->theme?->value) ? 'checked' : '' }} class="peer sr-only">
                                            <div class="p-4 rounded-xl border-2 border-gray-100 dark:border-white/5 peer-checked:border-gray-900 dark:peer-checked:border-white peer-checked:bg-gray-50 dark:peer-checked:bg-white/5 transition-all">
                                                <div class="text-center text-sm font-medium text-gray-900 dark:text-gray-100">{{ $label }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                                    Save Appearance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Tab -->
                <div x-show="tab === 'security'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-8" style="display: none;">
                    
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Update Password</h3>
                        <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-6" x-data="{
                            generatePassword() {
                                const length = 16;
                                const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
                                let retVal = '';
                                for (let i = 0; i < length; ++i) {
                                    retVal += charset.charAt(Math.floor(Math.random() * charset.length));
                                }
                                $refs.passwordInput.value = retVal;
                                $refs.passwordConfirmInput.value = retVal;
                            },
                            showPassword: false,
                            showConfirmPassword: false,
                            minLen: 8,
                            init() {
                                const passInput = $refs.passwordInput;
                                if (passInput && passInput.dataset.rules) {
                                    const minMatch = passInput.dataset.rules.match(/min:(\d+)/);
                                    if (minMatch) this.minLen = parseInt(minMatch[1]);
                                }
                            }
                        }">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                                    <input type="password" name="current_password" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                </div>
                                <div></div>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                                        <button type="button" @click="generatePassword()" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Generate Secure</button>
                                    </div>
                                    <div class="relative">
                                        <input :type="showPassword ? 'text' : 'password'" name="password" x-ref="passwordInput" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white pr-10"
                                           data-rules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}">
                                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.77 9.77 0 012.804-3.704M15.48 15.48l2.58 2.58M12 9a3 3 0 013 3m-3-3a3 3 0 00-3 3m0 0a3 3 0 013-3m0 0l-2.58-2.58M21 21l-9-9m0 0L3 3"/></svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500" x-text="`Min ${minLen} characters`"></p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                                    <div class="relative">
                                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" x-ref="passwordConfirmInput" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white pr-10"
                                               data-rules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}">
                                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.77 9.77 0 012.804-3.704M15.48 15.48l2.58 2.58M12 9a3 3 0 013 3m-3-3a3 3 0 00-3 3m0 0a3 3 0 013-3m0 0l-2.58-2.58M21 21l-9-9m0 0L3 3"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TFA Component -->
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Two-Factor Authentication</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Secure your account with an extra layer of protection.</p>
                        
                        @if(!$user->two_factor_secret)
                            {{-- Step 1: Enable --}}
                            <form action="{{ route('admin.profile.two-factor.enable') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-5 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                                    Enable Two-Factor
                                </button>
                            </form>
                        @elseif($user->two_factor_secret && !$user->two_factor_confirmed_at)
                            {{-- Step 2: Setup (Unconfirmed) --}}
                            <div class="space-y-6">
                                <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5 inline-block">
                                    {!! $qrCodeSvg !!}
                                </div>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-300">Scan this QR code with your authenticator app.</p>

                                <form action="{{ route('admin.profile.two-factor.confirm') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="text" name="code" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" placeholder="Enter authentication code">
                                    <button type="submit" class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-medium py-2 px-6 rounded-full text-sm">Confirm</button>
                                </form>
                            </div>
                        @else
                            {{-- Step 3: Confirmed --}}
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    <span>Two-factor authentication is enabled.</span>
                                </div>

                                <div class="flex flex-wrap gap-4 pt-2">
                                    <button type="button" 
                                            @click="showCodes()"
                                            :disabled="loadingCodes"
                                            class="bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white font-medium py-2 px-4 rounded-full text-sm transition-all">
                                        <span x-show="!loadingCodes">Show Recovery Codes</span>
                                        <span x-show="loadingCodes">Loading...</span>
                                    </button>

                                    <button type="button" 
                                            @click="regenerateCodes()"
                                            :disabled="loadingCodes"
                                            class="bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white font-medium py-2 px-4 rounded-full text-sm transition-all">
                                        Regenerate Recovery Codes
                                    </button>

                                    <form action="{{ route('admin.profile.two-factor.disable') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium py-2 px-4 rounded-full text-sm transition-all">
                                            Disable TFA
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Passkeys Tab -->
                <div x-show="tab === 'passkeys'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6" style="display: none;">
                    @livewire('passkeys')
                </div>

            </div>

    <!-- Delete Account Modal -->
    <div x-show="showDeleteModal" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 dark:bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
        <div x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 max-w-md w-full shadow-2xl">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Delete Account</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete your account? All of your data will be permanently removed. This action cannot be undone.</p>
            
            <form action="{{ route('admin.profile.destroy') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" name="password" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="showDeleteModal = false" class="flex-1 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300 font-medium py-2.5 px-4 rounded-full transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-full transition-all">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recovery Codes Modal -->
    <div x-show="showRecoveryCodesModal" 
         x-transition:enter="transition-opacity ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-900/50 dark:bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         style="display: none;"
         @keydown.escape.window="showRecoveryCodesModal = false">
        <div x-show="showRecoveryCodesModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 max-w-md w-full shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Recovery Codes</h3>
                <button @click="showRecoveryCodesModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two-factor authentication device is lost.</p>
            
            <div class="grid grid-cols-1 gap-2 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 mb-6">
                <template x-for="code in recoveryCodes" :key="code">
                    <div class="font-mono text-sm text-gray-900 dark:text-gray-100 text-center py-2 bg-white dark:bg-[#16161D] rounded-lg border border-gray-100 dark:border-white/5" x-text="code"></div>
                </template>
            </div>
            
            <div class="flex gap-4">
                <button type="button" 
                        @click="const text = recoveryCodes.join('\n'); navigator.clipboard.writeText(text).then(() => alert('Copied!'))"
                        class="flex-1 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white font-medium py-2.5 px-4 rounded-full transition-all">
                    Copy
                </button>
                <button type="button" @click="showRecoveryCodesModal = false" class="flex-1 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-4 rounded-full transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>
        </div>
    </div>
</x-layouts::admin>
