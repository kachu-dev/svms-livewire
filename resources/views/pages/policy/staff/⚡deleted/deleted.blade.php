<div>
    <x-card header="Deactivated Policy Types" icon="document-text">
        <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-12">
            <div class="md:col-span-4">
                <flux:input
                    icon="magnifying-glass"
                    label="Search"
                    placeholder="Search by Code or name..."
                    wire:model.live.debounce.500ms="search"
                />
            </div>

            <div class="md:col-span-4">
                <flux:select
                    label="Classification"
                    placeholder="All Classifications"
                    wire:model.live="classification"
                >
                    <flux:select.option value="">All Classifications</flux:select.option>
                    <flux:select.option> Minor </flux:select.option>
                    <flux:select.option> Major - Suspension </flux:select.option>
                    <flux:select.option> Major - Dismissal </flux:select.option>
                    <flux:select.option> Major - Expulsion </flux:select.option>
                </flux:select>
            </div>

            <div class="flex gap-3 md:col-span-2 md:items-end">
                <flux:button
                    class="w-full"
                    href="{{ route('staff.policy.index') }}"
                    icon="numbered-list"
                >
                    All Policies
                </flux:button>
            </div>
        </div>

        <div class="rounded border-t-4 border-t-blue-500 p-4 shadow">
            <flux:table :paginate="$this->policies">
                <flux:table.columns>
                    <flux:table.column>Code</flux:table.column>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Classification</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($this->policies as $policy)
                        <flux:table.row>
                            <flux:table.cell>{{ $policy->code }}</flux:table.cell>
                            <flux:table.cell>{{ $policy->name }}</flux:table.cell>
                            <flux:table.cell>{{ $policy->classification }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown position="left">
                                    <flux:button
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                        size="sm"
                                        variant="ghost"
                                    />
                                    <flux:menu>
                                        <flux:menu.item
                                            icon="archive-box-x-mark"
                                            @click="
                                                $dispatch('restore-policy', {
                                                id: {{ $policy->id }},
                                                });
                                                                     $flux.modal('restore-policy').show()
                                            "
                                        >Reactivate</flux:menu.item>
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
                                                No policies have been deleted yet
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
    </x-card>

    <livewire:violations.modals.restore-policy />
</div>
