<?php

use App\Exports\AllViolationExport;
use App\Helpers\SchoolYearHelper;
use App\Models\Violation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Masmerise\Toaster\Toaster;

new #[Layout('layouts::app', ['title' => 'Violation Management'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public string $search = '';

    public ?string $classification = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public ?string $schoolYear = null;

    public string $password = '';

    public bool $passwordConfirmed = false;

    public string $newYearFrom = '';

    public string $newYearTo = '';

    public function mount(): void
    {
        $this->schoolYear = SchoolYearHelper::current();
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'classification', 'dateFrom', 'dateTo', 'schoolYear'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'classification', 'dateFrom', 'dateTo', 'schoolYear']);
        $this->resetPage();
    }

    private function baseQuery(): Builder
    {
        return Violation::where('status', '!=', 'Complete')
            ->when($this->schoolYear, fn ($q) => $q->where('school_year', $this->schoolYear))
            ->where('is_active', true)
            ->when($this->search, fn (Builder $q) => $q->search($this->search))
            ->when($this->classification, fn (Builder $q) => $q->where('classification', $this->classification))
            ->when($this->dateFrom, fn (Builder $q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn (Builder $q) => $q->whereDate('created_at', '<=', $this->dateTo));
    }

    private function applyMinorOffenseNumbers($collection): void
    {
        $studentIds = $collection
            ->where('classification', 'Minor')
            ->where('is_active', true)
            ->pluck('student_id')
            ->unique();

        if ($studentIds->isEmpty()) {
            return;
        }

        $minorsByStudent = Violation::where('classification', 'Minor')
            ->whereIn('student_id', $studentIds)
            ->when($this->schoolYear, fn ($q) => $q->where('school_year', $this->schoolYear))
            ->where('is_active', true)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get(['id', 'student_id'])
            ->groupBy('student_id')
            ->map(fn ($group) => $group->pluck('id'));

        $collection->transform(function ($violation) use ($minorsByStudent) {
            if ($violation->classification !== 'Minor') {
                $violation->minor_offense_number = null;

                return $violation;
            }

            $studentViolations = $minorsByStudent[$violation->student_id] ?? collect();
            $position = $studentViolations->search($violation->id);

            $violation->minor_offense_number = $position !== false ? $position + 1 : null;

            return $violation;
        });
    }

    #[Computed]
    public function violations()
    {
        $sortColumn = $this->sortBy === 'count' ? 'created_at' : $this->sortBy;

        $violations = $this->baseQuery()
            ->with(['stages', 'recordedBy'])
            ->orderBy($sortColumn, $this->sortDirection)
            ->paginate(9);

        $this->applyMinorOffenseNumbers($violations->getCollection());

        if ($this->sortBy === 'count') {
            $sorted = $violations->getCollection()->sortBy(
                'minor_offense_number',
                SORT_REGULAR,
                $this->sortDirection === 'desc'
            )->values();

            $violations->setCollection($sorted);
        }

        return $violations;
    }

    #[Computed]
    public function classifications()
    {
        return cache()->remember(
            "violation_classifications_{$this->schoolYear}",
            60,
            fn () => Violation::distinct()
                ->where('status', '!=', 'Complete')
                ->where('school_year', $this->schoolYear)
                ->where('is_active', true)
                ->pluck('classification')
                ->sortDesc()
        );
    }

    #[Computed]
    public function availableYears(): array
    {
        return Violation::select('school_year')
            ->distinct()
            ->where('is_active', true)
            ->orderByDesc('school_year')
            ->pluck('school_year')
            ->toArray();
    }

    public function delete(int $violationId): void
    {
        Violation::findOrFail($violationId)->delete();
        cache()->forget("violation_classifications_{$this->schoolYear}");
    }

    public function exportExcel()
    {
        $violations = $this->baseQuery()
            ->with('stages')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();

        if ($violations->isEmpty()) {
            Toaster::error('No violations found to export.');

            return;
        }

        $this->applyMinorOffenseNumbers($violations);

        Toaster::success('Violations Exported!');

        return Excel::download(
            new AllViolationExport($violations),
            'violations-'.date('Y-m-d').'.xlsx'
        );
    }

    #[On('refresh-violation')]
    public function refreshTable(): void {}

    public function confirmPassword(): void
    {
        $this->validate([
            'password' => 'required',
        ]);

        if (! Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'Incorrect password.');

            return;
        }

        $this->password = '';
        $this->passwordConfirmed = true;

        // Pre-fill inputs with current year parts
        [$from, $to] = explode('-', (string) $this->schoolYear);
        $this->newYearFrom = $from;
        $this->newYearTo = $to;
    }

    public function updateSchoolYear(): void
    {
        $this->validate([
            'newYearFrom' => ['required', 'digits:4', 'integer'],
            'newYearTo' => ['required', 'digits:4', 'integer', 'gt:newYearFrom'],
        ]);

        $newYear = $this->newYearFrom.'-'.$this->newYearTo;

        DB::table('settings')
            ->where('key', 'school_year')
            ->update(['value' => $newYear]);

        cache()->forget('setting_school_year');

        $this->schoolYear = $newYear;
        $this->resetPasswordConfirmation();
        $this->modal('update-school-year')->close();
        $this->dispatch('close-modal', 'update-school-year');

        Toaster::success("School year updated to {$newYear}!");
    }

    public function resetPasswordConfirmation(): void
    {
        $this->password = '';
        $this->passwordConfirmed = false;
        $this->newYearFrom = '';
        $this->newYearTo = '';
    }
};
