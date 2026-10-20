{{--
    Deprecated alias for the old auth layout.

    Every auth screen now renders through x-layouts::auth. This wrapper exists so
    a straggler reference cannot fall back to the superseded design; once nothing
    points here, delete the file. See docs/design-system.md.
--}}
@props([
    'title' => null,
    'companyName' => null,
    'brandingSubtitle' => null,
])

<x-layouts::auth
    :title="$title"
    :heading="$title"
    :brandLine="$brandingSubtitle"
    {{ $attributes }}
>{{ $slot }}</x-layouts::auth>
