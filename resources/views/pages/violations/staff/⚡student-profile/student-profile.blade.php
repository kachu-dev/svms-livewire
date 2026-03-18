<div class="grid h-full grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-card
            class="p-0!"
            header="Student Violations"
            icon="exclamation-triangle"
        >
            <div class="flex flex-wrap items-center gap-2 p-4">
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
                >
                    Excel
                </flux:button>
                <flux:button
                    color="red"
                    icon="document-text"
                    variant="primary"
                >
                    PDF
                </flux:button>
            </div>

            <flux:separator />

            <div>
                <flux:table>
                    <flux:table.columns class="bg-blue-100 px-6 dark:bg-zinc-800">
                        <flux:table.column
                            :direction="$sortDirection"
                            :sorted="$sortBy === 'classification'"
                            class="px-4! w-24"
                            sortable
                            wire:click="sort('classification')"
                        >Classification</flux:table.column>
                        <flux:table.column
                            :direction="$sortDirection"
                            :sorted="$sortBy === 'violation'"
                            sortable
                            wire:click="sort('type_code')"
                        >Violation</flux:table.column>
                        <flux:table.column
                            :direction="$sortDirection"
                            :sorted="$sortBy === 'status'"
                            sortable
                            wire:click="sort('status')"
                        >Status</flux:table.column>
                        <flux:table.column
                            :direction="$sortDirection"
                            :sorted="$sortBy === 'created_at'"
                            sortable
                            wire:click="sort('created_at')"
                        >Date</flux:table.column>
                        <flux:table.column
                            :direction="$sortDirection"
                            :sorted="$sortBy === 'recorded_by'"
                            sortable
                            wire:click="sort('recorded_by')"
                        >Recorded by</flux:table.column>
                        <flux:table.column class="w-24">School Year</flux:table.column>
                        <flux:table.column>Actions</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($this->violations as $violation)
                            @php
                                $suffix = match (true) {
                                    $violation->minor_offense_number % 100 >= 11 &&
                                        $violation->minor_offense_number % 100 <= 13
                                        => 'th',
                                    $violation->minor_offense_number % 10 === 1 => 'st',
                                    $violation->minor_offense_number % 10 === 2 => 'nd',
                                    $violation->minor_offense_number % 10 === 3 => 'rd',
                                    $violation->minor_offense_number == 0 => '',
                                    default => 'th',
                                };
                            @endphp

                            <flux:table.row :key="$violation->id">

                                <flux:table.cell class="px-4! w-24 whitespace-nowrap">
                                    @if ($violation->classification != 'Minor')
                                        <flux:badge
                                            color="red"
                                            rounded
                                            size="sm"
                                        >
                                            {{ $violation->classification }}
                                        </flux:badge>
                                    @else
                                        <flux:badge
                                            :color="match (true) {
                                                $violation->minor_offense_number === 1 => 'green',
                                                $violation->minor_offense_number === 2 => 'yellow',
                                                $violation->minor_offense_number >= 3 => 'orange',
                                                default => 'zinc',
                                            }"
                                            rounded
                                            size="sm"
                                        >
                                            {{ $violation->minor_offense_number }}{{ $suffix }}
                                            {{ $violation->classification }}
                                        </flux:badge>
                                    @endif
                                </flux:table.cell>

                                <flux:table.cell>
                                    <flux:tooltip>
                                        <div class="w-56">
                                            <div class="flex items-baseline gap-1 truncate">
                                                <span
                                                    class="shrink-0 text-sm font-medium text-blue-600 dark:text-blue-400"
                                                >
                                                    {{ $violation->type_code }}
                                                </span>
                                                <span class="truncate text-sm">{{ $violation->type_name }}</span>
                                            </div>
                                            @if ($violation->remark)
                                                <div class="mt-0.5 truncate text-xs text-zinc-400">
                                                    {{ $violation->remark }}
                                                </div>
                                            @endif
                                        </div>
                                        <flux:tooltip.content class="max-w-xs space-y-1">
                                            <p class="text-sm font-medium">{{ $violation->type_code }} —
                                                {{ $violation->type_name }}</p>
                                            @if ($violation->remark)
                                                <p class="text-sm text-zinc-400">{{ $violation->remark }}</p>
                                            @endif
                                        </flux:tooltip.content>
                                    </flux:tooltip>
                                </flux:table.cell>

                                <flux:table.cell>
                                    <div class="flex flex-wrap gap-1">
                                        <flux:badge
                                            :color="match ($violation->status) {
                                                'Oral Reprimand' => 'violet',
                                                'Start 2 Days Suspension' => 'amber',
                                                default => 'blue',
                                            }"
                                            rounded
                                            size="sm"
                                        >
                                            {{ $violation->status }}
                                        </flux:badge>

                                        @if ($violation->is_escalated)
                                            <flux:badge
                                                color="red"
                                                rounded
                                                size="sm"
                                            >
                                                Escalated
                                            </flux:badge>
                                        @endif
                                    </div>
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap tabular-nums">
                                    <div class="text-sm">{{ $violation->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-zinc-400">{{ $violation->created_at->format('h:i:s A') }}
                                    </div>
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap">
                                    <div class="text-sm">{{ $violation->recordedBy?->name ?? '—' }}</div>
                                    @if ($violation->recordedBy?->assigned_gate)
                                        <div class="text-xs text-zinc-400">Gate
                                            {{ $violation->recordedBy->assigned_gate }}</div>
                                    @endif
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-normal tabular-nums">
                                    {{ $violation->school_year ?? '—' }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    <flux:dropdown>
                                        <flux:button
                                            icon="ellipsis-horizontal"
                                            inset="top bottom"
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
                                                @click="$dispatch('delete-violation', { id: {{ $violation->id }} });"
                                                icon="archive-box"
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
                                <flux:table.cell class="py-12 text-center" colspan="6">
                                    <div class="flex flex-col items-center gap-3">
                                        <flux:icon class="h-14 w-14 text-zinc-300" name="inbox" />
                                        <div>
                                            <flux:text class="font-medium text-zinc-500">No violations found</flux:text>
                                            <flux:text class="mt-1 text-sm text-zinc-400">
                                                @if ($search || $classification || $dateFrom || $dateTo)
                                                    Try adjusting your filters or search terms
                                                @else
                                                    No violations have been recorded yet
                                                @endif
                                            </flux:text>
                                        </div>
                                        @if ($search || $classification || $dateFrom || $dateTo)
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
                <flux:pagination :paginator="$this->violations" class="p-4" />
            </div>
        </x-card>
    </div>

    <x-card
        class="flex flex-col items-center gap-12"
        header="Student Information"
        icon="user-circle"
    >
        <div class="aspect-square w-full max-w-52 rounded-2xl">
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
