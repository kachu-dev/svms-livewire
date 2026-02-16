@php $iconTrailing ??= $attributes->pluck('icon:trailing'); @endphp
@php $iconLeading ??= $attributes->pluck('icon:leading'); @endphp
@php $iconVariant ??= $attributes->pluck('icon:variant'); @endphp
@php $maskDynamic ??= $attributes->pluck('mask:dynamic'); @endphp

@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'iconVariant' => 'mini',
    'variant' => 'outline',
    'iconTrailing' => null,
    'iconLeading' => null,
    'maskDynamic' => null,
    'expandable' => null,
    'clearable' => null,
    'copyable' => null,
    'viewable' => null,
    'invalid' => null,
    'loading' => null,
    'type' => 'text',
    'mask' => null,
    'size' => null,
    'icon' => null,
    'kbd' => null,
    'as' => null,
])

@php

    // There are a few loading scenarios that this covers:
    // If `:loading="false"` then never show loading.
    // If `:loading="true"` then always show loading.
    // If `:loading="foo"` then show loading when `foo` request is happening.
    // If `wire:model` then never show loading.
    // If `wire:model.live` then show loading when the `wire:model` value request is happening.
    $wireModel = $attributes->wire('model');
    $wireTarget = null;

    if ($loading !== false) {
        if ($loading === true) {
            $loading = true;
        } elseif ($wireModel?->directive) {
            $loading = $wireModel->hasModifier('live');
            $wireTarget = $loading ? $wireModel->value() : null;
        } else {
            $wireTarget = $loading;
            $loading = (bool) $loading;
        }
    }

    $invalid ??= $name && $errors->has($name);

    $iconLeading ??= $icon;

    $hasLeadingIcon = (bool) $iconLeading;
    $countOfTrailingIcons = collect([
        (bool) $iconTrailing,
        (bool) $kbd,
        (bool) $clearable,
        (bool) $copyable,
        (bool) $viewable,
        (bool) $expandable,
    ])
        ->filter()
        ->count();

    $iconClasses = Flux::classes()
        // When using the outline icon variant, we need to size it down to match the default icon sizes...
        ->add($iconVariant === 'outline' ? 'size-5' : '');

    $inputLoadingClasses = Flux::classes()
        // When loading, we need to add some extra padding to the input to account for the loading icon...
        ->add(
            match ($countOfTrailingIcons) {
                0 => 'pe-10',
                1 => 'pe-16',
                2 => 'pe-23',
                3 => 'pe-30',
                4 => 'pe-37',
                5 => 'pe-44',
                6 => 'pe-51',
            },
        );

    $classes = Flux::classes()
        ->add('w-full border rounded-lg block disabled:shadow-none dark:shadow-none')
        ->add('appearance-none') // Without this, input[type="date"] on mobile doesn't respect w-full...
    ->add(
        match ($size) {
            default
                => 'text-base sm:text-sm py-2 h-10 leading-[1.375rem]', // This makes the height of the input 40px (same as buttons and such...)
            'sm' => 'text-sm py-1.5 h-8 leading-[1.125rem]',
            'xs' => 'text-xs py-1.5 h-6 leading-[1.125rem]',
            'lg' => 'text-lg py-3 h-12 leading-[1.75rem]',
        },
    )
    ->add(
        match ($hasLeadingIcon) {
            true => 'ps-10',
            false => 'ps-3',
        },
    )
    ->add(
        match ($countOfTrailingIcons) {
            // Make sure there's enough padding on the right side of the input to account for all the icons...
                0 => 'pe-3',
                1 => 'pe-10',
                2 => 'pe-16',
                3 => 'pe-23',
                4 => 'pe-30',
                5 => 'pe-37',
                6 => 'pe-44',
            },
        )
        ->add(
            match (
                $variant // Background...
            ) {
                'outline' => 'bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]',
                'filled' => 'bg-zinc-800/5 dark:bg-white/10 dark:disabled:bg-white/[7%]',
            },
        )
        ->add(
            match (
                $variant // Text color
            ) {
                'outline'
                    => 'text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500',
                'filled'
                    => 'text-zinc-700 placeholder-zinc-500 disabled:placeholder-zinc-400 dark:text-zinc-200 dark:placeholder-white/60 dark:disabled:placeholder-white/40',
            },
        )
        ->add(
            match (
                $variant // Border...
            ) {
                'outline'
                    => 'shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5',
                'filled' => 'border-0',
            },
        )
        ->add(
            match (
                $variant // Invalid...
            ) {
                'outline'
                    => 'data-invalid:shadow-none data-invalid:border-red-500 dark:data-invalid:border-red-500 disabled:data-invalid:border-red-500 dark:disabled:data-invalid:border-red-500',
                'filled' => 'data-invalid:border-red-500',
            },
        )
        ->add($attributes->pluck('class:input'));
@endphp

<?php if ($type === 'file'): ?>
<flux:with-field :$attributes :$name>
    <flux:input.file
        :$attributes
        :$name
        :$size
    />
</flux:with-field>
<?php elseif ($as !== 'button'): ?>
<flux:with-field :$attributes :$name>
    <div {{ $attributes->only('class')->class('w-full relative block group/input') }} data-flux-input>
        <?php if (is_string($iconLeading)): ?>
        <div
            class="pointer-events-none absolute bottom-0 start-0 top-0 flex items-center justify-center border-s border-transparent ps-3 text-xs text-zinc-400/75 dark:text-white/60">
            <flux:icon
                :class="$iconClasses"
                :icon="$iconLeading"
                :variant="$iconVariant"
            />
        </div>
        <?php elseif ($iconLeading): ?>
        <div
            {{ $iconLeading->attributes->class('absolute top-0 bottom-0 border-s border-transparent flex items-center justify-center text-xs text-zinc-400/75 dark:text-white/60 ps-3 start-0') }}>
            {{ $iconLeading }}
        </div>
        <?php endif; ?>

        <input
            {{ $attributes->except('class')->class($type === 'file' ? '' : $classes) }}
            {{-- Leave file inputs unstyled... --}}
            @if (is_numeric($size)) size="{{ $size }}" @endif
            @isset($name) name="{{ $name }}" @endisset
            @if ($invalid) aria-invalid="true" data-invalid @endif
            @if ($loading && $wireTarget) wire:target="{{ $wireTarget }}" @endif
            @if ($loading) wire:loading.class="{{ $inputLoadingClasses }}" @endif
            @if ($maskDynamic) x-mask:dynamic="{{ $maskDynamic }}" @elseif ($mask) x-mask="{{ $mask }}" @endif
            data-flux-control
            data-flux-group-target
            type="{{ $type }}"
        >

        <?php if ($loading || $countOfTrailingIcons > 0): ?>
        <div
            class="absolute bottom-0 end-0 top-0 flex items-center gap-x-1.5 border-e border-transparent pe-2 text-xs text-zinc-400">
            {{-- Icon should be text-zinc-400/75 --}}
            <?php if ($loading): ?>
            <flux:icon
                :class="$iconClasses"
                :variant="$iconVariant"
                :wire:target="$wireTarget"
                name="loading"
                wire:loading
            />
            <?php endif; ?>

            <?php if ($clearable): ?>
            <flux:input.clearable :$size inset="left right" />
            <?php endif; ?>

            <?php if ($kbd): ?>
            <span class="pointer-events-none">{{ $kbd }}</span>
            <?php endif; ?>

            <?php if ($expandable): ?>
            <flux:input.expandable :$size inset="left right" />
            <?php endif; ?>

            <?php if ($copyable): ?>
            <flux:input.copyable :$size inset="left right" />
            <?php endif; ?>

            <?php if ($viewable): ?>
            <flux:input.viewable :$size inset="left right" />
            <?php endif; ?>

            <?php if (is_string($iconTrailing)): ?>
            <?php
            $trailingIconClasses = clone $iconClasses;
            $trailingIconClasses->add('text-zinc-400/75 dark:text-white/60 pointer-events-none');
            ?>
            <flux:icon
                :class="$trailingIconClasses"
                :icon="$iconTrailing"
                :variant="$iconVariant"
            />
            <?php elseif ($iconTrailing): ?>
            {{ $iconTrailing }}
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</flux:with-field>
<?php else: ?>
<button {{ $attributes->merge(['type' => 'button'])->class([$classes, 'w-full relative flex']) }}>
    <?php if (is_string($iconLeading)): ?>
    <div class="absolute bottom-0 start-0 top-0 flex items-center justify-center ps-3 text-xs text-zinc-400/75">
        <flux:icon
            :class="$iconClasses"
            :icon="$iconLeading"
            :variant="$iconVariant"
        />
    </div>
    <?php elseif ($iconLeading): ?>
    <div
        {{ $iconLeading->attributes->class('absolute top-0 bottom-0 flex items-center justify-center text-xs text-zinc-400/75 ps-3 start-0') }}>
        {{ $iconLeading }}
    </div>
    <?php endif; ?>

    <?php if ($attributes->has('placeholder')): ?>
    <div class="block flex-1 self-center text-start font-medium text-zinc-400 dark:text-white/40">
        {{ $attributes->get('placeholder') }}
    </div>
    <?php else: ?>
    <div class="flex-1 self-center text-start font-medium text-zinc-800 dark:text-white">
        {{ $slot }}
    </div>
    <?php endif; ?>

    <?php if ($kbd): ?>
    <div class="absolute bottom-0 end-0 top-0 flex items-center justify-center pe-4 text-xs text-zinc-400/75">
        {{ $kbd }}
    </div>
    <?php endif; ?>

    <?php if (is_string($iconTrailing)): ?>
    <div class="absolute bottom-0 end-0 top-0 flex items-center justify-center pe-3 text-xs text-zinc-400/75">
        <flux:icon
            :class="$iconClasses"
            :icon="$iconTrailing"
            :variant="$iconVariant"
        />
    </div>
    <?php elseif  ($iconTrailing): ?>
    <div
        {{ $iconTrailing->attributes->class('absolute top-0 bottom-0 flex items-center justify-center text-xs text-zinc-400/75 pe-2 end-0') }}>
        {{ $iconTrailing }}
    </div>
    <?php endif; ?>
</button>
<?php endif; ?>
