@props([
    'header' => 'header',
    'icon' => 'information-circle',
])

<flux:card class="p-0! h-full w-full rounded-2xl">
    <div class="flex items-center gap-3 rounded-t-2xl bg-zinc-100 px-6 py-5 dark:border-white/10 dark:bg-white/5">
        <div class="bg-accent flex items-center justify-center rounded-lg p-2 text-white">
            <flux:icon
                class="size-5"
                name="{{ $icon }}"
                variant="outline"
            />
        </div>
        <h3 class="text-2xl font-bold uppercase tracking-widest">
            {{ $header }}
        </h3>
    </div>
    <div {{ $attributes->merge(['class' => 'p-8']) }}>
        {{ $slot }}
    </div>
</flux:card>
