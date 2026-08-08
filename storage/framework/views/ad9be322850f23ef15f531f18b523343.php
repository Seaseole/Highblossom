<?php
    $images = $images ?? [];
    $columns = $columns ?? 3;
    $columns = max(1, min(6, $columns));
?>

<div class="cb-gallery" data-cb-gallery-columns="<?php echo e($columns); ?>">
    <div class="cb-gallery__grid" style="--cb-gallery-columns: <?php echo e($columns); ?>;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="cb-gallery__item">
                <figure class="cb-gallery__figure">
                    <img
                        src="<?php echo e($image['src'] ?? ''); ?>"
                        alt="<?php echo e($image['alt'] ?? ''); ?>"
                        loading="lazy"
                        class="cb-gallery__image"
                    />
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($image['caption']) && $image['caption']): ?>
                        <figcaption class="cb-gallery__caption"><?php echo e($image['caption']); ?></figcaption>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </figure>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\resources\views\gallery.blade.php ENDPATH**/ ?>