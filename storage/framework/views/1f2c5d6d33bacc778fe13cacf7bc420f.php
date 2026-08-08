<?php
    $input = 'block w-full h-9 rounded-md border border-input bg-card px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring transition-[box-shadow,border-color]';
?>

<form method="POST"
      action="<?php echo e(route('jobs-monitor.settings.alerts.built-in.update', ['key' => $b->key])); ?>"
      class="space-y-4 border border-brand/30 ring-2 ring-brand/15 rounded-lg bg-card p-4 shadow-xs animate-slide-down">
    <?php echo csrf_field(); ?>

    <div class="flex items-start justify-between gap-3 flex-wrap">
        <div class="flex items-start gap-2.5">
            <span class="flex h-8 w-8 items-center justify-center rounded-md bg-brand/10 text-brand ring-1 ring-inset ring-brand/20">
                <i data-lucide="pencil" class="text-[14px]"></i>
            </span>
            <div>
                <h3 class="text-sm font-semibold">Edit <?php echo e($b->key); ?></h3>
                <p class="text-xs text-muted-foreground mt-0.5">
                    <?php echo e($b->trigger->label()); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->triggerValue !== null): ?>: <code class="text-[11px] bg-muted px-1 py-0.5 rounded"><?php echo e($b->triggerValue); ?></code><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    · Trigger and identifier are fixed to the shipped built-in.
                </p>
            </div>
        </div>
        <a href="<?php echo e(route('jobs-monitor.settings.alerts')); ?>" class="text-xs text-muted-foreground hover:text-foreground">Cancel</a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="flex items-start gap-3 rounded-lg border border-destructive/25 bg-destructive/10 px-3 py-2 text-xs text-destructive">
            <i data-lucide="alert-circle" class="text-[14px] mt-0.5"></i>
            <ul class="list-disc list-inside space-y-0.5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li><?php echo e($err); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-3 gap-3">
        <div>
            <label for="threshold-<?php echo e($b->key); ?>" class="block text-xs font-medium mb-1">Threshold</label>
            <input type="number" id="threshold-<?php echo e($b->key); ?>" name="threshold" min="1"
                   value="<?php echo e(old('threshold', $b->threshold)); ?>" class="<?php echo e($input); ?> tabular-nums">
        </div>
        <div>
            <label for="window-<?php echo e($b->key); ?>" class="block text-xs font-medium mb-1">Window</label>
            <input type="text" id="window-<?php echo e($b->key); ?>" name="window" maxlength="16"
                   value="<?php echo e(old('window', $b->window)); ?>"
                   placeholder="5m, 1h, 2d" class="<?php echo e($input); ?>">
        </div>
        <div>
            <label for="cooldown-<?php echo e($b->key); ?>" class="block text-xs font-medium mb-1">Cooldown (min)</label>
            <input type="number" id="cooldown-<?php echo e($b->key); ?>" name="cooldown_minutes" min="1"
                   value="<?php echo e(old('cooldown_minutes', $b->cooldownMinutes)); ?>" class="<?php echo e($input); ?> tabular-nums">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="min-attempt-<?php echo e($b->key); ?>" class="block text-xs font-medium mb-1">Min attempt</label>
            <input type="number" id="min-attempt-<?php echo e($b->key); ?>" name="min_attempt" min="1"
                   value="<?php echo e(old('min_attempt', $b->minAttempt)); ?>"
                   placeholder="any" class="<?php echo e($input); ?> tabular-nums">
            <p class="mt-1 text-xs text-muted-foreground">Silence first-try noise (e.g. 2 = only retries).</p>
        </div>
        <div>
            <span class="block text-xs font-medium mb-1">Channels</span>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 py-2">
                <?php
                    $chs = old('channels', $b->channels);
                    // Data-driven catalog — single source used here and in
                    // _built-in-row badges. Adding a channel means one entry
                    // in this map, nothing else.
                    $channelCatalog = [
                        'slack' => ['label' => 'Slack', 'icon' => 'slack'],
                        'mail' => ['label' => 'Mail', 'icon' => 'mail'],
                        'pagerduty' => ['label' => 'PagerDuty', 'icon' => 'siren'],
                        'opsgenie' => ['label' => 'Opsgenie', 'icon' => 'shield-alert'],
                        'webhook' => ['label' => 'Webhook', 'icon' => 'webhook'],
                    ];
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $channelCatalog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="channels[]" value="<?php echo e($value); ?>"
                               <?php echo e(in_array($value, (array) $chs, true) ? 'checked' : ''); ?>

                               class="h-4 w-4 rounded border-input bg-card text-brand focus:ring-2 focus:ring-ring focus:ring-offset-0">
                        <span class="inline-flex items-center gap-1 text-sm">
                            <i data-lucide="<?php echo e($meta['icon']); ?>" class="text-[13px] text-muted-foreground"></i>
                            <?php echo e($meta['label']); ?>

                        </span>
                    </label>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between pt-3 border-t border-border">
        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="enabled" value="0">
            <input type="checkbox" name="enabled" value="1"
                   <?php echo e(old('enabled', $b->effectivelyEnabled) ? 'checked' : ''); ?>

                   class="h-4 w-4 rounded border-input bg-card text-brand focus:ring-2 focus:ring-ring focus:ring-offset-0">
            <span class="text-sm font-medium">Enabled</span>
        </label>

        <div class="flex items-center gap-2">
            <?php echo $__env->make('jobs-monitor::partials.button', [
                'as' => 'link',
                'href' => route('jobs-monitor.settings.alerts'),
                'variant' => 'secondary',
                'label' => 'Cancel',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('jobs-monitor::partials.button', [
                'variant' => 'brand',
                'as' => 'submit',
                'icon' => 'save',
                'label' => 'Save changes',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</form>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\alerts\_built-in-edit-form.blade.php ENDPATH**/ ?>