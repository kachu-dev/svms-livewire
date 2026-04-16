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

        $student = Student::select('studentid', 'firstname', 'lastname', 'mi', 'grouptag')
            ->find($this->studentId);

        // get student image, cache if first time
        if ($student) {
            $student->photo = Cache::remember(
                "student:{$student->studentid}:photo",
                now()->addHours(6),
                function () use ($student): ?string {
                    try {
                        $binary = DB::connection('imagedb')
                            ->table('pictures')
                            ->where('idnumber', $student->studentid)
                            ->where('idgroup', $student->grouptag)
                            ->value('idpicture');

                        return $binary
                            ? 'data:image/jpeg;base64,'.base64_encode((string) $binary)
                            : null;
                    } catch (Throwable) {
                        return null;
                    }
                }
            );
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
