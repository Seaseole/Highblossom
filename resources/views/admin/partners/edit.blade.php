<x-layouts::admin title="Edit Partner">
    <div class="mx-auto max-w-xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit Partner</h1>
                <p class="text-gray-500 dark:text-gray-400">Update partner logo and information.</p>
            </div>
            <a
                href="{{ route('admin.partners.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to List
            </a>
        </div>

        <form
            action="{{ route('admin.partners.update', $partner) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
            x-data="{ imagePreview: '{{ $partner->logo_url }}', handleFileSelect(event) { const file = event.target.files[0]; if (file) this.imagePreview = URL.createObjectURL(file); } }"
        >
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Partner Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $partner->name) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        required
                    />
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Partner Logo</label>
                    <div
                        class="relative flex min-h-[160px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                        @click="$refs.logoInput.click()"
                    >
                        <img :src="imagePreview" class="max-h-[140px] object-contain" />
                        <input
                            type="file"
                            name="logo"
                            x-ref="logoInput"
                            class="hidden"
                            accept="image/*"
                            @change="handleFileSelect"
                        />
                    </div>
                    <p class="text-center text-xs text-gray-500 dark:text-gray-400">
                        Click the area to replace the logo.
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Website URL</label>
                    <input
                        type="url"
                        name="website_url"
                        value="{{ old('website_url', $partner->website_url) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        placeholder="https://"
                    />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6 dark:border-white/5">
                <a
                    href="{{ route('admin.partners.index') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >Cancel</a>
                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Update Partner
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>
