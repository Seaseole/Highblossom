<x-layouts::admin title="Edit Service">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a
                    href="{{ route('admin.services.index') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    ← Back to Services
                </a>
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit Service</h1>
                <p class="text-gray-500 dark:text-gray-400">Modify the architectural service details.</p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('admin.services.update', $service) }}"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
        >
            @csrf
            @method('PUT')
            <input type="hidden" name="image_path" value="{{ $service->image ?? '' }}" />
            <input type="hidden" name="remove_image" value="0" />

            <div class="space-y-8 lg:col-span-2">
                <!-- Details Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $service->title) }}"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Summary</label>
                        <textarea
                            name="short_description"
                            rows="2"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        >{{ old('short_description', $service->short_description) }}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Detailed Description</label>
                        <textarea
                            name="full_description"
                            rows="6"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        >{{ old('full_description', $service->full_description) }}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Technical Features</label>
                        <textarea
                            name="features"
                            rows="4"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 font-mono text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        >{{ old('features', is_array($service->features) ? implode("\n", $service->features) : '') }}</textarea>
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
                        @if ($service->image)
                            <img src="{{ $service->image }}" class="h-full w-full rounded-2xl object-cover" />
                        @else
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                <span class="text-xs font-semibold">Click to upload image</span>
                            </div>
                        @endif
                        <input type="file" name="image" x-ref="imageInput" class="hidden" accept="image/*" />
                    </div>
                </div>

                <!-- Config Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                        <input
                            type="number"
                            name="sort_order"
                            value="{{ old('sort_order', $service->sort_order) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        />
                    </div>
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                        />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Visible</span>
                    </label>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Update Service
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
