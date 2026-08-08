<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Staff Absence Details']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Staff Absence Details']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <a
                href="<?php echo e(route('admin.absences.index')); ?>"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                ← Back to Staff Absences
            </a>
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                Staff Absence #<?php echo e($absence->id); ?>

            </h1>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Schedule Card -->
            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Schedule</h2>
                <dl class="space-y-4">
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Start Date</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            <?php echo e($absence->starts_at->format('F j, Y g:i A')); ?>

                        </dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">End Date</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            <?php echo e($absence->ends_at->format('F j, Y g:i A')); ?>

                        </dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Duration</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            <?php echo e($absence->starts_at->diffInDays($absence->ends_at)); ?> days
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Staff Information Card -->
            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Staff Information</h2>
                <dl class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($absence->staff): ?>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Staff Member</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($absence->staff->name); ?>

                            </dd>
                            <dd class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($absence->staff->email); ?></dd>
                        </div>
                    <?php else: ?>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Staff Member</dt>
                            <dd class="text-sm font-medium text-gray-500 italic dark:text-gray-400">Not assigned</dd>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </dl>
            </div>
        </div>

        <!-- Reason Card -->
        <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Reason</h2>
            <p class="rounded-2xl border border-gray-100 bg-gray-50 p-6 text-sm leading-relaxed text-gray-600 dark:border-white/5 dark:bg-white/5 dark:text-gray-300">
                <?php echo e($absence->reason); ?>

            </p>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views\admin\absences\show.blade.php ENDPATH**/ ?>