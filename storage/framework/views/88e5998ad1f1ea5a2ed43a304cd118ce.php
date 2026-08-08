<?php
    $url = $url ?? '';
    $title = $title ?? null;
    $embedHtml = $embed_html ?? null;
    $embedTitle = $embed_title ?? null;
    $embedThumbnail = $embed_thumbnail ?? null;
    $embedProvider = $embed_provider ?? null;
?>

<div class="cb-embed">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($embedHtml): ?>
        <div class="cb-embed__iframe-wrapper"><?php echo $embedHtml; ?></div>
    <?php elseif($url): ?>
        <div class="cb-embed__fallback">
            <a href="<?php echo e($url); ?>" target="_blank" rel="noopener noreferrer" class="cb-embed__link">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($embedThumbnail): ?>
                    <img
                        src="<?php echo e($embedThumbnail); ?>"
                        alt="<?php echo e($embedTitle ?? $title ?? 'Embedded content'); ?>"
                        class="cb-embed__thumbnail"
                    />
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="cb-embed__meta">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($embedProvider): ?>
                        <span class="cb-embed__provider"><?php echo e($embedProvider); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <span class="cb-embed__url"><?php echo e($url); ?></span>
                </div>
            </a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\resources\views\embed.blade.php ENDPATH**/ ?>