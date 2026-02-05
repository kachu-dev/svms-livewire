<div>

   {{-- <div class="space-y-6">
        <x-card header="Student Violations" icon="exclamation-triangle">
            <div class="space-y-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by ID, name, or violation..."
                        icon="magnifying-glass"
                    />

                    <flux:select wire:model.live="statusFilter" placeholder="All Statuses">
                        <flux:select.option value="">All Statuses</flux:select.option>
                        <flux:select.option value="Pending">Pending</flux:select.option>
                        <flux:select.option value="Resolved">Resolved</flux:select.option>
                        <flux:select.option value="Dismissed">Dismissed</flux:select.option>
                    </flux:select>

                    <flux:select wire:model.live="violationTypeFilter" placeholder="All Violations">
                        <flux:select.option value="">All Violations</flux:select.option>
                        <flux:select.option value="No ID">No ID</flux:select.option>
                        <flux:select.option value="Uniform Violation">Uniform Violation</flux:select.option>
                        <flux:select.option value="Tardiness">Tardiness</flux:select.option>
                        <flux:select.option value="Eating in Laboratory">Eating in Laboratory</flux:select.option>
                        <flux:select.option value="Public Display of Intimacy">Public Display of Intimacy</flux:select.option>
                        <flux:select.option value="Littering">Littering</flux:select.option>
                        <flux:select.option value="Smoking in Campus">Smoking in Campus</flux:select.option>
                    </flux:select>

                    <flux:select wire:model.live="perPage" placeholder="Per Page">
                        <flux:select.option value="10">10 per page</flux:select.option>
                        <flux:select.option value="25">25 per page</flux:select.option>
                        <flux:select.option value="50">50 per page</flux:select.option>
                        <flux:select.option value="100">100 per page</flux:select.option>
                    </flux:select>
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Showing {{ $from }} to {{ $to }} of {{ $total }} violations
                    </div>
                    <div class="flex gap-2">
                        <flux:button size="sm" variant="ghost" wire:click="resetFilters" icon="x-mark">
                            Clear Filters
                        </flux:button>
                        <flux:button size="sm" variant="primary" icon="plus">
                            Add Violation
                        </flux:button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded shadow border-t-4 border-t-blue-500 pl-4 pr-4">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column sortable :sorted="$sortBy === 'student_id'" :direction="$sortDirection" wire:click="sort('student_id')">
                            <strong>Student ID</strong>
                        </flux:table.column>
                        <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">
                            <p>Name</p>
                        </flux:table.column>
                        <flux:table.column sortable :sorted="$sortBy === 'violation'" :direction="$sortDirection" wire:click="sort('violation')">
                            <strong>Violation</strong>
                        </flux:table.column>
                        <flux:table.column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">
                            <strong>Count</strong>
                        </flux:table.column>
                        <flux:table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection" wire:click="sort('status')">
                            <strong>Status</strong>
                        </flux:table.column>
                        <flux:table.column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">
                            <strong>Date</strong>
                        </flux:table.column>
                        <flux:table.column>
                            <strong>Actions</strong>
                        </flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($violations as $violation)
                            <flux:table.row :key="$violation['id']">
                                <flux:table.cell variant="strong">{{ $violation['student_id'] }}</flux:table.cell>
                                <flux:table.cell>{{ $violation['name'] }}</flux:table.cell>
                                <flux:table.cell>{{ $violation['violation'] }}</flux:table.cell>
                                <flux:table.cell> 1 </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge
                                        size="sm"
                                        :color="match($violation['status']) {
                                        'Pending' => 'yellow',
                                        'Resolved' => 'green',
                                        'Dismissed' => 'gray',
                                        default => 'blue'
                                    }"
                                        inset="top bottom"
                                    >
                                        {{ $violation['status'] }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="whitespace-nowrap">{{ $violation['date'] }}</flux:table.cell>
                                <flux:table.cell>
                                    <flux:dropdown position="left">
                                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                                        <flux:menu>
                                            <flux:menu.item icon="eye">View Details</flux:menu.item>
                                            <flux:menu.item icon="pencil">Edit</flux:menu.item>
                                            <flux:menu.separator />
                                            <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <flux:icon name="inbox" class="w-16 h-16 text-gray-300" />
                                        <div>
                                            <p class="text-gray-600 font-medium">No violations found</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                @if($search || $statusFilter || $violationTypeFilter)
                                                    Try adjusting your filters or search terms
                                                @else
                                                    No violations have been recorded yet
                                                @endif
                                            </p>
                                        </div>
                                        @if($search || $statusFilter || $violationTypeFilter)
                                            <flux:button size="sm" variant="ghost" wire:click="resetFilters" icon="arrow-path">
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


            @if($total > 0)
                <div class="mt-2 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing {{ $from }} to {{ $to }} of {{ $total }} results
                    </div>

                    <div class="flex gap-2">
                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="chevron-left"
                            wire:click="previousPage"
                            :disabled="$currentPage === 1"
                        >
                            Previous
                        </flux:button>

                        <div class="flex gap-1">
                            @for ($i = 1; $i <= $lastPage; $i++)
                                @if ($i == 1 || $i == $lastPage || ($i >= $currentPage - 2 && $i <= $currentPage + 2))
                                    <flux:button
                                        size="sm"
                                        :variant="$i === $currentPage ? 'primary' : 'ghost'"
                                        wire:click="gotoPage({{ $i }})"
                                    >
                                        {{ $i }}
                                    </flux:button>
                                @elseif ($i == $currentPage - 3 || $i == $currentPage + 3)
                                    <span class="px-2 py-1 text-gray-400">...</span>
                                @endif
                            @endfor
                        </div>

                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="chevron-right"
                            icon:trailing
                            wire:click="nextPage"
                            :disabled="$currentPage === $lastPage"
                        >
                            Next
                        </flux:button>
                    </div>
                </div>
            @endif
        </x-card>
    </div>--}}

</div>
