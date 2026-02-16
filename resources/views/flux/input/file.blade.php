@blaze

@php
    extract(Flux::forwardedAttributes($attributes, ['name', 'multiple', 'size']));
@endphp

@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'multiple' => null,
    'size' => null,
])

@php
    $classes = Flux::classes()
        ->add('w-full flex items-center gap-4')
        ->add('[[data-flux-input-group]_&]:items-stretch [[data-flux-input-group]_&]:gap-0')

        // NOTE: We need to add relative positioning here to prevent odd overflow behaviors because of
        // "sr-only": https://github.com/tailwindlabs/tailwindcss/discussions/12429
        ->add('relative');

    [$styleAttributes, $attributes] = Flux::splitAttributes($attributes);
@endphp

<div
    {{ $styleAttributes->class($classes) }}
    data-flux-input-file
    tabindex="0"
    wire:ignore
    x-data="fluxInputFile({ files: '{{ __('files') }}', noFile: '{{ __('No file chosen') }}' })"
    x-on:change="updateLabel($event)"
    x-on:click.prevent.stop="$refs.input.click()"
    x-on:keydown.enter.prevent.stop="$refs.input.click()"
    x-on:keydown.space.prevent.stop
    x-on:keyup.space.prevent.stop="$refs.input.click()"
>
    <input
        {{ $attributes }}
        {{ $multiple ? 'multiple' : '' }}
        {{-- Without this, the parent element's click listener will ".prevent" the file input from being clicked... --}}
        @if ($name) name="{{ $name }}" @endif
        class="sr-only"
        tabindex="-1"
        type="file"
        x-on:click.stop
        x-ref="input"
    >

    <flux:button
        :$size
        aria-hidden="true"
        as="div"
        class="cursor-pointer"
    >
        <?php if ($multiple) : ?>
        {!! __('Choose files') !!}
        <?php else : ?>
        {!! __('Choose file') !!}
        <?php endif; ?>
    </flux:button>

    <div
        @class([
            'cursor-default select-none truncate whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400 font-medium',
            '[[data-flux-input-group]_&]:flex-1 [[data-flux-input-group]_&]:border-e [[data-flux-input-group]_&]:border-y [[data-flux-input-group]_&]:shadow-xs [[data-flux-input-group]_&]:border-zinc-200 dark:[[data-flux-input-group]_&]:border-zinc-600 [[data-flux-input-group]_&]:px-4 [[data-flux-input-group]_&]:bg-white dark:[[data-flux-input-group]_&]:bg-zinc-700 [[data-flux-input-group]_&]:flex [[data-flux-input-group]_&]:items-center dark:[[data-flux-input-group]_&]:text-zinc-300',
        ])
        aria-hidden="true"
        x-ref="name"
    >
        {!! __('No file chosen') !!}
    </div>
</div>
