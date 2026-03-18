<div>
    <x-table-wrapper heading="Deactivated Policies">
        <div
            class="flex flex-wrap items-center gap-2 border-zinc-200 bg-zinc-100 p-4 dark:border-white/10 dark:bg-white/5">
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
            >
                Clear Filters
            </flux:button>

            <div class="flex-1"></div>
        </div>

        <div>
            <flux:table>
                <flux:table.columns class="bg-blue-100 px-6 dark:bg-zinc-800">
                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'code'"
                        class="px-4!"
                        sortable
                        wire:click="sort('code')"
                    ><strong>Code</strong></flux:table.column>
                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'name'"
                        sortable
                        wire:click="sort('name')"
                    >Name</flux:table.column>
                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'classification'"
                        sortable
                        wire:click="sort('classification')"
                    >Classification</flux:table.column>
                    <flux:table.column align="center">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->policies as $policy)
                        <flux:table.row :key="$policy->id">
                            <flux:table.cell variant="strong">
                                <span class="px-4! text-sm font-medium tabular-nums text-blue-600 dark:text-blue-400">
                                    {{ $policy->code }}
                                </span>
                            </flux:table.cell>

                            <flux:table.cell class="max-w-72">
                                <div class="font-medium leading-snug">{{ $policy->name }}</div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:badge
                                    :color="match ($policy->classification) {
                                        'Minor' => 'green',
                                        'Major - Suspension' => 'amber',
                                        'Major - Dismissal' => 'orange',
                                        'Major - Expulsion' => 'red',
                                        default => 'zinc',
                                    }"
                                    rounded
                                    size="sm"
                                >
                                    {{ $policy->classification }}
                                </flux:badge>
                            </flux:table.cell>

                            <flux:table.cell align="center">
                                <flux:dropdown position="left">
                                    <flux:button
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                        variant="ghost"
                                    />
                                    <flux:menu>
                                        <flux:menu.item
                                            @click="$dispatch('restore-policy', { id: {{ $policy->id }} });"
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
                            <flux:table.cell class="py-12 text-center" colspan="4">
                                <div class="flex flex-col items-center gap-3">
                                    <flux:icon class="h-14 w-14 text-zinc-300" name="inbox" />
                                    <div>
                                        <flux:text class="font-medium text-zinc-500">No policies found</flux:text>
                                        <flux:text class="mt-1 text-sm text-zinc-400">
                                            @if ($search || $classification)
                                                Try adjusting your filters or search terms
                                            @else
                                                No policies have been deactivated yet
                                            @endif
                                        </flux:text>
                                    </div>
                                    @if ($search || $classification)
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
            <flux:pagination :paginator="$this->policies" class="p-4" />
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
