@props([
    'heading' => 'heading',
    'text' => 'text',
])
<!--table-wrapper-->
<div class="h-full w-full">
    <div class="flex min-h-10 items-end justify-between">
        <div>
            <flux:heading size="xl">{{ $heading }}</flux:heading>
        </div>

        <div>
            @isset($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </div>

    <div
        {{ $attributes->merge([
            'class' => 'rounded-lg mt-4 border border-zinc-200 dark:border-white/10 bg-zinc-50 dark:bg-white/5',
        ]) }}>
        {{ $slot }}
    </div>
</div>
