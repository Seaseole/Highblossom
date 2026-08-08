<?php
    $input = 'block w-full h-9 rounded-md border border-input bg-card px-3 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring transition-[box-shadow,border-color]';
?>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow-xs">
    <div class="flex items-start gap-3 p-5 border-b border-border">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand/10 text-brand ring-1 ring-inset ring-brand/20">
            <i data-lucide="<?php echo e($group->icon); ?>" class="text-[16px]"></i>
        </span>
        <div>
            <h2 class="text-base font-semibold tracking-tight"><?php echo e($group->label); ?></h2>
            <p class="mt-1 text-sm text-muted-foreground"><?php echo e($group->description); ?></p>
        </div>
    </div>

    <div class="divide-y divide-border">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group->settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php
                $fieldName = "settings[{$setting->group}][{$setting->key}]";
                $fieldId = "{$setting->group}_{$setting->key}";
                $isBoolean = $setting->type->value === 'boolean';
                $isString = $setting->type->value === 'string';
                $isNumber = in_array($setting->type->value, ['integer', 'float'], true);
                $hasOptions = !empty($setting->options);
            ?>

            <div class="p-5 <?php echo e($isBoolean ? 'flex items-start justify-between gap-6' : 'space-y-3'); ?>">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <label for="<?php echo e($fieldId); ?>" class="text-sm font-medium">
                            <?php echo e($setting->label); ?>

                        </label>
                        <?php echo $__env->make('jobs-monitor::settings.partials.source-badge', ['source' => $setting->source->value], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->suffix): ?>
                            <span class="text-xs text-muted-foreground">(<?php echo e($setting->suffix); ?>)</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <p class="mt-1 text-sm text-muted-foreground leading-relaxed">
                        <?php echo e($setting->description); ?>

                    </p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->source->value === 'default'): ?>
                        <p class="mt-1.5 text-xs text-muted-foreground/70">
                            Using package default. Save to store in database.
                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$fieldName];
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

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isBoolean): ?>
                    <div class="flex-shrink-0 pt-0.5">
                        <input type="hidden" name="<?php echo e($fieldName); ?>" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="<?php echo e($fieldName); ?>"
                                   id="<?php echo e($fieldId); ?>"
                                   value="1"
                                   <?php echo e($setting->value ? 'checked' : ''); ?>

                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-muted rounded-full peer
                                        peer-checked:bg-brand
                                        peer-focus-visible:ring-2 peer-focus-visible:ring-ring peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-card
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                        after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:shadow-sm after:transition-transform
                                        peer-checked:after:translate-x-5
                                        transition-colors"></div>
                        </label>
                    </div>
                <?php elseif($isNumber): ?>
                    <div class="max-w-xs">
                        <input type="number"
                               name="<?php echo e($fieldName); ?>"
                               id="<?php echo e($fieldId); ?>"
                               value="<?php echo e(old("settings.{$setting->group}.{$setting->key}", $setting->value)); ?>"
                               <?php if($setting->min !== null): ?> min="<?php echo e($setting->min); ?>" <?php endif; ?>
                               <?php if($setting->max !== null): ?> max="<?php echo e($setting->max); ?>" <?php endif; ?>
                               <?php if($setting->type->value === 'float'): ?> step="0.01" <?php else: ?> step="1" <?php endif; ?>
                               placeholder="<?php echo e($setting->default); ?>"
                               class="<?php echo e($input); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->min !== null && $setting->max !== null): ?>
                            <p class="mt-1 text-xs text-muted-foreground">
                                Range: <?php echo e($setting->min); ?> – <?php echo e($setting->max); ?>

                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php elseif($hasOptions): ?>
                    <div class="max-w-xs">
                        <?php echo $__env->make('jobs-monitor::settings.general._select-with-custom', [
                            'name' => $fieldName,
                            'value' => old("settings.{$setting->group}.{$setting->key}", $setting->value),
                            'fieldId' => $fieldId,
                            'options' => $setting->options,
                            'description' => 'Cron format: minute hour day month weekday. Examples: */3 * * * * (every 3 min), 0 */2 * * * (every 2 hours)',
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php elseif($isString): ?>
                    <div class="max-w-md">
                        <input type="text"
                               name="<?php echo e($fieldName); ?>"
                               id="<?php echo e($fieldId); ?>"
                               value="<?php echo e(old("settings.{$setting->group}.{$setting->key}", $setting->value)); ?>"
                               maxlength="255"
                               placeholder="<?php echo e($setting->default !== '' ? $setting->default : 'Not set'); ?>"
                               <?php if($setting->pattern): ?> pattern="<?php echo e($setting->pattern); ?>" title="Only letters, numbers, colons, dots, hyphens and underscores" <?php endif; ?>
                               class="<?php echo e($input); ?>">
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\general\_group.blade.php ENDPATH**/ ?>