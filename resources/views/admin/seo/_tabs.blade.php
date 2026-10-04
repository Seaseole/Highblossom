@php
    $seoTab = $seoTab ?? 'settings';
@endphp

<div class="flex gap-1 border-b border-gray-200 dark:border-white/10">
    <a
        href="{{ route('admin.seo.settings') }}"
        class="px-4 py-2.5 text-sm font-medium transition-colors {{ $seoTab === 'settings' ? 'border-b-2 border-gray-900 text-gray-900 dark:border-white dark:text-white' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}"
    >
        General Settings
    </a>
    <a
        href="{{ route('admin.seo.static-routes') }}"
        class="px-4 py-2.5 text-sm font-medium transition-colors {{ $seoTab === 'static-routes' ? 'border-b-2 border-gray-900 text-gray-900 dark:border-white dark:text-white' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}"
    >
        Static Routes
    </a>
</div>
