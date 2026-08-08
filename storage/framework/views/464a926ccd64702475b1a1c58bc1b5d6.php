<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'About Us Content']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'About Us Content']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">About Us</h1>
                <p class="text-gray-500 dark:text-gray-400">Manage your About Us page content.</p>
            </div>
        </div>

        <form
            action="<?php echo e(route('admin.about-us.update')); ?>"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-8"
            id="aboutUsForm"
        >
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input
                                type="text"
                                name="title"
                                value="<?php echo e(old('title', $content->title)); ?>"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-500"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Subtitle</label>
                            <input
                                type="text"
                                name="subtitle"
                                value="<?php echo e(old('subtitle', $content->subtitle)); ?>"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-500"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div
                            class="space-y-2"
                            x-data="{
                                editorId: 'body-editor',
                                initEditor() {
                                    this.$nextTick(() => {
                                        if (CKEDITOR.instances[this.editorId])
                                            CKEDITOR.instances[this.editorId].destroy(true);
                                        const instance = CKEDITOR.replace(this.editorId, { height: 200 });
                                        instance.on('change', () => {
                                            document.querySelector('textarea[name=body]').value = instance.getData();
                                        });
                                    });
                                },
                            }"
                            x-init="initEditor()"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Body</label>
                            <textarea
                                name="body"
                                id="body-editor"
                                rows="8"
                                required
                            ><?php echo e(old('body', $content->body)); ?></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-500"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div
                            class="space-y-2"
                            x-data="{
                                editorId: 'mission-editor',
                                initEditor() {
                                    this.$nextTick(() => {
                                        if (CKEDITOR.instances[this.editorId])
                                            CKEDITOR.instances[this.editorId].destroy(true);
                                        const instance = CKEDITOR.replace(this.editorId, { height: 150 });
                                        instance.on('change', () => {
                                            document.querySelector('textarea[name=mission]').value = instance.getData();
                                        });
                                    });
                                },
                            }"
                            x-init="initEditor()"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mission</label>
                            <textarea
                                name="mission"
                                id="mission-editor"
                                rows="3"
                            ><?php echo e(old('mission', $content->mission)); ?></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mission'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-500"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div
                            class="space-y-2"
                            x-data="{
                                editorId: 'vision-editor',
                                initEditor() {
                                    this.$nextTick(() => {
                                        if (CKEDITOR.instances[this.editorId])
                                            CKEDITOR.instances[this.editorId].destroy(true);
                                        const instance = CKEDITOR.replace(this.editorId, { height: 150 });
                                        instance.on('change', () => {
                                            document.querySelector('textarea[name=vision]').value = instance.getData();
                                        });
                                    });
                                },
                            }"
                            x-init="initEditor()"
                        >
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Vision</label>
                            <textarea
                                name="vision"
                                id="vision-editor"
                                rows="3"
                            ><?php echo e(old('vision', $content->vision)); ?></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['vision'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-500"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Status</h3>
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                <?php echo e(old('is_active', $content->is_active) ? 'checked' : ''); ?>

                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                            />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Active (visible on site)</span>
                        </label>

                        <div class="space-y-4 border-t border-gray-100 pt-4 dark:border-white/10">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Hero Image</label>
                            <input
                                type="hidden"
                                name="hero_image_path"
                                id="hero-image-path"
                                value="<?php echo e($content->hero_image ?? ''); ?>"
                            />
                            <input type="hidden" name="remove_hero_image" id="remove-hero-image" value="0" />

                            <div id="hero-image-preview" class="space-y-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->hero_image): ?>
                                    <div class="relative aspect-video w-full overflow-hidden rounded-2xl border border-gray-200 dark:border-white/10">
                                        <img
                                            src="<?php echo e(Storage::url($content->hero_image)); ?>"
                                            alt="Hero"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div id="hero-image-progress"></div>

                            <div class="relative flex w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 p-6 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white">
                                <span class="text-xs text-gray-500">Click to upload image</span>
                                <input
                                    type="file"
                                    name="hero_image"
                                    accept="image/*"
                                    class="absolute inset-0 cursor-pointer opacity-0"
                                />
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->hero_image): ?>
                                <button
                                    type="button"
                                    id="remove-hero-image-btn"
                                    class="w-full text-xs font-medium text-gray-500 transition-colors hover:text-red-500 dark:text-gray-400"
                                >
                                    Remove Image
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400">Recommended: 1920x600, max 2MB</p>
                        </div>

                        <div class="border-t border-gray-100 pt-4 dark:border-white/10">
                            <button
                                type="submit"
                                class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                            >
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="<?php echo e(asset('js/image-upload.js')); ?>"></script>
    <script>
        (function () {
            const initAboutUs = function () {
                function attachRemoveButtonHandler() {
                    const removeHeroImageBtn = document.getElementById('remove-hero-image-btn');
                    if (removeHeroImageBtn) {
                        removeHeroImageBtn.onclick = function (e) {
                            e.stopPropagation();
                            e.preventDefault();
                            if (!confirm('Are you sure you want to remove the hero image?')) return;
                            const removeInput = document.getElementById('remove-hero-image');
                            const pathInput = document.getElementById('hero-image-path');
                            if (removeInput) removeInput.value = '1';
                            if (pathInput) pathInput.value = '';
                            const preview = document.getElementById('hero-image-preview');
                            if (preview) preview.innerHTML = '';
                            const progress = document.getElementById('hero-image-progress');
                            if (progress) progress.innerHTML = '';
                            removeHeroImageBtn.remove();
                            const form = document.getElementById('aboutUsForm');
                            if (form) form.submit();
                        };
                    }
                }
                attachRemoveButtonHandler();
                if (typeof ImageUploader !== 'undefined') {
                    new ImageUploader({
                        fileInput: document.querySelector('input[name="hero_image"]'),
                        previewContainer: document.getElementById('hero-image-preview'),
                        progressContainer: document.getElementById('hero-image-progress'),
                        hiddenInput: document.getElementById('hero-image-path'),
                        uploadUrl: '<?php echo e(route("admin.image-upload")); ?>',
                        csrfToken: '<?php echo e(csrf_token()); ?>',
                        folder: 'about-us',
                        maxSize: 2 * 1024 * 1024,
                        acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                        onUploadComplete: function (response) {
                            const removeInput = document.getElementById('remove-hero-image');
                            if (removeInput) removeInput.value = '0';
                            if (!document.getElementById('remove-hero-image-btn')) {
                                const fileInput = document.querySelector('input[name="hero_image"]');
                                if (fileInput) {
                                    const uploadDiv = fileInput.closest('div');
                                    const newBtn = document.createElement('button');
                                    newBtn.type = 'button';
                                    newBtn.id = 'remove-hero-image-btn';
                                    newBtn.className =
                                        'w-full text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors';
                                    newBtn.innerHTML = 'Remove Image';
                                    uploadDiv.insertAdjacentElement('afterend', newBtn);
                                    attachRemoveButtonHandler();
                                }
                            }
                        },
                    });
                }
            };
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAboutUs);
            } else {
                initAboutUs();
            }
        })();
    </script>
    <style>
        .cke_chrome {
            border-color: #e5e7eb !important;
            border-radius: 12px !important;
        }
        .dark .cke_chrome {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        .cke_top {
            background-color: #f9fafb !important;
            border-bottom-color: #e5e7eb !important;
            border-radius: 12px 12px 0 0 !important;
        }
        .dark .cke_top {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        .cke_contents {
            background-color: #ffffff !important;
            border-radius: 0 0 12px 12px !important;
        }
        .dark .cke_contents {
            background-color: #0a0a0f !important;
        }
        .cke_editable {
            color: #111827 !important;
            padding: 15px !important;
        }
        .dark .cke_editable {
            color: #f9fafb !important;
        }
    </style>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views/admin/about-us/edit.blade.php ENDPATH**/ ?>