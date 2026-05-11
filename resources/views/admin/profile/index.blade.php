<x-layouts::admin title="Profile">
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-admin-text font-headline">Profile Settings</h1>
                <p class="text-admin-text-muted mt-1 text-sm">Manage your account settings and preferences</p>
            </div>
        </div>

        <div x-data="{ 
            tab: 'profile',
            showDeleteModal: false,
            showTwoFactorModal: false,
            twoFactorStep: 1,
            twoFactorData: null,
            twoFactorSecret: '',
            twoFactorCode: '',
            recoveryCodes: [],
            showRecoveryCodes: false,
            loading: false,
            errors: {}
        }">
            <!-- Tabs Navigation -->
            <div class="flex border-b border-admin-border-subtle space-x-8">
                <button type="button" @click="tab = 'profile'" :class="tab === 'profile' ? 'border-admin-accent text-admin-text' : 'border-transparent text-admin-text-muted hover:text-admin-text'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Profile
                </button>
                <button type="button" @click="tab = 'appearance'" :class="tab === 'appearance' ? 'border-admin-accent text-admin-text' : 'border-transparent text-admin-text-muted hover:text-admin-text'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Appearance
                </button>
                <button type="button" @click="tab = 'security'" :class="tab === 'security' ? 'border-admin-accent text-admin-text' : 'border-transparent text-admin-text-muted hover:text-admin-text'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Security
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="mt-8">
                <!-- Profile Tab -->
                <div x-show="tab === 'profile'" class="space-y-6">
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6">
                        <h3 class="text-lg font-bold text-admin-text mb-6">Profile Information</h3>
                        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="w-full admin-form-input">
                                    @error('name')
                                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="w-full admin-form-input">
                                    @error('email')
                                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-admin-accent/20 transition-all active:scale-[0.98]">
                                    Save Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-admin-surface rounded-2xl border border-red-500/20 p-6">
                        <h3 class="text-lg font-bold text-red-500 mb-2">Delete Account</h3>
                        <p class="text-sm text-admin-text-muted mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                        <button type="button" @click="showDeleteModal = true" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl transition-all">
                            Delete Account
                        </button>
                    </div>
                </div>

                <!-- Appearance Tab -->
                <div x-show="tab === 'appearance'" class="space-y-6" style="display: none;">
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6">
                        <h3 class="text-lg font-bold text-admin-text mb-6">Appearance Settings</h3>
                        <form action="{{ route('admin.profile.appearance.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <label class="text-sm font-medium text-admin-text-muted">Theme Preference</label>
                                <div class="grid grid-cols-3 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="theme_preference" value="light" {{ $user->theme_preference === 'light' ? 'checked' : '' }} class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-admin-border-subtle peer-checked:border-admin-accent peer-checked:bg-admin-accent/10 transition-all">
                                            <div class="text-center">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <span class="text-sm font-medium text-admin-text">Light</span>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="theme_preference" value="dark" {{ $user->theme_preference === 'dark' ? 'checked' : '' }} class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-admin-border-subtle peer-checked:border-admin-accent peer-checked:bg-admin-accent/10 transition-all">
                                            <div class="text-center">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                                </svg>
                                                <span class="text-sm font-medium text-admin-text">Dark</span>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="theme_preference" value="auto" {{ $user->theme_preference === 'auto' || !$user->theme_preference ? 'checked' : '' }} class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-admin-border-subtle peer-checked:border-admin-accent peer-checked:bg-admin-accent/10 transition-all">
                                            <div class="text-center">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-sm font-medium text-admin-text">Auto</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <p class="text-xs text-admin-text-muted">Auto will match your system's theme preference.</p>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-admin-accent/20 transition-all active:scale-[0.98]">
                                    Save Appearance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Tab -->
                <div x-show="tab === 'security'" class="space-y-6" style="display: none;">
                    <!-- Password -->
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6">
                        <h3 class="text-lg font-bold text-admin-text mb-6">Update Password</h3>
                        <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">Current Password</label>
                                    <input type="password" name="current_password" class="w-full admin-form-input">
                                    @error('current_password')
                                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">New Password</label>
                                    <input type="password" name="password" class="w-full admin-form-input">
                                    @error('password')
                                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="w-full admin-form-input">
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-admin-accent/20 transition-all active:scale-[0.98]">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Two-Factor Authentication -->
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6">
                        <h3 class="text-lg font-bold text-admin-text mb-2">Two-Factor Authentication</h3>
                        <p class="text-sm text-admin-text-muted mb-6">Add an extra layer of security to your account by enabling two-factor authentication.</p>
                        
                        @if($user->two_factor_secret && $user->two_factor_confirmed_at)
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-green-500/10 border border-green-500/20 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0m-6-4l-2-2m0 0l2 2m0-6l2-2" />
                                        </svg>
                                        <div>
                                            <p class="font-medium text-green-600">Two-factor authentication is enabled</p>
                                            <p class="text-sm text-admin-text-muted">Your account is protected with 2FA</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="showTwoFactorModal = true" class="text-admin-accent hover:text-admin-accent/80 text-sm font-medium">
                                        Manage Settings
                                    </button>
                                </div>
                                
                                <button type="button" @click="showRecoveryCodes = !showRecoveryCodes" class="text-admin-text-muted hover:text-admin-text text-sm font-medium">
                                    <span x-show="!showRecoveryCodes">Show Recovery Codes</span>
                                    <span x-show="showRecoveryCodes">Hide Recovery Codes</span>
                                </button>
                                
                                <div x-show="showRecoveryCodes" x-transition class="mt-4 p-4 bg-admin-surface-alt border border-admin-border-subtle rounded-xl space-y-2">
                                    <p class="text-sm text-admin-text-muted mb-3">Store these recovery codes in a safe place. You can use them to access your account if you lose access to your authenticator device.</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <template x-for="code in recoveryCodes" :key="code">
                                            <div class="p-3 bg-admin-surface border border-admin-border-subtle rounded-lg font-mono text-sm text-admin-text">
                                                <span x-text="code"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="mt-4 flex gap-3">
                                        <button type="button" @click="regenerateRecoveryCodes()" class="flex-1 bg-admin-accent hover:bg-admin-accent/90 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                            Regenerate Codes
                                        </button>
                                        <button type="button" @click="downloadRecoveryCodes()" class="flex-1 bg-admin-surface-alt hover:bg-admin-surface border border-admin-border-subtle text-admin-text font-medium py-2 px-4 rounded-lg transition-all">
                                            Download Codes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button type="button" @click="enableTwoFactor()" class="w-full bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-admin-accent/20 transition-all active:scale-[0.98]">
                                Enable Two-Factor Authentication
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div x-show="showDeleteModal" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-admin-text mb-2">Delete Account</h3>
            <p class="text-sm text-admin-text-muted mb-6">Are you sure you want to delete your account? All of your data will be permanently removed. This action cannot be undone.</p>
            
            <form action="{{ route('admin.profile.destroy') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-medium text-admin-text-muted">Password</label>
                    <input type="password" name="password" class="w-full admin-form-input">
                    @error('password')
                        <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="showDeleteModal = false" class="flex-1 bg-transparent text-admin-text-muted border border-admin-border py-2 px-4 rounded-xl hover:bg-admin-surface-alt transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl transition-all">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Two-Factor Setup Modal -->
    <div x-show="showTwoFactorModal" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-admin-text">Set Up Two-Factor Authentication</h3>
                    <button type="button" @click="showTwoFactorModal = false" class="text-admin-text-muted hover:text-admin-text">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Step 1: QR Code Setup -->
                <div x-show="twoFactorStep === 1" class="space-y-6">
                    <div class="text-center space-y-4">
                        <div class="w-32 h-32 mx-auto bg-white p-4 rounded-xl border border-admin-border-subtle">
                            <div x-show="!twoFactorData?.qr_code_url" class="w-full h-full flex items-center justify-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-admin-accent"></div>
                            </div>
                            <img x-show="twoFactorData?.qr_code_url" :src="`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(twoFactorData?.qr_code_url || '')}`" alt="QR Code" class="w-full h-full">
                        </div>
                        
                        <div class="space-y-2">
                            <p class="text-admin-text font-medium">Scan this QR code with your authenticator app</p>
                            <p class="text-sm text-admin-text-muted">Or manually enter this secret key:</p>
                            <div class="p-3 bg-admin-surface-alt border border-admin-border-subtle rounded-lg">
                                <code class="font-mono text-sm text-admin-text" x-text="twoFactorData?.secret || ''"></code>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="twoFactorStep = 2" :disabled="!twoFactorData" class="bg-admin-accent hover:bg-admin-accent/90 disabled:opacity-50 text-white font-medium py-2 px-6 rounded-lg transition-all">
                            Continue
                        </button>
                    </div>
                </div>

                <!-- Step 2: Verification -->
                <div x-show="twoFactorStep === 2" class="space-y-6">
                    <div class="text-center space-y-4">
                        <div class="w-16 h-16 mx-auto bg-green-500/10 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0m-6-4l-2-2m0 0l2 2m0-6l2-2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-admin-text font-medium">Enter the 6-digit code from your authenticator app</p>
                            <p class="text-sm text-admin-text-muted">This verifies that your app is set up correctly</p>
                        </div>
                    </div>

                    <form @submit.prevent="confirmTwoFactor()" class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-admin-text">Authentication Code</label>
                            <input 
                                type="text" 
                                x-model="twoFactorCode" 
                                maxlength="6" 
                                pattern="[0-9]{6}" 
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                class="w-full admin-form-input text-center text-lg tracking-widest"
                                placeholder="000000"
                                :class="{'border-red-500': errors.code}"
                            >
                            <p x-show="errors.code" class="mt-2 text-sm text-red-500" x-text="errors.code"></p>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="twoFactorStep = 1" class="flex-1 bg-admin-surface-alt hover:bg-admin-surface border border-admin-border-subtle text-admin-text font-medium py-2 px-4 rounded-lg transition-all">
                                Back
                            </button>
                            <button type="submit" :disabled="loading || !twoFactorCode" class="flex-1 bg-admin-accent hover:bg-admin-accent/90 disabled:opacity-50 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                <span x-show="!loading">Verify Code</span>
                                <span x-show="loading">Verifying...</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Recovery Codes -->
                <div x-show="twoFactorStep === 3" class="space-y-6">
                    <div class="text-center space-y-4">
                        <div class="w-16 h-16 mx-auto bg-green-500/10 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0m-6-4l-2-2m0 0l2 2m0-6l2-2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-admin-text font-medium">Save your recovery codes</p>
                            <p class="text-sm text-admin-text-muted">Store these codes in a safe place. You can use them to access your account if you lose access to your authenticator device.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="code in recoveryCodes" :key="code">
                                <div class="p-3 bg-admin-surface-alt border border-admin-border-subtle rounded-lg font-mono text-sm text-admin-text">
                                    <span x-text="code"></span>
                                </div>
                            </template>
                        </div>

                        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4">
                            <p class="text-sm text-yellow-600 font-medium">
                                <strong>Important:</strong> These codes are only shown once. Save them now before closing this window.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="downloadRecoveryCodes()" class="flex-1 bg-admin-surface-alt hover:bg-admin-surface border border-admin-border-subtle text-admin-text font-medium py-2 px-4 rounded-lg transition-all">
                                Download Codes
                            </button>
                            <button type="button" @click="finishTwoFactorSetup()" class="flex-1 bg-admin-accent hover:bg-admin-accent/90 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                Finish Setup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div x-show="$wire.flash.message" x-transition class="fixed top-4 right-4 z-50">
        <div class="bg-admin-surface border border-admin-border-subtle rounded-lg shadow-lg p-4 max-w-sm">
            <p class="text-admin-text" x-text="$wire.flash.message"></p>
        </div>
    </div>

    <script>
        function enableTwoFactor() {
            fetch('{{ route("admin.profile.two-factor.enable") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Alpine.store.profile.twoFactorData = data;
                    Alpine.store.profile.twoFactorSecret = data.secret;
                    Alpine.store.profile.recoveryCodes = data.recovery_codes;
                    Alpine.store.profile.twoFactorStep = 2;
                    Alpine.store.profile.errors = {};
                } else {
                    Alpine.store.profile.errors = { general: data.error };
                }
            })
            .catch(error => {
                console.error('Error enabling 2FA:', error);
                Alpine.store.profile.errors = { general: 'Failed to enable two-factor authentication' };
            });
        }

        function confirmTwoFactor() {
            Alpine.store.profile.loading = true;
            Alpine.store.profile.errors = {};

            fetch('{{ route("admin.profile.two-factor.confirm") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    code: Alpine.store.profile.twoFactorCode
                })
            })
            .then(response => response.json())
            .then(data => {
                Alpine.store.profile.loading = false;
                if (data.success) {
                    Alpine.store.profile.twoFactorStep = 3;
                    Alpine.store.profile.errors = {};
                } else {
                    Alpine.store.profile.errors = { code: data.error || 'Invalid code' };
                }
            })
            .catch(error => {
                Alpine.store.profile.loading = false;
                console.error('Error confirming 2FA:', error);
                Alpine.store.profile.errors = { code: 'Failed to verify code' };
            });
        }

        function finishTwoFactorSetup() {
            Alpine.store.profile.showTwoFactorModal = false;
            Alpine.store.profile.twoFactorStep = 1;
            Alpine.store.profile.twoFactorCode = '';
            Alpine.store.profile.errors = {};
            window.location.reload();
        }

        function regenerateRecoveryCodes() {
            if (!confirm('Are you sure? This will invalidate your existing recovery codes.')) {
                return;
            }

            fetch('{{ route("admin.profile.two-factor.regenerate-recovery-codes") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Alpine.store.profile.recoveryCodes = data.recovery_codes;
                }
            })
            .catch(error => {
                console.error('Error regenerating codes:', error);
            });
        }

        function downloadRecoveryCodes() {
            const codes = Alpine.store.profile.recoveryCodes.join('\\n');
            const blob = new Blob([codes], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'recovery-codes.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Initialize Alpine store for profile data
        document.addEventListener('alpine:init', () => {
            Alpine.store.profile = Alpine.reactive({
                showTwoFactorModal: false,
                twoFactorStep: 1,
                twoFactorData: null,
                twoFactorSecret: '',
                twoFactorCode: '',
                recoveryCodes: [],
                showRecoveryCodes: false,
                loading: false,
                errors: {}
            });
        });
    </script>
</x-layouts::admin>
