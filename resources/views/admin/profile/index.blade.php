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
            showDeleteModal: false
        }">
            <!-- Tabs Navigation -->
            <div class="flex border-b border-admin-border-subtle space-x-8">
                <button type="button" @click="tab = 'profile'" :class="tab === 'profile' ? 'border-[#DC2626] text-admin-text' : 'border-transparent text-admin-text-muted hover:text-admin-text'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Profile
                </button>
                <button type="button" @click="tab = 'appearance'" :class="tab === 'appearance' ? 'border-[#DC2626] text-admin-text' : 'border-transparent text-admin-text-muted hover:text-admin-text'" class="pb-4 border-b-2 font-medium transition-all duration-300">
                    Appearance
                </button>
                <button type="button" @click="tab = 'security'" :class="tab === 'security' ? 'border-[#DC2626] text-admin-text' : 'border-transparent text-admin-text-muted hover:text-admin-text'" class="pb-4 border-b-2 font-medium transition-all duration-300">
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
                                        <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="w-full admin-form-input">
                                    @error('email')
                                        <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">
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
                                        <div class="p-4 rounded-xl border-2 border-admin-border-subtle peer-checked:border-[#DC2626] peer-checked:bg-[#DC2626]/10 transition-all">
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
                                        <div class="p-4 rounded-xl border-2 border-admin-border-subtle peer-checked:border-[#DC2626] peer-checked:bg-[#DC2626]/10 transition-all">
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
                                        <div class="p-4 rounded-xl border-2 border-admin-border-subtle peer-checked:border-[#DC2626] peer-checked:bg-[#DC2626]/10 transition-all">
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
                                <button type="submit" class="bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">
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
                                        <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">New Password</label>
                                    <input type="password" name="password" class="w-full admin-form-input">
                                    @error('password')
                                        <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-admin-text-muted">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="w-full admin-form-input">
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all active:scale-[0.98]">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Two-Factor Authentication -->
                    <div class="bg-admin-surface rounded-2xl border border-admin-border-subtle p-6">
                        <h3 class="text-lg font-bold text-admin-text mb-2">Two-Factor Authentication</h3>
                        <p class="text-sm text-admin-text-muted mb-6">Add an extra layer of security to your account by enabling two-factor authentication.</p>
                        
                        @if($user->two_factor_secret)
                            <form action="{{ route('admin.profile.two-factor.disable') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl transition-all">
                                    Disable Two-Factor Authentication
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.profile.two-factor.enable') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold py-2 px-4 rounded-xl shadow-lg shadow-[#DC2626]/20 transition-all">
                                    Enable Two-Factor Authentication
                                </button>
                            </form>
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
                        <p class="mt-2 text-sm text-[#DC2626]">{{ $message }}</p>
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
