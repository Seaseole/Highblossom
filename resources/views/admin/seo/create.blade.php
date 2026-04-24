<x-layouts::admin title="Create SEO Configuration">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Configure SEO: {{ $route_label }}</h1>
            <a href="{{ route('admin.seo.static-routes') }}" class="admin-action-btn admin-action-btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.seo.store') }}" class="max-w-2xl">
            @csrf
            <input type="hidden" name="route_name" value="{{ $route_name }}">

            <div class="bg-admin-surface-alt border border-admin-border rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-admin-text">Basic Meta Tags</h3>
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-admin-text-muted mb-2">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" maxlength="70" class="w-full admin-form-input" placeholder="Page title">
                        <p class="text-sm text-admin-text-muted mt-1">{{ strlen(old('meta_title', '')) }}/70 characters</p>
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-admin-text-muted mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}" class="w-full admin-form-input" placeholder="keyword1, keyword2, keyword3">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-admin-text-muted mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="300" class="w-full admin-form-input resize-none" placeholder="Brief description for search results">{{ old('meta_description') }}</textarea>
                        <p class="text-sm text-admin-text-muted mt-1">{{ strlen(old('meta_description', '')) }}/300 characters</p>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-admin-text pt-4 border-t border-admin-border-subtle">OpenGraph / Social</h3>
                <div class="space-y-4">
                    <div>
                        <label for="og_title" class="block text-sm font-medium text-admin-text-muted mb-2">OG Title</label>
                        <input type="text" name="og_title" id="og_title" value="{{ old('og_title') }}" maxlength="70" class="w-full admin-form-input" placeholder="Social sharing title">
                    </div>

                    <div>
                        <label for="og_image" class="block text-sm font-medium text-admin-text-muted mb-2">OG Image URL</label>
                        <input type="text" name="og_image" id="og_image" value="{{ old('og_image') }}" class="w-full admin-form-input" placeholder="https://example.com/image.jpg">
                    </div>

                    <div>
                        <label for="og_description" class="block text-sm font-medium text-admin-text-muted mb-2">OG Description</label>
                        <textarea name="og_description" id="og_description" rows="3" maxlength="300" class="w-full admin-form-input resize-none" placeholder="Description for social media sharing">{{ old('og_description') }}</textarea>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-admin-text pt-4 border-t border-admin-border-subtle">Twitter Cards</h3>
                <div class="space-y-4">
                    <div>
                        <label for="twitter_title" class="block text-sm font-medium text-admin-text-muted mb-2">Twitter Title</label>
                        <input type="text" name="twitter_title" id="twitter_title" value="{{ old('twitter_title') }}" maxlength="70" class="w-full admin-form-input" placeholder="Twitter sharing title">
                    </div>

                    <div>
                        <label for="twitter_image" class="block text-sm font-medium text-admin-text-muted mb-2">Twitter Image URL</label>
                        <input type="text" name="twitter_image" id="twitter_image" value="{{ old('twitter_image') }}" class="w-full admin-form-input" placeholder="https://example.com/image.jpg">
                    </div>

                    <div>
                        <label for="twitter_description" class="block text-sm font-medium text-admin-text-muted mb-2">Twitter Description</label>
                        <textarea name="twitter_description" id="twitter_description" rows="3" maxlength="300" class="w-full admin-form-input resize-none" placeholder="Description for Twitter cards">{{ old('twitter_description') }}</textarea>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-admin-text pt-4 border-t border-admin-border-subtle">Advanced Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label for="canonical_url" class="block text-sm font-medium text-admin-text-muted mb-2">Canonical URL</label>
                        <input type="text" name="canonical_url" id="canonical_url" value="{{ old('canonical_url') }}" class="w-full admin-form-input" placeholder="https://example.com/page">
                    </div>

                    <div>
                        <label for="robots" class="block text-sm font-medium text-admin-text-muted mb-2">Robots</label>
                        <input type="text" name="robots" id="robots" value="{{ old('robots') }}" class="w-full admin-form-input" placeholder="index, follow">
                    </div>

                    <div>
                        <label for="changefreq" class="block text-sm font-medium text-admin-text-muted mb-2">Change Frequency</label>
                        <select name="changefreq" id="changefreq" class="w-full admin-form-input">
                            <option value="always" {{ old('changefreq') === 'always' ? 'selected' : '' }}>Always</option>
                            <option value="hourly" {{ old('changefreq') === 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="daily" {{ old('changefreq') === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('changefreq') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('changefreq', 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('changefreq') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="never" {{ old('changefreq') === 'never' ? 'selected' : '' }}>Never</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-admin-text-muted mb-2">Priority</label>
                        <input type="number" name="priority" id="priority" step="0.1" min="0" max="1" value="{{ old('priority', '0.5') }}" class="w-full admin-form-input" placeholder="0.5">
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-admin-surface-alt rounded-xl border border-admin-border">
                        <input type="checkbox" name="no_index" id="no_index" value="1" {{ old('no_index') ? 'checked' : '' }} class="h-5 w-5 bg-admin-input-bg border-admin-border rounded focus:ring-2 focus:ring-admin-accent cursor-pointer">
                        <label for="no_index" class="text-sm font-medium text-admin-text cursor-pointer select-none">No Index</label>
                        <span class="text-xs text-admin-text-muted ml-auto">Prevent search engine indexing</span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.seo.static-routes') }}" class="admin-action-btn admin-action-btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-action-btn admin-action-btn-primary">
                        Create SEO Configuration
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
