

<div class="<?php echo e($makeFormGroupClass()); ?>">

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($label)): ?>
        <label for="<?php echo e($id); ?>" <?php if(isset($labelClass)): ?> class="<?php echo e($labelClass); ?>" <?php endif; ?>>
            <?php echo e($label); ?>

        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="<?php echo e($makeInputGroupClass()); ?>">

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($prependSlot)): ?>
            <div class="input-group-prepend"><?php echo e($prependSlot); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php echo $__env->yieldContent('input_group_item'); ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($appendSlot)): ?>
            <div class="input-group-append"><?php echo e($appendSlot); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isInvalid()): ?>
        <span class="invalid-feedback d-block" role="alert">
            <strong><?php echo e($errors->first($errorKey)); ?></strong>
        </span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($bottomSlot)): ?>
        <?php echo e($bottomSlot); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>



<?php if (! $__env->hasRenderedOnce('5cb78329-63f9-4500-81b9-6e8db7217382')): $__env->markAsRenderedOnce('5cb78329-63f9-4500-81b9-6e8db7217382'); ?>
<?php $__env->startPush('css'); ?>
<style type="text/css">

    

    .adminlte-invalid-igroup {
        box-shadow: 0 .25rem 0.5rem rgba(0,0,0,.1);
    }

    

    .adminlte-invalid-igroup > .input-group-prepend > *,
    .adminlte-invalid-igroup > .input-group-append > * {
        border-color: #dc3545 !important;
    }

</style>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\vendor\jeroennoten\laravel-adminlte\resources\views\components\form\input-group-component.blade.php ENDPATH**/ ?>