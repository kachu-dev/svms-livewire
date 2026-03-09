<?php

use App\Models\Student;
use App\Models\Violation;
use App\Services\ViolationService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?int $studentId = null;

    public string $selectedTypeCode;
    public string $selectedTypeName;
    public string $selectedTypeClassification;
    public string $selectedTypeLabel;

    public ?string $selectedRemarkLabel = null;

    public ?array $resolvedData = null;

    #[Computed]
    public function student()
    {
        return $this->studentId
            ? Student::select('studentid', 'firstname', 'lastname', 'mi')->find($this->studentId)
            : null;
    }

    #[On('to-confirm')]
    public function confirm(
        int $studentId,
        string $typeCode,
        string $typeName,
        string $typeLabel,
        string $typeClassification,
        ?string $remarkLabel = null,
    ): void {
        $this->studentId = $studentId;
        $this->selectedTypeCode = $typeCode;
        $this->selectedTypeName = $typeName;
        $this->selectedTypeLabel = $typeLabel;
        $this->selectedTypeClassification = $typeClassification;
        $this->selectedRemarkLabel = $remarkLabel;

        $this->resolvedData = app(ViolationService::class)->prepareViolationData(
            studentId: $studentId,
            typeCode: $typeCode,
            typeName: $typeName,
            classification: $typeClassification,
            typeLabel: $typeLabel,
            remarkLabel: $remarkLabel,
        );

        $this->modal('confirm-violation')->show();
    }

    public function save(): void
    {
        $service = app(ViolationService::class);

        if ($service->isDuplicate($this->studentId, $this->selectedTypeCode)) {
            $this->modal('confirm-violation')->close();
            $this->modal('duplicate-warning')->show();

            return;
        }

        $this->saveViolation($service);
    }

    public function saveOverride(): void
    {
        $this->saveViolation(app(ViolationService::class));
    }

    private function saveViolation(ViolationService $service): void
    {
        try {
            $student = $this->student;

            $service->create(
                studentId: $this->studentId,
                studentName: "{$student->lastname}, {$student->firstname} {$student->mi}.",
                violationData: $this->resolvedData,
            );

            $this->dispatch('show-result', type: 'success', message: 'Violation recorded successfully.');
            $this->dispatch('violation-created');
            $this->modal('duplicate-warning')->close();
            $this->modal('confirm-violation')->close();

        } catch (Throwable $e) {
            Log::error('Violation save failed', [
                'student_id' => $this->studentId,
                'exception'  => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            $this->dispatch('show-result', type: 'error',
                message: 'Failed to save violation. Please contact support.');
            $this->modal('confirm-violation')->close();
            $this->modal('duplicate-warning')->close();
        }
    }
};
