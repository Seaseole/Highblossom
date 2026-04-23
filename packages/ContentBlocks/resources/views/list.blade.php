@if($type === 'ordered')
    <ol @if($class) class="{{ $class }}" @endif>
        @foreach($items as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ol>
@else
    <ul @if($class) class="{{ $class }}" @endif>
        @foreach($items as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
@endif
