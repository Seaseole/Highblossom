<x-layouts::admin title="Create Blog Post">
    <div class="mx-auto max-w-7xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Create Blog Post</h1>
                <p class="text-gray-500 dark:text-gray-400">Compose a new masterpiece for your blog.</p>
            </div>
            <a
                href="{{ route('admin.posts.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Posts
            </a>
        </div>

        <form
            method="POST"
            action="{{ route('admin.posts.store') }}"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
        >
            @csrf

            <input type="hidden" name="content" id="content-input" />

            <div class="space-y-8 lg:col-span-2">
                <!-- Basic Info Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
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
                        ></textarea>
                    </div>
                </div>

                <!-- Content Blocks -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Content</h2>
                    <livewire:block-builder name="content" :value="old('content')" />
                </div>

                <!-- Categories & Tags -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Categories & Tags</h2>

                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categories</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($categories as $category)
                                <label class="cursor-pointer rounded-full border border-gray-200 bg-gray-50 px-4 py-2 text-sm transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white">
                                    <input
                                        type="checkbox"
                                        name="categories[]"
                                        value="{{ $category->id }}"
                                        class="sr-only"
                                    />
                                    {{ $category->name }}
                                </label>
                            @endforeach
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
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Published At</label>
                        <input
                            type="datetime-local"
                            name="published_at"
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
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            <span class="text-xs font-semibold">Click to upload image</span>
                        </div>
                        <input type="file" name="featured_image" x-ref="imageInput" class="hidden" accept="image/*" />
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-full bg-gray-900 py-3 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Create Post
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>
