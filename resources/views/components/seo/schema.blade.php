@php
    /** @var \App\Services\DataTransferObjects\SeoMetadata $seoMetadata */
@endphp

{{--
    Rendered in the body, not the head: wire:navigate treats a <script> in <head> as an
    asset and never removes it, so a page-specific schema would survive into every page
    visited afterwards. The body is replaced wholesale on each navigation.
--}}
@if ($seoMetadata->schemaJsonLd)
    <script type="application/ld+json">
        {!! json_encode($seoMetadata->schemaJsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}
    </script>
@endif
