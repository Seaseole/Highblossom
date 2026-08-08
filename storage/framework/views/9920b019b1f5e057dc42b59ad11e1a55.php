<?php
    $canEdit = $alerts->recipientsSource->value === 'db';
    $input = 'block w-full rounded-md border border-input bg-card px-3 py-2 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring';
?>

<div class="rounded-xl border border-border bg-card text-card-foreground p-5 space-y-4 shadow-xs">
    <div class="flex items-start gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-info/10 text-info ring-1 ring-inset ring-info/20">
            <i data-lucide="mail" class="text-[16px]"></i>
        </span>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-semibold tracking-tight">Mail recipients</h2>
                <?php echo $__env->make('jobs-monitor::settings.partials.source-badge', ['source' => $alerts->recipientsSource->value], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">
                Addresses that receive email when an alert rule routed to
                <code class="text-[11px] bg-muted px-1 py-0.5 rounded">mail</code> fires.
            </p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($alerts->recipients)): ?>
        <div class="rounded-lg border border-dashed border-border bg-muted/30 px-4 py-6 text-center text-sm text-muted-foreground">
            <i data-lucide="inbox" class="text-lg mb-1 inline-block"></i>
            <p>No recipients yet. Add one below.</p>
        </div>
    <?php else: ?>
        <ul class="divide-y divide-border border border-border rounded-lg bg-card overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $alerts->recipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <li class="flex items-center justify-between px-3 py-2 hover:bg-muted/40 transition-colors">
                    <span class="flex items-center gap-2 text-sm">
                        <i data-lucide="at-sign" class="text-[13px] text-muted-foreground"></i>
                        <?php echo e($email); ?>

                    </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canEdit): ?>
                        <form method="POST"
                              action="<?php echo e(route('jobs-monitor.settings.alerts.recipients.delete', ['email' => $email])); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                    class="inline-flex items-center gap-1 text-xs text-destructive hover:bg-destructive/10 px-2 py-1 rounded-md transition-colors">
                                <i data-lucide="x" class="text-[12px]"></i>
                                Remove
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1 text-xs text-muted-foreground">
                            <i data-lucide="lock" class="text-[12px]"></i>
                            read-only
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('jobs-monitor.settings.alerts.recipients.add')); ?>"
          class="space-y-2 pt-3 border-t border-border">
        <?php echo csrf_field(); ?>
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <label for="email" class="text-sm font-medium">Add recipients</label>
                <?php echo $__env->make('jobs-monitor::settings.partials.tooltip', [
                    'text' => 'One email per recipient. Add multiple at once by separating with comma, semicolon, space or newline. Each is validated and de-duplicated against the existing list before saving.',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <textarea name="email" id="email"
                      rows="2"
                      placeholder="ops@example.com, sre@example.com"
                      class="<?php echo e($input); ?>"
                      required><?php echo e(old('email')); ?></textarea>
            <p class="mt-1 text-xs text-muted-foreground">Multiple addresses allowed — separate with comma, semicolon, space or newline.</p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>   <p class="mt-1.5 text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['emails'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  <p class="mt-1.5 text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['emails.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="flex justify-end">
            <?php echo $__env->make('jobs-monitor::partials.button', [
                'variant' => 'brand',
                'as' => 'submit',
                'icon' => 'plus',
                'label' => 'Add recipients',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </form>
</div>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\alerts\_recipients.blade.php ENDPATH**/ ?>