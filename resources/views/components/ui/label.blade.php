{{--
    Field label. The uppercase micro-label is the only label style in this
    language, so hierarchy comes from the control and heading scales, not from
    making labels compete with them.
--}}
@props([
    'for' => null,
    'required' => false,
])

<label
    @if ($for) for="{{ $for }}" @endif
    {{ $attributes->class('block text-ui-xs font-medium tracking-[0.08em] text-ui-muted uppercase') }}
>
    {{ $slot }}@if ($required)<span class="ml-1 text-ui-danger" aria-hidden="true">*</span>@endif
</label>
