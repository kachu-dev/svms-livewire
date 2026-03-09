<?php

/*use App\Mail\ViolationRecorded;
use App\Models\Student;
use App\Models\User;
use App\Models\Violation;
use App\Models\ViolationStages;
use App\Models\ViolationStageTemplate;
use App\Models\ViolationType;
use App\Services\ViolationService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;*/

use App\Models\Student;
use App\Models\Violation;
use App\Services\ViolationService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $studentId;

    public $classification;

    public $selectedTypeClassification;

    public $selectedTypeLabel;

    public $originalTypeId;

    public $selectedTypeId;

    public $selectedTypeCode;

    public $selectedTypeName;

    public $selectedRemarkLabel;

    public $selectedRemarkId;

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
        int $studentId, int $typeId, string $typeCode, string $typeName,
        string $typeLabel, string $typeClassification, ?int $remarkId = null,
        ?string $remarkLabel = null): void
    {
        $this->studentId = $studentId;

        $this->originalTypeId = $typeId;
        $this->selectedTypeId = $typeId;
        $this->selectedTypeCode = $typeCode;
        $this->selectedTypeName = $typeName;
        $this->selectedTypeLabel = $typeLabel;
        $this->selectedTypeClassification = $typeClassification;

        $this->selectedRemarkId = $remarkId;
        $this->selectedRemarkLabel = $remarkLabel;

        $this->resolvedData = app(ViolationService::class)->prepareViolationData(
            studentId: $studentId,
            typeId: $typeId,
            typeCode: $typeCode,
            typeName: $typeName,
            classification: $typeClassification,
            typeLabel: $typeLabel,
            remarkId: $remarkId,
            remarkLabel: $remarkLabel,
        );

        $this->modal('confirm-violation')->show();
    }

    public function save(): void
    {
        $service = app(ViolationService::class);

        if ($service->isDuplicate($this->studentId, $this->originalTypeId)) {
            $this->modal('confirm-violation')->close();
            $this->modal('duplicate-warning')->show();

            return;
        }

        $this->saveViolation($service);

        /*$originalTypeId = $this->selectedTypeId;

        $alreadyExists = Violation::where('student_id', $this->studentId)
            ->where('original_violation_type_id', $originalTypeId)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($alreadyExists) {
            $this->modal('confirm-violation')->close();
            $this->modal('duplicate-warning')->show();

            return;
        }
        $this->saveViolation();*/
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
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->dispatch('show-result', type: 'error',
                message: 'Failed to save violation. Please contact support.');
            $this->modal('confirm-violation')->close();
            $this->modal('duplicate-warning')->close();
        }
    }

    /*private function saveViolation(): void
    {
        try {
            DB::transaction(function () {
                $originalTypeId = $this->selectedTypeId;

                if ($this->classification === 'Minor') {
                    $minorCount = Violation::where('student_id', $this->studentId)
                        ->where('classification', 'Minor')
                        ->lockForUpdate()
                        ->count();

                    if ($minorCount >= 3) {
                        $this->escalateToFourthMinor();
                    }
                }

                $student = $this->student;

                $violation = Violation::create([
                    'student_id' => $this->studentId,
                    'student_name' => "{$student->firstname} {$student->lastname}",
                    'classification' => $this->classification,
                    'violation_type_id' => $this->selectedTypeId,
                    'original_violation_type_id' => $originalTypeId,
                    'violation_type_snapshot' => $this->selectedTypeLabel,
                    'violation_remark_id' => $this->selectedRemarkId,
                    'violation_remark_snapshot' => $this->selectedRemarkLabel,
                ]);

                $offense_key = $violation->resolveOffenseKey();

                $templates = ViolationStageTemplate::where('offense_key', $offense_key)
                    ->orderBy('order')
                    ->get();

                $templates->each(fn ($template) => ViolationStages::create([
                    'violation_id' => $violation->id,
                    'order' => $template->order,
                    'name' => $template->name,
                ]));

                $violation->update(['status' => $templates->first()->name]);

                $userAccount = User::where('username', $this->studentId)
                    ->where('role', 'student')
                    ->first();

                if ($userAccount?->email) {
                    Mail::to($userAccount->email)->queue(new ViolationRecorded($violation));
                }

                $this->dispatch('show-result', type: 'success', message: 'Violation recorded successfully.');
                $this->dispatch('violation-created');
                $this->modal('duplicate-warning')->close();
                $this->modal('confirm-violation')->close();
            });
        } catch (Throwable $e) {
            $this->dispatch('show-result', type: 'error', message: 'Failed to save violation. Please contact support if this persists.');
            $this->modal('confirm-violation')->close();
            $this->modal('duplicate-warning')->close();
        }
    }*/

    /*private function escalateToFourthMinor(): void
    {
        $escalationType = ViolationType::where('code', 'C.3.9')->firstOrFail();

        $this->selectedRemarkLabel = $this->selectedRemarkLabel
            ? "{$this->selectedTypeLabel} - {$this->selectedRemarkLabel}"
            : $this->selectedTypeLabel;

        $this->selectedRemarkId = null;
        $this->classification = 'Major - Suspension';
        $this->selectedTypeId = $escalationType->id;
        $this->selectedTypeLabel = $escalationType->name;
    }*/
};
