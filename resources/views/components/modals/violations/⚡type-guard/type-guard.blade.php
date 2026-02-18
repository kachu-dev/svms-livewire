<flux:modal class="w-full max-w-md sm:max-w-96 md:max-w-3xl" name="set-violation">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Choose Violation Type</flux:heading>
            <flux:subheading>Search and select from the list of violations</flux:subheading>
        </div>

        <flux:input
            icon="magnifying-glass"
            placeholder="Type to search violations..."
            wire:model.live.debounce.300ms="typeSearch"
        />

        <div class="max-h-125 space-y-4 overflow-y-auto pr-2">
            @forelse($this->filteredTypes as $category => $types)
                <div>
                    <flux:subheading class="sticky top-0 mb-3 mt-2 rounded-lg bg-white p-4 shadow dark:bg-zinc-900">
                        {{ $category }}
                    </flux:subheading>

                    <div class="space-y-2">
                        @foreach ($types as $type)
                            <button
                                @click="$dispatch('type-selected', {
                                    id: {{ $type->id }},
                                    code: @js($type->code),
                                    name: @js($type->name),
                                    classification: @js($type->classification)
                                }); $flux.modal('set-violation').close()"
                                class="group w-full rounded-lg border-2 border-zinc-200 p-4 text-left transition-all hover:border-blue-500 hover:bg-blue-50 dark:border-zinc-700 dark:hover:bg-blue-950/30"
                                type="button"
                                wire:key="type-{{ $type->id }}"
                            >
                                <div class="flex items-start gap-3">
                                    <div class="min-w-15 text-sm font-bold text-blue-600 dark:text-blue-400">
                                        {{ $type->code }}
                                    </div>
                                    <div
                                        class="flex-1 text-sm text-zinc-600 group-hover:text-zinc-900 dark:text-zinc-400 dark:group-hover:text-zinc-100">
                                        {{ $type->name }}
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <flux:icon class="mx-auto mb-4 size-12 text-zinc-400" name="magnifying-glass" />
                    <flux:subheading>No violations found</flux:subheading>
                    <flux:text>Try searching with different keywords</flux:text>
                </div>
            @endforelse
        </div>

        <div class="flex gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
