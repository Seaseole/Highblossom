<x-layouts::admin title="Edit SEO">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a
                    href="{{ route('admin.seo.static-routes') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    ← Back to SEO Routes
                </a>
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    Edit SEO: {{ $route_label }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">Update meta tags and search engine optimization.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.seo.update', $route->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Meta Cards -->
                <div class="space-y-6">
                    <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Meta Tags</h2>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Title</label>
                            <input
                                type="text"
                                name="meta_title"
                                value="{{ old('meta_title', $route->meta_title) }}"
                                maxlength="70"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Keywords</label>
                            <input
                                type="text"
                                name="meta_keywords"
                                value="{{ old('meta_keywords', $route->meta_keywords) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
                            <textarea
                                name="meta_description"
                                rows="3"
                                maxlength="300"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            >{{ old('meta_description', $route->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Social Cards -->
                    <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">OpenGraph / Social</h2>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">OG Title</label>
                            <input
                                type="text"
                                name="og_title"
                                value="{{ old('og_title', $route->og_title) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">OG Image URL</label>
                            <input
                                type="text"
                                name="og_image"
                                value="{{ old('og_image', $route->og_image) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                    </div>

                    <!-- Advanced Card -->
                    <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Advanced Settings</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Change Frequency</label>
                                <select
                                    name="changefreq"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                >
                                    @foreach (['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'] as $f)
                                        <option
                                            value="{{ $f }}"
                                            {{ old('changefreq', $route->changefreq) === $f ? 'selected' : '' }}
                                        >
                                            {{ ucfirst($f) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                                <input
                                    type="number"
                                    name="priority"
                                    step="0.1"
                                    min="0"
                                    max="1"
                                    value="{{ old('priority', $route->priority) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                            </div>
                        </div>
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="no_index"
                                value="1"
                                {{ old('no_index', $route->no_index) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                            />
                            <span class="text-sm text-gray-700 dark:text-gray-300">No Index (Prevent search engine indexing)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6 dark:border-white/5">
                <a
                    href="{{ route('admin.seo.static-routes') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >Cancel</a>
                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>
