@php
    $columns = $columns ?? [];
    $columnWidths = $columnWidths ?? [];
    $blockRenderer = $blockRenderer ?? null;
    $totalWidth = array_sum($columnWidths);
@endphp

<div class="cb-columns">
    <div class="cb-columns__grid">
        @foreach($columns as $index => $columnBlocks)
            @php
                $width = $columnWidths[$index] ?? floor(12 / count($columns));
            @endphp
            <div class="cb-columns__column cb-columns__column--{{ $width }}">
                @if($blockRenderer)
                    @foreach($columnBlocks as $block)
                        {!! $blockRenderer->render($block['type'], $block['attributes']) !!}
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</div>
