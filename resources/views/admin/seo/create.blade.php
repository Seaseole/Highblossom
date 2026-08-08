<x-layouts::admin title="Create SEO Configuration">
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-6">
            <a
                href="{{ route('admin.seo.static-routes') }}"
                class="text-admin-text-muted hover:text-admin-accent group inline-flex items-center gap-2 text-sm transition-colors duration-200"
            >
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to SEO Routes
            </a>
        </div>

        {{-- Title Section --}}
        <div class="mb-8">
            <h1 class="font-headline text-admin-text text-3xl font-bold tracking-tight">
                Configure SEO: <span class="text-admin-accent">{{ $route_label }}</span>
            </h1>
            <p class="text-admin-text-muted mt-2 text-sm">Set up meta tags and search engine optimization</p>
        </div>

        <form method="POST" action="{{ route('admin.seo.store') }}">
            @csrf
            <input type="hidden" name="route_name" value="{{ $route_name }}" />

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Left Column - Basic & Social --}}
                <div class="space-y-6">
                    {{-- Basic Meta Tags --}}
                    <div class="admin-glass-card rounded-3xl p-6 shadow-black/20">
                        <h2 class="font-headline text-admin-text mb-5 flex items-center gap-3 text-lg font-semibold tracking-wide uppercase">
                            <span class="bg-admin-surface-alt flex h-8 w-8 items-center justify-center rounded-xl">
                                <svg class="text-admin-accent h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            Basic Meta Tags
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label
                                    for="meta_title"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >Meta Title</label>
                                <input
                                    type="text"
                                    name="meta_title"
                                    id="meta_title"
                                    value="{{ old('meta_title') }}"
                                    maxlength="70"
                                    class="admin-form-input w-full"
                                    placeholder="Page title"
                                />
                                <p class="text-admin-text-muted mt-1 text-xs">
                                    {{ strlen(old('meta_title', '')) }}/70 characters
                                </p>
                            </div>

                            <div>
                                <label
                                    for="meta_keywords"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >Meta Keywords</label>
                                <input
                                    type="text"
                                    name="meta_keywords"
                                    id="meta_keywords"
                                    value="{{ old('meta_keywords') }}"
                                    class="admin-form-input w-full"
                                    placeholder="keyword1, keyword2, keyword3"
                                />
                            </div>

                            <div>
                                <label
                                    for="meta_description"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >Meta Description</label>
                                <textarea
                                    name="meta_description"
                                    id="meta_description"
                                    rows="3"
                                    maxlength="300"
                                    class="admin-form-input w-full resize-none"
                                    placeholder="Brief description for search results"
                                >{{ old('meta_description') }}</textarea>
                                <p class="text-admin-text-muted mt-1 text-xs">
                                    {{ strlen(old('meta_description', '')) }}/300 characters
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- OpenGraph --}}
                    <div class="admin-glass-card rounded-3xl p-6 shadow-black/20">
                        <h2 class="font-headline text-admin-text mb-5 flex items-center gap-3 text-lg font-semibold tracking-wide uppercase">
                            <span class="bg-admin-surface-alt flex h-8 w-8 items-center justify-center rounded-xl">
                                <svg class="text-admin-accent h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                            </span>
                            OpenGraph / Social
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label
                                    for="og_title"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >OG Title</label>
                                <input
                                    type="text"
                                    name="og_title"
                                    id="og_title"
                                    value="{{ old('og_title') }}"
                                    maxlength="70"
                                    class="admin-form-input w-full"
                                    placeholder="Social sharing title"
                                />
                            </div>

                            <div>
                                <label
                                    for="og_image"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >OG Image URL</label>
                                <input
                                    type="text"
                                    name="og_image"
                                    id="og_image"
                                    value="{{ old('og_image') }}"
                                    class="admin-form-input w-full"
                                    placeholder="https://example.com/image.jpg"
                                />
                            </div>

                            <div>
                                <label
                                    for="og_description"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >OG Description</label>
                                <textarea
                                    name="og_description"
                                    id="og_description"
                                    rows="3"
                                    maxlength="300"
                                    class="admin-form-input w-full resize-none"
                                    placeholder="Description for social media sharing"
                                >{{ old('og_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Twitter & Advanced --}}
                <div class="space-y-6">
                    {{-- Twitter Cards --}}
                    <div class="admin-glass-card rounded-3xl p-6 shadow-black/20">
                        <h2 class="font-headline text-admin-text mb-5 flex items-center gap-3 text-lg font-semibold tracking-wide uppercase">
                            <span class="bg-admin-surface-alt flex h-8 w-8 items-center justify-center rounded-xl">
                                <svg class="text-admin-accent h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                            </span>
                            Twitter Cards
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label
                                    for="twitter_title"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >Twitter Title</label>
                                <input
                                    type="text"
                                    name="twitter_title"
                                    id="twitter_title"
                                    value="{{ old('twitter_title') }}"
                                    maxlength="70"
                                    class="admin-form-input w-full"
                                    placeholder="Twitter sharing title"
                                />
                            </div>

                            <div>
                                <label
                                    for="twitter_image"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >Twitter Image URL</label>
                                <input
                                    type="text"
                                    name="twitter_image"
                                    id="twitter_image"
                                    value="{{ old('twitter_image') }}"
                                    class="admin-form-input w-full"
                                    placeholder="https://example.com/image.jpg"
                                />
                            </div>

                            <div>
                                <label
                                    for="twitter_description"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >Twitter Description</label>
                                <textarea
                                    name="twitter_description"
                                    id="twitter_description"
                                    rows="3"
                                    maxlength="300"
                                    class="admin-form-input w-full resize-none"
                                    placeholder="Description for Twitter cards"
                                >{{ old('twitter_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Advanced Settings --}}
                    <div class="admin-glass-card rounded-3xl p-6 shadow-black/20">
                        <h2 class="font-headline text-admin-text mb-5 flex items-center gap-3 text-lg font-semibold tracking-wide uppercase">
                            <span class="bg-admin-surface-alt flex h-8 w-8 items-center justify-center rounded-xl">
                                <svg class="text-admin-accent h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            Advanced Settings
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label
                                    for="canonical_url"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >Canonical URL</label>
                                <input
                                    type="text"
                                    name="canonical_url"
                                    id="canonical_url"
                                    value="{{ old('canonical_url') }}"
                                    class="admin-form-input w-full"
                                    placeholder="https://example.com/page"
                                />
                            </div>

                            <div>
                                <label
                                    for="robots"
                                    class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                >Robots</label>
                                <input
                                    type="text"
                                    name="robots"
                                    id="robots"
                                    value="{{ old('robots') }}"
                                    class="admin-form-input w-full"
                                    placeholder="index, follow"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        for="changefreq"
                                        class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                    >Change Frequency</label>
                                    <select name="changefreq" id="changefreq" class="admin-form-input w-full">
                                        <option value="always" {{ old('changefreq') === 'always' ? 'selected' : '' }}>
                                            Always
                                        </option>
                                        <option value="hourly" {{ old('changefreq') === 'hourly' ? 'selected' : '' }}>
                                            Hourly
                                        </option>
                                        <option value="daily" {{ old('changefreq') === 'daily' ? 'selected' : '' }}>
                                            Daily
                                        </option>
                                        <option value="weekly" {{ old('changefreq') === 'weekly' ? 'selected' : '' }}>
                                            Weekly
                                        </option>
                                        <option
                                            value="monthly"
                                            {{ old('changefreq', 'monthly') === 'monthly' ? 'selected' : '' }}
                                        >
                                            Monthly
                                        </option>
                                        <option value="yearly" {{ old('changefreq') === 'yearly' ? 'selected' : '' }}>
                                            Yearly
                                        </option>
                                        <option value="never" {{ old('changefreq') === 'never' ? 'selected' : '' }}>
                                            Never
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        for="priority"
                                        class="text-admin-text-muted mb-2 block text-xs font-semibold tracking-wider uppercase"
                                    >Priority</label>
                                    <input
                                        type="number"
                                        name="priority"
                                        id="priority"
                                        step="0.1"
                                        min="0"
                                        max="1"
                                        value="{{ old('priority', '0.5') }}"
                                        class="admin-form-input w-full"
                                        placeholder="0.5"
                                    />
                                </div>
                            </div>

                            <label class="bg-admin-surface-alt/50 border-admin-border-subtle hover:border-admin-accent/30 flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-colors">
                                <input
                                    type="checkbox"
                                    name="no_index"
                                    id="no_index"
                                    value="1"
                                    {{ old('no_index') ? 'checked' : '' }}
                                    class="bg-admin-input-bg border-admin-border focus:ring-admin-accent h-5 w-5 cursor-pointer rounded focus:ring-2"
                                />
                                <div>
                                    <span class="text-admin-text block text-sm font-medium">No Index</span>
                                    <span class="text-admin-text-muted text-xs">Prevent search engine indexing</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="admin-action-btn admin-action-btn-primary w-full justify-center">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Configuration
                        </button>
                        <a
                            href="{{ route('admin.seo.static-routes') }}"
                            class="admin-action-btn admin-action-btn-secondary w-full justify-center"
                        >
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
