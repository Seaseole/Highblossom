<x-layouts::admin title="Create SEO Configuration">
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.seo.static-routes') }}" class="inline-flex items-center gap-2 text-sm text-admin-text-muted hover:text-admin-accent transition-colors duration-200 group">
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to SEO Routes
            </a>
        </div>

        {{-- Title Section --}}
        <div class="mb-8">
            <h1 class="font-headline text-3xl font-bold text-admin-text tracking-tight">
                Configure SEO: <span class="text-admin-accent">{{ $route_label }}</span>
            </h1>
            <p class="text-admin-text-muted text-sm mt-2">Set up meta tags and search engine optimization</p>
        </div>

        <form method="POST" action="{{ route('admin.seo.store') }}">
            @csrf
            <input type="hidden" name="route_name" value="{{ $route_name }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Left Column - Basic & Social --}}
                <div class="space-y-6">
                    {{-- Basic Meta Tags --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                                <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            Basic Meta Tags
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label for="meta_title" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" maxlength="70" class="admin-form-input w-full" placeholder="Page title">
                                <p class="text-xs text-admin-text-muted mt-1">{{ strlen(old('meta_title', '')) }}/70 characters</p>
                            </div>

                            <div>
                                <label for="meta_keywords" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Meta Keywords</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}" class="admin-form-input w-full" placeholder="keyword1, keyword2, keyword3">
                            </div>

                            <div>
                                <label for="meta_description" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" rows="3" maxlength="300" class="admin-form-input w-full resize-none" placeholder="Brief description for search results">{{ old('meta_description') }}</textarea>
                                <p class="text-xs text-admin-text-muted mt-1">{{ strlen(old('meta_description', '')) }}/300 characters</p>
                            </div>
                        </div>
                    </div>

                    {{-- OpenGraph --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                                <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                            </span>
                            OpenGraph / Social
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label for="og_title" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">OG Title</label>
                                <input type="text" name="og_title" id="og_title" value="{{ old('og_title') }}" maxlength="70" class="admin-form-input w-full" placeholder="Social sharing title">
                            </div>

                            <div>
                                <label for="og_image" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">OG Image URL</label>
                                <input type="text" name="og_image" id="og_image" value="{{ old('og_image') }}" class="admin-form-input w-full" placeholder="https://example.com/image.jpg">
                            </div>

                            <div>
                                <label for="og_description" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">OG Description</label>
                                <textarea name="og_description" id="og_description" rows="3" maxlength="300" class="admin-form-input w-full resize-none" placeholder="Description for social media sharing">{{ old('og_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Twitter & Advanced --}}
                <div class="space-y-6">
                    {{-- Twitter Cards --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                                <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                            </span>
                            Twitter Cards
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label for="twitter_title" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Twitter Title</label>
                                <input type="text" name="twitter_title" id="twitter_title" value="{{ old('twitter_title') }}" maxlength="70" class="admin-form-input w-full" placeholder="Twitter sharing title">
                            </div>

                            <div>
                                <label for="twitter_image" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Twitter Image URL</label>
                                <input type="text" name="twitter_image" id="twitter_image" value="{{ old('twitter_image') }}" class="admin-form-input w-full" placeholder="https://example.com/image.jpg">
                            </div>

                            <div>
                                <label for="twitter_description" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Twitter Description</label>
                                <textarea name="twitter_description" id="twitter_description" rows="3" maxlength="300" class="admin-form-input w-full resize-none" placeholder="Description for Twitter cards">{{ old('twitter_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Advanced Settings --}}
                    <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                        <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                                <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            Advanced Settings
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label for="canonical_url" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Canonical URL</label>
                                <input type="text" name="canonical_url" id="canonical_url" value="{{ old('canonical_url') }}" class="admin-form-input w-full" placeholder="https://example.com/page">
                            </div>

                            <div>
                                <label for="robots" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Robots</label>
                                <input type="text" name="robots" id="robots" value="{{ old('robots') }}" class="admin-form-input w-full" placeholder="index, follow">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="changefreq" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Change Frequency</label>
                                    <select name="changefreq" id="changefreq" class="admin-form-input w-full">
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
                                    <label for="priority" class="block text-xs font-semibold text-admin-text-muted uppercase tracking-wider mb-2">Priority</label>
                                    <input type="number" name="priority" id="priority" step="0.1" min="0" max="1" value="{{ old('priority', '0.5') }}" class="admin-form-input w-full" placeholder="0.5">
                                </div>
                            </div>

                            <label class="flex items-center gap-3 p-4 bg-admin-surface-alt/50 rounded-xl border border-admin-border-subtle cursor-pointer hover:border-admin-accent/30 transition-colors">
                                <input type="checkbox" name="no_index" id="no_index" value="1" {{ old('no_index') ? 'checked' : '' }} class="h-5 w-5 bg-admin-input-bg border-admin-border rounded focus:ring-2 focus:ring-admin-accent cursor-pointer">
                                <div>
                                    <span class="text-sm font-medium text-admin-text block">No Index</span>
                                    <span class="text-xs text-admin-text-muted">Prevent search engine indexing</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="admin-action-btn admin-action-btn-primary w-full justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Configuration
                        </button>
                        <a href="{{ route('admin.seo.static-routes') }}" class="admin-action-btn admin-action-btn-secondary w-full justify-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
