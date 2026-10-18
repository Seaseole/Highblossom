{{--
    Reusable admin image field: AJAX upload through the admin.image-upload
    endpoint with live preview, progress bar, inline errors and optional
    removal. Submits the stored path via a hidden input (SafeStoredUploadPath
    validated) instead of a multipart file, matching the canonical pattern
    used by blog posts and about-us.
--}}
@props([
    'name' => 'image_path',
    'removeName' => null,
    'label' => 'Image',
    'folder' => 'uploads/images',
    'existingPath' => null,
    'maxSize' => 2 * 1024 * 1024,
    'hint' => 'Max 2MB. JPEG, PNG or WebP.',
    'deleteable' => false,
    'id' => null,
])

@php
    $idPrefix = $id ?? \Illuminate\Support\Str::kebab($name);
    $existingUrl = filled($existingPath) ? media_url($existingPath) : null;
@endphp

<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>

    <input type="hidden" name="{{ $name }}" id="{{ $idPrefix }}-path" value="{{ $existingPath ?? '' }}" />
    @if ($removeName)
        <input type="hidden" name="{{ $removeName }}" id="{{ $idPrefix }}-remove" value="0" />
    @endif

    <div id="{{ $idPrefix }}-preview" class="space-y-4">
        @if ($existingUrl)
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl border border-gray-200 dark:border-white/10">
                <img src="{{ $existingUrl }}" alt="{{ $label }}" class="h-full w-full object-cover" />
            </div>
        @endif
    </div>

    <div id="{{ $idPrefix }}-progress"></div>

    <div
        id="{{ $idPrefix }}-dropzone"
        class="relative flex min-h-[160px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 p-6 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
    >
        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload image</span>
        <input type="file" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 cursor-pointer opacity-0" />
    </div>

    @if ($deleteable && $existingPath)
        <button
            type="button"
            id="{{ $idPrefix }}-remove-btn"
            class="w-full text-xs font-medium text-gray-500 transition-colors hover:text-red-500 dark:text-gray-400"
        >
            Remove Image
        </button>
    @endif

    @error($name)
        <p class="text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror

    <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $hint }}</p>
</div>

<script src="{{ asset('js/image-upload.js') }}"></script>
<script>
    (function () {
        const init = function () {
            const dropzone = document.getElementById('{{ $idPrefix }}-dropzone');
            const preview = document.getElementById('{{ $idPrefix }}-preview');
            if (!dropzone || !preview || typeof ImageUploader === 'undefined') {
                return;
            }

            const pathInput = document.getElementById('{{ $idPrefix }}-path');
            const removeInput = document.getElementById('{{ $idPrefix }}-remove');
            const progress = document.getElementById('{{ $idPrefix }}-progress');
            const deleteable = @json($deleteable);
            const labelText = @json($label);

            function attachRemoveButtonHandler() {
                const removeBtn = document.getElementById('{{ $idPrefix }}-remove-btn');
                if (!removeBtn) {
                    return;
                }
                removeBtn.onclick = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!confirm('Are you sure you want to remove this image?')) {
                        return;
                    }
                    if (removeInput) removeInput.value = '1';
                    pathInput.value = '';
                    preview.innerHTML = '';
                    progress.innerHTML = '';
                    removeBtn.remove();
                };
            }

            function addRemoveButton() {
                if (!deleteable || document.getElementById('{{ $idPrefix }}-remove-btn')) {
                    return;
                }
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.id = '{{ $idPrefix }}-remove-btn';
                btn.className = 'w-full text-xs font-medium text-gray-500 transition-colors hover:text-red-500 dark:text-gray-400';
                btn.textContent = 'Remove Image';
                dropzone.insertAdjacentElement('afterend', btn);
                attachRemoveButtonHandler();
            }

            attachRemoveButtonHandler();

            new ImageUploader({
                fileInput: dropzone.querySelector('input[type=file]'),
                previewContainer: dropzone,
                progressContainer: progress,
                hiddenInput: pathInput,
                uploadUrl: @json(route('admin.image-upload')),
                csrfToken: @json(csrf_token()),
                folder: @json($folder),
                maxSize: {{ (int) $maxSize }},
                acceptedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
                onUploadComplete: function (response) {
                    if (removeInput) removeInput.value = '0';
                    preview.innerHTML = `
                        <div class="relative aspect-video w-full overflow-hidden rounded-2xl border border-gray-200 dark:border-white/10">
                            <img src="${response.url}" alt="${labelText}" class="h-full w-full object-cover" />
                        </div>`;
                    addRemoveButton();
                },
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
