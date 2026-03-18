<?php

use App\Helpers\SchoolYearHelper;
use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Dashboard'])] class extends Component
{
    public string $period = 'today';

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public string $filterProgram = '';

    public string $filterYear = '';

    public string $filterClassification = '';

    public string $schoolYear = '';

    // ── Lifecycle ────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->schoolYear = SchoolYearHelper::current();
    }

    public function updatedPeriod(): void
    {
        $this->dateFrom = $this->dateTo = null;
        $this->refresh();
    }

    public function updatedDateFrom(): void
    {
        $this->period = '';
        $this->refresh();
    }

    public function updatedDateTo(): void
    {
        $this->period = '';
        $this->refresh();
    }

    public function updatedSchoolYear(): void
    {
        $this->refresh();
    }

    public function updatedFilterProgram(): void
    {
        $this->refresh();
    }

    public function updatedFilterYear(): void
    {
        $this->refresh();
    }

    public function updatedFilterClassification(): void
    {
        $this->refresh();
    }

    public function resetFilters(): void
    {
        $this->reset(['dateFrom', 'dateTo', 'filterProgram', 'filterYear', 'filterClassification']);
        $this->period = 'today';
        $this->schoolYear = SchoolYearHelper::current();
        $this->refresh();
    }

    // ── Queries ──────────────────────────────────────────────────────────────

    protected function baseQuery(): Builder
    {
        return Violation::period($this->period, $this->dateFrom, $this->dateTo)
            ->when($this->schoolYear, fn ($q) => $q->where('school_year', $this->schoolYear))
            ->when($this->filterProgram, fn ($q) => $q->where('st_program', $this->filterProgram))
            ->when($this->filterYear, fn ($q) => $q->where('st_year', $this->filterYear))
            ->when($this->filterClassification, fn ($q) => $q->where('classification', $this->filterClassification))
            ->where('is_active', true);
    }

    protected function previousQuery(): Builder
    {
        $q = Violation::query();

        if ($this->dateFrom && $this->dateTo) {
            $from = Carbon::parse($this->dateFrom);
            $days = $from->diffInDays(Carbon::parse($this->dateTo)) + 1;

            return $q->whereBetween('created_at', [
                $from->copy()->subDays($days)->startOfDay(),
                $from->copy()->subDay()->endOfDay(),
            ]);
        }

        return match ($this->period) {
            'today' => $q->whereDate('created_at', today()->subDay()),
            'week' => $q->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]),
            'month' => $q->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year),
            'year' => $q->whereYear('created_at', now()->subYear()->year),
            default => $q->whereRaw('0 = 1'),
        };
    }

    // ── Filter Options ───────────────────────────────────────────────────────

    #[Computed]
    public function programs(): array
    {
        return Violation::query()->selectRaw('DISTINCT st_program')
            ->whereNotNull('st_program')->orderBy('st_program')
            ->pluck('st_program')->toArray();
    }

    #[Computed]
    public function years(): array
    {
        return Violation::query()->selectRaw('DISTINCT st_year')
            ->whereNotNull('st_year')->orderBy('st_year')
            ->pluck('st_year')->toArray();
    }

    #[Computed]
    public function availableYears(): array
    {
        return Violation::select('school_year')->distinct()
            ->orderByDesc('school_year')->pluck('school_year')->toArray();
    }

    // ── Stats ─────────────────────────────────────────────────────────────────
    // Single computed that runs all counts in one pass, keyed by name.

    #[Computed]
    public function stats(): array
    {
        $base = $this->baseQuery();
        $prev = $this->previousQuery();

        $counts = [
            'total' => $base->count(),
            'pending' => (clone $base)->pending()->count(),
            'resolved' => (clone $base)->resolved()->count(),
            'minor' => (clone $base)->minor()->count(),
            'majorSuspension' => (clone $base)->majorSuspension()->count(),
            'majorDismissal' => (clone $base)->majorDismissal()->count(),
            'majorExpulsion' => (clone $base)->majorExpulsion()->count(),
        ];

        $prevCounts = [
            'total' => $prev->count(),
            'pending' => (clone $prev)->where('status', '!=', 'Complete')->count(),
            'resolved' => (clone $prev)->where('status', 'Complete')->count(),
            'minor' => (clone $prev)->where('classification', 'Minor')->count(),
            'majorSuspension' => (clone $prev)->where('classification', 'Major - Suspension')->count(),
            'majorDismissal' => (clone $prev)->where('classification', 'Major - Dismissal')->count(),
            'majorExpulsion' => (clone $prev)->where('classification', 'Major - Expulsion')->count(),
        ];

        return collect($counts)->map(fn (int $val, $key) => [
            'value' => $val,
            'change' => $this->formatChange($val, $prevCounts[$key]),
        ])->toArray();
    }

    // ── Chart Data ───────────────────────────────────────────────────────────

    #[Computed]
    public function violationsOverTime(): array
    {
        $format = $this->resolveTimeFormat();

        $results = $this->baseQuery()
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as label, COUNT(*) as count")
            ->groupBy('label')
            ->orderByRaw('MIN(created_at)')
            ->pluck('count', 'label');

        if ($this->period === 'year') {
            $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
            $filled = $months->mapWithKeys(fn ($m) => [$m => $results->get($m, 0)]);

            return ['labels' => $filled->keys()->toArray(), 'data' => $filled->values()->toArray()];
        }

        return ['labels' => $results->keys()->toArray(), 'data' => $results->values()->toArray()];
    }

    #[Computed]
    public function byStatus(): array
    {
        return [
            'pending' => $this->baseQuery()->pending()->count(),
            'resolved' => $this->baseQuery()->resolved()->count(),
        ];
    }

    #[Computed]
    public function byClassification(): array
    {
        return [
            'minor' => $this->baseQuery()->minor()->count(),
            'suspension' => $this->baseQuery()->majorSuspension()->count(),
            'dismissal' => $this->baseQuery()->majorDismissal()->count(),
            'expulsion' => $this->baseQuery()->majorExpulsion()->count(),
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

        return ['labels' => $results->keys()->toArray(), 'data' => $results->values()->toArray()];
    }

    #[Computed]
    public function byYearLevel(): array
    {
        $levels = ['1', '2', '3', '4'];

        $results = $this->baseQuery()
            ->selectRaw('st_year as label, COUNT(*) as count')
            ->whereNotNull('st_year')->whereIn('st_year', $levels)
            ->groupBy('st_year')
            ->pluck('count', 'label');

        $filled = collect($levels)->mapWithKeys(fn ($y) => [$y => $results->get($y, 0)]);

        return ['labels' => $filled->keys()->toArray(), 'data' => $filled->values()->toArray()];
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
            'labels' => $results->pluck('type_code')->toArray(),
            'names' => $results->pluck('type_name')->toArray(),
            'data' => $results->pluck('count')->toArray(),
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    protected function resolveTimeFormat(): string
    {
        if ($this->dateFrom && $this->dateTo) {
            $days = Carbon::parse($this->dateFrom)->diffInDays(Carbon::parse($this->dateTo)) + 1;

            return match (true) {
                $days <= 2 => '%H:00',
                $days <= 31 => '%b %d',
                $days <= 365 => '%b %Y',
                default => '%Y',
            };
        }

        return match ($this->period) {
            'today' => '%H:00',
            'week' => '%a',
            'month' => '%d',
            'year' => '%b',
            'all' => '%Y',
            default => '%b %Y',
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

    protected function refresh(): void
    {
        // Clear all memoised computeds at once
        unset(
            $this->stats,
            $this->violationsOverTime,
            $this->byStatus,
            $this->byClassification,
            $this->byProgram,
            $this->byYearLevel,
            $this->byViolationType,
        );

        $this->dispatch('chart-data-updated', [
            'violationsOverTime' => $this->violationsOverTime,
            'byStatus' => $this->byStatus,
            'byClassification' => $this->byClassification,
            'byProgram' => $this->byProgram,
            'byYearLevel' => $this->byYearLevel,
            'byViolationType' => $this->byViolationType,
        ]);
    }
};
