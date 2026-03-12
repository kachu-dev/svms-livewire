<div class="grid h-full grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-card header="Student Violations" icon="exclamation-triangle">
            <div class="flex flex-wrap items-center gap-2 pb-6">
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

            <flux:separator />

            <div class="pt-0">
                <flux:table :paginate="$this->violations">
                    <flux:table.columns>
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

                        <flux:table.column align="center">Actions</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($this->violations as $violation)
                            <flux:table.row :key="$violation->id">
                                <flux:table.cell class="max-w-10 whitespace-normal">{{ $violation->classification }}
                                </flux:table.cell>
                                <flux:table.cell align="center">
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
                                                :href="route('staff.violations.detail', [
                                                    'violation' => $violation,
                                                    'stage' => $violation->current_stage?->id,
                                                ])"
                                                icon="eye"
                                                wire:navigate
                                            >
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
                                <flux:table.cell class="py-12 text-center" colspan="7">
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
        </x-card>
    </div>

    <x-card
        class="flex flex-col items-center gap-12"
        header="Student Information"
        icon="user-circle"
    >
        <div class="aspect-square w-full max-w-64 rounded-2xl">
            @if ($this->student?->photo ?? '')
                <img
                    alt="Photo"
                    class="h-full w-full rounded-2xl object-cover"
                    src="{{ $this->student->photo ?? '' }}"
                />
            @else
                <div class="flex h-full w-full items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
                    <flux:icon class="h-16 w-16 text-zinc-400" name="user-circle" />
                </div>
            @endif
        </div>

        <p class="text-center text-3xl font-bold">
            {{ $this->student->lastname ?? '-' }}, {{ $this->student->firstname ?? '-' }} {{ $this->student->mi }}.
        </p>

        <div class="flex w-full flex-col gap-4">
            <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                <flux:label class="text-xs font-bold uppercase tracking-widest">
                    Student ID
                </flux:label>
                <p class="mt-2 text-xl font-bold text-zinc-900 dark:text-white">
                    {{ $this->student->grouptag ?? '' }}{{ $this->student->studentid ?? '' }}
                </p>
            </div>

            <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                <flux:label class="text-xs uppercase tracking-widest">Year</flux:label>
                <p class="mt-2 text-xl font-bold text-zinc-900 dark:text-white">
                    {{ $this->student->year ?? '' }}
                </p>
            </div>

            <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                <flux:label class="text-xs uppercase tracking-widest">Program</flux:label>
                <p class="mt-2 text-xl font-bold text-zinc-900 dark:text-white">
                    {{ $this->student->program ?? '' }}
                </p>
            </div>
        </div>
    </x-card>

    @teleport('body')
        <div>
            <livewire:modals.violations.delete-violation />
        </div>
    @endteleport
</div>
