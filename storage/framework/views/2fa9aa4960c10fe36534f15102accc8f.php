<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($src): ?>
    <figure <?php if($class): ?> class="<?php echo e($class); ?>" <?php endif; ?>>
        <img
            src="<?php echo e($src); ?>"
            alt="<?php echo e($alt ?? ''); ?>"
            <?php if($width): ?> width="<?php echo e($width); ?>" <?php endif; ?>
            <?php if($height): ?> height="<?php echo e($height); ?>" <?php endif; ?>
        />
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($caption): ?>
            <figcaption><?php echo e($caption); ?></figcaption>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </figure>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\resources\views\image.blade.php ENDPATH**/ ?>