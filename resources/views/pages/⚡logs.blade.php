<?php

use Spatie\Activitylog\Models\Activity;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'Activity Logs'])] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $channel = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'channel', 'dateFrom', 'dateTo');
        $this->resetPage();
    }

    #[Computed]
    public function logs()
    {
        return Activity::with('causer')
            ->when($this->channel, fn($q) => $q->inLog($this->channel))
            ->when(
                $this->search,
                fn($q) => $q
                    ->where('description', 'like', "%{$this->search}%")
                    ->orWhere('properties', 'like', "%{$this->search}%")
                    ->orWhereHasMorph('causer', \App\Models\User::class, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('username', 'like', "%{$this->search}%")),
            )
            ->when($this->dateFrom, fn($q) => $q->where('created_at', '>=', \Carbon\Carbon::parse($this->dateFrom)->startOfDay()))
            ->when($this->dateTo, fn($q) => $q->where('created_at', '<=', \Carbon\Carbon::parse($this->dateTo)->endOfDay()))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(9);
    }

    #[Computed]
    public function channels(): array
    {
        return Activity::distinct()->pluck('log_name')->sort()->values()->toArray();
    }
};
?>

<div>
    <x-table-wrapper heading="Activity Logs">

        <div
            class="flex flex-wrap items-center gap-2 border-zinc-200 bg-zinc-100 p-4 dark:border-white/10 dark:bg-white/5">

            <div class="min-w-48 max-w-72 flex-1">
                <flux:input
                    icon="magnifying-glass"
                    placeholder="Search logs..."
                    wire:model.live.debounce.500ms="search"
                />
            </div>

            <flux:separator vertical />

            <div class="w-44">
                <flux:select placeholder="All Channels" wire:model.live="channel">
                    <flux:select.option value="">All Channels</flux:select.option>
                    @foreach ($this->channels as $ch)
                        <flux:select.option value="{{ $ch }}">{{ Str::title(str_replace('_', ' ', $ch)) }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:separator vertical />

            <div class="flex items-center gap-2">
                <flux:input
                    class="w-36"
                    type="date"
                    wire:model.change.live="dateFrom"
                />
                <flux:icon.arrow-long-right class="shrink-0 text-zinc-400" />
                <flux:input
                    class="w-36"
                    type="date"
                    wire:model.change.live="dateTo"
                />
            </div>

            <flux:separator vertical />

            <flux:button
                icon="x-mark"
                variant="ghost"
                wire:click="resetFilters"
            >
                Clear Filters
            </flux:button>

        </div>

        <flux:table>
            <flux:table.columns class="bg-blue-100 px-6 dark:bg-zinc-800">
                <flux:table.column class="p-0! px-4!">
                    Channel
                </flux:table.column>
                <flux:table.column
                    :direction="$sortDirection"
                    :sorted="$sortBy === 'description'"
                    sortable
                    wire:click="sort('description')"
                >
                    Action
                </flux:table.column>
                <flux:table.column class="w-44">
                    Subject
                </flux:table.column>
                <flux:table.column class="w-48">
                    Details
                </flux:table.column>
                <flux:table.column
                    :direction="$sortDirection"
                    :sorted="$sortBy === 'causer_id'"
                    class="w-40"
                    sortable
                    wire:click="sort('causer_id')"
                >
                    User
                </flux:table.column>
                <flux:table.column
                    :direction="$sortDirection"
                    :sorted="$sortBy === 'created_at'"
                    class="w-40"
                    sortable
                    wire:click="sort('created_at')"
                >
                    Date
                </flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->logs as $log)
                    <flux:table.row :key="$log->id">

                        <flux:table.cell class="p-0! px-4!">
                            <flux:badge
                                :color="match ($log->log_name) {
                                    'auth' => 'green',
                                    'violation' => 'blue',
                                    'violation_stage' => 'violet',
                                    'violation_delete_request' => 'red',
                                    'violation_update_request' => 'amber',
                                    'user' => 'zinc',
                                    default => 'zinc',
                                }"
                                rounded
                                size="sm"
                            >
                                {{ Str::title(str_replace('_', ' ', $log->log_name)) }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell>
                            <span class="text-sm font-medium">{{ $log->description }}</span>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($log->subject)
                                <div class="text-sm font-medium">{{ class_basename($log->subject_type) }}
                                    #{{ $log->subject_id }}</div>
                            @else
                                <span class="text-xs text-zinc-400">—</span>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($log->properties->isNotEmpty())
                                <flux:tooltip>
                                    <div class="max-w-xs space-y-0.5">
                                        @foreach ($log->properties->take(2) as $key => $value)
                                            <div class="truncate text-xs">
                                                <span class="font-medium text-zinc-500">{{ $key }}:</span>
                                                {{ is_array($value) ? json_encode($value) : $value }}
                                            </div>
                                        @endforeach
                                        @if ($log->properties->count() > 2)
                                            <div class="text-xs text-zinc-400">+{{ $log->properties->count() - 2 }} more
                                            </div>
                                        @endif
                                    </div>
                                    <flux:tooltip.content class="max-w-xs space-y-1">
                                        @foreach ($log->properties as $key => $value)
                                            <div class="text-xs">
                                                <span class="font-medium">{{ $key }}:</span>
                                                {{ is_array($value) ? json_encode($value) : $value }}
                                            </div>
                                        @endforeach
                                    </flux:tooltip.content>
                                </flux:tooltip>
                            @else
                                <span class="text-xs text-zinc-400">—</span>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($log->causer)
                                <div class="flex items-center gap-2">
                                    <flux:avatar name="{{ $log->causer->name }}" size="xs" />
                                    <div>
                                        <div class="text-sm font-medium leading-snug">{{ $log->causer->name }}</div>
                                        <div class="text-xs text-zinc-400">{{ $log->causer->role_label }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-zinc-400">System</span>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell class="whitespace-nowrap tabular-nums">
                            <div class="text-sm">{{ $log->created_at->format('M j, Y') }}</div>
                            <div class="text-xs text-zinc-400">{{ $log->created_at->format('h:i:s A') }}</div>
                        </flux:table.cell>

                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell class="py-12 text-center" colspan="6">
                            <div class="flex flex-col items-center gap-3">
                                <flux:icon class="h-14 w-14 text-zinc-300" name="clipboard-document-list" />
                                <div>
                                    <flux:text class="font-medium text-zinc-500">No logs found</flux:text>
                                    <flux:text class="mt-1 text-sm text-zinc-400">
                                        @if ($search || $channel || $dateFrom || $dateTo)
                                            Try adjusting your filters or search terms
                                        @else
                                            No activity has been recorded yet
                                        @endif
                                    </flux:text>
                                </div>
                                @if ($search || $channel || $dateFrom || $dateTo)
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

        <flux:pagination :paginator="$this->logs" class="p-4" />

    </x-table-wrapper>
</div>
