<?php

use App\Models\Student;
use App\Models\Violation;
use App\Models\ViolationRemark;
use App\Models\ViolationType;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
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
                'student_name' => $this->student()?->firstname.' '.$this->student()?->lastname,
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
            $this->dispatch('show-result', type: 'error', message: 'Failed to save violation. Please contact support if this persists.'.$e->getMessage());
        }
    }
};
