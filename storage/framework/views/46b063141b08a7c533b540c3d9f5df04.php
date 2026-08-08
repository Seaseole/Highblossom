<?php
    $style = $style ?? 'line';
    $size = $size ?? 'md';
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($style === 'line'): ?>
    <hr class="cb-divider cb-divider__line" role="separator" aria-orientation="horizontal" />
<?php elseif($style === 'dots'): ?>
    <div class="cb-divider cb-divider__dots" role="separator" aria-orientation="horizontal"></div>
<?php elseif($style === 'space'): ?>
    <div class="cb-divider cb-divider__space cb-divider__space--<?php echo e($size); ?>"></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\resources\views\divider.blade.php ENDPATH**/ ?>