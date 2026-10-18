@php($custom = filled($faviconSet['v'] ?? null))
@php($iconVersion = $custom ? $faviconSet['v'] : config('icons.version', '1'))

@if (! $custom && ($favicon ?? false))
    <link rel="icon" href="{{ media_url($favicon) }}" sizes="any" />
@endif
<link rel="icon" href="{{ asset('favicon.ico?v='.$iconVersion) }}" sizes="any" />
@if (! $custom)
    <link rel="icon" href="{{ asset('favicon.svg?v='.$iconVersion) }}" type="image/svg+xml" />
@endif
<link rel="icon" href="{{ asset('favicon-48x48.png?v='.$iconVersion) }}" type="image/png" sizes="48x48" />
<link rel="icon" href="{{ asset('favicon-96x96.png?v='.$iconVersion) }}" type="image/png" sizes="96x96" />
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png?v='.$iconVersion) }}" />
<link rel="manifest" href="{{ route('manifest') }}" />
<meta name="theme-color" content="#b01828" />
