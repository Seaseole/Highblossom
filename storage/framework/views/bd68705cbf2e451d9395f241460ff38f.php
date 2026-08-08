<?php if (isset($component)) { $__componentOriginalb296b6586cd7455368e01c4dab26e36a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb296b6586cd7455368e01c4dab26e36a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::auth-premium','data' => ['title' => 'Forgot Password','companyName' => config('app.name'),'brandingSubtitle' => 'Forgot your password? No worries. Enter your email and we\'ll send you a reset link.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-premium'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Forgot Password','companyName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('app.name')),'brandingSubtitle' => 'Forgot your password? No worries. Enter your email and we\'ll send you a reset link.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mb-10">
        <h2 class="font-headline mb-2 text-3xl font-bold tracking-tight text-[#18181B]">Forgot Password</h2>
        <p class="text-[#71717A]">Enter your email to receive a password reset link</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="animate-fade-in-up mb-6 rounded-2xl border border-green-500/20 bg-green-500/10 p-4 delay-200">
            <p class="text-sm font-medium text-green-600"><?php echo e(session('status')); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>

        <div class="animate-fade-in-up delay-200">
            <label for="email" class="mb-2 block px-1 text-xs font-bold tracking-widest text-[#71717A] uppercase"
                >Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="w-full rounded-2xl border border-[#E4E4E7] bg-white/50 px-5 py-4 text-[#18181B] placeholder-[#A1A1AA] shadow-sm transition-all duration-300 focus:border-[#DC2626] focus:ring-2 focus:ring-[#DC2626]/20 focus:outline-none"
                placeholder="you@example.com"
                required
                autofocus
                value="<?php echo e(old('email')); ?>"
            />
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="mt-2 space-y-1 px-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->get('email'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <p class="flex items-start gap-1.5 text-sm font-medium text-[#DC2626]">
                            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><?php echo e($error); ?></span>
                        </p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="animate-fade-in-up pt-2 delay-300">
            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-[#DC2626] px-6 py-4 text-lg font-bold text-white shadow-xl shadow-[#DC2626]/20 transition-all duration-300 hover:bg-[#B91C1C] focus:ring-4 focus:ring-[#DC2626]/20 focus:outline-none active:scale-[0.98]"
            >
                <span>Send Reset Link</span>
                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <div class="animate-fade-in-up mt-10 text-center delay-400">
        <p class="font-medium text-[#71717A]">
            Remember your password?
            <a
                href="<?php echo e(route('login')); ?>"
                class="font-bold text-[#DC2626] underline decoration-[#DC2626]/20 decoration-2 underline-offset-4 transition-colors hover:text-[#B91C1C] hover:decoration-[#DC2626]"
            >
                Sign in
            </a>
        </p>
    </div>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>