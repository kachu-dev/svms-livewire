<div>
    <x-card header="Deactivated Policy Types" icon="document-text">
        <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-12">
            <div class="md:col-span-4">
                <flux:input
                    label="Search"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Search by Code or name..."
                    icon="magnifying-glass"
                />
            </div>

            <div class="md:col-span-4">
                <flux:select
                    wire:model.live="classification"
                    placeholder="All Classifications"
                    label="Classification"
                >
                    <flux:select.option value="">All Classifications</flux:select.option>
                    <flux:select.option> Minor </flux:select.option>
                    <flux:select.option> Major - Suspension </flux:select.option>
                    <flux:select.option> Major - Dismissal </flux:select.option>
                    <flux:select.option> Major - Expulsion </flux:select.option>
                </flux:select>
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
                                        variant="ghost"
                                        size="sm"
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                    />
                                    <flux:menu>
                                        <flux:menu.item wire:click="reactivate({{ $policy->id }})" icon="archive-box-x-mark">Reactivate</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <flux:icon name="inbox" class="h-16 w-16 text-gray-300" />
                                    <div>
                                        <p class="font-medium text-gray-600">No policies found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if ($search)
                                                Try adjusting your filters or search terms
                                            @else
                                                No policies have been recorded yet
                                            @endif
                                        </p>
                                    </div>
                                    @if ($search)
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            wire:click="resetFilters"
                                            icon="arrow-path"
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
</div>
