@aware(['post' => null])
@props([
    'existingPath' => null,
    'deleteable' => false,
])

<div class="space-y-4 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Featured Image</label>

    <input type="hidden" name="featured_image_path" id="featured-image-path" value="{{ $existingPath ?? '' }}" />
    <input type="hidden" name="delete_featured_image" id="delete-featured-image" value="0" />

    <div id="featured-image-preview" class="space-y-4">
        @if ($existingPath)
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl border border-gray-200 dark:border-white/10">
                <img
                    src="{{ Storage::url($existingPath) }}"
                    alt="{{ $post->title ?? 'Featured image' }}"
                    class="h-full w-full object-cover"
                />
            </div>
        @endif
    </div>

    <div id="featured-image-progress"></div>

    <div
        id="featured-image-dropzone"
        class="relative flex w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 p-6 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
    >
        <span class="text-xs text-gray-500 dark:text-gray-400">Click to upload image</span>
        <input type="file" accept="image/*" class="absolute inset-0 cursor-pointer opacity-0" />
    </div>

    @if ($deleteable && $existingPath)
        <button
            type="button"
            id="remove-featured-image-btn"
            class="w-full text-xs font-medium text-gray-500 transition-colors hover:text-red-500 dark:text-gray-400"
        >
            Remove Image
        </button>
    @endif

    <p class="text-[10px] text-gray-500 dark:text-gray-400">Recommended: 1600x900 (16:9), max 2MB</p>
</div>

<script src="{{ asset('js/image-upload.js') }}"></script>
<script>
    (function () {
        const initFeaturedImage = function () {
            const dropzone = document.getElementById('featured-image-dropzone');
            const preview = document.getElementById('featured-image-preview');
            if (!dropzone || !preview || typeof ImageUploader === 'undefined') {
                return;
            }

            function attachRemoveButtonHandler() {
                const removeBtn = document.getElementById('remove-featured-image-btn');
                if (!removeBtn) {
                    return;
                }
                removeBtn.onclick = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!confirm('Are you sure you want to remove the featured image?')) {
                        return;
                    }
                    document.getElementById('delete-featured-image').value = '1';
                    document.getElementById('featured-image-path').value = '';
                    preview.innerHTML = '';
                    document.getElementById('featured-image-progress').innerHTML = '';
                    removeBtn.remove();
                };
            }

            function addRemoveButton() {
                if (document.getElementById('remove-featured-image-btn')) {
                    return;
                }
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.id = 'remove-featured-image-btn';
                btn.className = 'w-full text-xs font-medium text-gray-500 transition-colors hover:text-red-500 dark:text-gray-400';
                btn.textContent = 'Remove Image';
                dropzone.insertAdjacentElement('afterend', btn);
                attachRemoveButtonHandler();
            }

            attachRemoveButtonHandler();

            new ImageUploader({
                fileInput: dropzone.querySelector('input[type=file]'),
                previewContainer: dropzone,
                progressContainer: document.getElementById('featured-image-progress'),
                hiddenInput: document.getElementById('featured-image-path'),
                uploadUrl: '{{ route("admin.image-upload") }}',
                csrfToken: '{{ csrf_token() }}',
                folder: 'uploads/blog',
                maxSize: 2 * 1024 * 1024,
                acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                onUploadComplete: function (response) {
                    document.getElementById('delete-featured-image').value = '0';
                    preview.innerHTML = `
                        <div class="relative aspect-video w-full overflow-hidden rounded-2xl border border-gray-200 dark:border-white/10">
                            <img src="${response.url}" alt="Featured image" class="h-full w-full object-cover" />
                        </div>`;
                    addRemoveButton();
                },
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initFeaturedImage);
        } else {
            initFeaturedImage();
        }
    })();
</script>
