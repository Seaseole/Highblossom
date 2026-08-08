<?php
    $fields = $fields ?? [];
    $submitText = $submit_text ?? 'Submit';
    $actionUrl = $action_url ?? null;
    $formId = 'cb-form-'.uniqid();
?>

<form class="cb-form" <?php if($actionUrl): ?> action="<?php echo e($actionUrl); ?>" method="POST" <?php endif; ?>>
    <?php echo csrf_field(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($actionUrl): ?>
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php
            $fieldName = $field['name'] ?? '';
            $fieldLabel = $field['label'] ?? '';
            $fieldType = $field['type'] ?? 'text';
            $fieldRequired = $field['required'] ?? false;
            $fieldOptions = $field['options'] ?? [];
        ?>

        <div class="cb-form__field">
            <label for="<?php echo e($formId); ?>-<?php echo e($fieldName); ?>" class="cb-form__label">
                <?php echo e($fieldLabel); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fieldRequired): ?>
                    <span class="cb-form__required">*</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </label>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fieldType === 'textarea'): ?>
                <textarea
                    id="<?php echo e($formId); ?>-<?php echo e($fieldName); ?>"
                    name="<?php echo e($fieldName); ?>"
                    <?php if($fieldRequired): ?> required <?php endif; ?>
                    class="cb-form__input cb-form__input--textarea"
                    rows="4"
                ></textarea>
            <?php elseif($fieldType === 'select'): ?>
                <select
                    id="<?php echo e($formId); ?>-<?php echo e($fieldName); ?>"
                    name="<?php echo e($fieldName); ?>"
                    <?php if($fieldRequired): ?> required <?php endif; ?>
                    class="cb-form__input cb-form__input--select"
                >
                    <option value="">Select an option</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fieldOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($option); ?>"><?php echo e($option); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            <?php elseif($fieldType === 'checkbox'): ?>
                <input
                    type="checkbox"
                    id="<?php echo e($formId); ?>-<?php echo e($fieldName); ?>"
                    name="<?php echo e($fieldName); ?>"
                    value="1"
                    <?php if($fieldRequired): ?> required <?php endif; ?>
                    class="cb-form__input cb-form__input--checkbox"
                />
            <?php elseif($fieldType === 'radio'): ?>
                <div class="cb-form__radio-group">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fieldOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <label class="cb-form__radio-label">
                            <input
                                type="radio"
                                id="<?php echo e($formId); ?>-<?php echo e($fieldName); ?>-<?php echo e($index); ?>"
                                name="<?php echo e($fieldName); ?>"
                                value="<?php echo e($option); ?>"
                                <?php if($fieldRequired && $index === 0): ?> required <?php endif; ?>
                                class="cb-form__input cb-form__input--radio"
                            />
                            <span><?php echo e($option); ?></span>
                        </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php else: ?>
                <input
                    type="<?php echo e($fieldType); ?>"
                    id="<?php echo e($formId); ?>-<?php echo e($fieldName); ?>"
                    name="<?php echo e($fieldName); ?>"
                    <?php if($fieldRequired): ?> required <?php endif; ?>
                    class="cb-form__input"
                />
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

    <button type="submit" class="cb-form__submit"><?php echo e($submitText); ?></button>
</form>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\resources\views\form.blade.php ENDPATH**/ ?>