<?php

use App\Models\Student;
use App\Models\Violation;
use App\Models\ViolationRemark;
use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Carbon;

new class extends Component {
    public $studentId;
    public $classification;

    public $selectedTypeLabel;
    public $selectedTypeId;

    public $selectedRemarkLabel;
    public $selectedRemarkId;

    #[Computed]
    public function student()
    {
        return $this->studentId ? Student::find($this->studentId) : null;
    }

    #[On('to-confirm')]
    public function confirm($studentId, $typeId, $remarkId = null, $remarkLabel = null): void
    {
        $type = ViolationType::findOrFail($typeId);

        if ($remarkLabel) {
            $remark = null;
            $this->selectedRemarkLabel = $remarkLabel;
        } else {
            $remark = $remarkId ? ViolationRemark::find($remarkId) : null;
            $this->selectedRemarkLabel = $remark?->label;
        }

        $this->studentId = $studentId;
        $this->selectedTypeId = $typeId;
        $this->classification = $type->classification;
        $this->selectedRemarkId = $remarkId;
        $this->selectedTypeLabel = $type->name;

        /*dd([
            $this->studentId = $studentId,
            $this->selectedTypeId = $typeId,
            $this->classification = $type->classification,
            $this->selectedRemarkId = $remarkId,
            $this->selectedTypeLabel = $type->name,
            $this->selectedRemarkLabel = $remark?->label,
        ]);*/

        $this->modal('confirm-violation')->show();
    }

    public function save(): void
    {
        try {
            $originalTypeId = $this->selectedTypeId;

            $alreadyExists = Violation::where('student_id', $this->studentId)->where('original_violation_type_id', $originalTypeId)->whereDate('created_at', Carbon::today())->first();

            if ($alreadyExists) {
                $this->dispatch('show-result', type: 'error', message: sprintf('Violation "%s" has already been recorded today for %s.', $this->selectedTypeLabel, $alreadyExists->student_name));

                $this->modal('confirm-violation')->close();
                return;
            }

            $isMinor = $this->classification === 'Minor';

            $previousMinorCount = Violation::where('student_id', $this->studentId)->where('classification', 'Minor')->count();

            if ($isMinor && $previousMinorCount >= 3) {
                $isMinor = false;

                $originalRemark = $this->selectedRemarkLabel ?: '';
                $this->selectedRemarkLabel = $originalRemark
                    ? "{$this->selectedTypeLabel} - {$originalRemark}"
                    : $this->selectedTypeLabel;
                $this->selectedRemarkId = null;
                $this->classification = 'Major - Suspension';
                $this->selectedTypeId = 23;
                $this->selectedTypeLabel = 'Commission of a fourth minor violation';
            }

            if ($isMinor) {
                $currentCount = $previousMinorCount + 1;
            } else {
                $previousMajorCount = Violation::where('student_id', $this->studentId)->where('classification', '!=', 'Minor')->count();
                $currentCount = $previousMajorCount + 1;
            }

            Violation::create([
                'student_id' => $this->studentId,
                'student_name' => $this->student()?->firstname . ' ' . $this->student()?->lastname,
                'classification' => $this->classification,
                'count' => $currentCount,
                'violation_type_id' => $this->selectedTypeId,
                'original_violation_type_id' => $originalTypeId,
                'violation_type_snapshot' => $this->selectedTypeLabel,
                'violation_remark_id' => $this->selectedRemarkId,
                'violation_remark_snapshot' => $this->selectedRemarkLabel,
            ]);

            $this->dispatch('violation-created');
            $this->modal('confirm-violation')->close();

            $this->dispatch('show-result', type: 'success', message: 'Violation has been recorded successfully');
        } catch (Exception $e) {
            Log::error('Violation creation failed', [
                'student_id' => $this->studentId,
                'type_id' => $this->selectedTypeId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->modal('confirm-violation')->close();
            $this->dispatch('show-result', type: 'error', message: 'Failed to save violation. Please contact support if this persists.' . $e->getMessage());
        }
    }
};
?>

<flux:modal name="confirm-violation" class="w-full max-w-md sm:max-w-96 md:max-w-3xl">
    <div class="space-y-6">
        <div class="text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                <flux:icon name="exclamation-triangle" class="h-6 w-6 text-red-600 dark:text-red-400" />
            </div>
            <flux:heading size="lg" class="mt-4">Confirm Violation Submission</flux:heading>
            <flux:subheading class="mt-2">
                Please review the details carefully before submitting
            </flux:subheading>
        </div>

        <div class="space-y-3 rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
            <div class="flex items-start justify-between border-b border-zinc-200 pb-2 dark:border-zinc-700">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Student Name</flux:text>
                <flux:text class="text-right text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $this->student?->firstname ?? 'N/A' }} {{ $this->student?->lastname ?? 'N/A' }}
                </flux:text>
            </div>

            <div class="flex items-start justify-between border-b border-zinc-200 pb-2 dark:border-zinc-700">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Student ID</flux:text>
                <flux:text class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $this->student?->studentid ?? 'N/A' }}
                </flux:text>
            </div>

            <div class="flex items-start justify-between border-b border-zinc-200 pb-2 dark:border-zinc-700">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Violation Type</flux:text>
                <flux:text class="max-w-xs text-right text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $selectedTypeLabel ?: 'N/A' }}
                </flux:text>
            </div>

            <div class="flex items-start justify-between">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Remarks</flux:text>
                <flux:text class="max-w-xs text-right text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $selectedRemarkLabel ?: 'N/A' }}
                </flux:text>
            </div>
        </div>

        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-900/20">
            <div class="flex gap-3">
                <flux:icon name="information-circle"
                    class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-600 dark:text-amber-400"
                />
                <div>
                    <flux:text class="text-sm font-medium text-amber-800 dark:text-amber-200">
                        Are you sure you want to submit this violation?
                    </flux:text>
                    <flux:text class="mt-1 text-xs text-amber-700 dark:text-amber-300">
                        This action will be recorded in the student's permanent record.
                    </flux:text>
                </div>
            </div>
        </div>

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
                wire:loading.attr="disabled"
                wire:target="save"
            >
                <span wire:loading.remove wire:target="save">Confirm & Submit</span>
                <span wire:loading wire:target="save">Saving...</span>
            </flux:button>
        </div>
    </div>
</flux:modal>
