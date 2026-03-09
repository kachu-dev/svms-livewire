<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $studentId;

    public $notFound = false;

    public $selectedTypeId;

    public $selectedTypeCode;

    public $selectedTypeName;

    public $selectedTypeLabel;

    public $selectedTypeClassification;

    public $selectedRemarkId;

    public $selectedRemarkLabel;

    public function confirmViolation(): void
    {
        $this->validate([
            'studentId' => 'required',
            'selectedTypeLabel' => 'required',
        ]);

        $this->dispatch('to-confirm',
            studentId: $this->studentId,
            typeId: $this->selectedTypeId,
            typeCode: $this->selectedTypeCode,
            typeName: $this->selectedTypeName,
            typeLabel: $this->selectedTypeLabel,
            typeClassification: $this->selectedTypeClassification,
            remarkId: $this->selectedRemarkId,
            remarkLabel: $this->selectedRemarkLabel
        );
    }

    #[On('student-found')]
    public function studentFound($studentId): void
    {
        $this->studentId = $studentId;
        $this->notFound = false;

        $this->reset(['selectedTypeId', 'selectedTypeCode', 'selectedTypeName',
            'selectedTypeLabel', 'selectedRemarkId', 'selectedRemarkLabel']);

        $this->dispatch('reset-type');

        $this->resetValidation();
    }

    #[On('student-not-found')]
    public function studentNotFound(): void
    {
        $this->studentId = null;
        $this->notFound = true;

        $this->reset(['selectedTypeId', 'selectedTypeCode', 'selectedTypeName',
            'selectedTypeLabel', 'selectedRemarkId', 'selectedRemarkLabel']);

        $this->resetValidation();
    }

    #[On('type-selected')]
    public function setType($id, $code, $name, $classification): void
    {
        $this->selectedTypeId = $id;
        $this->selectedTypeCode = $code;
        $this->selectedTypeName = $name;
        $this->selectedTypeLabel = "{$code} - {$name}";
        $this->selectedTypeClassification = $classification;

        $this->reset(['selectedRemarkId', 'selectedRemarkLabel']);
    }

    #[On('remark-selected')]
    public function setRemark($remarkId, $remarkLabel): void
    {
        $this->selectedRemarkId = $remarkId;
        $this->selectedRemarkLabel = $remarkLabel;
    }

    #[On('custom-remark')]
    public function setCustomRemark($remark): void
    {
        $this->selectedRemarkId = null;
        $this->selectedRemarkLabel = $remark;
    }

    #[On('violation-created')]
    public function resetInputs(): void
    {
        $this->reset([
            'studentId', 'notFound', 'selectedTypeId', 'selectedTypeCode', 'selectedTypeName',
            'selectedTypeLabel', 'selectedRemarkId', 'selectedRemarkLabel',
        ]);
        $this->resetValidation();
    }
};
