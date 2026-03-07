<?php

use App\Exports\AllViolationExport;
use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'Violation Management'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public $search = '';

    public $classification;

    public $dateFrom;

    public $dateTo;

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function violations()
    {
        $violations = Violation::with('stages')
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('classification_snapshot', $this->classification))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        // Get the student IDs on this page only
        $studentIds = $violations->getCollection()
            ->where('classification_snapshot', 'Minor')
            ->pluck('student_id')
            ->unique();

        // One query: all minor violations for those students, ordered
        $minorsByStudent = Violation::where('classification_snapshot', 'Minor')
            ->whereIn('student_id', $studentIds)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get(['id', 'student_id'])
            ->groupBy('student_id')
            ->map(fn ($group) => $group->pluck('id'));

        $violations->getCollection()->transform(function ($violation) use ($minorsByStudent) {
            $violation->minor_offense_number = $violation->classification === 'Minor'
                ? ($minorsByStudent[$violation->student_id]->search($violation->id) + 1)
                : null;

            return $violation;
        });

        return $violations;
    }

    #[Computed]
    public function classifications()
    {
        return Violation::distinct('classification')
            ->pluck('classification_snapshot')
            ->sortDesc();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'classification', 'dateFrom', 'dateTo']);
    }

    public function delete($violationId): void
    {
        $violation = Violation::findOrFail($violationId);
        $violation->delete();
    }

    public function updating($property, $value): void
    {
        if (in_array($property, ['search', 'classification', 'dateFrom', 'dateTo'])) {
            $this->resetPage();
        }
    }

    #[On('refresh-violation')]
    public function refreshTable(): void {}

    public function exportExcel()
    {
        $violations = Violation::with('stages')
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('classification', $this->classification))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();

        if ($violations->isEmpty()) {
            Toaster::error('No violations found to export.');

            return;
        }

        $studentIds = $violations->where('classification', 'Minor')
            ->pluck('student_id')
            ->unique();

        $minorsByStudent = Violation::where('classification', 'Minor')
            ->whereIn('student_id', $studentIds)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get(['id', 'student_id'])
            ->groupBy('student_id')
            ->map(fn ($group) => $group->pluck('id'));

        $violations->transform(function ($violation) use ($minorsByStudent) {
            $violation->minor_offense_number = $violation->classification === 'Minor'
                ? ($minorsByStudent[$violation->student_id]->search($violation->id) + 1)
                : null;

            return $violation;
        });

        Toaster::success('Violations Exported!');

        return Excel::download(
            new AllViolationExport($violations),
            'violations-'.date('Y-m-d').'.xlsx'
        );
    }
};
