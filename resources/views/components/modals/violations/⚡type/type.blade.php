<flux:modal
    @close="clearSearch"
    class="w-full max-w-lg sm:max-w-96 md:max-w-3xl"
    name="set-violation"
>
    <div class="space-y-6">
        <div>
            <flux:heading size="xl">Choose Violation Type</flux:heading>
            <flux:subheading class="text-base">Search and select from the list of violations below</flux:subheading>
        </div>

        <flux:separator />

        <flux:input
            class="py-3 text-lg"
            icon="magnifying-glass"
            placeholder="Type to search violations..."
            size="accessible"
            variant="accessible"
            wire:model.live.debounce.300ms="typeSearch"
        />

        <div class="max-h-125 space-y-4 overflow-y-auto">
            @forelse ($this->filteredTypes as $category => $types)
                <div>
                    <flux:heading class="sticky top-0 mb-3 mt-2 bg-white p-4 font-bold shadow dark:bg-zinc-900">
                        <p class="text-2xl">{{ $category }}</p>
                    </flux:heading>

                    <div class="space-y-3">
                        @foreach ($types as $type)
                            <button
                                :class="{{ $type->id }} === $wire.selectedTypeId ?
                                    'border-blue-500 bg-blue-50 dark:bg-blue-950/30' :
                                    'border-zinc-500 hover:border-blue-500 hover:bg-blue-50 dark:border-zinc-600 dark:hover:bg-blue-950/30'"
                                @click="
                                    $wire.selectedTypeId = {{ $type->id }};
                                    $dispatch('type-selected', {
                                        id: {{ $type->id }},
                                        code: @js($type->code),
                                        name: @js($type->name),
                                        classification: @js($type->classification)
                                    }); $flux.modal('set-violation').close()
                                "
                                class="group w-full rounded-xl border-2 p-5 text-left transition-all focus:outline-none focus:ring-4 focus:ring-blue-400 active:scale-95"
                                type="button"
                                wire:key="type-{{ $type->id }}"
                            >
                                <div class="flex items-center gap-4">
                                    <div class="text-base font-bold text-blue-600 dark:text-blue-400">
                                        {{ $type->code }}
                                    </div>
                                    <flux:separator vertical />
                                    <div class="flex-1">
                                        <flux:heading>
                                            {{ $type->name }}
                                        </flux:heading>
                                        <div class="mt-1">
                                            <flux:badge
                                                color="{{ $type->classification === 'Minor' ? 'yellow' : 'red' }}"
                                                size="sm"
                                            >
                                                {{ $type->classification }}
                                            </flux:badge>
                                        </div>
                                    </div>
                                    <flux:icon
                                        class="h-5 w-5 text-blue-500"
                                        name="check-circle"
                                        x-show="{{ $type->id }} === $wire.selectedTypeId"
                                    />
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <flux:icon class="mx-auto mb-4 size-16 text-zinc-400" name="magnifying-glass" />
                    <flux:subheading class="text-lg">No violations found</flux:subheading>
                    <flux:text class="text-base">Try searching with different keywords</flux:text>
                </div>
            @endforelse
        </div>

        <flux:separator />

        <div class="flex gap-3">
            <flux:spacer />
            <flux:modal.close>
                <flux:button
                    class="px-6 py-3 text-base"
                    size="lg"
                    variant="ghost"
                >Cancel</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
