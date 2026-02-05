<?php

use App\Models\Student;
use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $studentId;

    public $selectedTypeLabel;

    public $selectedRemarkLabel;

    #[Computed]
    public function student()
    {
        return $this->studentId ? Student::find($this->studentId) : null;
    }

    #[On('to-confirm')]
    public function confirm($studentId, $violationType, $violationRemark): void
    {
        $this->studentId = $studentId;
        $this->selectedTypeLabel = $violationType;
        $this->selectedRemarkLabel = $violationRemark;

        $this->modal('confirm-violation')->show();
    }

    public function save(): void
    {
        Violation::create([
            'student_id' => $this->studentId,
            'student_name' => $this->student()->name,
            'type' => $this->selectedTypeLabel,
            'remarks' => $this->selectedRemarkLabel,
        ]);
        $this->dispatch('violation-created');
        $this->modal('confirm-violation')->close();
    }
};
