<div class="space-y-4 flex flex-col">
    @forelse ($this->filteredTypes as $category => $types)
        <flux:card
            class="border p-0!"
            id="category-{{ $category }}"
        >
            <div slot="header" class="rounded-t-lg flex items-center gap-3 px-4 py-3 bg-zinc-100 dark:bg-zinc-700">
                <span class="font-mono text-2xl font-bold uppercase tracking-widest">
                    {{ $category }}
                </span>

                <div class="flex-1" style="height:1px;background:repeating-linear-gradient(90deg, currentColor 0, currentColor 2px, transparent 2px, transparent 7px); opacity: 0.15;"></div>

                <span class="font-mono text-xl tabular-nums text-zinc-500 dark:text-zinc-400">
                    {{ count($types) }} items
                </span>
            </div>

            <ul>
                @foreach ($types as $type)
                    <li
                        wire:key="type-{{ $type->id }}"
                        class="group relative flex items-center border-b border-zinc-100 px-4 py-3 last:border-0 hover:bg-zinc-100 dark:border-zinc-800 dark:hover:bg-zinc-800/50"
                    >
                        <span class="absolute left-0 top-0 h-full w-0.5 scale-y-0 bg-blue-500 transition-transform duration-150 group-hover:scale-y-100"></span>

                        <span class="w-16 shrink-0 font-mono text-xl font-bold tabular-nums text-zinc-400 transition-colors group-hover:text-blue-500 dark:text-zinc-500">
                            {{ $type->code }}
                        </span>

                        <flux:separator class="mx-4 flex-1"/>

                        <span class="text-xl text-zinc-700 transition-colors group-hover:text-zinc-900 dark:text-zinc-300 dark:group-hover:text-zinc-100">
                            {{ $type->name }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </flux:card>

    @empty
        <div class="py-12 text-center">
            <flux:icon class="mx-auto mb-4 size-14 text-zinc-400" name="magnifying-glass" />
            <p class="text-xl text-zinc-500 dark:text-zinc-400">No policies found</p>
        </div>
    @endforelse
</div>
