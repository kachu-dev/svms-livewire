<?php

use App\Exports\AllViolationExport;
use App\Models\Student;
use App\Models\Violation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Masmerise\Toaster\Toaster;

new #[Layout('layouts::app', ['title' => 'Student Profile'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public $search = '';

    public $classification;

    public $dateFrom;

    public $dateTo;

    public $studentId;

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
            ->where('student_id', $this->studentId)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(9);

        // One query for all minor IDs instead of one per violation
        $allMinors = Violation::where('student_id', $this->studentId)
            ->where('classification_snapshot', 'Minor')
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id');

        $violations->getCollection()->transform(function ($violation) use ($allMinors) {
            $violation->minor_offense_number = $violation->classification_snapshot === 'Minor'
                ? $allMinors->search($violation->id) + 1
                : null;

            return $violation;
        });

        return $violations;
    }

    #[Computed]
    public function classifications()
    {
        return Violation::distinct()
            ->where('student_id', $this->studentId)
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

    #[Computed]
    public function student()
    {
        if (! $this->studentId) {
            return null;
        }

        $student = Student::select('studentid', 'firstname', 'lastname', 'grouptag')
            ->find($this->studentId);

        if ($student) {
            $student->photo = Cache::remember(
                "student:{$student->studentid}:photo",
                now()->addHours(6),
                function () use ($student): ?string {
                    $binary = DB::connection('imagedb')
                        ->table('pictures')
                        ->where('idnumber', $student->studentid)
                        ->where('idgroup', $student->grouptag)
                        ->value('idpicture');

                    if (! $binary) {
                        return null;
                    }

                    return 'data:image/jpeg;base64,'.base64_encode($binary);
                }
            );
        }

        return $student;
    }

    public function mount($studentId): void
    {
        $this->studentId = $studentId;

        if (! Student::where('studentid', $studentId)->exists()) {
            abort(404);
        }
    }

    #[On('refresh-violation')]
    public function refreshTable(): void {}

    public function exportExcel()
    {
        $violations = Violation::with('stages')
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('classification_snapshot', $this->classification))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->where('student_id', $this->studentId)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();

        if ($violations->isEmpty()) {
            Toaster::error('No violations found for '.$this->studentId.' to export.');
            return;
        }

        $allMinors = Violation::where('student_id', $this->studentId)
            ->where('classification_snapshot', 'Minor')
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id');

        $violations->transform(function ($violation) use ($allMinors) {
            $violation->minor_offense_number = $violation->classification_snapshot === 'Minor'
                ? $allMinors->search($violation->id) + 1
                : null;

            return $violation;
        });

        Toaster::success('Violations Exported!');

        return Excel::download(
            new AllViolationExport($violations),
            $this->student->studentid.'-violations-'.date('Y-m-d').'.xlsx'
        );
    }
};
