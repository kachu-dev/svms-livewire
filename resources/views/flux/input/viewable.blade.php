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
    aria-label="{{ __('Toggle password visibility') }}"
    x-bind:data-viewable-open="open"
    x-data="fluxInputViewable"
    x-on:click="toggle()"
>
    <flux:icon.eye-slash class="hidden [[data-viewable-open]>&]:block" variant="micro" />
    <flux:icon.eye class="block [[data-viewable-open]>&]:hidden" variant="micro" />
</flux:button>
