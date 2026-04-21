<x-layouts::admin title="Edit SEO">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-4">
                <a href="{{ route('admin.seo.static-routes') }}" class="hover:text-zinc-700 transition-colors">SEO</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-zinc-400">Edit</span>
            </nav>
            <h1 class="text-4xl font-bold text-[#39393c] tracking-tight">Edit SEO: {{ $route_label }}</h1>
            <p class="mt-2 text-zinc-500">Manage meta tags, social sharing, and search engine settings</p>
        </div>

        <form method="POST" action="{{ route('admin.seo.update', $route->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Meta Tags -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-zinc-900">Basic Meta Tags</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="meta_title" class="block text-sm font-semibold text-zinc-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $route->meta_title) }}" maxlength="70" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Page title">
                        <p class="text-sm text-zinc-500 mt-2">{{ strlen(old('meta_title', $route->meta_title ?? '')) }}/70 characters</p>
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-semibold text-zinc-700 mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $route->meta_keywords) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="keyword1, keyword2, keyword3">
                    </div>

                    <div class="md:col-span-2">
                        <label for="meta_description" class="block text-sm font-semibold text-zinc-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="300" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 resize-none" placeholder="Brief description for search results">{{ old('meta_description', $route->meta_description) }}</textarea>
                        <p class="text-sm text-zinc-500 mt-2">{{ strlen(old('meta_description', $route->meta_description ?? '')) }}/300 characters</p>
                    </div>
                </div>
            </div>

            <!-- OpenGraph / Social -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-zinc-900">OpenGraph / Social</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="og_title" class="block text-sm font-semibold text-zinc-700 mb-2">OG Title</label>
                        <input type="text" name="og_title" id="og_title" value="{{ old('og_title', $route->og_title) }}" maxlength="70" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Social sharing title">
                    </div>

                    <div>
                        <label for="og_image" class="block text-sm font-semibold text-zinc-700 mb-2">OG Image URL</label>
                        <input type="text" name="og_image" id="og_image" value="{{ old('og_image', $route->og_image) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="md:col-span-2">
                        <label for="og_description" class="block text-sm font-semibold text-zinc-700 mb-2">OG Description</label>
                        <textarea name="og_description" id="og_description" rows="3" maxlength="300" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 resize-none" placeholder="Description for social media sharing">{{ old('og_description', $route->og_description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Twitter Cards -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-zinc-900">Twitter Cards</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="twitter_title" class="block text-sm font-semibold text-zinc-700 mb-2">Twitter Title</label>
                        <input type="text" name="twitter_title" id="twitter_title" value="{{ old('twitter_title', $route->twitter_title) }}" maxlength="70" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="Twitter sharing title">
                    </div>

                    <div>
                        <label for="twitter_image" class="block text-sm font-semibold text-zinc-700 mb-2">Twitter Image URL</label>
                        <input type="text" name="twitter_image" id="twitter_image" value="{{ old('twitter_image', $route->twitter_image) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="md:col-span-2">
                        <label for="twitter_description" class="block text-sm font-semibold text-zinc-700 mb-2">Twitter Description</label>
                        <textarea name="twitter_description" id="twitter_description" rows="3" maxlength="300" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200 resize-none" placeholder="Description for Twitter cards">{{ old('twitter_description', $route->twitter_description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-zinc-900">Advanced Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="canonical_url" class="block text-sm font-semibold text-zinc-700 mb-2">Canonical URL</label>
                        <input type="text" name="canonical_url" id="canonical_url" value="{{ old('canonical_url', $route->canonical_url) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="https://example.com/page">
                    </div>

                    <div>
                        <label for="robots" class="block text-sm font-semibold text-zinc-700 mb-2">Robots</label>
                        <input type="text" name="robots" id="robots" value="{{ old('robots', $route->robots) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="index, follow">
                    </div>

                    <div>
                        <label for="changefreq" class="block text-sm font-semibold text-zinc-700 mb-2">Change Frequency</label>
                        <select name="changefreq" id="changefreq" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200">
                            <option value="always" {{ old('changefreq', $route->changefreq) === 'always' ? 'selected' : '' }}>Always</option>
                            <option value="hourly" {{ old('changefreq', $route->changefreq) === 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="daily" {{ old('changefreq', $route->changefreq) === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('changefreq', $route->changefreq) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('changefreq', $route->changefreq) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('changefreq', $route->changefreq) === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="never" {{ old('changefreq', $route->changefreq) === 'never' ? 'selected' : '' }}>Never</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-semibold text-zinc-700 mb-2">Priority</label>
                        <input type="number" name="priority" id="priority" step="0.1" min="0" max="1" value="{{ old('priority', $route->priority) }}" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all duration-200" placeholder="0.5">
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-200">
                            <input type="checkbox" name="no_index" id="no_index" value="1" {{ old('no_index', $route->no_index) ? 'checked' : '' }} class="h-5 w-5 text-[#dc2626] focus:ring-[#dc2626] focus:ring-offset-0 border-zinc-300 rounded transition-all duration-200 cursor-pointer">
                            <label for="no_index" class="text-sm font-medium text-zinc-700 cursor-pointer select-none">No Index</label>
                            <span class="text-xs text-zinc-500 ml-auto">Prevent search engine indexing</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-200">
                <a href="{{ route('admin.seo.static-routes') }}" class="px-6 py-3 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-300 transition-all duration-200 active:scale-[0.98]">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#dc2626] text-white text-sm font-medium shadow-lg shadow-[#dc2626]/20 hover:bg-[#b91c1c] hover:shadow-xl hover:shadow-[#dc2626]/30 transition-all duration-200 active:scale-[0.98]">
                    Save SEO Settings
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>
