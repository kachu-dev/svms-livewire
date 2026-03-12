<div>
    <x-table-wrapper heading="Pending Records">
        <div class="flex flex-wrap items-center gap-2 p-6 pb-4 pt-4">
            <div class="min-w-48 max-w-72 flex-1">
                <flux:input
                    icon="magnifying-glass"
                    placeholder="Search records..."
                    wire:model.live.debounce.500ms="search"
                />
            </div>

            <flux:separator vertical />

            <div class="w-44">
                <flux:select placeholder="All Classifications" wire:model.live="classification">
                    <flux:select.option value="">All Classifications</flux:select.option>
                    @foreach ($this->classifications as $class)
                        <flux:select.option value="{{ $class }}">{{ $class }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:separator vertical />

            <div class="flex items-center gap-2">
                <flux:input
                    class="w-36"
                    max="2999-12-31"
                    type="date"
                    wire:model.change.live="dateFrom"
                />
                <flux:icon.arrow-long-right class="shrink-0 text-zinc-400" />
                <flux:input
                    class="w-36"
                    max="2999-12-31"
                    min="{{ $this->dateFrom }}"
                    type="date"
                    wire:model.change.live="dateTo"
                />
            </div>

            <flux:separator vertical />

            <flux:button
                icon="x-mark"
                variant="ghost"
                wire:click="resetFilters"
            >Clear Filters</flux:button>

            <div class="flex-1"></div>

            <flux:button
                color="green"
                icon="arrow-down-tray"
                variant="primary"
                wire:click="exportExcel"
            >Excel</flux:button>
            <flux:button
                color="red"
                icon="document-text"
                variant="primary"
            >PDF</flux:button>
        </div>

        <div class="pb-6">
            <flux:table :paginate="$this->violations">
                <flux:table.columns class="bg-zinc-100 dark:bg-zinc-800 px-6">
                    <flux:table.column class="px-4!"><strong>ID</strong></flux:table.column>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Program</flux:table.column>
                    <flux:table.column>Classification</flux:table.column>
                    <flux:table.column align="center">Count</flux:table.column>
                    <flux:table.column>Violation</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'created_at'"
                        sortable
                        wire:click="sort('created_at')"
                    >
                        Date
                    </flux:table.column>
                    <flux:table.column>Recorded by</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->violations as $violation)
                        <flux:table.row :key="$violation->id">
                            <flux:table.cell class="px-4!" variant="strong">
                                <flux:link
                                    class="tabular-nums"
                                    href="{{ route('staff.violations.student', $violation->student_id) }}"
                                    wire:navigate
                                >
                                    {{ $violation->student_id }}
                                </flux:link>
                            </flux:table.cell>
                            <flux:table.cell class="max-w-40 whitespace-normal">
                                {{ $violation->st_last_name }},
                                {{ $violation->st_first_name }}{{ $violation->st_mi ? ' ' . $violation->st_mi . '.' : '' }}
                            </flux:table.cell>
                            <flux:table.cell>{{ $violation->st_program }} {{ $violation->st_year }}</flux:table.cell>
                            <flux:table.cell class="max-w-10 whitespace-normal">
                                @php
                                    $n = $violation->minor_offense_number;

                                    $suffix = match (true) {
                                        $n % 100 >= 11 && $n % 100 <= 13 => 'th',
                                        $n % 10 === 1 => 'st',
                                        $n % 10 === 2 => 'nd',
                                        $n % 10 === 3 => 'rd',
                                        $n == 0 => '',
                                        default => 'th',
                                    };
                                @endphp

                                {{ $n }}{{ $suffix }} {{ $violation->classification }}
                            </flux:table.cell>                            <flux:table.cell align="center">
                                <div class="flex items-center justify-center">
                                    @if ($violation->is_escalated)
                                        <flux:tooltip content="C.3.9 - Commission of a fourth minor violation"
                                            toggleable
                                        >
                                            <flux:button
                                                class="text-red-500!"
                                                icon="arrow-trending-up"
                                                variant="ghost"
                                            />
                                        </flux:tooltip>
                                    @else
                                        <flux:badge
                                            :color="match (true) {
                                                $violation->minor_offense_number === 1 => 'lime',
                                                $violation->minor_offense_number === 2 => 'yellow',
                                                $violation->minor_offense_number === 3 => 'amber',
                                                $violation->minor_offense_number >= 4 => 'red',
                                                default => 'red',
                                            }"
                                            rounded
                                            variant="solid"
                                        >
                                            {{ $violation->minor_offense_number }}
                                        </flux:badge>
                                    @endif
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="w-60 space-y-0.5">
                                    <flux:tooltip toggleable>
                                        <div>
                                            <flux:text class="truncate font-medium">
                                                <flux:link
                                                    as="button"
                                                    class="tabular-nums"
                                                    variant="ghost"
                                                >{{ $violation->type_code }}</flux:link>
                                                {{ $violation->type_name }}
                                            </flux:text>
                                            @if ($violation->remark)
                                                <flux:text class="truncate text-sm text-zinc-400">
                                                    {{ $violation->remark }}</flux:text>
                                            @endif
                                        </div>
                                        <flux:tooltip.content class="max-w-xs space-y-2">
                                            <p>{{ $violation->type_code }} — {{ $violation->type_name }}</p>
                                            @if ($violation->remark)
                                                <p class="text-zinc-400">{{ $violation->remark }}</p>
                                            @endif
                                        </flux:tooltip.content>
                                    </flux:tooltip>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div>
                                    <flux:text size="xs">{{ $violation->status }}</flux:text>
                                    <flux:badge>{{ $violation->status }}</flux:badge>
                                    @if ($violation->is_escalated)
                                        <flux:text color="red" size="xs">Escalated</flux:text>
                                    @endif
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="tabular-nums">
                                <flux:text>{{ $violation->created_at->format('m-d-y') ?? 'N/A' }}</flux:text>
                                <flux:text>{{ $violation->created_at->format('h:i A') ?? 'N/A' }}</flux:text>
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($violation->recordedBy?->assigned_gate)
                                    <div>
                                        <flux:text>
                                            {{ $violation->recordedBy?->name }}
                                        </flux:text>
                                        <flux:text>
                                            Gate {{ $violation->recordedBy?->assigned_gate }}
                                        </flux:text>
                                    </div>
                                @else
                                    {{ $violation->recordedBy?->name }}
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown position="left">
                                    <flux:button
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                        variant="ghost"
                                    />
                                    <flux:menu>
                                        <flux:menu.item :href="route('staff.violations.detail', [
                                            'violation' => $violation,
                                            'stage' => $violation->current_stage?->id,
                                        ])" icon="eye">
                                            View Details
                                        </flux:menu.item>
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
                            <flux:table.cell class="py-12 text-center" colspan="10">
                                <div class="flex flex-col items-center gap-3">
                                    <flux:icon class="h-16 w-16 text-gray-300" name="inbox" />
                                    <div>
                                        <flux:text class="font-medium text-gray-600">No violations found</flux:text>
                                        <flux:text class="mt-1 text-sm text-gray-500">
                                            @if ($search || $dateFrom || $dateTo)
                                                Try adjusting your filters or search terms
                                            @else
                                                No violations have been recorded yet
                                            @endif
                                        </flux:text>
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

        <x-slot:actions>
            <flux:dropdown>
                <flux:button icon:trailing="chevron-down">School Year</flux:button>

                <flux:menu>
                    <flux:modal.trigger name="reset-sy">
                        <flux:menu.item icon="arrow-uturn-left">Reset Violations</flux:menu.item>
                    </flux:modal.trigger>
                    <flux:menu.separator />
                    <flux:menu.item icon="academic-cap">2025-2026</flux:menu.item>
                    <flux:menu.item icon="academic-cap">2026-2027</flux:menu.item>
                    <flux:menu.item icon="academic-cap">2027-2028</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
            <flux:button
                class="w-full"
                href="{{ route('staff.violations.deleted') }}"
                icon="archive-box"
                wire:navigate
            >
                Archived Violations
            </flux:button>
            <flux:button
                class="w-full"
                href="{{ route('staff.violations.create') }}"
                icon="plus-circle"
                variant="primary"
                wire:navigate
            >
                New Record
            </flux:button>
        </x-slot>

        <flux:modal class="md:w-96" name="reset-sy">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">This resets all violations!</flux:heading>
                    <flux:text class="mt-2">PLEASE DOUBLE CHECK.</flux:text>
                </div>

                <div class="grid grid-cols-[1fr_auto_1fr] items-end gap-2">
                    <flux:input label="School Year From" placeholder="From" />
                    <span class="pb-2">-</span>
                    <flux:input label="School Year To" placeholder="To" />
                </div>

                <flux:button class="w-full" variant="danger">RESET SCHOOL YEAR</flux:button>
            </div>
        </flux:modal>

    </x-table-wrapper>

    @teleport('body')
        <div>
            <livewire:modals.violations.delete-violation />
        </div>
    @endteleport
</div>
