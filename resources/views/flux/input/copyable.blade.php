@blaze(fold: true, memo: true)

@php
    $attributes = $attributes->merge([
        'variant' => 'subtle',
        'class' => '-me-1',
        'square' => true,
        'size' => null,
    ]);
@endphp

<flux:button
    :$attributes
    :size="$size === 'sm' || $size === 'xs' ? 'xs' : 'sm'"
    x-data="fluxInputCopyable"
    x-on:click="copy()"
    x-bind:data-copyable-copied="copied"
    aria-label="{{ __('Copy to clipboard') }}"
>
    <flux:icon.clipboard-document-check variant="mini" class="[[data-copyable-copied]>&]:block hidden" />
    <flux:icon.clipboard-document variant="mini" class="[[data-copyable-copied]>&]:hidden block" />
</flux:button>
