<x-layouts::admin title="Edit Blog Post">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Edit Blog Post</h1>
            <a href="{{ route('admin.posts.index') }}" class="admin-action-btn admin-action-btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data" class="max-w-4xl">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
                    <h2 class="text-lg font-semibold text-admin-text mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-admin-text-muted mb-2">Title</label>
                            <input 
                                type="text" 
                                name="title" 
                                value="{{ old('title', $post->title) }}" 
                                required
                                class="w-full bg-admin-surface-alt border border-admin-border rounded-xl px-4 py-3 text-admin-text placeholder-admin-text-muted focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                placeholder="Enter post title..."
                            >
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-admin-text-muted mb-2">Excerpt</label>
                            <textarea 
                                name="excerpt" 
                                rows="3"
                                class="w-full bg-admin-surface-alt border border-admin-border rounded-xl px-4 py-3 text-admin-text placeholder-admin-text-muted focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                placeholder="Brief summary of the post..."
                            >{{ old('excerpt', $post->excerpt) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-admin-text-muted mb-2">Status</label>
                                <select 
                                    name="status" 
                                    required
                                    class="w-full bg-admin-surface-alt border border-admin-border rounded-xl px-4 py-3 text-admin-text focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                >
                                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-admin-text-muted mb-2">Published At</label>
                                <input 
                                    type="datetime-local" 
                                    name="published_at" 
                                    value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                                    class="w-full bg-admin-surface-alt border border-admin-border rounded-xl px-4 py-3 text-admin-text focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
                    <h2 class="text-lg font-semibold text-admin-text mb-4">Content Blocks</h2>

                    <div x-ref="blockBuilder">
                        <livewire:block-builder name="content" :value="old('content', $post->content)" />
                    </div>

                    <input type="hidden" name="content" id="content-input" x-ref="contentInput" value="{{ old('content', json_encode($post->content)) }}">
                </div>

                <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
                    <h2 class="text-lg font-semibold text-admin-text mb-4">Categories & Tags</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-admin-text-muted mb-3">Categories</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($categories as $category)
                                    <label class="relative cursor-pointer group">
                                        <input 
                                            type="checkbox" 
                                            name="categories[]" 
                                            value="{{ $category->id }}"
                                            {{ in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                            class="peer sr-only"
                                        >
                                        <div class="px-3 py-2 rounded-lg border border-admin-border bg-admin-surface text-admin-text-muted text-sm text-center transition-all duration-200 peer-checked:bg-admin-accent/20 peer-checked:border-admin-accent peer-checked:text-admin-text group-hover:border-admin-text-muted/50">
                                            {{ $category->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-admin-text-muted mb-3">Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <label class="relative cursor-pointer group">
                                        <input 
                                            type="checkbox" 
                                            name="tags[]" 
                                            value="{{ $tag->id }}"
                                            {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                            class="peer sr-only"
                                        >
                                        <div class="px-3 py-1.5 rounded-full border border-admin-border bg-admin-surface text-admin-text-muted text-sm transition-all duration-200 peer-checked:bg-admin-accent/20 peer-checked:border-admin-accent peer-checked:text-admin-text group-hover:border-admin-text-muted/50">
                                            {{ $tag->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
                    <h2 class="text-lg font-semibold text-admin-text mb-4">Featured Image</h2>
                    
                    {{-- Existing Image --}}
                    @if($post->featured_image_url)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-admin-text-muted mb-2">Current Image</label>
                            <div class="relative inline-block">
                                <img src="{{ $post->featured_image_url }}" alt="Current featured image" class="max-h-48 rounded-lg">
                                <label class="flex items-center gap-2 mt-2 cursor-pointer">
                                    <input type="checkbox" name="delete_featured_image" value="1" class="rounded border-admin-border bg-admin-surface text-admin-accent focus:ring-admin-accent">
                                    <span class="text-sm text-admin-text-muted">Remove image</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    {{-- Upload New Image --}}
                    <div>
                        <label class="block text-sm font-medium text-admin-text-muted mb-2">
                            {{ $post->featured_image_url ? 'Replace Image' : 'Upload Image' }}
                        </label>
                        <div class="border-2 border-dashed border-admin-border rounded-xl p-6 text-center hover:border-admin-accent/50 transition-colors">
                            <input 
                                type="file" 
                                name="featured_image" 
                                accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                                id="featured-image-upload"
                                class="hidden"
                                onchange="previewImage(this)"
                            >
                            <label for="featured-image-upload" class="cursor-pointer">
                                <div id="image-preview-container" class="hidden mb-4">
                                    <img id="image-preview" src="" alt="Preview" class="max-h-64 mx-auto rounded-lg">
                                </div>
                                <div id="upload-placeholder">
                                    <svg class="w-12 h-12 mx-auto text-admin-text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-admin-text-muted">Click to upload or drag and drop</p>
                                    <p class="text-xs text-admin-text-muted mt-1">JPEG, PNG, WebP, GIF (max 5MB)</p>
                                </div>
                            </label>
                        </div>
                        @error('featured_image')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.posts.index') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Update Post
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('blocks-updated', (event) => {
                const blocks = event.blocks || (Array.isArray(event) ? event : []);
                document.getElementById('content-input').value = JSON.stringify(blocks);
                console.log('Blocks updated:', blocks);
            });
        });

        // Ensure content is synced before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const contentInput = document.getElementById('content-input');
            const blockBuilder = document.querySelector('[wire\\:id]');

            // Get current blocks from Livewire component synchronously
            if (blockBuilder && blockBuilder.__livewire) {
                const currentBlocks = blockBuilder.__livewire.get('blocks') || [];
                contentInput.value = JSON.stringify(currentBlocks);
            } else if (!contentInput.value) {
                contentInput.value = JSON.stringify([]);
            }
        });
    </script>
</x-layouts::admin>
