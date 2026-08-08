<x-layouts::admin title="Create Gallery Item">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Create Gallery Item</h1>
                <p class="text-gray-500 dark:text-gray-400">Add a new masterpiece to the architectural showcase.</p>
            </div>
            <a
                href="{{ route('admin.gallery.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Gallery
            </a>
        </div>

        <form
            method="POST"
            action="{{ route('admin.gallery.store') }}"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
            x-data="{
                imagePreview: null,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) this.imagePreview = URL.createObjectURL(file);
                },
            }"
        >
            @csrf

            <div class="space-y-8 lg:col-span-2">
                <!-- Details Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Project Title</label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            placeholder="e.g. Modern Minimalist Residence"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <select
                            name="gallery_category_id"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    {{ old('gallery_category_id') == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Project Description</label>
                        <textarea
                            name="description"
                            rows="4"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            placeholder="Details about the installation..."
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-6 border-t border-gray-100 pt-6 md:grid-cols-2 dark:border-white/10">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                            <input
                                type="number"
                                name="latitude"
                                step="any"
                                value="{{ old('latitude') }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="-24.6532"
                            />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                            <input
                                type="number"
                                name="longitude"
                                step="any"
                                value="{{ old('longitude') }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="25.9087"
                            />
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Physical Address</label>
                            <input
                                type="text"
                                name="location_address"
                                value="{{ old('location_address') }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="123 Main St, Gaborone, Botswana"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Visuals Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project Image</label>
                    <div
                        class="relative flex min-h-[200px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                        @click="$refs.imageInput.click()"
                    >
                        <template x-if="! imagePreview">
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                <span class="text-xs font-semibold">Click to upload image</span>
                            </div>
                        </template>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="h-full w-full rounded-2xl object-cover" />
                        </template>
                        <input
                            type="file"
                            name="image"
                            x-ref="imageInput"
                            class="hidden"
                            accept="image/*"
                            @change="handleFileSelect"
                            required
                        />
                    </div>
                </div>

                <!-- Config Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                        <input
                            type="number"
                            name="sort_order"
                            value="0"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        />
                    </div>
                    <div class="flex items-center gap-6">
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                            />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                checked
                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                            />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Visible</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Publish Item
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
