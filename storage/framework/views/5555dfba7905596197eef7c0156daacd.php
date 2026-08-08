<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content): ?>
    <pre <?php if($class): ?> class="<?php echo e($class); ?>" <?php endif; ?>><code><?php echo e($content); ?></code></pre>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\resources\views\code.blade.php ENDPATH**/ ?>