@php
/** @var \App\Domains\Seo\DataTransferObjects\SeoMetadata $seoMetadata */
@endphp

{{-- Basic Meta Tags --}}
<title>{{ $seoMetadata->metaTitle ?? config('seo.site_name') }}</title>
@if($seoMetadata->metaDescription)
<meta name="description" content="{{ $seoMetadata->metaDescription }}">
@endif
@if($seoMetadata->metaKeywords)
<meta name="keywords" content="{{ $seoMetadata->metaKeywords }}">
@endif

{{-- Robots --}}
@if($seoMetadata->noIndex)
<meta name="robots" content="noindex, nofollow">
@elseif($seoMetadata->robots)
<meta name="robots" content="{{ $seoMetadata->robots }}">
@else
<meta name="robots" content="index, follow">
@endif

{{-- Canonical URL --}}
@if($seoMetadata->canonicalUrl)
<link rel="canonical" href="{{ $seoMetadata->canonicalUrl }}">
@endif

{{-- OpenGraph --}}
<meta property="og:site_name" content="{{ config('seo.site_name') }}">
@if($seoMetadata->ogTitle)
<meta property="og:title" content="{{ $seoMetadata->ogTitle }}">
@endif
@if($seoMetadata->ogDescription)
<meta property="og:description" content="{{ $seoMetadata->ogDescription }}">
@endif
@if($seoMetadata->ogImage)
<meta property="og:image" content="{{ $seoMetadata->ogImage }}">
@endif
<meta property="og:type" content="{{ $seoMetadata->ogType ?? 'website' }}">
<meta property="og:url" content="{{ $seoMetadata->canonicalUrl ?? url()->current() }}">

{{-- Twitter Cards --}}
@if($seoMetadata->twitterCard)
<meta name="twitter:card" content="{{ $seoMetadata->twitterCard }}">
@endif
@if($seoMetadata->twitterTitle)
<meta name="twitter:title" content="{{ $seoMetadata->twitterTitle }}">
@endif
@if($seoMetadata->twitterDescription)
<meta name="twitter:description" content="{{ $seoMetadata->twitterDescription }}">
@endif
@if($seoMetadata->twitterImage)
<meta name="twitter:image" content="{{ $seoMetadata->twitterImage }}">
@endif

{{-- JSON-LD Structured Data --}}
@if($seoMetadata->schemaJsonLd)
<script type="application/ld+json">
{!! json_encode($seoMetadata->schemaJsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
