@props([
    'header' => 'header',
    'icon' => 'information-circle',
])

<div
    class="h-full w-full rounded-lg border border-zinc-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">
    <div class="flex items-center gap-3 border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
        <div class="flex items-center justify-center rounded-lg bg-zinc-100 p-1.5 dark:bg-zinc-800">
            <flux:icon
                class="size-5 text-zinc-600 dark:text-zinc-400"
                name="{{ $icon }}"
                variant="solid"
            />
        </div>
        <h3 class="text-lg font-semibold tracking-wide text-zinc-800 dark:text-zinc-100">
            {{ $header }}
        </h3>
    </div>
    <div {{ $attributes->merge(['class' => 'p-6']) }}>
        {{ $slot }}
    </div>
</div>
