<?php

use App\Models\Student;
use App\Models\ViolationRemark;
use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $studentId;

    public $notFound = false;

    public $selectedTypeId;

    public $selectedTypeLabel;

    public $selectedRemarkId;

    public $selectedRemarkLabel;

    protected $rules = [
        'selectedTypeLabel' => 'required',
        'selectedRemarkLabel' => 'required',
    ];

    public function confirm()
    {
        $this->validate();

        $this->dispatch('to-confirm',
            studentId: $this->studentId,
            violationType: $this->selectedTypeLabel,  // Send label, not ID
            violationRemark: $this->selectedRemarkLabel  // Send label, not ID
        );
    }

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

        $this->selectedRemarkLabel = '';
        $this->selectedTypeLabel = '';
    }

    #[On('student-not-found')]
    public function studentNotFound(): void
    {
        $this->studentId = null;
        $this->notFound = true;

        $this->selectedRemarkLabel = '';
        $this->selectedTypeLabel = '';
    }

    #[On('type-selected')]
    public function setType($violationId): void
    {
        $violation = ViolationType::findOrFail($violationId);

        $this->selectedTypeId = $violation->id;
        $this->selectedTypeLabel = "{$violation->code} — {$violation->name}";
    }

    #[On('remark-selected')]
    public function setRemark($remarkId): void
    {
        if ($remarkId === null) {
            $this->selectedRemarkId = null;
            $this->selectedRemarkLabel = 'None';
        } else {
            $remark = ViolationRemark::findOrFail($remarkId);

            $this->selectedRemarkId = $remark->id;
            $this->selectedRemarkLabel = $remark->label;
        }
    }

    #[On('violation-created')]
    public function resetInputs(): void
    {
        $this->reset([
            'studentId',
            'notFound',
            'selectedTypeId',
            'selectedTypeLabel',
            'selectedRemarkId',
            'selectedRemarkLabel',
        ]);

        $this->resetValidation();
    }
};
