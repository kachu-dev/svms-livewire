<?php

use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Dashboard'])] class extends Component
{
    public string $period = 'today';

    public $dateFrom;

    public $dateTo;

    public string $filterProgram = '';

    public string $filterYear = '';

    public string $filterClassification = '';

    // ── Base Query ───────────────────────────────────────────────────────────

    protected function baseQuery(): Builder
    {
        $query = Violation::period($this->period, $this->dateFrom, $this->dateTo);

        if ($this->filterProgram !== '') {
            $query->where('st_program', $this->filterProgram);
        }

        if ($this->filterYear !== '') {
            $query->where('st_year', $this->filterYear);
        }

        if ($this->filterClassification !== '') {
            $query->where('classification', $this->filterClassification);
        }

        return $query;
    }

    // ── Filter Options ───────────────────────────────────────────────────────

    #[Computed]
    public function programs(): array
    {
        return Violation::query()
            ->selectRaw('DISTINCT st_program')
            ->whereNotNull('st_program')
            ->orderBy('st_program')
            ->pluck('st_program')
            ->toArray();
    }

    // ── Stat Cards ───────────────────────────────────────────────────────────

    #[Computed]
    public function total(): int
    {
        return $this->baseQuery()->total();
    }

    #[Computed]
    public function pending(): int
    {
        return $this->baseQuery()->pending();
    }

    #[Computed]
    public function resolved(): int
    {
        return $this->baseQuery()->resolved();
    }

    #[Computed]
    public function minor(): int
    {
        return $this->baseQuery()->minor();
    }

    #[Computed]
    public function majorSuspension(): int
    {
        return $this->baseQuery()->majorSuspension();
    }

    #[Computed]
    public function majorDismissal(): int
    {
        return $this->baseQuery()->majorDismissal();
    }

    #[Computed]
    public function majorExpulsion(): int
    {
        return $this->baseQuery()->majorExpulsion();
    }

    // ── Change Indicators ────────────────────────────────────────────────────

    #[Computed]
    public function totalChange(): ?string
    {
        return $this->formatChange($this->total, $this->previousQuery()->count());
    }

    #[Computed]
    public function pendingChange(): ?string
    {
        return $this->formatChange($this->pending, $this->previousQuery()->where('status', '!=', 'Complete')->count());
    }

    #[Computed]
    public function resolvedChange(): ?string
    {
        return $this->formatChange($this->resolved, $this->previousQuery()->where('status', 'Complete')->count());
    }

    #[Computed]
    public function minorChange(): ?string
    {
        return $this->formatChange($this->minor, $this->previousQuery()->where('classification', 'Minor')->count());
    }

    #[Computed]
    public function majorSuspensionChange(): ?string
    {
        return $this->formatChange(
            $this->majorSuspension,
            $this->previousQuery()->where('classification', 'Major - Suspension')->count()
        );
    }

    #[Computed]
    public function majorDismissalChange(): ?string
    {
        return $this->formatChange(
            $this->majorDismissal,
            $this->previousQuery()->where('classification', 'Major - Dismissal')->count()
        );
    }

    #[Computed]
    public function majorExpulsionChange(): ?string
    {
        return $this->formatChange(
            $this->majorExpulsion,
            $this->previousQuery()->where('classification', 'Major - Expulsion')->count()
        );
    }

    // ── Chart Data ───────────────────────────────────────────────────────────

    #[Computed]
    public function violationsOverTime(): array
    {
        // Determine format based on period or date range span
        if ($this->dateFrom && $this->dateTo) {
            $days = Carbon::parse($this->dateFrom)->diffInDays(Carbon::parse($this->dateTo)) + 1;

            $format = match (true) {
                $days <= 2 => '%H:00',      // hourly
                $days <= 31 => '%b %d',      // Mar 01, Mar 02...
                $days <= 365 => '%b %Y',      // Mar 2025, Apr 2025...
                default => '%Y',         // 2024, 2025...
            };
        } else {
            $format = match ($this->period) {
                'today' => '%H:00',
                'week' => '%a',
                'month' => '%d',
                'year' => '%b',
                'all' => '%Y',
                default => '%b %Y',
            };
        }

        $results = $this->baseQuery()
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as label, COUNT(*) as count")
            ->groupBy('label')
            ->orderByRaw('MIN(created_at)')
            ->pluck('count', 'label');

        if ($this->period === 'year') {
            $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
            $filled = $months->mapWithKeys(fn ($m) => [$m => $results->get($m, 0)]);

            return [
                'labels' => $filled->keys()->toArray(),
                'data' => $filled->values()->toArray(),
            ];
        }

        return [
            'labels' => $results->keys()->toArray(),
            'data' => $results->values()->toArray(),
        ];
    }

    #[Computed]
    public function byStatus(): array
    {
        return [
            'pending' => $this->baseQuery()->pending(),
            'resolved' => $this->baseQuery()->resolved(),
        ];
    }

    #[Computed]
    public function byClassification(): array
    {
        return [
            'minor' => $this->baseQuery()->minor(),
            'suspension' => $this->baseQuery()->majorSuspension(),
            'dismissal' => $this->baseQuery()->majorDismissal(),
            'expulsion' => $this->baseQuery()->majorExpulsion(),
        ];
    }

    #[Computed]
    public function byProgram(): array
    {
        $results = $this->baseQuery()
            ->selectRaw('st_program as label, COUNT(*) as count')
            ->whereNotNull('st_program')
            ->groupBy('st_program')
            ->orderByDesc('count')
            ->pluck('count', 'label');

        return [
            'labels' => $results->keys()->toArray(),
            'data' => $results->values()->toArray(),
        ];
    }

    #[Computed]
    public function byYearLevel(): array
    {
        $levels = ['1', '2', '3', '4'];

        $results = $this->baseQuery()
            ->selectRaw('st_year as label, COUNT(*) as count')
            ->whereNotNull('st_year')
            ->whereIn('st_year', $levels)
            ->groupBy('st_year')
            ->pluck('count', 'label');

        $filled = collect($levels)->mapWithKeys(fn ($y) => [$y => $results->get($y, 0)]);

        return [
            'labels' => $filled->keys()->toArray(),
            'data' => $filled->values()->toArray(),
        ];
    }

    #[Computed]
    public function byViolationType(): array
    {
        $results = $this->baseQuery()
            ->selectRaw('type_code, type_name, COUNT(*) as count')
            ->whereNotNull('type_code')
            ->groupBy('type_code', 'type_name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return [
            'labels' => $results->map(fn ($r) => $r->type_code.' – '.str($r->type_name)->limit(30))->toArray(),
            'names' => $results->pluck('type_name')->toArray(),
            'data' => $results->pluck('count')->toArray(),
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    protected function previousQuery(): Builder
    {
        $query = Violation::query();

        if ($this->dateFrom && $this->dateTo) {
            $from = Carbon::parse($this->dateFrom);
            $to = Carbon::parse($this->dateTo);
            $days = $from->diffInDays($to) + 1;

            return $query->whereBetween('created_at', [
                $from->copy()->subDays($days)->startOfDay(),
                $from->copy()->subDay()->endOfDay(),
            ]);
        }

        return match ($this->period) {
            'today' => $query->whereDate('created_at', today()->subDay()),
            'week' => $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year),
            'year' => $query->whereYear('created_at', now()->subYear()->year),
            default => $query->whereRaw('0 = 1'),
        };
    }

    protected function formatChange(int $current, int $previous): ?string
    {
        if ($previous === 0) {
            return $current > 0 ? '+'.$current : null;
        }

        $diff = $current - $previous;
        $pct = round(($diff / $previous) * 100);

        return ($diff >= 0 ? '+' : '').$pct.'%';
    }

    protected function dispatchChartUpdates(): void
    {
        unset(
            $this->violationsOverTime,
            $this->byStatus,
            $this->byClassification,
            $this->byProgram,
            $this->byViolationType,
            $this->byYearLevel,
            $this->total,
            $this->pending,
            $this->resolved,
            $this->minor,
            $this->majorSuspension,
            $this->majorDismissal,
            $this->majorExpulsion,
        );

        $this->dispatch('violations-over-time-updated', ...$this->violationsOverTime);
        $this->dispatch('by-status-updated', ...$this->byStatus);
        $this->dispatch('by-classification-updated', ...$this->byClassification);
        $this->dispatch('by-program-updated', ...$this->byProgram);
        $this->dispatch('by-violation-type-updated', ...$this->byViolationType);
        $this->dispatch('by-year-level-updated', ...$this->byYearLevel);
    }

    // ── Lifecycle ────────────────────────────────────────────────────────────

    public function updatedPeriod(): void
    {
        $this->dateFrom = null;
        $this->dateTo = null;
        $this->dispatchChartUpdates();
    }

    public function updatedDateFrom(): void
    {
        $this->period = '';
        $this->dispatchChartUpdates();
    }

    public function updatedDateTo(): void
    {
        $this->period = '';
        $this->dispatchChartUpdates();
    }

    public function updatedFilterProgram(): void
    {
        $this->dispatchChartUpdates();
    }

    public function updatedFilterYear(): void
    {
        $this->dispatchChartUpdates();
    }

    public function updatedFilterClassification(): void
    {
        $this->dispatchChartUpdates();
    }

    public function resetFilters(): void
    {
        $this->reset(['dateFrom', 'dateTo', 'filterProgram', 'filterYear', 'filterClassification']);
        $this->dispatchChartUpdates();
    }
};
