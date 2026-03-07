@props([
    'heading' => 'heading',
    'text' => 'text',
])

<div class="h-full w-full">
    <div class="flex items-end justify-between">
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

    {{-- <div class="grid grid-cols-4 gap-3 mt-3">
        <div class="relative overflow-hidden rounded-lg border border-zinc-800 bg-zinc-900 p-4 transition-colors hover:border-zinc-700">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-0 transition-opacity hover:opacity-100"></div>
            <div class="mb-2 flex items-center gap-2 text-xs font-medium text-zinc-400">
                <span class="size-1.5 rounded-full bg-blue-500"></span>
                Total Records
            </div>
            <div class="text-3xl font-semibold tracking-tight text-white">142</div>
            <div class="mt-1 text-xs text-zinc-500">All time</div>
        </div>

        <div class="relative overflow-hidden rounded-lg border border-zinc-800 bg-zinc-900 p-4 transition-colors hover:border-zinc-700">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-red-500 to-transparent opacity-0 transition-opacity hover:opacity-100"></div>
            <div class="mb-2 flex items-center gap-2 text-xs font-medium text-zinc-400">
                <span class="size-1.5 rounded-full bg-red-500"></span>
                Critical
            </div>
            <div class="text-3xl font-semibold tracking-tight text-white">18</div>
            <div class="mt-1 text-xs text-zinc-500">Require attention</div>
        </div>

        <div class="relative overflow-hidden rounded-lg border border-zinc-800 bg-zinc-900 p-4 transition-colors hover:border-zinc-700">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-amber-500 to-transparent opacity-0 transition-opacity hover:opacity-100"></div>
            <div class="mb-2 flex items-center gap-2 text-xs font-medium text-zinc-400">
                <span class="size-1.5 rounded-full bg-amber-500"></span>
                Pending Review
            </div>
            <div class="text-3xl font-semibold tracking-tight text-white">34</div>
            <div class="mt-1 text-xs text-zinc-500">Awaiting action</div>
        </div>

        <div class="relative overflow-hidden rounded-lg border border-zinc-800 bg-zinc-900 p-4 transition-colors hover:border-zinc-700">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-green-500 to-transparent opacity-0 transition-opacity hover:opacity-100"></div>
            <div class="mb-2 flex items-center gap-2 text-xs font-medium text-zinc-400">
                <span class="size-1.5 rounded-full bg-green-500"></span>
                Resolved
            </div>
            <div class="text-3xl font-semibold tracking-tight text-white">90</div>
            <div class="mt-1 text-xs text-zinc-500">This semester</div>
        </div>
    </div> --}}

    <div
        {{ $attributes->merge(['class' => 'dark:bg-slate-900 bg-white rounded-md mt-6 border-1 border-slate-300 dark:border-slate-700']) }}>
        {{ $slot }}
    </div>
</div>
