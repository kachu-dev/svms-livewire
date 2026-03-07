<?php

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $studentId;

    public $notFound = false;

    #[Computed]
    public function student()
    {
        if (! $this->studentId) {
            return null;
        }

        $student = Student::select('studentid', 'firstname', 'lastname', 'grouptag')
            ->find($this->studentId);

        if ($student) {
            try {
                $image = DB::connection('imagedb')
                    ->table('pictures')
                    ->where('idnumber', $student->studentid)
                    ->where('idgroup', $student->grouptag)
                    ->value('idpicture');

                $student->photo = $image ? base64_encode((string) $image) : null;
            } catch (\Throwable $e) {
                $student->photo = null; // show placeholder if imagedb is unavailable
            }
        }

        return $student;
    }

    #[On('student-found')]
    public function studentFound($studentId): void
    {
        $this->studentId = $studentId;
        $this->notFound = false;
    }

    #[On('student-not-found')]
    public function studentNotFound(): void
    {
        $this->studentId = null;
        $this->notFound = true;
    }

    #[On('violation-created')]
    public function resetDisplay(): void
    {
        $this->reset(['studentId', 'notFound']);
    }
};
