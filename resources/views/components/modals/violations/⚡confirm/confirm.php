<?php

use App\Models\Student;
use App\Models\User;
use App\Notifications\DatabaseNotification;
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
            ? Student::find($this->studentId)
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
                student: $student,
                violationData: $this->resolvedData,
            );

            User::osa()->get()->each->notify(new DatabaseNotification(
                title: 'Violation Recorded',
                message: "{$student->studentid}: {$this->selectedTypeLabel}",
                type: 'warning',
                actionUrl: route('staff.violations.student', $student->studentid),
                actionText: 'View Student',
                meta: ['student_name' => $student->full_name, 'type_code' => $this->selectedTypeCode],
            ));

            $this->dispatch('show-result', type: 'success', message: 'Violation recorded successfully.');
            $this->dispatch('violation-created');
            $this->modal('duplicate-warning')->close();
            $this->modal('confirm-violation')->close();

        } catch (Throwable $e) {
            Log::error('Violation save failed', [
                'student_id' => $this->studentId,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->dispatch('show-result', type: 'error',
                message: 'Failed to save violation. Please contact support.'.$e->getMessage());
            $this->modal('confirm-violation')->close();
            $this->modal('duplicate-warning')->close();
        }
    }
};
