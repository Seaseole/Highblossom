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
            tab: '{{ session('showTwoFactorSetup') || session('showRecoveryCodes') ? 'security' : 'profile' }}',
            showDeleteModal: false,
            showTwoFactorSetup: {{ session('showTwoFactorSetup') ? 'true' : 'false' }},
            showRecoveryCodes: {{ session('showRecoveryCodes') ? 'true' : 'false' }},
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

                        @if($user->hasEnabledTwoFactorAuthentication())
                            {{-- TFA is fully enabled and confirmed --}}
                            <div class="flex items-center gap-2 mb-6 p-3 rounded-xl bg-green-500/10 border border-green-500/20">
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="text-sm font-medium text-green-500">Two-factor authentication is enabled.</span>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <form action="{{ route('admin.profile.two-factor.recovery-codes.regenerate') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-2 px-4 rounded-xl shadow-lg shadow-admin-accent/20 transition-all">
                                        Regenerate Recovery Codes
                                    </button>
                                </form>

                                <form action="{{ route('admin.profile.two-factor.disable') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl transition-all">
                                        Disable Two-Factor Authentication
                                    </button>
                                </form>
                            </div>
                        @elseif($user->two_factor_secret && !$user->two_factor_confirmed_at)
                            {{-- TFA secret generated but not yet confirmed --}}
                            <div class="flex items-center gap-2 mb-6 p-3 rounded-xl bg-yellow-500/10 border border-yellow-500/20">
                                <svg class="w-5 h-5 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <span class="text-sm font-medium text-yellow-500">Finish setting up two-factor authentication by scanning the QR code below.</span>
                            </div>

                            <div class="space-y-6">
                                <div class="flex flex-col items-center gap-4 p-6 rounded-xl bg-white">
                                    {!! $user->twoFactorQrCodeSvg() !!}
                                </div>

                                <div class="space-y-2">
                                    <p class="text-sm font-medium text-admin-text">Setup Key</p>
                                    <p class="text-xs text-admin-text-muted mb-1">If you are unable to scan the QR code, enter this key manually in your authenticator app.</p>
                                    <code class="block p-3 rounded-xl bg-admin-surface-alt text-sm text-admin-text font-mono break-all select-all">{{ decrypt($user->two_factor_secret) }}</code>
                                </div>

                                <form action="{{ route('admin.profile.two-factor.confirm') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-admin-text-muted">Verification Code</label>
                                        <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" class="w-full admin-form-input max-w-xs" placeholder="Enter 6-digit code">
                                        @error('code', 'confirmTwoFactorAuthentication')
                                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-2 px-4 rounded-xl shadow-lg shadow-admin-accent/20 transition-all">
                                        Confirm & Enable
                                    </button>
                                </form>

                                <form action="{{ route('admin.profile.two-factor.disable') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-sm text-admin-text-muted hover:text-red-500 transition-colors">
                                        Cancel Setup
                                    </button>
                                </form>
                            </div>
                        @else
                            {{-- TFA not enabled --}}
                            <form action="{{ route('admin.profile.two-factor.enable') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-2 px-4 rounded-xl shadow-lg shadow-admin-accent/20 transition-all">
                                    Enable Two-Factor Authentication
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Recovery Codes Modal --}}
                    <div x-show="showRecoveryCodes" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center" style="display: none;">
                        <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6 max-w-md w-full mx-4">
                            <h3 class="text-xl font-bold text-admin-text mb-2">Recovery Codes</h3>
                            <p class="text-sm text-admin-text-muted mb-4">Store these recovery codes in a secure location. They can be used to recover access to your account if you lose your two-factor authentication device.</p>

                            @if(session('recoveryCodes'))
                                <div class="grid grid-cols-2 gap-2 mb-6 p-4 rounded-xl bg-admin-surface-alt font-mono text-sm">
                                    @foreach(session('recoveryCodes') as $code)
                                        <div class="text-admin-text select-all">{{ $code }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <button type="button" @click="showRecoveryCodes = false" class="w-full bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-2 px-4 rounded-xl transition-all">
                                Done
                            </button>
                        </div>
                    </div>

                    {{-- TFA Setup Modal (shown after clicking Enable) --}}
                    <div x-show="showTwoFactorSetup" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center" style="display: none;">
                        <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
                            <h3 class="text-xl font-bold text-admin-text mb-2">Set Up Two-Factor Authentication</h3>
                            <p class="text-sm text-admin-text-muted mb-6">Scan the QR code below with your authenticator app (e.g. Google Authenticator, Authy), then enter the verification code to confirm.</p>

                            @if(session('twoFactorQrCode'))
                                <div class="flex flex-col items-center gap-4 p-6 rounded-xl bg-white mb-4">
                                    {!! session('twoFactorQrCode') !!}
                                </div>

                                @if(session('twoFactorSecret'))
                                    <div class="space-y-2 mb-6">
                                        <p class="text-sm font-medium text-admin-text">Setup Key</p>
                                        <code class="block p-3 rounded-xl bg-admin-surface-alt text-sm text-admin-text font-mono break-all select-all">{{ session('twoFactorSecret') }}</code>
                                    </div>
                                @endif
                            @endif

                            <form action="{{ route('admin.profile.two-factor.confirm') }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">Verification Code</label>
                                    <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" class="w-full admin-form-input" placeholder="Enter 6-digit code">
                                    @error('code', 'confirmTwoFactorAuthentication')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" @click="showTwoFactorSetup = false" class="flex-1 bg-transparent text-admin-text-muted border border-admin-border py-2 px-4 rounded-xl hover:bg-admin-surface-alt transition-all">
                                        Close
                                    </button>
                                    <button type="submit" class="flex-1 bg-admin-accent hover:bg-admin-accent/90 text-white font-bold py-2 px-4 rounded-xl transition-all">
                                        Confirm
                                    </button>
                                </div>
                            </form>
                        </div>
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
</x-layouts::admin>
