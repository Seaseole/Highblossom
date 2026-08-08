<?php if (isset($component)) { $__componentOriginalb296b6586cd7455368e01c4dab26e36a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb296b6586cd7455368e01c4dab26e36a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::auth-premium','data' => ['title' => 'Register','companyName' => config('app.name'),'brandingSubtitle' => 'Create your account to get started with our platform.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-premium'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Register','companyName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('app.name')),'brandingSubtitle' => 'Create your account to get started with our platform.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mb-10">
        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">Create Account</h2>
        <p class="text-[#71717A]">Fill in your details to get started</p>
    </div>

    <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-5">
        <?php echo csrf_field(); ?>

        <div class="animate-fade-in-up delay-200">
            <label for="name" class="mb-2 block px-1 text-xs font-bold tracking-widest text-[#71717A] uppercase"
                >Full Name</label>
            <input
                id="name"
                type="text"
                name="name"
                class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-3.5 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                placeholder="John Doe"
                required
                autofocus
                value="<?php echo e(old('name')); ?>"
            />
        </div>

        <div class="animate-fade-in-up delay-300">
            <label for="email" class="mb-2 block px-1 text-xs font-bold tracking-widest text-[#71717A] uppercase"
                >Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-3.5 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                placeholder="you@example.com"
                required
                value="<?php echo e(old('email')); ?>"
            />
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="animate-fade-in-up delay-400">
                <div class="mb-2 flex h-5 items-center justify-between px-1">
                    <label for="password" class="block text-xs font-bold tracking-widest text-[#71717A] uppercase"
                        >Password</label>
                    <button
                        type="button"
                        data-generate-password="password"
                        data-confirm-target="password_confirmation"
                        class="group flex items-center gap-1 text-[10px] font-bold tracking-wider text-[#DC2626] uppercase transition-colors hover:text-[#B91C1C]"
                    >
                        <svg class="h-3 w-3 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Generate</span>
                    </button>
                </div>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-3.5 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                    placeholder="••••••••"
                    passwordrules="<?php echo e(\Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString()); ?>"
                    data-min="12"
                    data-max="64"
                    autocomplete="new-password"
                    required
                />
            </div>
            <div id="pw-hint" class="hidden text-[10px] text-[#A1A1AA] italic"></div>
            <div class="animate-fade-in-up delay-400">
                <div class="mb-2 flex h-5 items-center px-1">
                    <label
                        for="password_confirmation"
                        class="block text-xs font-bold tracking-widest text-[#71717A] uppercase"
                    >Confirm</label>
                </div>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-3.5 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                    placeholder="••••••••"
                    passwordrules="<?php echo e(\Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString()); ?>"
                    autocomplete="new-password"
                    required
                />
            </div>
        </div>
        <div class="animate-fade-in-up px-1 delay-500">
            <div class="light-checkbox">
                <?php if (isset($component)) { $__componentOriginala40cc9faf0a70b4042aba6747c772818 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala40cc9faf0a70b4042aba6747c772818 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.checkbox','data' => ['name' => 'terms','id' => 'terms','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'terms','id' => 'terms','required' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    I agree to the
                    <a href="<?php echo e(route('terms')); ?>" class="font-bold text-[#DC2626] hover:text-[#B91C1C]">terms</a>
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
        <div class="animate-fade-in-up pt-2 delay-500">
            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98]"
            >
                <span>Create Account</span>
                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <div class="animate-fade-in-up mt-8 text-center delay-500">
        <p class="font-medium text-[#71717A]">
            Already have an account?
            <a
                href="<?php echo e(route('login')); ?>"
                class="font-bold text-[#DC2626] underline decoration-[#DC2626]/20 decoration-2 underline-offset-4 transition-colors hover:text-[#B91C1C] hover:decoration-[#DC2626]"
            >
                Sign in
            </a>
        </p>
    </div>

    <script>
        document.getElementById('password').addEventListener('input', function () {
            const val = this.value;
            const min = parseInt(this.dataset.min);
            const max = parseInt(this.dataset.max);
            const hint = document.getElementById('pw-hint');
            const errors = [];

            if (val.length < min) errors.push('At least ' + min + ' characters');
            if (val.length > max) errors.push('At most ' + max + ' characters');
            if (!/[a-z]/.test(val)) errors.push('One lowercase letter');
            if (!/[A-Z]/.test(val)) errors.push('One uppercase letter');
            if (!/[0-9]/.test(val)) errors.push('One number');
            if (!/[^a-zA-Z0-9]/.test(val)) errors.push('One symbol');

            hint.textContent = errors.join(' · ');
            hint.classList.toggle('hidden', errors.length === 0);
        });
    </script>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views\auth\register.blade.php ENDPATH**/ ?>