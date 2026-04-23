@if($content)
    <pre @if($class) class="{{ $class }}" @endif><code>{{ $content }}</code></pre>
@endif
