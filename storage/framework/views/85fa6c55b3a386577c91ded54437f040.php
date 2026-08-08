<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Edit Blog Post']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Edit Blog Post']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-7xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit Blog Post</h1>
                <p class="text-gray-500 dark:text-gray-400">Modify your blog content.</p>
            </div>
            <div class="flex items-center gap-4">
                <a
                    href="<?php echo e(route('blog.show', $post)); ?>"
                    target="_blank"
                    class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Preview
                </a>
                <a
                    href="<?php echo e(route('admin.posts.index')); ?>"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    Back to Posts
                </a>
            </div>
        </div>

        <form
            method="POST"
            action="<?php echo e(route('admin.posts.update', $post)); ?>"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
        >
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <input type="hidden" name="content" id="content-input" value="<?php echo e(json_encode($post->content)); ?>" />

            <div class="space-y-8 lg:col-span-2">
                <!-- Basic Info Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input
                            type="text"
                            name="title"
                            value="<?php echo e(old('title', $post->title)); ?>"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt</label>
                        <textarea
                            name="excerpt"
                            rows="3"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        ><?php echo e(old('excerpt', $post->excerpt)); ?></textarea>
                    </div>
                </div>

                <!-- Content Blocks -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Content</h2>
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('block-builder', ['name' => 'content','value' => $post->content]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1399896162-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                </div>

                <!-- Categories & Tags -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Categories & Tags</h2>

                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categories</label>
                        <div class="flex flex-wrap gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <label class="px-4 py-2 <?php echo e($post->categories->contains($category->id) ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-gray-50 dark:bg-white/5'); ?> rounded-full border border-gray-200 dark:border-white/10 text-sm cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all">
                                    <input
                                        type="checkbox"
                                        name="categories[]"
                                        value="<?php echo e($category->id); ?>"
                                        <?php echo e($post->categories->contains($category->id) ? 'checked' : ''); ?>

                                        class="sr-only"
                                    />
                                    <?php echo e($category->name); ?>

                                </label>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Publish Settings -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Publish Settings</h2>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select
                            name="status"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <option value="draft" <?php echo e($post->status === 'draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="published" <?php echo e($post->status === 'published' ? 'selected' : ''); ?>>
                                Published
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Published At</label>
                        <input
                            type="datetime-local"
                            name="published_at"
                            value="<?php echo e($post->published_at?->format('Y-m-d\TH:i')); ?>"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Featured Image</label>
                    <div
                        class="relative flex min-h-[200px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                        @click="$refs.imageInput.click()"
                    >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->featured_image): ?>
                            <img
                                src="<?php echo e(Storage::url($post->featured_image)); ?>"
                                class="h-full w-full rounded-2xl object-cover"
                            />
                        <?php else: ?>
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                <span class="text-xs font-semibold">Click to upload image</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <input type="file" name="featured_image" x-ref="imageInput" class="hidden" accept="image/*" />
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-full bg-gray-900 py-3 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Update Post
                </button>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views/admin/blog/posts/edit.blade.php ENDPATH**/ ?>