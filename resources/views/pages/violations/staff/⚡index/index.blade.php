<div>
    <x-card header="Student Violations" icon="exclamation-triangle">
        <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-12">
            <div class="md:col-span-4">
                <flux:input
                    icon="magnifying-glass"
                    label="Search"
                    placeholder="Search by ID, name, or violation..."
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
                    @foreach ($this->classifications as $class)
                        <flux:select.option value="{{ $class }}">{{ $class }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="md:col-span-2">
                <flux:input
                    label="From"
                    max="2999-12-31"
                    type="date"
                    wire:model.change.live="dateFrom"
                />
            </div>

            <div class="md:col-span-2">
                <flux:input
                    label="To"
                    max="2999-12-31"
                    min="{{ $this->dateFrom }}"
                    type="date"
                    wire:model.change.live="dateTo"
                />
            </div>
        </div>

        <div class="rounded border-t-4 border-t-blue-500 pl-4 pr-4 shadow">
            <flux:table :paginate="$this->violations">
                <flux:table.columns>
                    <flux:table.column><strong>Student ID</strong></flux:table.column>
                    <flux:table.column>
                        Student Name
                    </flux:table.column>
                    <flux:table.column>
                        Classification
                    </flux:table.column>

                    <flux:table.column>
                        Count
                    </flux:table.column>

                    <flux:table.column>
                        Violation
                    </flux:table.column>

                    <flux:table.column>
                        Status
                    </flux:table.column>

                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'created_at'"
                        sortable
                        wire:click="sort('created_at')"
                    >
                        Date
                    </flux:table.column>

                    <flux:table.column align="center">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($this->violations as $violation)
                        <flux:table.row :key="$violation->id">
                            <flux:table.cell variant="strong">{{ $violation->student_id }}</flux:table.cell>
                            <flux:table.cell>{{ $violation->student_name }}</flux:table.cell>
                            <flux:table.cell>{{ $violation->classification }}</flux:table.cell>
                            <flux:table.cell>{{ $violation->minorOffenseNumber() }}</flux:table.cell>
                            <flux:table.cell>
                                <div>
                                    <flux:tooltip position="left">
                                        <p>{{ str($violation->violation_type_snapshot)->words(6) }}</p>
                                        <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                            <p>{{ $violation->violation_type_snapshot }}</p>
                                            <flux:separator />
                                            <p>{{ $violation->violation_remark_snapshot }}</p>
                                        </flux:tooltip.content>
                                    </flux:tooltip>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge> {{ $violation->status }} </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">
                                {{ $violation->created_at->format('M j, Y - h:i A') ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell align="center">
                                <flux:dropdown position="left">
                                    <flux:button
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                        size="sm"
                                        variant="ghost"
                                    />
                                    <flux:menu>
                                        <flux:menu.item :href="route('staff.violations.detail', [
                                            'violation' => $violation,
                                            'stage' => $violation->current_stage?->id,
                                        ])" icon="eye">
                                            View Details
                                        </flux:menu.item>
                                        <flux:menu.item icon="pencil">Edit</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item
                                            @click="
                                                $dispatch('delete-violation', {
                                                id: {{ $violation->id }},
                                                });"
                                            icon="arrow-path"
                                            variant="danger"
                                        >
                                            Archive
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
                                        <p class="font-medium text-gray-600">No violations found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if ($search || $dateFrom || $dateTo)
                                                Try adjusting your filters or search terms
                                            @else
                                                No violations have been recorded yet
                                            @endif
                                        </p>
                                    </div>
                                    @if ($search || $dateFrom || $dateTo)
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

    <livewire:modals.violations.delete-violation />
</div>
