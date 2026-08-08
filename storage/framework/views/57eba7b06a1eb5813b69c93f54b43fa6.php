<?php
    $sourceNameIsAuto = $alerts->sourceNameSource->value === 'auto';
    $monitorUrlIsAuto = $alerts->monitorUrlSource->value === 'auto';
    $input = 'block w-full h-9 rounded-md border border-input bg-card px-3 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring transition-[box-shadow,border-color]';
?>

<form method="POST" action="<?php echo e(route('jobs-monitor.settings.alerts.update')); ?>"
      class="rounded-xl border border-border bg-card text-card-foreground p-5 space-y-5 shadow-xs">
    <?php echo csrf_field(); ?>

    <div class="flex items-start gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand/10 text-brand ring-1 ring-inset ring-brand/20">
            <i data-lucide="fingerprint" class="text-[16px]"></i>
        </span>
        <div>
            <h2 class="text-base font-semibold tracking-tight">Identification</h2>
            <p class="mt-1 text-sm text-muted-foreground">
                Used in alert messages and "Open dashboard" links so a single Slack channel can distinguish environments.
            </p>
        </div>
    </div>

    <div>
        <div class="flex items-center gap-2 mb-1.5">
            <label for="source_name" class="text-sm font-medium">Source name</label>
            <?php echo $__env->make('jobs-monitor::settings.partials.tooltip', [
                'text' => 'Short label that identifies this environment in alert messages — e.g. "Production" or "Staging". Shown in the Slack header and email subject so a single channel can distinguish environments. Auto-derived from app.name (+ env) when not set here.',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('jobs-monitor::settings.partials.source-badge', ['source' => $alerts->sourceNameSource->value], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <input type="text" name="source_name" id="source_name"
               value="<?php echo e(old('source_name', $sourceNameIsAuto ? '' : $alerts->sourceName)); ?>"
               maxlength="100"
               placeholder="<?php echo e($sourceNameIsAuto ? $alerts->sourceName : 'e.g. Production'); ?>"
               class="<?php echo e($input); ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sourceNameIsAuto): ?>
            <p class="mt-1.5 text-xs text-muted-foreground">
                Auto-derived from <code class="bg-muted px-1 py-0.5 rounded text-[11px]">app.name</code>. Override only if you want a different label.
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['source_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1.5 text-xs text-destructive"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div>
        <div class="flex items-center gap-2 mb-1.5">
            <label for="monitor_url" class="text-sm font-medium">Monitor URL</label>
            <?php echo $__env->make('jobs-monitor::settings.partials.tooltip', [
                'text' => 'Base URL where this monitor is reachable. Used to render "Open dashboard" / "Open DLQ" deep-link buttons inside alert messages so on-call can jump to the failing job in one click. Auto-derived from app.url + ui.path when not set here.',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('jobs-monitor::settings.partials.source-badge', ['source' => $alerts->monitorUrlSource->value], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <input type="url" name="monitor_url" id="monitor_url"
               value="<?php echo e(old('monitor_url', $monitorUrlIsAuto ? '' : $alerts->monitorUrl)); ?>"
               maxlength="500"
               placeholder="<?php echo e($monitorUrlIsAuto ? $alerts->monitorUrl : 'https://monitor.example.com'); ?>"
               class="<?php echo e($input); ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monitorUrlIsAuto): ?>
            <p class="mt-1.5 text-xs text-muted-foreground">
                Auto-derived from <code class="bg-muted px-1 py-0.5 rounded text-[11px]">app.url</code> + <code class="bg-muted px-1 py-0.5 rounded text-[11px]">ui.path</code>. Override only if the monitor lives on a different host.
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['monitor_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1.5 text-xs text-destructive"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="flex justify-end pt-2 border-t border-border">
        <?php echo $__env->make('jobs-monitor::partials.button', [
            'variant' => 'brand',
            'as' => 'submit',
            'icon' => 'save',
            'label' => 'Save settings',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</form>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\alerts\_scalars.blade.php ENDPATH**/ ?>