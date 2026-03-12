@props([
    'heading' => null,
    'number' => null,
    'change' => null,
    'variant' => 'default',
])

@php
    $isPositive = $change && str_starts_with($change, '+');
    $isNegative = $change && str_starts_with($change, '-');
    $icon = $isPositive ? 'arrow-trending-up' : 'arrow-trending-down';
    $changeColor = $isPositive ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400';

    $headingColor = match ($variant) {
        'warning' => 'yellow',
        'success' => 'green',
        'neutral' => 'orange',
        'danger' => 'red',
        default => null,
    };
@endphp

<flux:card class="relative flex flex-col gap-2 overflow-hidden p-4">
    <flux:text class="text-xs font-semibold uppercase tracking-widest">
        {{ $heading }}
    </flux:text>

    <flux:text :color="$headingColor ?: false" class="text-4xl font-bold leading-none">
        {{ is_numeric($number) ? number_format($number) : $number }}
    </flux:text>

    <div class="flex items-center justify-between">
        @if ($change)
            <div class="{{ $changeColor }} flex items-center gap-1 text-sm font-medium">
                <flux:icon
                    :icon="$icon"
                    class="size-5"
                    variant="micro"
                />
                <span>{{ $change }} vs prior period</span>
            </div>
        @else
            <span class="text-xs text-zinc-300 dark:text-zinc-600">No comparison</span>
        @endif
    </div>

    <div class="absolute right-0 top-0 pr-2 pt-2">
        {{ $slot }}
    </div>

    {{--    <select class="text-xs">
        <option></option>
        <option>Today</option>
        <option>This Week</option>
        <option>This Month</option>
        <option>This Year</option>
        <option>All Time</option>
    </select> --}}
</flux:card>
