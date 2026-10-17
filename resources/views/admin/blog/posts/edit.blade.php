<x-layouts::admin title="Edit Blog Post">
    <div class="mx-auto max-w-7xl space-y-8 py-6 sm:py-10">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit Blog Post</h1>
                <p class="text-gray-500 dark:text-gray-400">
                    {{ $post->author ? 'Authored by '.$post->author->name.' · ' : '' }}
                    Created {{ $post->created_at?->format('M j, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-4">
                @if ($post->isLive())
                    <a
                        href="{{ route('blog.show', $post) }}"
                        target="_blank"
                        class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Preview
                    </a>
                @endif
                <a
                    href="{{ route('admin.posts.index') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    Back to Posts
                </a>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('admin.posts.update', $post) }}"
            enctype="multipart/form-data"
            x-data="postEditor"
            x-on:submit="guardSubmit($event)"
            x-on:input="refreshCounts()"
            x-on:change="refreshCounts()"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
        >
            @csrf
            @method('PUT')

            <input type="hidden" name="content" id="content-input" value="{{ old('content', json_encode($post->content)) }}" />

            <div class="space-y-8 lg:col-span-2">
                @include('admin.blog.posts.partials.basic-info')

                <!-- Content Blocks -->
                <div class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Content</h2>
                    <livewire:block-builder name="content" :value="old('content', $post->content)" />
                </div>

                @include('admin.blog.posts.partials.taxonomy')
            </div>

            <div class="space-y-8">
                @include('admin.blog.posts.partials.publish-settings')

                <x-blog.featured-image :existing-path="$post->featured_image_path" :deleteable="true" />

                <button
                    type="submit"
                    class="w-full rounded-full bg-gray-900 py-3 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Update Post
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>
