<x-layouts::admin title="Edit Testimonial">
    <div class="mx-auto max-w-2xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit Testimonial</h1>
                <p class="text-gray-500 dark:text-gray-400">Update testimonial details.</p>
            </div>
            <a
                href="{{ route('admin.testimonials.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to List
            </a>
        </div>

        <form
            method="POST"
            action="{{ route('admin.testimonials.update', $testimonial) }}"
            class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
        >
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Name</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $testimonial->name) }}"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        placeholder="Customer name"
                    />
                </div>

                <div>
                    <label for="role" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Role (Optional)</label>
                    <input
                        type="text"
                        name="role"
                        id="role"
                        value="{{ old('role', $testimonial->role) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        placeholder="e.g. CEO, Customer"
                    />
                </div>

                <div>
                    <label for="content" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Content</label>
                    <textarea
                        name="content"
                        id="content"
                        rows="5"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        placeholder="Customer testimonial"
                    >{{ old('content', $testimonial->content) }}</textarea>
                </div>

                <div>
                    <label for="rating" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Rating</label>
                    <select
                        name="rating"
                        id="rating"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                    >
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>
                                {{ $i }} Stars
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            type="checkbox"
                            name="is_featured"
                            value="1"
                            {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                        />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            type="checkbox"
                            name="is_published"
                            value="1"
                            {{ old('is_published', $testimonial->is_published) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                        />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Published</span>
                    </label>
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Sort Order (Optional)</label>
                    <input
                        type="number"
                        name="sort_order"
                        id="sort_order"
                        value="{{ old('sort_order', $testimonial->sort_order) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        placeholder="Lower numbers appear first"
                    />
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-white/5">
                    <a
                        href="{{ route('admin.testimonials.index') }}"
                        class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Update Testimonial
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
