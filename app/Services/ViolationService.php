<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\ViolationRecorded;
use App\Models\Student;
use App\Models\Violation;
use App\Models\ViolationStages;
use App\Models\ViolationStageTemplate;
use App\Models\ViolationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class ViolationService
{
    private const ESCALATION_CODE = 'C.3.9';

    private const MINOR_ESCALATION_THRESHOLD = 3;

    public function prepareViolationData(
        int $studentId,
        string $typeCode,
        string $typeName,
        string $classification,
        string $typeLabel,
        ?string $remarkLabel,
    ): array {
        if ($classification === 'Minor' && $this->shouldEscalate($studentId)) {
            return $this->buildEscalatedPayload(
                originalTypeCode: $typeCode,
                originalTypeName: $typeName,
                originalRemarkLabel: $remarkLabel,
            );
        }

        return [
            'typeCode' => $typeCode,
            'typeName' => $typeName,
            'classification' => $classification,
            'remarkLabel' => $remarkLabel,
            'isEscalated' => false,
        ];
    }

    public function create(
        Student $student,
        array $violationData,
    ): Violation {
        return DB::transaction(function () use ($student, $violationData) {
            // Re-check inside transaction with lock to prevent race conditions
            if (! $violationData['isEscalated'] && $violationData['classification'] === 'Minor' && $this->shouldEscalate($student->studentid, lock: true)) {
                $violationData = $this->buildEscalatedPayload(
                    originalTypeCode: $violationData['typeCode'],
                    originalTypeName: $violationData['typeName'],
                    originalRemarkLabel: $violationData['remarkLabel'],
                );
            }

            $violation = Violation::create([
                'student_id' => $student->studentid,
                'st_first_name' => $student->firstname,
                'st_last_name' => $student->lastname,
                'st_mi' => $student->mi,
                'st_program' => $student->program,
                'st_year' => $student->year,
                'classification' => $violationData['classification'],
                'type_code' => $violationData['typeCode'],
                'type_name' => $violationData['typeName'],
                'remark' => $violationData['remarkLabel'],
                'is_escalated' => $violationData['isEscalated'],
                'recorded_by' => auth()->id(),
            ]);

            $this->createStages($violation);

            Mail::to($student->studentid.'@adzu.edu.ph')->queue(new ViolationRecorded($violation));

            return $violation;
        });
    }

    public function isDuplicate(int $studentId, string $typeCode): bool
    {
        return Violation::where('student_id', $studentId)
            ->where('type_code', $typeCode)
            ->whereDate('created_at', now(config('app.timezone')))
            ->exists();
    }

    private function shouldEscalate(int $studentId, bool $lock = false): bool
    {
        $query = Violation::where('student_id', $studentId)
            ->where('classification', 'Minor');

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->count() >= self::MINOR_ESCALATION_THRESHOLD;
    }

    private function createStages(Violation $violation): void
    {
        $offenseKey = $violation->resolveOffenseKey();

        $templates = ViolationStageTemplate::where('offense_key', $offenseKey)
            ->orderBy('order')
            ->get();

        if ($templates->isEmpty()) {
            throw new RuntimeException("No stage templates found for offense key: {$offenseKey}");
        }

        $templates->each(fn ($template) => ViolationStages::create([
            'violation_id' => $violation->id,
            'order' => $template->order,
            'name' => $template->name,
        ]));

        $violation->update(['status' => $templates->first()->name]);
    }

    private function buildEscalatedPayload(
        string $originalTypeCode,
        string $originalTypeName,
        ?string $originalRemarkLabel,
    ): array {
        $escalationType = ViolationType::where('code', self::ESCALATION_CODE)->firstOrFail();

        return [
            'typeCode' => $originalTypeCode,       // keep original — what they actually did
            'typeName' => $originalTypeName,       // keep original — what they actually did
            'remarkLabel' => $originalRemarkLabel,    // keep original — untouched
            'classification' => $escalationType->classification ?? 'Major - Suspension',
            'isEscalated' => true,
        ];
    }

    public function toggleStage(Violation $violation, int $stageId): string
    {
        $stages = $violation->stages()->orderBy('order')->get();
        $stage = $stages->firstWhere('id', $stageId);
        $index = $stages->search(fn ($s) => $s->id === $stageId);

        if (! $stage->is_complete) {
            $prev = $stages[$index - 1] ?? null;
            if ($prev && ! $prev->is_complete) {
                return 'previous_incomplete';
            }
        } else {
            $next = $stages[$index + 1] ?? null;
            if ($next && $next->is_complete) {
                return 'next_complete';
            }
        }

        $stage->is_complete = ! $stage->is_complete;
        $stage->completed_at = $stage->is_complete ? now() : null;
        $stage->save();

        $this->updateViolationStatus($violation, $stage);

        return $stage->is_complete ? 'completed' : 'incomplete';
    }

    public function updateViolationStatus(Violation $violation, ViolationStages $stage): void
    {
        $stages = $violation->stages()->orderBy('order')->get();
        $isLastStage = $stages->last()->id === $stage->id;

        if ($stage->is_complete && $isLastStage) {
            $violation->status = 'Complete';
        } elseif (! $stage->is_complete) {
            $violation->status = $stage->name;
        } else {
            $nextStage = $stages->where('order', '>', $stage->order)->first();
            $violation->status = $nextStage?->name ?? $stage->name;
        }

        $violation->save();
    }
}
