<x-layouts::admin title="Edit SEO">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a href="{{ route('admin.seo.static-routes') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    ← Back to SEO Routes
                </a>
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">
                    Edit SEO: {{ $route_label }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">Update meta tags and search engine optimization.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.seo.update', $route->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Meta Cards -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Meta Tags</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $route->meta_title) }}" maxlength="70" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $route->meta_keywords) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" maxlength="300" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">{{ old('meta_description', $route->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Social Cards -->
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">OpenGraph / Social</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">OG Title</label>
                            <input type="text" name="og_title" value="{{ old('og_title', $route->og_title) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">OG Image URL</label>
                            <input type="text" name="og_image" value="{{ old('og_image', $route->og_image) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        </div>
                    </div>

                    <!-- Advanced Card -->
                    <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Advanced Settings</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Change Frequency</label>
                                <select name="changefreq" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                                    @foreach(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'] as $f)
                                        <option value="{{ $f }}" {{ old('changefreq', $route->changefreq) === $f ? 'selected' : '' }}>{{ ucfirst($f) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                                <input type="number" name="priority" step="0.1" min="0" max="1" value="{{ old('priority', $route->priority) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                            </div>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="no_index" value="1" {{ old('no_index', $route->no_index) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                            <span class="text-sm text-gray-700 dark:text-gray-300">No Index (Prevent search engine indexing)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-white/5 flex items-center justify-end gap-3">
                <a href="{{ route('admin.seo.static-routes') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>