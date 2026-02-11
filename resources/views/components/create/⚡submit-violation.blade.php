<?php

use App\Models\Student;
use App\Models\ViolationRemark;
use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $studentId;

    public $notFound = false;

    public $selectedTypeId;

    public $selectedTypeLabel;

    public $selectedRemarkId;

    public $selectedRemarkLabel;

    public function confirmViolation(): void
    {
        $this->validate([
            'studentId' => 'required',
        ]);

        if ($this->selectedRemarkId == null && $this->selectedRemarkLabel) {
            $this->dispatch('to-confirm',
                studentId: $this->studentId,
                typeId: $this->selectedTypeId,
                remarkId: null,
                remarkLabel: $this->selectedRemarkLabel
            );
        } else {
            $this->dispatch('to-confirm',
                studentId: $this->studentId,
                typeId: $this->selectedTypeId,
                remarkId: $this->selectedRemarkId
            );
        }
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

        $this->reset(['selectedTypeId', 'selectedTypeLabel', 'selectedRemarkId', 'selectedRemarkLabel']);

        $this->resetValidation();
    }

    #[On('student-not-found')]
    public function studentNotFound(): void
    {
        $this->studentId = null;
        $this->notFound = true;

        $this->reset(['selectedTypeId', 'selectedTypeLabel', 'selectedRemarkId', 'selectedRemarkLabel']);

        $this->resetValidation();
    }

    #[On('type-selected')]
    public function setType($violationId): void
    {
        $type = ViolationType::findOrFail($violationId);

        $this->selectedTypeId = $type->id;
        $this->selectedTypeLabel = "{$type->code} — {$type->name}";

        $this->reset(['selectedRemarkId', 'selectedRemarkLabel']);
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

    #[On('custom-remark')]
    public function setCustomRemark($remark): void
    {
        $this->selectedRemarkId = null;
        $this->selectedRemarkLabel = $remark;
    }

    #[On('violation-created')]
    public function resetInputs(): void
    {
        $this->reset(['studentId', 'notFound', 'selectedTypeId', 'selectedTypeLabel', 'selectedRemarkId', 'selectedRemarkLabel']);

        $this->resetValidation();
    }
};
?>

<x-card header="Violation Details" icon="exclamation-triangle">
    <div class="flex flex-col gap-4">
        @if ($this->student)
            <flux:modal.trigger name="set-violation">
                <flux:input
                    readonly
                    label="Type of Violation"
                    size="lg"
                    wire:model="selectedTypeLabel"
                    placeholder="Click to choose violation"
                />
            </flux:modal.trigger>
        @else
            <flux:input
                readonly
                disabled
                label="Type of Violation"
                size="lg"
                wire:model="selectedTypeLabel"
                placeholder="Click to choose violation"
            />
        @endif

        @if ($this->selectedTypeId)
            <flux:modal.trigger name="set-remark">
                <flux:input
                    readonly
                    label="Remarks"
                    size="lg"
                    wire:model="selectedRemarkLabel"
                    placeholder="Remarks"
                />
            </flux:modal.trigger>
        @else
            <flux:input
                readonly
                disabled
                label="Remarks"
                size="lg"
                wire:model="selectedRemarkLabel"
                placeholder="Remarks"
            />
        @endif

        <div class="mt-4 flex gap-2">
            <flux:button
                wire:click="confirmViolation()"
                type="submit"
                variant="primary"
                icon="paper-airplane"
                class="flex-1"
                size="lg"
                wire:loading.attr="disabled"
                wire:target="confirmViolation"
            >
                <span wire:loading.remove wire:target="confirmViolation">Submit Violation</span>
                <span wire:loading wire:target="confirmViolation">Submitting...</span>
            </flux:button>
        </div>
    </div>
</x-card>
