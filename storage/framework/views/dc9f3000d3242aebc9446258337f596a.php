<?php $__env->startFragment('image-grid'); ?>
    <div id="image-grid" class="grid max-h-[450px] grid-cols-2 gap-6 overflow-y-auto p-2 md:grid-cols-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <button
                type="button"
                @click="window.dispatchEvent(new CustomEvent('image-selected', { detail: { url: '<?php echo e($image->image_url); ?>' }})); document.querySelector('[x-data*=\"open\"]').__x.$data.open = false"
                class="group border-admin-border hover:border-admin-accent hover:shadow-admin-accent/25 relative aspect-square overflow-hidden rounded-xl border-2 transition-all hover:shadow-lg active:scale-95"
            >
                <img
                    src="<?php echo e($image->image_url); ?>"
                    alt="<?php echo e($image->title); ?>"
                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-admin-accent scale-0 transform rounded-full p-2 text-white transition-transform duration-300 group-hover:scale-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute right-0 bottom-0 left-0 p-2 text-xs font-medium text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    <?php echo e(\Illuminate\Support\Str::limit($image->title, 20)); ?>

                </div>
            </button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <div class="flex justify-center pt-4"><?php echo e($images->links()); ?></div>
<?php echo $__env->stopFragment(); ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\admin\media-library\grid.blade.php ENDPATH**/ ?>