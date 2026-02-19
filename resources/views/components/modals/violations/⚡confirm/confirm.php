<?php

use App\Models\Student;
use App\Models\Violation;
use App\Models\ViolationStages;
use App\Models\ViolationStageTemplate;
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
    public function confirm($studentId, $typeId, $typeLabel, $typeClassification, $remarkId = null, $remarkLabel = null): void
    {
        $this->studentId = $studentId;

        $this->selectedTypeId = $typeId;
        $this->selectedTypeLabel = $typeLabel;
        $this->classification = $typeClassification;

        $this->selectedRemarkId = $remarkId;
        $this->selectedRemarkLabel = $remarkLabel;

        $this->modal('confirm-violation')->show();
    }

    #[On('duplicate-override-confirmed')]
    public function saveOverride(): void
    {
        $this->saveViolation();
    }

    public function save(): void
    {
        $originalTypeId = $this->selectedTypeId;

        $alreadyExists = Violation::where('student_id', $this->studentId)
            ->where('original_violation_type_id', $originalTypeId)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($alreadyExists) {
            $this->modal('confirm-violation')->close();
            $this->modal('duplicate-warning')->show();
            return;
        }

        $this->saveViolation();
    }

    private function saveViolation(): void
    {
        try {
            $originalTypeId = $this->selectedTypeId;

            if ($this->classification === 'Minor') {
                $minorCount = Violation::where('student_id', $this->studentId)
                    ->where('classification', 'Minor')
                    ->count();

                if ($minorCount >= 3) {
                    $this->escalateToFourthMinor();
                }
            }

            $student = $this->student;

            $violation = Violation::create([
                'student_id'                 => $this->studentId,
                'student_name'               => "{$student->firstname} {$student->lastname}",
                'classification'             => $this->classification,
                'violation_type_id'          => $this->selectedTypeId,
                'original_violation_type_id' => $originalTypeId,
                'violation_type_snapshot'    => $this->selectedTypeLabel,
                'violation_remark_id'        => $this->selectedRemarkId,
                'violation_remark_snapshot'  => $this->selectedRemarkLabel,
            ]);

            $offense_key = $violation->resolveOffenseKey();

            $templates = ViolationStageTemplate::where('offense_key', $offense_key)
                ->orderBy('order')
                ->get();

            $templates->each(fn ($template) => ViolationStages::create([
                'violation_id' => $violation->id,
                'order'        => $template->order,
                'name'         => $template->name,
                'status'       => 'pending',
            ]));

            $violation->update(['status' => $templates->first()->name]);

            $this->dispatch('violation-created');
            $this->modal('duplicate-warning')->close();
            $this->dispatch('show-result', type: 'success', message: 'Violation recorded successfully.');

        } catch (\Throwable $e) {
            Log::error('Violation creation failed', [
                'student_id' => $this->studentId,
                'type_id'    => $this->selectedTypeId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            $this->modal('duplicate-warning')->close();
            $this->dispatch('show-result', type: 'error', message: 'Failed to save violation. Please contact support if this persists.');
        }
    }

    /**
     * Mutates the current violation in-place to reflect the C.3.9 escalation.
     * The original type is already preserved in $originalTypeId before this is called.
     */
    private function escalateToFourthMinor(): void
    {
        $escalationType = ViolationType::where('code', 'C.3.9')->firstOrFail();

        // Fold the original violation into the remark for reporting traceability.
        $this->selectedRemarkLabel = $this->selectedRemarkLabel
            ? "{$this->selectedTypeLabel} - {$this->selectedRemarkLabel}"
            : $this->selectedTypeLabel;

        $this->selectedRemarkId   = null;
        $this->classification     = 'Major - Suspension';
        $this->selectedTypeId     = $escalationType->id;
        $this->selectedTypeLabel  = $escalationType->name;
    }


};
