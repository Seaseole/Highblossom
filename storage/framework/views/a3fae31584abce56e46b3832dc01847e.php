<?php
    use App\Enums\VideoSourceType;
    use App\Services\VideoSourceDetector;

    $src = $block['attributes']['src'] ?? '';
    $detector = app(VideoSourceDetector::class);
    $sourceType = $detector->detect($src);
    $embedUrl = $detector->getEmbedUrl($src, $sourceType);
    $fullUrl = $detector->getFullUrl($src);
?>

<div class="space-y-4">
    <!-- Video Preview with Auto-Detection Badge -->
    <div>
        <label class="text-admin-text-muted mb-2 block text-sm font-medium">Video Preview</label>
        <div class="bg-admin-surface-alt border-admin-border relative overflow-hidden rounded-lg border">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($src)): ?>
                
                <div class="absolute top-2 left-2 z-10">
                    <span class="bg-admin-accent/20 text-admin-accent rounded px-2 py-1 text-xs font-medium">
                        <?php echo e($sourceType->label()); ?>

                    </span>
                </div>
                open file
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sourceType->usesIframe() && $embedUrl): ?>
                    
                    <div class="bg-admin-surface flex aspect-video items-center justify-center">
                        <div class="text-center">
                            <svg class="text-admin-accent mx-auto mb-2 h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-admin-text-muted text-sm"><?php echo e($sourceType->label()); ?> (embedded)</p>
                        </div>
                    </div>
                <?php else: ?>
                    
                    <video src="<?php echo e($fullUrl); ?>" class="aspect-video w-full object-cover" controls muted></video>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                
                <div class="bg-admin-surface-alt flex aspect-video items-center justify-center">
                    <div class="text-admin-text-muted text-center">
                        <svg class="mx-auto mb-2 h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm">No video selected</p>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- File Upload -->
    <div
        x-data="{
            videoPreview: null,
            isDragging: false,
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.videoPreview = URL.createObjectURL(file);
                }
            },
        }"
    >
        <label class="text-admin-text-muted mb-2 block text-sm font-medium">Upload Video File</label>
        <div
            class="group relative transition-all duration-300"
            :class="isDragging ? 'scale-[0.99]' : ''"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="
                isDragging = false;
                $refs.videoInput.files = $event.dataTransfer.files;
                handleFileSelect({ target: $refs.videoInput });
            "
        >
            <div
                class="relative flex cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed p-12 text-center transition-all duration-300"
                :class="isDragging
                    ? 'border-admin-accent bg-admin-accent/5'
                    : 'border-admin-border hover:border-admin-border-subtle bg-admin-surface-alt'"
                @click="$refs.videoInput.click()"
            >
                <template x-if="! videoPreview">
                    <div class="flex flex-col items-center">
                        <div class="bg-admin-accent/10 mb-6 flex h-20 w-20 items-center justify-center rounded-xl transition-transform duration-300 group-hover:scale-110">
                            <svg class="text-admin-accent h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-admin-accent mb-1 font-semibold">Click to upload or drag and drop</p>
                        <p class="text-admin-text-muted text-sm">MP4, WEBM, MOV up to 30MB</p>
                    </div>
                </template>

                <template x-if="videoPreview">
                    <div class="border-admin-border relative aspect-video w-full overflow-hidden rounded-xl border">
                        <video :src="videoPreview" class="h-full w-full object-cover" controls></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 backdrop-blur-sm transition-opacity duration-300 hover:opacity-100">
                            <div class="flex flex-col items-center text-white">
                                <svg class="mb-2 h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span class="text-sm font-semibold">Change Video</span>
                            </div>
                        </div>
                    </div>
                </template>

                <input
                    type="file"
                    id="video-file-<?php echo e($index); ?>"
                    x-ref="videoInput"
                    class="hidden"
                    accept="video/mp4,video/webm,video/quicktime,video/x-msvideo"
                    @change="handleFileSelect"
                />
            </div>
        </div>
    </div>

    <!-- Hidden Input for Path -->
    <input type="hidden" id="video-path-<?php echo e($index); ?>" wire:model.live="blocks.<?php echo e($index); ?>.attributes.src" />

    <!-- URL Input -->
    <div>
        <label class="text-admin-text-muted mb-2 block text-sm font-medium">
            Video URL
            <span class="text-admin-text-muted/70 ml-1 text-xs">(YouTube, Vimeo, Dailymotion, Facebook, or direct link)</span>
        </label>
        <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['wire:model.live' => 'blocks.'.e($index).'.attributes.src','type' => 'url','placeholder' => 'https://...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'blocks.'.e($index).'.attributes.src','type' => 'url','placeholder' => 'https://...']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
    </div>

    <?php if (isset($component)) { $__componentOriginal9384bd05e996fcc8c16dc84e6bbc1c8f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9384bd05e996fcc8c16dc84e6bbc1c8f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::checkbox.index','data' => ['wire:model.live' => 'blocks.'.e($index).'.attributes.controls','label' => 'Show Controls']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'blocks.'.e($index).'.attributes.controls','label' => 'Show Controls']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9384bd05e996fcc8c16dc84e6bbc1c8f)): ?>
<?php $attributes = $__attributesOriginal9384bd05e996fcc8c16dc84e6bbc1c8f; ?>
<?php unset($__attributesOriginal9384bd05e996fcc8c16dc84e6bbc1c8f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9384bd05e996fcc8c16dc84e6bbc1c8f)): ?>
<?php $component = $__componentOriginal9384bd05e996fcc8c16dc84e6bbc1c8f; ?>
<?php unset($__componentOriginal9384bd05e996fcc8c16dc84e6bbc1c8f); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['wire:model.live' => 'blocks.'.e($index).'.attributes.class','label' => 'CSS Classes','placeholder' => 'Additional CSS classes...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'blocks.'.e($index).'.attributes.class','label' => 'CSS Classes','placeholder' => 'Additional CSS classes...']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
</div>

    <?php
        $__scriptKey = '4172250889-0';
        ob_start();
    ?>
    <script src="<?php echo e(asset('js/video-uploader.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof VideoUploader !== 'undefined') {
                new VideoUploader({
                    fileInput: document.querySelector('#video-file-<?php echo e($index); ?>'),
                    previewContainer: document.querySelector('#video-file-<?php echo e($index); ?>').parentElement,
                    progressContainer: null,
                    hiddenInput: document.querySelector('#video-path-<?php echo e($index); ?>'),
                    uploadUrl: '<?php echo e(route('admin.video-upload')); ?>',
                    csrfToken: '<?php echo e(csrf_token()); ?>',
                    maxSize: 30 * 1024 * 1024, // 30MB
                    acceptedTypes: ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo'],
                    onUploadComplete: function (response) {
                        console.log('Video upload complete:', response);
                    },
                    onUploadError: function (message) {
                        console.error('Video upload error:', message);
                    },
                });
            }
        });
    </script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\video-editor.blade.php ENDPATH**/ ?>