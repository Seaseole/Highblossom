<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Profile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Profile']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-4xl space-y-10 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Profile Settings</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your account settings and preferences.</p>
        </div>

        <div
            x-data="{ 
            tab: '<?php echo e(request()->query('tab', 'profile')); ?>',
            showDeleteModal: false,
            showRecoveryCodesModal: false,
            recoveryCodes: <?php echo json_encode(session('recovery_codes', []), 512) ?>,
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
                fetch('<?php echo e(route('admin.profile.two-factor.recovery-codes')); ?>', {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async r => {
                    if (! r.ok) {
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
                if (! confirm('Regenerate recovery codes? Old ones will stop working.')) return;
                
                this.loadingCodes = true;
                fetch('<?php echo e(route('admin.profile.two-factor.regenerate-recovery-codes')); ?>', {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                })
                .then(async r => {
                    if (! r.ok) {
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
        }"
        >
            <!-- Tabs Navigation -->
            <div class="mb-8 flex space-x-1 border-b border-gray-200 dark:border-white/10">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['profile' => 'Profile', 'appearance' => 'Appearance', 'security' => 'Security', 'passkeys' => 'Passkeys']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button
                        type="button"
                        @click="tab = '<?php echo e($key); ?>'"
                        :class="tab === '<?php echo e($key); ?>' ? 'border-gray-900 dark:border-white text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="border-b-2 px-1 pb-4 text-sm font-medium transition-colors"
                    >
                        <?php echo e($label); ?>

                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <!-- Tab Contents -->
            <div class="space-y-8">
                <!-- Profile Tab -->
                <div
                    x-show="tab === 'profile'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8"
                >
                    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h3>
                        <form action="<?php echo e(route('admin.profile.update')); ?>" method="POST" class="space-y-6">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="<?php echo e($user->name); ?>"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        value="<?php echo e($user->email); ?>"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    />
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                >
                                    Save Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Delete Account -->
                    <div class="rounded-3xl border border-red-100 bg-red-50 p-8 dark:border-red-900/30 dark:bg-red-950/10">
                        <h3 class="mb-2 text-lg font-semibold text-red-700 dark:text-red-400">Delete Account</h3>
                        <p class="mb-6 max-w-lg text-sm text-red-600/80 dark:text-red-400/70">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Please enter your password to confirm you would like to permanently delete your account.
                        </p>
                        <button
                            type="button"
                            @click="showDeleteModal = true"
                            class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-medium text-white transition-all hover:bg-red-700 active:scale-[0.98]"
                        >
                            Delete Account
                        </button>
                    </div>
                </div>

                <!-- Appearance Tab -->
                <div
                    x-show="tab === 'appearance'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8"
                    style="display: none"
                >
                    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Appearance Settings</h3>
                        <form action="<?php echo e(route('admin.profile.appearance.update')); ?>" method="POST" class="space-y-6">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="space-y-4">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Theme Preference</label>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['light' => 'Light', 'dark' => 'Dark', 'auto' => 'Auto']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <label class="relative cursor-pointer">
                                            <input
                                                type="radio"
                                                name="theme"
                                                value="<?php echo e($value); ?>"
                                                <?php echo e($user->theme?->value === $value || ($value === 'auto' && !$user->theme?->value) ? 'checked' : ''); ?>

                                                class="peer sr-only"
                                            />
                                            <div class="rounded-xl border-2 border-gray-100 p-4 transition-all peer-checked:border-gray-900 peer-checked:bg-gray-50 dark:border-white/5 dark:peer-checked:border-white dark:peer-checked:bg-white/5">
                                                <div class="text-center text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    <?php echo e($label); ?>

                                                </div>
                                            </div>
                                        </label>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                >
                                    Save Appearance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Tab -->
                <div
                    x-show="tab === 'security'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8"
                    style="display: none"
                >
                    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Update Password</h3>
                        <form
                            action="<?php echo e(route('admin.profile.password.update')); ?>"
                            method="POST"
                            class="space-y-6"
                            x-data="{
                                generatePassword() {
                                    const length = 16;
                                    const charset =
                                        'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
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
                                },
                            }"
                        >
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                                    <input
                                        type="password"
                                        name="current_password"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    />
                                </div>
                                <div></div>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                                        <button
                                            type="button"
                                            @click="generatePassword()"
                                            class="text-xs text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            Generate Secure
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            name="password"
                                            x-ref="passwordInput"
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-10 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                            data-rules="<?php echo e(\Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString()); ?>"
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
                                    <p class="text-xs text-gray-500" x-text="`Min ${minLen} characters`"></p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                                    <div class="relative">
                                        <input
                                            :type="showConfirmPassword ? 'text' : 'password'"
                                            name="password_confirmation"
                                            x-ref="passwordConfirmInput"
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-10 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                            data-rules="<?php echo e(\Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString()); ?>"
                                        />
                                        <button
                                            type="button"
                                            @click="showConfirmPassword = ! showConfirmPassword"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        >
                                            <svg x-show="! showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.77 9.77 0 012.804-3.704M15.48 15.48l2.58 2.58M12 9a3 3 0 013 3m-3-3a3 3 0 00-3 3m0 0a3 3 0 013-3m0 0l-2.58-2.58M21 21l-9-9m0 0L3 3" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                >
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TFA Component -->
                    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                            Two-Factor Authentication
                        </h3>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                            Secure your account with an extra layer of protection.
                        </p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $user->two_factor_secret): ?>
                            
                            <form action="<?php echo e(route('admin.profile.two-factor.enable')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button
                                    type="submit"
                                    class="rounded-full bg-gray-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                >
                                    Enable Two-Factor
                                </button>
                            </form>
                        <?php elseif($user->two_factor_secret && ! $user->two_factor_confirmed_at): ?>
                            
                            <div class="space-y-6">
                                <div class="inline-block rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                                    <?php echo $qrCodeSvg; ?>

                                </div>

                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Scan this QR code with your authenticator app.
                                </p>

                                <form
                                    action="<?php echo e(route('admin.profile.two-factor.confirm')); ?>"
                                    method="POST"
                                    class="space-y-4"
                                >
                                    <?php echo csrf_field(); ?>
                                    <input
                                        type="text"
                                        name="code"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm dark:border-white/10 dark:bg-white/5"
                                        placeholder="Enter authentication code"
                                    />
                                    <button
                                        type="submit"
                                        class="rounded-full bg-gray-900 px-6 py-2 text-sm font-medium text-white dark:bg-white dark:text-gray-900"
                                    >
                                        Confirm
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 font-medium text-emerald-600 dark:text-emerald-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    <span>Two-factor authentication is enabled.</span>
                                </div>

                                <div class="flex flex-wrap gap-4 pt-2">
                                    <button
                                        type="button"
                                        @click="showCodes()"
                                        :disabled="loadingCodes"
                                        class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                                    >
                                        <span x-show="! loadingCodes">Show Recovery Codes</span>
                                        <span x-show="loadingCodes">Loading...</span>
                                    </button>

                                    <button
                                        type="button"
                                        @click="regenerateCodes()"
                                        :disabled="loadingCodes"
                                        class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                                    >
                                        Regenerate Recovery Codes
                                    </button>

                                    <form action="<?php echo e(route('admin.profile.two-factor.disable')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button
                                            type="submit"
                                            class="rounded-full bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition-all hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400"
                                        >
                                            Disable TFA
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Passkeys Tab -->
                <div
                    x-show="tab === 'passkeys'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6"
                    style="display: none"
                >
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('passkeys');

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3983632849-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                </div>
            </div>

            <!-- Delete Account Modal -->
            <div
                x-show="showDeleteModal"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4 backdrop-blur-sm dark:bg-black/60"
                style="display: none"
            >
                <div
                    x-show="showDeleteModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="w-full max-w-md rounded-3xl border border-gray-200 bg-white p-8 shadow-2xl dark:border-white/10 dark:bg-[#0A0A0F]"
                >
                    <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Delete Account</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete your account? All of your data will be permanently removed. This
                        action cannot be undone.
                    </p>

                    <form action="<?php echo e(route('admin.profile.destroy')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button
                                type="button"
                                @click="showDeleteModal = false"
                                class="flex-1 rounded-full bg-gray-100 px-4 py-2.5 font-medium text-gray-700 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="flex-1 rounded-full bg-red-600 px-4 py-2.5 font-medium text-white transition-all hover:bg-red-700"
                            >
                                Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recovery Codes Modal -->
            <div
                x-show="showRecoveryCodesModal"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4 backdrop-blur-sm dark:bg-black/60"
                style="display: none"
                @keydown.escape.window="showRecoveryCodesModal = false"
            >
                <div
                    x-show="showRecoveryCodesModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="w-full max-w-md rounded-3xl border border-gray-200 bg-white p-8 shadow-2xl dark:border-white/10 dark:bg-[#0A0A0F]"
                >
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Recovery Codes</h3>
                        <button
                            @click="showRecoveryCodesModal = false"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        Store these recovery codes in a secure password manager. They can be used to recover access to
                        your account if your two-factor authentication device is lost.
                    </p>

                    <div class="mb-6 grid grid-cols-1 gap-2 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                        <template x-for="code in recoveryCodes" :key="code">
                            <div
                                class="rounded-lg border border-gray-100 bg-white py-2 text-center font-mono text-sm text-gray-900 dark:border-white/5 dark:bg-[#16161D] dark:text-gray-100"
                                x-text="code"
                            ></div>
                        </template>
                    </div>

                    <div class="flex gap-4">
                        <button
                            type="button"
                            @click="
                                const text = recoveryCodes.join('\n');
                                navigator.clipboard.writeText(text).then(() => alert('Copied!'));
                            "
                            class="flex-1 rounded-full bg-gray-100 px-4 py-2.5 font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                        >
                            Copy
                        </button>
                        <button
                            type="button"
                            @click="showRecoveryCodesModal = false"
                            class="flex-1 rounded-full bg-gray-900 px-4 py-2.5 font-medium text-white transition-all hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal501803f3e4defcbbeaedee798b98ded4)): ?>
<?php $attributes = $__attributesOriginal501803f3e4defcbbeaedee798b98ded4; ?>
<?php unset($__attributesOriginal501803f3e4defcbbeaedee798b98ded4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal501803f3e4defcbbeaedee798b98ded4)): ?>
<?php $component = $__componentOriginal501803f3e4defcbbeaedee798b98ded4; ?>
<?php unset($__componentOriginal501803f3e4defcbbeaedee798b98ded4); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\admin\profile\index.blade.php ENDPATH**/ ?>