<x-layouts::admin title="Create Blog Post">
    <div class="mx-auto max-w-7xl space-y-8 py-6 sm:py-10">
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
            x-data="postEditor"
            x-on:submit="guardSubmit($event)"
            x-on:input="refreshCounts()"
            x-on:change="refreshCounts()"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
        >
            @csrf

            <input type="hidden" name="content" id="content-input" value="{{ old('content') }}" />

            <div class="space-y-8 lg:col-span-2">
                @include('admin.blog.posts.partials.basic-info', ['post' => null])

                <!-- Content Blocks -->
                <div class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Content</h2>
                    <livewire:block-builder name="content" :value="old('content')" />
                </div>

                @include('admin.blog.posts.partials.taxonomy', ['post' => null])
            </div>

            <div class="space-y-8">
                @include('admin.blog.posts.partials.publish-settings', ['post' => null])

                <x-blog.featured-image :existing-path="old('featured_image_path')" />

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
