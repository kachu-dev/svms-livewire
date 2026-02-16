@blaze

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
    aria-label="{{ __('Copy to clipboard') }}"
    x-bind:data-copyable-copied="copied"
    x-data="fluxInputCopyable"
    x-on:click="copy()"
>
    <flux:icon.clipboard-document-check class="hidden [[data-copyable-copied]>&]:block" variant="mini" />
    <flux:icon.clipboard-document class="block [[data-copyable-copied]>&]:hidden" variant="mini" />
</flux:button>
