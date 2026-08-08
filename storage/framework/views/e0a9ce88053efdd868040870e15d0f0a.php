<div <?php echo e($attributes->merge(['class' => $makeBoxClass()])); ?>>

    
    <div class="inner">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($title)): ?>
            <h3><?php echo e($title); ?></h3>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($text)): ?>
            <h5><?php echo e($text); ?></h5>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($icon)): ?>
        <div class="icon">
            <i class="<?php echo e($icon); ?>"></i>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($url)): ?>
        <a href="<?php echo e($url); ?>" class="small-box-footer">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($urlText)): ?>
                <?php echo e($urlText); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <i class="fas fa-lg fa-arrow-circle-right"></i>
        </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="<?php echo e($makeOverlayClass()); ?>">
        <i class="fas fa-2x fa-spin fa-sync-alt text-gray"></i>
    </div>

</div>



<?php if (! $__env->hasRenderedOnce('44c4fc14-a4e1-429d-b1af-55c2b70a0543')): $__env->markAsRenderedOnce('44c4fc14-a4e1-429d-b1af-55c2b70a0543'); ?>
<?php $__env->startPush('js'); ?>
<script>

    class _AdminLTE_SmallBox {

        /**
         * Constructor.
         *
         * target: The id of the target small box.
         */
        constructor(target)
        {
            this.target = target;
        }

        /**
         * Update the small box.
         *
         * data: An object with the new data.
         */
        update(data)
        {
            // Check if target and data exists.

            let t = $(`#${this.target}`);

            if (t.length <= 0 || ! data) {
                return;
            }

            // Update available data.

            if (data.title) {
                t.find('.inner h3').html(data.title);
            }

            if (data.text) {
                t.find('.inner h5').html(data.text);
            }

            if (data.icon) {
                t.find('.icon i').attr('class', data.icon);
            }

            if (data.url) {
                t.find('.small-box-footer').attr('href', data.url);
            }
        }

        /**
         * Toggle the loading animation of the small box.
         */
        toggleLoading()
        {
            // Check if target exists.

            let t = $(`#${this.target}`);

            if (t.length <= 0) {
                return;
            }

            // Toggle the loading overlay.

            t.find('.overlay').toggleClass('d-none');
        }
    }

</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\vendor\jeroennoten\laravel-adminlte\resources\views\components\widget\small-box.blade.php ENDPATH**/ ?>