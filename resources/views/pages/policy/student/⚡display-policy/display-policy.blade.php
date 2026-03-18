<div class="flex flex-col space-y-6">
    <flux:input
        icon="magnifying-glass"
        placeholder="Search policies..."
        wire:model.live.debounce.300ms="search"
    />
    <div class="flex flex-col space-y-4">

        @forelse ($this->filteredTypes as $category => $types)

            <flux:card class="p-0! border">

                <div class="flex items-center gap-3 rounded-t-lg bg-zinc-100 px-4 py-3 dark:bg-zinc-700">

                    <span class="font-mono text-lg font-bold tracking-widest">
                        {{ str($category)->upper() }}
                    </span>

                    <div class="flex-1 border-t border-dashed opacity-30"></div>

                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $types->count() }} policies
                    </span>

                </div>

                <ul>
                    @foreach ($types as $type)
                        <li class="group relative flex items-center gap-4 border-b border-zinc-100 px-4 py-3 last:border-0 hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50"
                            wire:key="type-{{ $type->id }}"
                        >

                            <span
                                class="absolute left-0 top-0 h-full w-1 scale-y-0 bg-blue-500 transition-transform duration-150 group-hover:scale-y-100"
                            ></span>

                            <flux:text class="font-mono text-sm font-bold text-blue-600">
                                {{ $type->code }}
                            </flux:text>

                            <flux:text>
                                {{ $type->name }}
                            </flux:text>

                        </li>
                    @endforeach
                </ul>

            </flux:card>

        @empty

            <div class="py-12 text-center">
                <flux:icon class="mx-auto mb-4 size-14 text-zinc-400" name="magnifying-glass" />
                <p class="text-xl text-zinc-500">No policies found</p>
            </div>

        @endforelse

    </div>

</div>
