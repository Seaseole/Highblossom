@php
    $tabs = $tabs ?? [];
    $blockRenderer = $blockRenderer ?? null;
    $uniqueId = 'cb-tabs-' . uniqid();
@endphp

<div class="cb-tabs" x-data="{ activeTab: 0 }">
    <div class="cb-tabs__nav" role="tablist">
        @foreach($tabs as $index => $tab)
            <button
                class="cb-tabs__nav-item"
                :class="{ 'cb-tabs__nav-item--active': activeTab === {{ $index }} }"
                @click="activeTab = {{ $index }}"
                role="tab"
                :aria-selected="activeTab === {{ $index }}"
                :aria-controls="'{{ $uniqueId }}-panel-{{ $index }}'"
            >
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    @foreach($tabs as $index => $tab)
        <div
            class="cb-tabs__panel"
            :class="{ 'cb-tabs__panel--active': activeTab === {{ $index }} }"
            x-show="activeTab === {{ $index }}"
            role="tabpanel"
            id="{{ $uniqueId }}-panel-{{ $index }}"
        >
            @if($blockRenderer)
                @foreach($tab['content'] as $block)
                    {!! $blockRenderer->render($block['type'], $block['attributes']) !!}
                @endforeach
            @endif
        </div>
    @endforeach
</div>
