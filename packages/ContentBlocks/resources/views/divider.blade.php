@php
    $style = $style ?? 'line';
    $size = $size ?? 'md';
@endphp

@if($style === 'line')
    <hr class="cb-divider cb-divider__line" role="separator" aria-orientation="horizontal">
@elseif($style === 'dots')
    <div class="cb-divider cb-divider__dots" role="separator" aria-orientation="horizontal"></div>
@elseif($style === 'space')
    <div class="cb-divider cb-divider__space cb-divider__space--{{ $size }}"></div>
@endif
