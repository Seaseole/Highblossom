<?php if (isset($component)) { $__componentOriginal501803f3e4defcbbeaedee798b98ded4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal501803f3e4defcbbeaedee798b98ded4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::admin','data' => ['title' => 'Media Library']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Media Library']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto max-w-7xl space-y-8 py-10" x-data="mediaLibrary()">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Media Library</h1>
            <p class="text-gray-500 dark:text-gray-400">Upload or select existing media assets.</p>
        </div>

        
        <div class="flex w-max gap-2 rounded-full bg-gray-100 p-1.5 dark:bg-white/5">
            <button
                type="button"
                @click="activeTab = 'browse'"
                :class="activeTab === 'browse'
                    ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm'
                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                class="rounded-full px-6 py-2 text-sm font-medium transition-all"
            >
                Browse
            </button>
            <button
                type="button"
                @click="activeTab = 'upload'"
                :class="activeTab === 'upload'
                    ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm'
                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                class="rounded-full px-6 py-2 text-sm font-medium transition-all"
            >
                Upload
            </button>
        </div>

        
        <div
            x-show="activeTab === 'browse'"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="space-y-6 pt-2"
        >
            <?php echo $__env->make('admin.media-library.partials.image-grid', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div
            x-show="activeTab === 'upload'"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="space-y-6 pt-2"
            style="display: none"
        >
            <form
                action="<?php echo e(route('admin.media-library.upload')); ?>"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
            >
                <?php echo csrf_field(); ?>
                <input type="hidden" name="image_path" id="media-image-path" />

                <div id="media-image-preview"></div>
                <div id="media-image-progress"></div>

                <div class="relative flex min-h-[200px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white">
                    <label class="cursor-pointer p-6 text-center text-gray-500 dark:text-gray-400">
                        <span class="text-xs font-semibold">Click to upload image</span>
                        <input
                            type="file"
                            name="upload"
                            id="media-upload-input"
                            class="hidden"
                            accept="image/*"
                            required
                        />
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input
                            type="text"
                            name="title"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <select
                            name="category"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <option value="automotive">Automotive</option>
                            <option value="heavy_machinery">Heavy Machinery</option>
                            <option value="fleet">Fleet</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Upload Media
                </button>
            </form>
        </div>

        
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none">
            <div
                x-show="showModal"
                @click="showModal = false"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
            ></div>
            <div
                x-show="showModal"
                class="relative flex w-full max-w-4xl flex-col gap-8 rounded-3xl border border-gray-200 bg-white p-8 shadow-2xl md:flex-row dark:border-white/10 dark:bg-[#0A0A0F]"
            >
                <div class="flex items-center justify-center rounded-2xl bg-gray-50 p-4 md:w-3/5 dark:bg-white/5">
                    <img :src="selectedImage.url" class="max-h-[50vh] max-w-full rounded-lg object-contain" />
                </div>
                <div class="flex flex-col justify-between md:w-2/5">
                    <div>
                        <h2
                            class="mb-4 text-xl font-semibold text-gray-900 dark:text-white"
                            x-text="selectedImage.title"
                        ></h2>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase">File Info</dt>
                                <dd
                                    class="text-sm text-gray-700 dark:text-gray-300"
                                    x-text="selectedImage.metadata?.file_size"
                                ></dd>
                            </div>
                        </dl>
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button
                            @click="selectImage()"
                            class="flex-1 rounded-full bg-gray-900 py-2 text-sm font-medium text-white dark:bg-white dark:text-gray-900"
                        >
                            Select
                        </button>
                        <button
                            @click="deleteImage()"
                            class="rounded-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
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

<script src="<?php echo e(asset('js/image-upload.js')); ?>"></script>
<script>
    function mediaLibrary() {
        return {
            activeTab: 'browse',
            showModal: false,
            selectedImage: {},
            init() {
                window.addEventListener('open-image-preview', (e) => {
                    this.fetchImageDetails(e.detail.id);
                });
            },
            async fetchImageDetails(id) {
                try {
                    const response = await fetch(`/admin/media-library/${id}`);
                    this.selectedImage = await response.json();
                    this.showModal = true;
                } catch (error) {
                    console.error(error);
                }
            },
            selectImage() {
                window.dispatchEvent(new CustomEvent('image-selected', { detail: { url: this.selectedImage.url } }));
                this.showModal = false;
            },
            async deleteImage() {
                if (!confirm('Are you sure?')) return;
                try {
                    await fetch(`/admin/media-library/${this.selectedImage.id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    });
                    window.location.reload();
                } catch (error) {
                    console.error(error);
                }
            },
        };
    }

    (function () {
        const initMediaLibrary = function () {
            if (typeof ImageUploader !== 'undefined') {
                new ImageUploader({
                    fileInput: document.querySelector('input[name="upload"]'),
                    previewContainer: document.getElementById('media-image-preview'),
                    progressContainer: document.getElementById('media-image-progress'),
                    hiddenInput: document.getElementById('media-image-path'),
                    uploadUrl: '<?php echo e(route("admin.image-upload")); ?>',
                    csrfToken: '<?php echo e(csrf_token()); ?>',
                    folder: 'uploads',
                    maxSize: 5 * 1024 * 1024, // 5MB
                    acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'],
                    onUploadComplete: function (response) {
                        console.log('Image uploaded successfully:', response);
                        window.location.reload();
                    },
                    onUploadError: function (message) {
                        console.error('Upload error:', message);
                    },
                });
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMediaLibrary);
        } else {
            initMediaLibrary();
        }
    })();
</script>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\admin\media-library\index.blade.php ENDPATH**/ ?>