<x-layouts::admin title="Edit Blog Post">
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 text-sm text-admin-text-muted hover:text-admin-accent transition-colors duration-200 group">
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Posts
            </a>
        </div>

        {{-- Title Section --}}
        <div class="mb-8">
            <h1 class="font-headline text-3xl font-bold text-admin-text tracking-tight">Edit Blog Post</h1>
            <p class="text-admin-text-muted text-sm mt-2">Update post content and settings</p>
        </div>

        <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Column - Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Basic Information --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                                <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            Basic Information
                        </h2>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Title</label>
                                <input 
                                    type="text" 
                                    name="title" 
                                    value="{{ old('title', $post->title) }}" 
                                    required
                                    class="admin-form-input w-full"
                                    placeholder="Enter post title..."
                                >
                                @error('title')
                                    <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Excerpt</label>
                                <textarea 
                                    name="excerpt" 
                                    rows="3"
                                    class="admin-form-input w-full resize-none"
                                    placeholder="Brief summary of the post..."
                                >{{ old('excerpt', $post->excerpt) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Content Blocks --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                                <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </span>
                            Content Blocks
                        </h2>

                        <div x-ref="blockBuilder">
                            <livewire:block-builder name="content" :value="old('content', $post->content)" />
                        </div>

                        <input type="hidden" name="content" id="content-input" x-ref="contentInput" value="{{ old('content', json_encode($post->content)) }}">
                    </div>

                    {{-- Categories & Tags --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                                <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </span>
                            Categories & Tags
                        </h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-3">Categories</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($categories as $category)
                                        <label class="relative cursor-pointer group">
                                            <input 
                                                type="checkbox" 
                                                name="categories[]" 
                                                value="{{ $category->id }}"
                                                {{ in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                                class="peer sr-only"
                                            >
                                            <div class="px-3 py-2 rounded-xl border border-admin-border bg-admin-surface text-admin-text-muted text-sm text-center transition-all duration-200 peer-checked:bg-admin-accent/20 peer-checked:border-admin-accent peer-checked:text-admin-text group-hover:border-admin-text-muted/50">
                                                {{ $category->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-3">Tags</label>
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
                </div>

                {{-- Right Column - Meta & Media --}}
                <div class="space-y-6">
                    {{-- Publish Settings --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-sm font-semibold text-admin-text uppercase tracking-wide mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Publish Settings
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Status</label>
                                <select name="status" required class="admin-form-input w-full">
                                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Published At</label>
                                <input 
                                    type="datetime-local" 
                                    name="published_at" 
                                    value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                                    class="admin-form-input w-full"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-sm font-semibold text-admin-text uppercase tracking-wide mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Featured Image
                        </h2>
                        
                        {{-- Existing Image --}}
                        @if($post->featured_image_url)
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Current Image</label>
                                <div class="relative">
                                    <img src="{{ $post->featured_image_url }}" alt="Current featured image" class="max-h-48 mx-auto rounded-xl">
                                    <label class="flex items-center gap-2 mt-3 p-3 bg-admin-surface-alt/50 rounded-xl border border-admin-border-subtle cursor-pointer hover:border-admin-accent/30 transition-colors">
                                        <input type="checkbox" name="delete_featured_image" value="1" class="h-4 w-4 bg-admin-input-bg border-admin-border rounded focus:ring-2 focus:ring-admin-accent cursor-pointer">
                                        <span class="text-sm text-admin-text-muted">Remove image</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        {{-- Upload New Image --}}
                        <div>
                            <label class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">
                                {{ $post->featured_image_url ? 'Replace Image' : 'Upload Image' }}
                            </label>
                            <div class="border-2 border-dashed border-admin-border rounded-2xl p-6 text-center hover:border-admin-accent/50 transition-colors">
                                <input 
                                    type="file" 
                                    name="featured_image" 
                                    accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                                    id="featured-image-upload"
                                    class="hidden"
                                    onchange="previewImage(this)"
                                >
                                <label for="featured-image-upload" class="cursor-pointer block">
                                    <div id="image-preview-container" class="hidden mb-4">
                                        <img id="image-preview" src="" alt="Preview" class="max-h-48 mx-auto rounded-xl">
                                    </div>
                                    <div id="upload-placeholder">
                                        <svg class="w-12 h-12 mx-auto text-admin-text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm text-admin-text-muted">Click to upload</p>
                                        <p class="text-xs text-admin-text-muted mt-1">JPEG, PNG, WebP</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('featured_image')
                            <p class="mt-2 text-sm text-admin-accent">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="admin-action-btn admin-action-btn-primary w-full justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Post
                        </button>
                        <a href="{{ route('admin.posts.index') }}" class="admin-action-btn admin-action-btn-secondary w-full justify-center">
                            Cancel
                        </a>
                    </div>
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
