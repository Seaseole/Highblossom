<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Create Gallery Item']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Create Gallery Item']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Create Gallery Item</h1>
                <p class="text-gray-500 dark:text-gray-400">Add a new masterpiece to the architectural showcase.</p>
            </div>
            <a
                href="<?php echo e(route('admin.gallery.index')); ?>"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Gallery
            </a>
        </div>

        <form
            method="POST"
            action="<?php echo e(route('admin.gallery.store')); ?>"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
            x-data="{
                imagePreview: null,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) this.imagePreview = URL.createObjectURL(file);
                },
            }"
        >
            <?php echo csrf_field(); ?>

            <div class="space-y-8 lg:col-span-2">
                <!-- Details Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Project Title</label>
                        <input
                            type="text"
                            name="title"
                            value="<?php echo e(old('title')); ?>"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            placeholder="e.g. Modern Minimalist Residence"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <select
                            name="gallery_category_id"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <option value="">Select a category</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option
                                    value="<?php echo e($category->id); ?>"
                                    <?php echo e(old('gallery_category_id') == $category->id ? 'selected' : ''); ?>

                                >
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Project Description</label>
                        <textarea
                            name="description"
                            rows="4"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            placeholder="Details about the installation..."
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-6 border-t border-gray-100 pt-6 md:grid-cols-2 dark:border-white/10">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                            <input
                                type="number"
                                name="latitude"
                                step="any"
                                value="<?php echo e(old('latitude')); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="-24.6532"
                            />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                            <input
                                type="number"
                                name="longitude"
                                step="any"
                                value="<?php echo e(old('longitude')); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="25.9087"
                            />
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Physical Address</label>
                            <input
                                type="text"
                                name="location_address"
                                value="<?php echo e(old('location_address')); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="123 Main St, Gaborone, Botswana"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Visuals Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project Image</label>
                    <div
                        class="relative flex min-h-[200px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                        @click="$refs.imageInput.click()"
                    >
                        <template x-if="! imagePreview">
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                <span class="text-xs font-semibold">Click to upload image</span>
                            </div>
                        </template>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="h-full w-full rounded-2xl object-cover" />
                        </template>
                        <input
                            type="file"
                            name="image"
                            x-ref="imageInput"
                            class="hidden"
                            accept="image/*"
                            @change="handleFileSelect"
                            required
                        />
                    </div>
                </div>

                <!-- Config Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                        <input
                            type="number"
                            name="sort_order"
                            value="0"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        />
                    </div>
                    <div class="flex items-center gap-6">
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                            />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                checked
                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                            />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Visible</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Publish Item
                    </button>
                </div>
            </div>
        </form>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal501803f3e4defcbbeaedee798b98ded4)): ?>
<?php $attributes = $__attributesOriginal501803f3e4defcbbeaedee798b98ded4; ?>
<?php unset($__attributesOriginal501803f3e4defcbbeaedee798b98ded4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal501803f3e4defcbbeaedee798b98ded4)): ?>
<?php $component = $__componentOriginal501803f3e4defcbbeaedee798b98ded4; ?>
<?php unset($__componentOriginal501803f3e4defcbbeaedee798b98ded4); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\admin\gallery\create.blade.php ENDPATH**/ ?>