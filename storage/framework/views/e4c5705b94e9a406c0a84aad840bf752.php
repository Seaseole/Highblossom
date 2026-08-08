<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'url' => '',
    'src' => '',
    'caption' => '',
    'alignment' => 'center',
    'poster' => '',
    'autoplay' => false,
    'controls' => true,
    'class' => '',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'url' => '',
    'src' => '',
    'caption' => '',
    'alignment' => 'center',
    'poster' => '',
    'autoplay' => false,
    'controls' => true,
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use App\Services\VideoSourceDetector;

    $videoUrl = $url ?: $src;
    $detector = app(VideoSourceDetector::class);
    $sourceType = $detector->detect($videoUrl);
    $videoId = $detector->extractVideoId($videoUrl, $sourceType);
    $embedUrl = $detector->getEmbedUrl($videoUrl, $sourceType);
    $fullUrl = $detector->getFullUrl($videoUrl);

    $containerClass = match ($alignment) {
        'left' => 'text-left',
        'right' => 'text-right',
        'full' => 'w-full',
        default => 'text-center',
    };

    $wrapperClass = $alignment === 'full' ? 'w-full' : 'max-w-3xl mx-auto';
?>

<figure class="<?php echo e($containerClass); ?> my-8">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sourceType->usesIframe() && $embedUrl): ?>
        
        <div class="aspect-video <?php echo e($wrapperClass); ?> <?php echo e($class); ?>">
            <iframe
                src="<?php echo e($embedUrl); ?>"
                title="<?php echo e($sourceType->label()); ?>"
                frameborder="0"
                allow="
                    accelerometer;
                    autoplay;
                    clipboard-write;
                    encrypted-media;
                    gyroscope;
                    picture-in-picture;
                    fullscreen;
                "
                allowfullscreen
                class="h-full w-full rounded-lg shadow-md"
                loading="lazy"
            ></iframe>
        </div>
    <?php elseif($sourceType->value === 'local_file' || $sourceType->value === 'direct_url' || $sourceType->value === 'unknown'): ?>
        
        <div class="<?php echo e($wrapperClass); ?>">
            <video
                <?php if($class): ?> class="<?php echo e($class); ?>" <?php endif; ?>
                <?php if($poster): ?> poster="<?php echo e($poster); ?>" <?php endif; ?>
                <?php if($autoplay): ?> autoplay <?php endif; ?>
                <?php if($controls): ?> controls <?php endif; ?>
                playsinline
                class="w-full rounded-lg shadow-md"
            >
                <source src="<?php echo e($fullUrl); ?>" />
                Your browser does not support the video tag.
            </video>
        </div>
    <?php else: ?>
        <div class="bg-admin-surface border-admin-border text-admin-text-muted rounded-lg border p-4">
            <?php echo e(__('Invalid video source')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($caption): ?>
        <figcaption class="text-admin-text-muted mt-3 text-sm italic"><?php echo e($caption); ?></figcaption>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</figure>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\video.blade.php ENDPATH**/ ?>