@if($content)
    <blockquote @if($class) class="{{ $class }}" @endif @if($cite) cite="{{ $cite }}" @endif>
        <p>{{ $content }}</p>
        @if($author)
            <footer>
                <cite>{{ $author }}</cite>
            </footer>
        @endif
    </blockquote>
@endif
