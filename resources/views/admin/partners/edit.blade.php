<x-layouts::admin title="Edit Partner">
    <div class="max-w-xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Edit Partner</h1>
                <p class="text-gray-500 dark:text-gray-400">Update partner logo and information.</p>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Back to List
            </a>
        </div>

        <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data" 
              class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6"
              x-data="{ imagePreview: '{{ $partner->logo_url }}', handleFileSelect(event) { const file = event.target.files[0]; if (file) this.imagePreview = URL.createObjectURL(file); } }">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Partner Name</label>
                    <input type="text" name="name" value="{{ old('name', $partner->name) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" required>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Partner Logo</label>
                    <div class="relative w-full bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center min-h-[160px] cursor-pointer hover:border-gray-900 dark:hover:border-white transition-all" @click="$refs.logoInput.click()">
                        <img :src="imagePreview" class="max-h-[140px] object-contain">
                        <input type="file" name="logo" x-ref="logoInput" class="hidden" accept="image/*" @change="handleFileSelect">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Click the area to replace the logo.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website URL</label>
                    <input type="url" name="website_url" value="{{ old('website_url', $partner->website_url) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="https://">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-white/5 flex items-center justify-end gap-3">
                <a href="{{ route('admin.partners.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">Update Partner</button>
            </div>
        </form>
    </div>
</x-layouts::admin>