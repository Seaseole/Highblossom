<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Create Staff Member']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Create Staff Member']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Add Staff Member</h1>
                <p class="text-gray-500 dark:text-gray-400">Introduce a new member of your expert team.</p>
            </div>
            <a
                href="<?php echo e(route('admin.staff.index')); ?>"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Staff
            </a>
        </div>

        <form
            action="<?php echo e(route('admin.staff.store')); ?>"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
            x-data="{
                imagePreview: null,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) this.imagePreview = URL.createObjectURL(file);
                },
            }"
        >
            <?php echo csrf_field(); ?>

            <div class="space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input
                        type="text"
                        name="name"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                    />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <input
                        type="text"
                        name="role"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                    />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Bio</label>
                    <textarea
                        name="bio"
                        rows="4"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                    ></textarea>
                </div>
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                    <div
                        class="relative flex min-h-[160px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                        @click="$refs.photoInput.click()"
                    >
                        <template x-if="! imagePreview">
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                <span class="text-xs font-semibold">Click to upload photo</span>
                            </div>
                        </template>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="max-h-[140px] rounded-full object-cover" />
                        </template>
                        <input
                            type="file"
                            name="photo"
                            x-ref="photoInput"
                            class="hidden"
                            accept="image/*"
                            @change="handleFileSelect"
                            required
                        />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6 dark:border-white/5">
                <a
                    href="<?php echo e(route('admin.staff.index')); ?>"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >Cancel</a>
                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Save Member
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views\admin\staff\create.blade.php ENDPATH**/ ?>