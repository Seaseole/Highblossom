@if($title || $description || ($button_text && $button_url))
    <div @if($class) class="{{ $class }}" @endif>
        @if($title)
            <h3>{{ $title }}</h3>
        @endif
        @if($description)
            <p>{{ $description }}</p>
        @endif
        @if($button_text && $button_url)
            <a href="{{ $button_url }}">{{ $button_text }}</a>
        @endif
    </div>
@endif
