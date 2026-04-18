@props(['height' => 'medium'])

@php
$heightClass = match($height) {
    'small' => 'py-4',
    'large' => 'py-16',
    'xl' => 'py-24',
    default => 'py-8',
};
@endphp

<div class="{{ $heightClass }}"></div>
