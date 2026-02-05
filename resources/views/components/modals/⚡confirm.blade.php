<?php

use App\Models\Student;
use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {

    public $studentId;
    public $selectedTypeLabel;
    public $selectedRemarkLabel;

    #[Computed]
    public function student()
    {
        return $this->studentId ? Student::find($this->studentId) : null;
    }

    #[On('to-confirm')]
    public function confirm($studentId, $violationType, $violationRemark)
    {
        $this->studentId = $studentId;
        $this->selectedTypeLabel = $violationType;
        $this->selectedRemarkLabel = $violationRemark;

        $this->modal('confirm-violation')->show();
    }

    public function save()
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
?>

<flux:modal name="confirm-violation" class="w-full max-w-md">
    <div class="space-y-6">
        {{-- Warning Header --}}
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                <flux:icon name="exclamation-triangle" class="h-6 w-6 text-red-600 dark:text-red-400"/>
            </div>
            <flux:heading size="lg" class="mt-4">Confirm Violation Submission</flux:heading>
            <flux:subheading class="mt-2">
                Please review the details carefully before submitting
            </flux:subheading>
        </div>

        {{-- Violation Details --}}
        <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-4 space-y-3">
            <div class="flex justify-between items-start border-b border-zinc-200 dark:border-zinc-700 pb-2">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Student Name</flux:text>
                <flux:text class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 text-right">
                    {{ $this->student?->name ?? 'N/A' }}
                </flux:text>
            </div>

            <div class="flex justify-between items-start border-b border-zinc-200 dark:border-zinc-700 pb-2">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Student ID</flux:text>
                <flux:text class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $this->student?->id ?? 'N/A' }}
                </flux:text>
            </div>

            <div class="flex justify-between items-start border-b border-zinc-200 dark:border-zinc-700 pb-2">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Violation Type</flux:text>
                <flux:text class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 text-right max-w-xs">
                    {{ $selectedTypeLabel ?: 'N/A' }}
                </flux:text>
            </div>

            <div class="flex justify-between items-start">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Remarks</flux:text>
                <flux:text class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 text-right max-w-xs">
                    {{ $selectedRemarkLabel ?: 'N/A' }}
                </flux:text>
            </div>
        </div>

        {{-- Warning Message --}}
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
            <div class="flex gap-3">
                <flux:icon name="information-circle"
                           class="h-5 w-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5"/>
                <div>
                    <flux:text class="text-sm font-medium text-amber-800 dark:text-amber-200">
                        Are you sure you want to submit this violation?
                    </flux:text>
                    <flux:text class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                        This action will be recorded in the student's permanent record.
                    </flux:text>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3 pt-2">
            <flux:modal.close class="flex-1">
                <flux:button variant="ghost" class="w-full">
                    Cancel
                </flux:button>
            </flux:modal.close>

            <flux:button
                wire:click="save"
                variant="danger"
                class="flex-1"
                icon="paper-airplane"
            >
                Confirm & Submit
            </flux:button>
        </div>
    </div>
</flux:modal>
