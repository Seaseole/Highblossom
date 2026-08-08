<x-layouts::admin title="Edit Service Type">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit Service Type</h1>
                <p class="text-gray-500 dark:text-gray-400">Modify the architectural service category details.</p>
            </div>
            <a
                href="{{ route('admin.service-types.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Service Types
            </a>
        </div>

        <form
            method="POST"
            action="{{ route('admin.service-types.update', $serviceType) }}"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
        >
            @csrf
            @method('PUT')

            <!-- Details Card -->
            <div class="space-y-8 lg:col-span-2">
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $serviceType->name) }}"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        />
                    </div>
                </div>
            </div>

            <!-- Config Card -->
            <div class="space-y-6">
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                        <input
                            type="number"
                            name="sort_order"
                            value="{{ old('sort_order', $serviceType->sort_order) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        />
                    </div>
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $serviceType->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                        />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Visible</span>
                    </label>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Update Service Type
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
