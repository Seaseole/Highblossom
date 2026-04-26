@php
    $items = $items ?? [];
    $multipleOpen = $multiple_open ?? false;
    $accordionId = 'cb-accordion-' . uniqid();
@endphp

<div class="cb-accordion" data-cb-accordion-id="{{ $accordionId }}" data-cb-accordion-multiple="{{ $multipleOpen ? 'true' : 'false' }}">
    @foreach($items as $index => $item)
        @php
            $itemId = $accordionId . '-' . $index;
        @endphp
        <details class="cb-accordion__item" data-cb-accordion-item="{{ $itemId }}" @if(!$multipleOpen) data-cb-accordion-exclusive @endif>
            <summary class="cb-accordion__header">
                <span class="cb-accordion__title">{{ $item['title'] ?? '' }}</span>
                <span class="cb-accordion__icon" aria-hidden="true">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </summary>
            <div class="cb-accordion__content">
                {!! $item['content'] ?? '' !!}
            </div>
        </details>
    @endforeach
</div>

@push('scripts')
<script>
    (function() {
        if (typeof window.cbAccordionInit === 'function') return;

        window.cbAccordionInit = function() {
            document.querySelectorAll('[data-cb-accordion-id]').forEach(function(accordion) {
                var accordionId = accordion.getAttribute('data-cb-accordion-id');
                var multipleOpen = accordion.getAttribute('data-cb-accordion-multiple') === 'true';

                accordion.querySelectorAll('[data-cb-accordion-item]').forEach(function(item) {
                    var summary = item.querySelector('summary');

                    summary.addEventListener('click', function(e) {
                        if (!multipleOpen) {
                            accordion.querySelectorAll('[data-cb-accordion-item]').forEach(function(otherItem) {
                                if (otherItem !== item) {
                                    otherItem.removeAttribute('open');
                                }
                            });
                        }
                    });
                });
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', window.cbAccordionInit);
        } else {
            window.cbAccordionInit();
        }
    })();
</script>
@endpush
