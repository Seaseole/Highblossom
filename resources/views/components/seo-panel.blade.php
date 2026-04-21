@props([
    'contentModel' => null,
])

@php
$seoMetadata = $contentModel ? $contentModel->getSeoMetadata() : null;
@endphp

<div x-data="{
    formData: {
        meta_title: @js($seoMetadata?->metaTitle ?? ''),
        meta_description: @js($seoMetadata?->metaDescription ?? ''),
        og_image: @js($seoMetadata?->ogImage ?? ''),
        canonical_url: @js($seoMetadata?->canonicalUrl ?? ''),
        no_index: @js($seoMetadata?->noIndex ?? false),
        priority: @js($seoMetadata?->priority ?? 0.5),
        changefreq: @js($seoMetadata?->changefreq ?? 'weekly'),
    },
    saving: false
}" class="space-y-6">
    <!-- Meta Title -->
    <div class="space-y-2">
        <label class="block text-sm font-medium text-[#FAFAFA]">
            Meta Title
        </label>
        <input 
            type="text" 
            x-model="formData.meta_title"
            wire:model.live="formData.meta_title"
            maxlength="60"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
            placeholder="Enter meta title"
        >
        <p class="text-xs text-[#71717A]">
            <span x-text="formData.meta_title.length"></span>/60 characters
        </p>
    </div>

    <!-- Meta Description -->
    <div class="space-y-2">
        <label class="block text-sm font-medium text-[#FAFAFA]">
            Meta Description
        </label>
        <textarea 
            x-model="formData.meta_description"
            wire:model.live="formData.meta_description"
            maxlength="160"
            rows="3"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200 resize-none"
            placeholder="Enter meta description"
        ></textarea>
        <p class="text-xs text-[#71717A]">
            <span x-text="formData.meta_description.length"></span>/160 characters
        </p>
    </div>

    <!-- OG Image -->
    <div class="space-y-2">
        <label class="block text-sm font-medium text-[#FAFAFA]">
            Open Graph Image
        </label>
        <div class="space-y-3">
            @if(isset($seoMetadata?->ogImage) && $seoMetadata->ogImage)
                <div class="relative">
                    <img src="{{ $seoMetadata->ogImage }}" alt="OG Image Preview" class="w-full h-32 object-cover rounded-xl border border-white/10">
                </div>
            @endif
            <input 
                type="text" 
                x-model="formData.og_image"
                wire:model.live="formData.og_image"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
                placeholder="Enter OG image URL"
            >
        </div>
    </div>

    <!-- Canonical URL -->
    <div class="space-y-2">
        <label class="block text-sm font-medium text-[#FAFAFA]">
            Canonical URL
        </label>
        <input 
            type="text" 
            x-model="formData.canonical_url"
            wire:model.live="formData.canonical_url"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] placeholder-[#71717A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
            placeholder="https://example.com/page"
        >
    </div>

    <!-- Advanced Settings -->
    <div class="space-y-4 pt-4 border-t border-white/5">
        <h3 class="text-sm font-semibold text-[#FAFAFA] uppercase tracking-wider">Advanced Settings</h3>
        
        <!-- No Index -->
        <label class="flex items-center gap-3 cursor-pointer">
            <input 
                type="checkbox" 
                x-model="formData.no_index"
                wire:model.live="formData.no_index"
                class="w-5 h-5 rounded bg-white/5 border-white/20 text-[#DC2626] focus:ring-2 focus:ring-[#DC2626] focus:ring-offset-0 transition-all duration-200"
            >
            <div>
                <span class="text-sm text-[#FAFAFA]">No Index</span>
                <p class="text-xs text-[#71717A]">Prevent search engines from indexing this page</p>
            </div>
        </label>

        <!-- Priority -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-[#FAFAFA]">
                Sitemap Priority
            </label>
            <input 
                type="range" 
                x-model="formData.priority"
                wire:model.live="formData.priority"
                min="0"
                max="1"
                step="0.1"
                class="w-full"
            >
            <div class="flex justify-between text-xs text-[#71717A]">
                <span>0.0</span>
                <span x-text="formData.priority"></span>
                <span>1.0</span>
            </div>
        </div>

        <!-- Change Frequency -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-[#FAFAFA]">
                Change Frequency
            </label>
            <select 
                x-model="formData.changefreq"
                wire:model.live="formData.changefreq"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-[#FAFAFA] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200"
            >
                <option value="always">Always</option>
                <option value="hourly">Hourly</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
                <option value="never">Never</option>
            </select>
        </div>
    </div>

    <!-- SERP Preview -->
    <div class="pt-4 border-t border-white/5">
        <h3 class="text-sm font-semibold text-[#FAFAFA] uppercase tracking-wider mb-4">SERP Preview</h3>
        <div class="p-4 rounded-xl bg-white border border-gray-200">
            <div class="space-y-1">
                <p class="text-sm text-[#1a0dab] hover:underline cursor-pointer" x-text="formData.meta_title || $contentModel->title"></p>
                <p class="text-xs text-[#006621] truncate" x-text="formData.canonical_url || window.location.href"></p>
                <p class="text-sm text-[#545454] line-clamp-2" x-text="formData.meta_description || $contentModel->excerpt || 'No description available'"></p>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="pt-4 border-t border-white/5">
        <button 
            wire:click="$parent.updateSeo(formData)"
            :disabled="saving"
            class="w-full px-4 py-3 rounded-xl bg-[#DC2626] border border-[#DC2626] text-white hover:bg-[#B91C1C] transition-all duration-200 shadow-lg shadow-[#DC2626]/20 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span x-show="!saving">Save SEO Settings</span>
            <span x-show="saving">Saving...</span>
        </button>
    </div>
</div>
