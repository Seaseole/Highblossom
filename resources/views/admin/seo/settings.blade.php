<x-layouts::admin title="SEO Settings">
    @php
        $siteHost = parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.name');
        $storedImage = (string) $seo['meta_image'];
        $metaImageUrl = $storedImage !== '' && ! \Illuminate\Support\Str::startsWith($storedImage, ['http://', 'https://'])
            ? asset('storage/'.$storedImage)
            : ($storedImage ?: null);
    @endphp

    <div class="mx-auto max-w-6xl space-y-8 py-6 sm:py-10">
        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="magnifying-glass" size="sm" class="text-gray-500 dark:text-gray-400" />
                    <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">SEO Settings</h1>
                </div>
                <p class="text-gray-500 dark:text-gray-400">Configure site-wide SEO defaults to improve your website's search engine visibility.</p>
            </div>
            <button
                type="submit"
                form="seo-settings-form"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700"
            >
                <x-ui.icon name="check" size="sm" />
                Save Changes
            </button>
        </div>

        @include('admin.seo._tabs', ['seoTab' => 'settings'])

        <div
            x-data="{
                metaTitle: '',
                metaDescription: '',
                metaKeywords: '',
                previewImage: '',
                previewImageName: '',
                storedImage: '',
                imageRemoved: false,
                init() {
                    const d = this.$el.dataset;
                    this.metaTitle = d.metaTitle || '';
                    this.metaDescription = d.metaDescription || '';
                    this.metaKeywords = d.metaKeywords || '';
                    this.storedImage = d.storedImage || '';
                },
                get titleLength() { return this.metaTitle.length },
                get descriptionLength() { return this.metaDescription.length },
                get keywordCount() { return this.metaKeywords.split(',').map(s => s.trim()).filter(Boolean).length },
                get displayImage() { return this.previewImage || (this.imageRemoved ? '' : this.storedImage) },
                get displayImageName() { return this.previewImageName || (this.imageRemoved ? '' : (this.storedImage ? this.storedImage.split('/').pop() : '')) },
                get serpTitle() { return this.metaTitle || '{{ config('app.name') }}' },
                get serpDescription() { return this.metaDescription || 'Add a meta description to control how your page appears in search results.' },
                pickImage($event) {
                    const file = $event.target.files[0];
                    if (file) {
                        this.previewImage = URL.createObjectURL(file);
                        this.previewImageName = file.name;
                        this.imageRemoved = false;
                    }
                },
                removeImage() {
                    this.previewImage = '';
                    this.previewImageName = '';
                    this.imageRemoved = true;
                    if (this.$refs.imageInput) this.$refs.imageInput.value = '';
                },
            }"
            data-meta-title="{{ old('meta_title', $seo['meta_title']) }}"
            data-meta-description="{{ old('meta_description', $seo['meta_description']) }}"
            data-meta-keywords="{{ old('meta_keywords', $seo['meta_keywords']) }}"
            data-stored-image="{{ $metaImageUrl }}"
            class="grid gap-8 lg:grid-cols-3"
        >
            {{-- Form --}}
            <form id="seo-settings-form" method="POST" action="{{ route('admin.seo.settings.update') }}" enctype="multipart/form-data" class="space-y-6 lg:col-span-2">
                @csrf
                @method('PUT')

                {{-- Meta Title --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label for="meta_title" class="text-sm font-semibold text-gray-900 dark:text-white">Meta Title</label>
                        <span
                            class="text-sm font-medium"
                            :class="titleLength > 60 ? 'text-orange-500' : 'text-emerald-600 dark:text-emerald-400'"
                            x-text="titleLength + '/60'"
                        ></span>
                    </div>
                    <input
                        type="text"
                        id="meta_title"
                        name="meta_title"
                        x-model="metaTitle"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition-colors focus:border-gray-900 focus:outline-none dark:border-white/10 dark:bg-[#0A0A0F] dark:text-white dark:focus:border-white"
                        placeholder="Page title shown in search results"
                    />
                    @error('meta_title')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Appears as the clickable headline in search results. Used on pages without their own SEO title.</p>
                </div>

                {{-- Meta Description --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label for="meta_description" class="text-sm font-semibold text-gray-900 dark:text-white">Meta Description</label>
                        <span
                            class="flex items-center gap-1 text-sm font-medium"
                            :class="descriptionLength > 160 ? 'text-orange-500' : 'text-emerald-600 dark:text-emerald-400'"
                        >
                            <template x-if="descriptionLength > 160">
                                <x-ui.icon name="exclamation-triangle" size="xs" />
                            </template>
                            <span x-text="descriptionLength + '/160'"></span>
                        </span>
                    </div>
                    <textarea
                        id="meta_description"
                        name="meta_description"
                        rows="3"
                        x-model="metaDescription"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition-colors focus:border-gray-900 focus:outline-none dark:border-white/10 dark:bg-[#0A0A0F] dark:text-white dark:focus:border-white"
                        placeholder="Short summary of your site shown under the title in search results"
                    ></textarea>
                    @error('meta_description')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Appears below the title in search results. Optimal length: 120-160 characters.</p>
                </div>

                {{-- Meta Keywords --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label for="meta_keywords" class="text-sm font-semibold text-gray-900 dark:text-white">Meta Keywords</label>
                        <span class="rounded-full border border-gray-200 px-3 py-0.5 text-xs font-medium text-gray-600 dark:border-white/10 dark:text-gray-300" x-text="keywordCount + ' keywords'"></span>
                    </div>
                    <input
                        type="text"
                        id="meta_keywords"
                        name="meta_keywords"
                        x-model="metaKeywords"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition-colors focus:border-gray-900 focus:outline-none dark:border-white/10 dark:bg-[#0A0A0F] dark:text-white dark:focus:border-white"
                        placeholder="keyword one, keyword two, keyword three"
                    />
                    @error('meta_keywords')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Comma-separated keywords relevant to your content.</p>
                </div>

                {{-- Meta Image --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Meta Image</label>
                    <div class="flex items-center gap-3">
                        <label class="flex min-w-0 flex-1 cursor-pointer items-center rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm transition-colors hover:bg-gray-50 dark:border-white/10 dark:bg-[#0A0A0F] dark:hover:bg-white/5">
                            <input type="file" name="meta_image" accept="image/jpeg,image/png,image/webp" class="sr-only" x-ref="imageInput" @change="pickImage($event)" />
                            <span class="truncate text-gray-500 dark:text-gray-400" x-text="displayImageName || 'No image selected'"></span>
                        </label>
                        <button
                            type="button"
                            @click="removeImage()"
                            x-show="storedImage || previewImage"
                            x-cloak
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-3 py-2.5 text-gray-500 shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900 dark:border-white/10 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white"
                            aria-label="Remove meta image"
                        >
                            <x-ui.icon name="x-mark" size="sm" />
                        </button>
                    </div>
                    <input type="hidden" name="remove_meta_image" :value="imageRemoved ? '1' : '0'" />
                    @error('meta_image')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <div class="mt-3" x-show="displayImage" x-cloak>
                        <img :src="displayImage" alt="Meta image preview" class="h-24 w-auto rounded-xl border border-gray-200 object-cover dark:border-white/10" />
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Image displayed when sharing on social media. Recommended: 1200x630px.</p>
                </div>

                {{-- Google Site Verification --}}
                <div>
                    <label for="google_site_verification" class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Google Search Console Verification</label>
                    <input
                        type="text"
                        id="google_site_verification"
                        name="google_site_verification"
                        value="{{ old('google_site_verification', $seo['google_site_verification']) }}"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 font-mono text-sm text-gray-900 shadow-sm transition-colors focus:border-gray-900 focus:outline-none dark:border-white/10 dark:bg-[#0A0A0F] dark:text-white dark:focus:border-white"
                        placeholder="Google verification token"
                    />
                    @error('google_site_verification')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Paste the token from Google Search Console's HTML tag verification method. Leave empty if verifying via DNS.</p>
                </div>
            </form>

            {{-- Preview column --}}
            <div class="space-y-6">
                {{-- Google SERP preview --}}
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div class="mb-4 flex items-center gap-2">
                        <x-ui.icon name="globe-alt" size="sm" class="text-blue-600 dark:text-blue-400" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">SEO Preview</h2>
                    </div>
                    <div class="rounded-2xl border border-gray-100 p-4 dark:border-white/5">
                        <p class="text-sm text-emerald-700 dark:text-emerald-400">{{ $siteHost }}</p>
                        <p class="mt-1 truncate text-lg font-medium text-blue-700 dark:text-blue-400" x-text="serpTitle"></p>
                        <p class="mt-1 line-clamp-2 text-sm text-gray-600 dark:text-gray-300" x-text="serpDescription"></p>
                    </div>
                </div>

                {{-- Social preview --}}
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-4 text-lg font-semibold text-gray-500 dark:text-gray-400">Social Media Preview</h2>
                    <div class="overflow-hidden rounded-2xl border border-gray-100 dark:border-white/5">
                        <div x-show="displayImage" x-cloak>
                            <img :src="displayImage" alt="" class="aspect-[1200/630] w-full object-cover" />
                        </div>
                        <div x-show="! displayImage" class="flex aspect-[1200/630] w-full items-center justify-center bg-gray-50 dark:bg-white/5">
                            <x-ui.icon name="photo" size="lg" class="text-gray-300 dark:text-gray-600" />
                        </div>
                        <div class="p-4">
                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white" x-text="serpTitle"></p>
                            <p class="mt-1 line-clamp-2 text-sm text-gray-600 dark:text-gray-300" x-text="serpDescription"></p>
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="rounded-3xl border border-blue-100 bg-blue-50/50 p-6 dark:border-blue-900/40 dark:bg-blue-950/20">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">SEO Tips</h2>
                    <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-300">
                        <li>&bull; Title: 50-60 characters optimal</li>
                        <li>&bull; Description: 150-160 characters</li>
                        <li>&bull; Include target keywords early</li>
                        <li>&bull; Image: 1200x630px works best</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>
