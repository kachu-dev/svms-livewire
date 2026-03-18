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

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public string $search = '';

    public ?string $classification = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public int|string $studentId;

    public function mount(int|string $studentId): void
    {
        $this->studentId = $studentId;

        if (! Student::where('studentid', $studentId)->exists()) {
            abort(404);
        }
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
        if (in_array($property, ['search', 'classification', 'dateFrom', 'dateTo'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'classification', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    #[Computed]
    public function student()
    {
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

                    return $binary
                        ? 'data:image/jpeg;base64,'.base64_encode($binary)
                        : null;
                }
            );
        }

        return $student;
    }

    #[Computed]
    public function violations()
    {
        $violations = Violation::with(['stages', 'student', 'recordedBy'])
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('classification', $this->classification))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->where('student_id', $this->studentId)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(9);

        $allMinors = Violation::where('student_id', $this->studentId)
            ->where('classification', 'Minor')
            ->where('is_active', true)
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id');

        $violations->getCollection()->transform(function ($violation) use ($allMinors) {
            $violation->minor_offense_number = $violation->classification === 'Minor'
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
            ->where('is_active', true)
            ->pluck('classification')
            ->sortDesc();
    }

    public function delete(int $violationId): void
    {
        Violation::findOrFail($violationId)->delete();
    }

    public function exportExcel()
    {
        $violations = Violation::with('stages')
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->where('is_active', true)
            ->when($this->classification, fn ($q) => $q->where('classification', $this->classification))
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
            ->where('classification', 'Minor')
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id');

        $violations->transform(function ($violation) use ($allMinors) {
            $violation->minor_offense_number = $violation->classification === 'Minor'
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

    #[On('refresh-violation')]
    public function refreshTable(): void {}
};
