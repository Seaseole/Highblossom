@if($src)
    <figure @if($class) class="{{ $class }}" @endif>
        <img src="{{ $src }}" alt="{{ $alt ?? '' }}" @if($width) width="{{ $width }}" @endif @if($height) height="{{ $height }}" @endif>
        @if($caption)
            <figcaption>{{ $caption }}</figcaption>
        @endif
    </figure>
@endif
