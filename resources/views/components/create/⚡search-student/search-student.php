<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component
{
    public $studentId = '';

    public $student = null;

    public $notFound = false;

    public function findStudent(): void
    {
        $this->validate(['studentId' => 'required|string']);

        $this->student = Student::find($this->studentId);
        $this->notFound = ! $this->student;

        if ($this->student) {
            $this->dispatch('student-found', studentId: $this->student->id);
        } else {
            $this->dispatch('student-not-found');
        }

        $this->reset([
            'student',
            'studentId',
            'notFound',
        ]);
    }
};
