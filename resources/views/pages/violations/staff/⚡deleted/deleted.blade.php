<div>
    <x-table-wrapper heading="Archived Records">
        <x-slot:searches>
            <div class="flex flex-wrap items-center gap-2 p-6 pb-4 pt-4">
                <div class="min-w-48 max-w-72 flex-1">
                    <flux:input
                        icon="magnifying-glass"
                        placeholder="Search archived..."
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
            </div>
        </x-slot:searches>

        <div class="p-6 pt-0">
            <flux:table :paginate="$this->violations">
                <flux:table.columns>
                    <flux:table.column><strong>ID</strong></flux:table.column>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Program</flux:table.column>
                    <flux:table.column>Classification</flux:table.column>
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
                    <flux:table.column align="center">Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse ($this->violations as $violation)
                        <flux:table.row :key="$violation->id">
                            <flux:table.cell variant="strong">
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
                            <flux:table.cell class="max-w-10 whitespace-normal">{{ $violation->classification }}
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
                                        <flux:tooltip position="right">
                                            <p>{{ $violation->recordedBy?->name }}</p>
                                            <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                <p>Gate {{ $violation->recordedBy?->assigned_gate }}</p>
                                            </flux:tooltip.content>
                                        </flux:tooltip>
                                    </div>
                                @else
                                    {{ $violation->recordedBy?->name }}
                                @endif
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
                                            @click="$dispatch('restore-violation', { id: {{ $violation->id }} });"
                                            icon="arrow-path"
                                            variant="success"
                                        >
                                            Restore
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
                                        <p class="font-medium text-gray-600">No violations found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if ($search || $dateFrom || $dateTo)
                                                Try adjusting your filters or search terms
                                            @else
                                                No violations have been archived yet
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

        <x-slot:actions>
            <flux:button
                class="w-full"
                href="{{ route('staff.violations.index') }}"
                icon="arrow-uturn-left"
                wire:navigate
            >
                Back to Active Records
            </flux:button>
        </x-slot>
    </x-table-wrapper>

    @teleport('body')
        <div>
            <livewire:modals.violations.restore-violation />
        </div>
    @endteleport
</div>
