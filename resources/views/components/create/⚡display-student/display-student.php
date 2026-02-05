<?php

use App\Models\Student;
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
        return $this->studentId ? Student::find($this->studentId) : null;
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
        $this->reset([
            'studentId',
            'notFound',
        ]);
    }
};
