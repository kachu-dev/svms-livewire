<x-card header="List of Violations" icon="document-text">
    <div class="space-y-4">
        @forelse ($this->filteredTypes as $category => $types)
            <div>
                <div class="mb-4">
                    <h3 class="text-sm font-semibold">
                        {{ $category }}
                    </h3>
                </div>

                <div class="space-y-4">
                    @foreach ($types as $type)
                        <div class="w-full rounded-lg border border-zinc-500 p-4" wire:key="type-{{ $type->id }}">
                            <div class="flex items-start gap-3">
                                <div class="text-sm font-bold text-blue-600">
                                    {{ $type->code }}
                                </div>

                                <div class="flex-1">
                                    <div class="text-sm dark:text-zinc-100">
                                        {{ $type->name }}
                                    </div>

                                    <div class="text-xs italic dark:text-zinc-400">
                                        {{ $type->classification }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-12 text-center">
                <flux:icon class="mx-auto mb-4 size-12 text-zinc-400" name="magnifying-glass" />
                <p class="text-sm text-zinc-500">No policies found</p>
            </div>
        @endforelse
    </div>
</x-card>
