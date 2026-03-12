<div>
    <x-table-wrapper heading="Deactivated Policies">
        <x-slot:searches>
            <div class="flex flex-wrap items-center gap-2 p-6 pb-4 pt-4">
                <div class="min-w-48 max-w-72 flex-1">
                    <flux:input
                        icon="magnifying-glass"
                        placeholder="Search by Code or name..."
                        wire:model.live.debounce.500ms="search"
                    />
                </div>

                <flux:separator vertical />

                <div class="w-44">
                    <flux:select placeholder="All Classifications" wire:model.live="classification">
                        <flux:select.option value="">All Classifications</flux:select.option>
                        <flux:select.option>Minor</flux:select.option>
                        <flux:select.option>Major - Suspension</flux:select.option>
                        <flux:select.option>Major - Dismissal</flux:select.option>
                        <flux:select.option>Major - Expulsion</flux:select.option>
                    </flux:select>
                </div>

                <flux:separator vertical />

                <flux:button
                    icon="x-mark"
                    variant="ghost"
                    wire:click="resetFilters"
                >Clear Filters</flux:button>

                <div class="flex-1"></div>
            </div>
        </x-slot:searches>

        <div class="p-6 pt-0">
            <flux:table :paginate="$this->policies">
                <flux:table.columns>
                    <flux:table.column>Code</flux:table.column>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Classification</flux:table.column>
                    <flux:table.column align="center">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->policies as $policy)
                        <flux:table.row>
                            <flux:table.cell>{{ $policy->code }}</flux:table.cell>
                            <flux:table.cell>{{ $policy->name }}</flux:table.cell>
                            <flux:table.cell>{{ $policy->classification }}</flux:table.cell>
                            <flux:table.cell align="center">
                                <flux:dropdown position="left">
                                    <flux:button
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                        size="sm"
                                        variant="ghost"
                                    />
                                    <flux:menu>
                                        <flux:menu.item
                                            @click="
                                                $dispatch('restore-policy', {
                                                    id: {{ $policy->id }},
                                                });"
                                            icon="archive-box-x-mark"
                                            variant="success"
                                        >
                                            Reactivate
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell class="py-12 text-center" colspan="7">
                                <div class="flex flex-col items-center gap-3">
                                    <flux:icon class="h-16 w-16 text-gray-300" name="inbox" />
                                    <div>
                                        <p class="font-medium text-gray-600">No policies found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if ($search)
                                                Try adjusting your filters or search terms
                                            @else
                                                No policies have been deactivated yet
                                            @endif
                                        </p>
                                    </div>
                                    @if ($search)
                                        <flux:button
                                            icon="arrow-path"
                                            size="sm"
                                            variant="ghost"
                                            wire:click="resetFilters"
                                        >
                                            Reset Filters
                                        </flux:button>
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <x-slot:actions>
            <flux:button
                class="w-full"
                href="{{ route('staff.policy.index') }}"
                icon="numbered-list"
                wire:navigate
            >
                All Policies
            </flux:button>
        </x-slot:actions>
    </x-table-wrapper>

    @teleport('body')
        <div>
            <livewire:modals.policy.restore-policy />
        </div>
    @endteleport
</div>
