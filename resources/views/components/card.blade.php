@props([
    'header' => 'header',
    'icon' => 'information-circle',
])

<div class="rounded border-t-4 border-t-blue-500 bg-white shadow dark:bg-zinc-900">
    <div class="flex gap-1 border-b border-b-gray-200 p-4 dark:border-b-zinc-700">
        <flux:icon class="text-gray-700 dark:text-zinc-300" name="{{ $icon }}" />
        <h3 class="font-semibold text-gray-900 dark:text-zinc-100">{{ $header }}</h3>
    </div>
    <div {{ $attributes->merge(['class' => 'p-8']) }}>
        {{ $slot }}
    </div>
</div>
