@props([
    'heading' => 'heading',
    'text' => 'text',
])

<div class="h-full w-full">
    <div class="flex min-h-10 items-end justify-between">
        <div>
            <flux:heading level="1" size="xl">{{ $heading }}</flux:heading>
        </div>

        <div class="flex items-center gap-2">
            @isset($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </div>

    <div
        {{ $attributes->merge(['class' => 'dark:bg-slate-900 bg-white rounded-xl mt-6 border-1 border-slate-300 dark:border-slate-700']) }}>
        {{ $slot }}
    </div>
</div>
