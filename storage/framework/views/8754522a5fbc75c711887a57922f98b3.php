<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($src): ?>
    <?php
        $sourceType = $source_type ?? 'unknown';
        $embedUrl = $embed_url ?? null;
        $fullUrl = $full_url ?? $src;
        $containerClass = $class ?? '';
        $posterUrl = $poster ?? null;
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sourceType === 'youtube' && $embedUrl): ?>
        <div class="aspect-video <?php echo e($containerClass); ?>">
            <iframe
                src="<?php echo e($embedUrl); ?>"
                title="YouTube video"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                class="h-full w-full rounded-lg shadow-md"
            ></iframe>
        </div>
    <?php elseif($sourceType === 'vimeo' && $embedUrl): ?>
        <div class="aspect-video <?php echo e($containerClass); ?>">
            <iframe
                src="<?php echo e($embedUrl); ?>"
                title="Vimeo video"
                frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                class="h-full w-full rounded-lg shadow-md"
            ></iframe>
        </div>
    <?php elseif($sourceType === 'dailymotion' && $embedUrl): ?>
        <div class="aspect-video <?php echo e($containerClass); ?>">
            <iframe
                src="<?php echo e($embedUrl); ?>"
                title="Dailymotion video"
                frameborder="0"
                allow="autoplay; fullscreen"
                allowfullscreen
                class="h-full w-full rounded-lg shadow-md"
            ></iframe>
        </div>
    <?php elseif($sourceType === 'facebook' && $embedUrl): ?>
        <div class="aspect-video <?php echo e($containerClass); ?>">
            <iframe
                src="<?php echo e($embedUrl); ?>"
                title="Facebook video"
                frameborder="0"
                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                allowfullscreen
                class="h-full w-full rounded-lg shadow-md"
            ></iframe>
        </div>
    <?php elseif($sourceType === 'local_file' || $sourceType === 'direct_url' || $sourceType === 'unknown'): ?>
        <video
            <?php if($containerClass): ?> class="<?php echo e($containerClass); ?>" <?php endif; ?>
            <?php if(! empty($posterUrl)): ?> poster="<?php echo e($posterUrl); ?>" <?php endif; ?>
            <?php if(! empty($autoplay)): ?> autoplay <?php endif; ?>
            <?php if(! empty($controls) || ! isset($controls)): ?> controls <?php endif; ?>
            playsinline
        >
            <source src="<?php echo e($fullUrl); ?>" <?php if(! empty($type)): ?> type="<?php echo e($type); ?>" <?php endif; ?> />
            Your browser does not support the video tag.
        </video>
    <?php else: ?>
        <div class="rounded-lg bg-gray-100 p-4 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
            <?php echo e(__('Invalid video source')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\src/../resources/views/video.blade.php ENDPATH**/ ?>