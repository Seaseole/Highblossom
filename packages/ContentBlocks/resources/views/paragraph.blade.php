@if($content)
    <p @if($class) class="{{ $class }}" @endif>
        {!! $content !!}
    </p>
@endif
