

<div class="table-responsive">

<table id="<?php echo e($id); ?>" style="width:100%" <?php echo e($attributes->merge(['class' => $makeTableClass()])); ?>>

    
    <thead <?php if(isset($headTheme)): ?> class="thead-<?php echo e($headTheme); ?>" <?php endif; ?>>
        <tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $heads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <th <?php if(isset($th['classes'])): ?> class="<?php echo e($th['classes']); ?>" <?php endif; ?>
                    <?php if(isset($th['width'])): ?> style="width:<?php echo e($th['width']); ?>%" <?php endif; ?>
                    <?php if(isset($th['no-export'])): ?> dt-no-export <?php endif; ?>>
                    <?php echo e(is_array($th) ? ($th['label'] ?? '') : $th); ?>

                </th>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tr>
    </thead>

    
    <tbody><?php echo e($slot); ?></tbody>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($withFooter)): ?>
        <tfoot <?php if(isset($footerTheme)): ?> class="thead-<?php echo e($footerTheme); ?>" <?php endif; ?>>
            <tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $heads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <th><?php echo e(is_array($th) ? ($th['label'] ?? '') : $th); ?></th>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tr>
        </tfoot>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</table>

</div>



<?php $__env->startPush('js'); ?>
<script>

    $(() => {
        $('#<?php echo e($id); ?>').DataTable( <?php echo json_encode($config, 15, 512) ?> );
    })

</script>
<?php $__env->stopPush(); ?>



<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($beautify)): ?>
    <?php $__env->startPush('css'); ?>
    <style type="text/css">
        #<?php echo e($id); ?> tr td,  #<?php echo e($id); ?> tr th {
            vertical-align: middle;
            text-align: center;
        }
    </style>
    <?php $__env->stopPush(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($config['responsive'])): ?>
    <?php if (! $__env->hasRenderedOnce('41b8f3aa-e100-490e-b373-f649635632b3')): $__env->markAsRenderedOnce('41b8f3aa-e100-490e-b373-f649635632b3'); ?>
    <?php $__env->startPush('css'); ?>
    <style type="text/css">
        .dataTable .child .dtr-details {
            width: 100%;
        }
        .dataTable .child .dtr-data {
            float: right;
        }
    </style>
    <?php $__env->stopPush(); ?>
    <?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\vendor\jeroennoten\laravel-adminlte\resources\views\components\tool\datatable.blade.php ENDPATH**/ ?>