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

        <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="max-w-4xl">
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
                    
                    <livewire:block-builder name="content" :value="old('content', $post->content)" />
                    
                    <input type="hidden" name="content" id="content-input" value="{{ old('content', json_encode($post->content)) }}">
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
                    
                    <div>
                        <label class="block text-sm font-medium text-admin-text-muted mb-2">Image URL</label>
                        <input 
                            type="url" 
                            name="featured_image_url" 
                            value="{{ old('featured_image_url', $post->featured_image_url) }}"
                            class="w-full bg-admin-surface-alt border border-admin-border rounded-xl px-4 py-3 text-admin-text placeholder-admin-text-muted focus:ring-2 focus:ring-admin-accent focus:border-transparent"
                            placeholder="https://..."
                        >
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
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('blocks-updated', (event) => {
                const blocks = event.detail?.blocks ?? event.blocks ?? [];
                document.getElementById('content-input').value = JSON.stringify(blocks);
            });
        });

        // Ensure content is synced before form submission
        document.querySelector('form').addEventListener('submit', function() {
            const contentInput = document.getElementById('content-input');
            if (!contentInput.value) {
                contentInput.value = JSON.stringify([]);
            }
        });
    </script>
</x-layouts::admin>
