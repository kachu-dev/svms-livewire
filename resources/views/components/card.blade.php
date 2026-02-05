@props([
    'header' => 'header',
    'icon' => 'information-circle'
])

<div class="rounded shadow border-t-4 border-t-blue-500 bg-white dark:bg-zinc-900">
    <div class="flex p-4 border-b border-b-gray-200 dark:border-b-zinc-700 gap-1">
        <flux:icon name="{{ $icon }}" class="text-gray-700 dark:text-zinc-300"/>
        <h3 class="font-semibold text-gray-900 dark:text-zinc-100">{{ $header }}</h3>
    </div>
    <div {{ $attributes->merge(['class' => 'p-8'])  }}>
        {{ $slot }}
    </div>
</div>
