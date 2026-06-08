<x-layouts::admin title="Create Testimonial">
    <div class="max-w-2xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Create Testimonial</h1>
                <p class="text-gray-500 dark:text-gray-400">Add a new customer testimonial.</p>
            </div>
            <a href="{{ route('admin.testimonials.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Back to List
            </a>
        </div>

        <form method="POST" action="{{ route('admin.testimonials.store') }}" class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                    <input type="text" name="name" id="name" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Customer name">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role (Optional)</label>
                    <input type="text" name="role" id="role" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="e.g. CEO, Customer">
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content</label>
                    <textarea name="content" id="content" rows="5" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Customer testimonial"></textarea>
                </div>

                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                    <select name="rating" id="rating" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ $i === 5 ? 'selected' : '' }}>{{ $i }} Stars</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" checked class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Published</span>
                    </label>
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort Order (Optional)</label>
                    <input type="number" name="sort_order" id="sort_order" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="Lower numbers appear first">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-white/5">
                    <a href="{{ route('admin.testimonials.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                        Create Testimonial
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
