<?php if (isset($component)) { $__componentOriginalb296b6586cd7455368e01c4dab26e36a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb296b6586cd7455368e01c4dab26e36a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::auth-premium','data' => ['title' => __('auth.login.title'),'companyName' => config('app.name'),'brandingSubtitle' => __('auth.login.welcome_back')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-premium'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('auth.login.title')),'companyName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('app.name')),'brandingSubtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('auth.login.welcome_back'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mb-10">
        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">
            <?php echo e(__('auth.login.heading')); ?>

        </h2>
        <p class="text-[#71717A]"><?php echo e(__('auth.login.subheading')); ?></p>
    </div>

    <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>

        <div class="animate-fade-in-up delay-200">
            <label
                for="email"
                class="mb-2 block px-1 text-xs font-bold tracking-widest text-[#71717A] uppercase"
            ><?php echo e(__('auth.login.email_label')); ?></label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-4 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                placeholder="<?php echo e(__('auth.login.email_placeholder')); ?>"
                required
                autofocus
                autocomplete="username"
                value="<?php echo e(old('email')); ?>"
            />
        </div>
        <div class="animate-fade-in-up delay-300">
            <div class="mb-2 flex items-center justify-between px-1">
                <label
                    for="password"
                    class="block text-xs font-bold tracking-widest text-[#71717A] uppercase"
                ><?php echo e(__('auth.login.password_label')); ?></label>
                <a
                    href="<?php echo e(route('password.request')); ?>"
                    class="text-xs font-bold tracking-widest text-[#DC2626] uppercase transition-colors hover:text-[#B91C1C]"
                >
                    <?php echo e(__('auth.login.forgot_password')); ?>

                </a>
            </div>
            <input
                id="password"
                type="password"
                name="password"
                class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-4 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                placeholder="<?php echo e(__('auth.login.password_placeholder')); ?>"
                required
                autocomplete="current-password"
            />
        </div>

        <div class="animate-fade-in-up flex items-center justify-between px-1 delay-400">
            <div class="light-checkbox">
                <?php if (isset($component)) { $__componentOriginala40cc9faf0a70b4042aba6747c772818 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala40cc9faf0a70b4042aba6747c772818 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.checkbox','data' => ['name' => 'remember','label' => ''.e(__('auth.login.remember_me')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'remember','label' => ''.e(__('auth.login.remember_me')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala40cc9faf0a70b4042aba6747c772818)): ?>
<?php $attributes = $__attributesOriginala40cc9faf0a70b4042aba6747c772818; ?>
<?php unset($__attributesOriginala40cc9faf0a70b4042aba6747c772818); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala40cc9faf0a70b4042aba6747c772818)): ?>
<?php $component = $__componentOriginala40cc9faf0a70b4042aba6747c772818; ?>
<?php unset($__componentOriginala40cc9faf0a70b4042aba6747c772818); ?>
<?php endif; ?>
            </div>
        </div>

        <div class="animate-fade-in-up flex flex-col gap-4 pt-2 delay-500">
            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98]"
            >
                <span><?php echo e(__('auth.login.sign_in_button')); ?></span>
                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>

            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-[#E4E4E7]"></div>
                <span class="mx-4 flex-shrink text-xs font-bold tracking-widest text-[#A1A1AA] uppercase">Or</span>
                <div class="flex-grow border-t border-[#E4E4E7]"></div>
            </div>

            <button
                type="button"
                onclick="signInWithPasskey()"
                class="group flex w-full items-center justify-center gap-3 rounded-2xl border-2 border-[#E4E4E7] bg-white px-6 py-4 text-lg font-bold text-[#18181B] transition-all duration-300 hover:border-[#DC2626]/30 hover:bg-[#F9FAFB] focus:ring-4 focus:ring-[#DC2626]/10 focus:outline-none active:scale-[0.98]"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" />
                </svg>
                <span>Sign in with Passkey</span>
            </button>
        </div>
    </form>

    <script>
        async function signInWithPasskey() {
            console.log('Starting passkey login...');
            try {
                const response = await window.Passkeys.verify();
                console.log('Passkey login successful', response);

                // Manual redirect if the library doesn't handle it
                if (response && response.redirect) {
                    window.location.href = response.redirect;
                }
            } catch (e) {
                if (e.name === 'NotAllowedError' || e.name === 'AbortError') {
                    console.log('Passkey login cancelled by user.');
                } else {
                    console.error('Passkey login failed:', e);
                    alert('Failed to sign in with passkey. Please try again or use your password.');
                }
            }
        }
    </script>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('features.registration_enabled')): ?>
        <div class="animate-fade-in-up mt-10 text-center delay-500">
            <p class="font-medium text-[#71717A]">
                Don't have an account?
                <a
                    href="<?php echo e(route('register')); ?>"
                    class="font-bold text-[#DC2626] underline decoration-[#DC2626]/20 decoration-2 underline-offset-4 transition-colors hover:text-[#B91C1C] hover:decoration-[#DC2626]"
                >
                    Register
                </a>
            </p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb296b6586cd7455368e01c4dab26e36a)): ?>
<?php $attributes = $__attributesOriginalb296b6586cd7455368e01c4dab26e36a; ?>
<?php unset($__attributesOriginalb296b6586cd7455368e01c4dab26e36a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb296b6586cd7455368e01c4dab26e36a)): ?>
<?php $component = $__componentOriginalb296b6586cd7455368e01c4dab26e36a; ?>
<?php unset($__componentOriginalb296b6586cd7455368e01c4dab26e36a); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\auth\login.blade.php ENDPATH**/ ?>