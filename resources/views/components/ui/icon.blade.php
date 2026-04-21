@props([
    'name' => null,
    'size' => 'md',
])

@php
$sizeClasses = match($size) {
    'xs' => 'h-4 w-4',
    'sm' => 'h-5 w-5',
    'md' => 'h-6 w-6',
    'lg' => 'h-8 w-8',
    'xl' => 'h-10 w-10',
    default => 'h-6 w-6',
};
@endphp

<svg class="{{ $sizeClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    @php
    $icons = [
        'magnifying-glass' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
        'plus' => 'M12 4v16m8-8H4',
        'pencil' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        'trash' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
        'x-mark' => 'M6 18L18 6M6 6l12 12',
        'check' => 'M5 13l4 4L19 7',
        'photo' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
        'cloud-arrow-up' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12',
    ];
    $path = $icons[$name] ?? '';
    @endphp
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}" />
</svg>
