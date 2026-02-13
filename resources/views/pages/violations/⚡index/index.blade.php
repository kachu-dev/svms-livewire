<div>
     <x-card header="Student Violations" icon="exclamation-triangle">
        <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-12">
            <div class="md:col-span-4">
                <flux:input
                    label="Search"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Search by ID, name, or violation..."
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
                    @foreach ($this->classifications as $class)
                        <flux:select.option value="{{ $class }}">{{ $class }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="md:col-span-2">
                <flux:input
                    wire:model.change.live="dateFrom"
                    type="date"
                    max="2999-12-31"
                    label="From"
                />
            </div>

            <div class="md:col-span-2">
                <flux:input
                    wire:model.change.live="dateTo"
                    type="date"
                    min="{{ $this->dateFrom }}"
                    max="2999-12-31"
                    label="To"
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

                    <flux:table.column
                        sortable
                        :sorted="$sortBy === 'count'"
                        :direction="$sortDirection"
                        wire:click="sort('count')"
                    >
                        Count
                    </flux:table.column>

                    <flux:table.column>
                        <p>Violation</p>
                    </flux:table.column>

                    <flux:table.column
                        sortable
                        :sorted="$sortBy === 'created_at'"
                        :direction="$sortDirection"
                        wire:click="sort('created_at')"
                    >
                        Date
                    </flux:table.column>

                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($this->violations as $violation)
                        <flux:table.row :key="$violation->id">
                            <flux:table.cell variant="strong">{{ $violation->student_id }}</flux:table.cell>
                            <flux:table.cell>{{ $violation->student_name }}</flux:table.cell>
                            <flux:table.cell>{{ $violation->classification }}</flux:table.cell>
                            <flux:table.cell>{{ $violation->count }}</flux:table.cell>
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
                            <flux:table.cell class="whitespace-nowrap">
                                {{ $violation->created_at->format('M j, Y - h:i A') ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown position="left">
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                    />
                                    <flux:menu>
                                        <flux:menu.item icon="eye">View Details</flux:menu.item>
                                        <flux:menu.item href="/violationsss" icon="pencil">Edit</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
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
