@php
use App\Services\VideoSourceDetector;
use App\Enums\VideoSourceType;

$src = $block['attributes']['src'] ?? '';
$detector = app(VideoSourceDetector::class);
$sourceType = $detector->detect($src);
$embedUrl = $detector->getEmbedUrl($src, $sourceType);
$fullUrl = $detector->getFullUrl($src);
@endphp

<div class="space-y-4">
    <!-- Video Preview with Auto-Detection Badge -->
    <div>
        <label class="block text-sm font-medium text-admin-text-muted mb-2">Video Preview</label>
        <div class="relative rounded-lg overflow-hidden bg-admin-surface-alt border border-admin-border">
            @if(!empty($src))
                {{-- Platform badge --}}
                <div class="absolute top-2 left-2 z-10">
                    <span class="px-2 py-1 text-xs font-medium rounded bg-admin-accent/20 text-admin-accent">
                        {{ $sourceType->label() }}
                    </span>
                </div>

                open file

                @if($sourceType->usesIframe() && $embedUrl)
                    {{-- Platform embed preview --}}
                    <div class="aspect-video bg-admin-surface flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-admin-text-muted">{{ $sourceType->label() }} (embedded)</p>
                        </div>
                    </div>
                @else
                    {{-- Native video preview --}}
                    <video src="{{ $fullUrl }}" class="w-full aspect-video object-cover" controls muted></video>
                @endif
            @else
                {{-- Empty state --}}
                <div class="aspect-video bg-admin-surface-alt flex items-center justify-center">
                    <div class="text-center text-admin-text-muted">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm">No video selected</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- File Upload -->
    <div x-data="{ 
        videoPreview: null,
        isDragging: false,
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.videoPreview = URL.createObjectURL(file);
            }
        }
    }">
        <label class="block text-sm font-medium text-admin-text-muted mb-2">Upload Video File</label>
        <div 
            class="relative group transition-all duration-300"
            :class="isDragging ? 'scale-[0.99]' : ''"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="isDragging = false; $refs.videoInput.files = $event.dataTransfer.files; handleFileSelect({target: $refs.videoInput})"
        >
            <div 
                class="relative border-2 border-dashed rounded-2xl p-12 transition-all duration-300 flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden"
                :class="isDragging ? 'border-admin-accent bg-admin-accent/5' : 'border-admin-border hover:border-admin-border-subtle bg-admin-surface-alt'"
                @click="$refs.videoInput.click()"
            >
                <template x-if="!videoPreview">
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 rounded-xl bg-admin-accent/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-admin-accent font-semibold mb-1">Click to upload or drag and drop</p>
                        <p class="text-admin-text-muted text-sm">MP4, WEBM, MOV up to 30MB</p>
                    </div>
                </template>

                <template x-if="videoPreview">
                    <div class="relative w-full aspect-video rounded-xl overflow-hidden border border-admin-border">
                        <video :src="videoPreview" class="w-full h-full object-cover" controls></video>
                        <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                            <div class="flex flex-col items-center text-white">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span class="font-semibold text-sm">Change Video</span>
                            </div>
                        </div>
                    </div>
                </template>

                <input 
                    type="file" 
                    id="video-file-{{ $index }}"
                    x-ref="videoInput" 
                    class="hidden" 
                    accept="video/mp4,video/webm,video/quicktime,video/x-msvideo"
                    @change="handleFileSelect"
                >
            </div>
        </div>
    </div>

    <!-- Hidden Input for Path -->
    <input
        type="hidden"
        id="video-path-{{ $index }}"
        wire:model.live="blocks.{{ $index }}.attributes.src"
    >

    <!-- URL Input -->
    <div>
        <label class="block text-sm font-medium text-admin-text-muted mb-2">
            Video URL
            <span class="text-xs text-admin-text-muted/70 ml-1">(YouTube, Vimeo, Dailymotion, Facebook, or direct link)</span>
        </label>
        <flux:input
            wire:model.live="blocks.{{ $index }}.attributes.src"
            type="url"
            placeholder="https://..."
        />
    </div>

    <flux:checkbox
        wire:model.live="blocks.{{ $index }}.attributes.controls"
        label="Show Controls"
    />

    <flux:input
        wire:model.live="blocks.{{ $index }}.attributes.class"
        label="CSS Classes"
        placeholder="Additional CSS classes..."
    />
</div>

@script
<script src="{{ asset('js/video-uploader.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof VideoUploader !== 'undefined') {
            new VideoUploader({
                fileInput: document.querySelector('#video-file-{{ $index }}'),
                previewContainer: document.querySelector('#video-file-{{ $index }}').parentElement,
                progressContainer: null,
                hiddenInput: document.querySelector('#video-path-{{ $index }}'),
                uploadUrl: '{{ route('admin.video-upload') }}',
                csrfToken: '{{ csrf_token() }}',
                maxSize: 30 * 1024 * 1024, // 30MB
                acceptedTypes: ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo'],
                onUploadComplete: function(response) {
                    console.log('Video upload complete:', response);
                },
                onUploadError: function(message) {
                    console.error('Video upload error:', message);
                }
            });
        }
    });
</script>
@endscript
