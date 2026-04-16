<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SchoolYearHelper;
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

    public function create(Student $student, array $violationData): Violation
    {
        return DB::transaction(function () use ($student, $violationData) {
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
                'school_year' => SchoolYearHelper::current(),
                'recorded_by' => auth()->id(),
            ]);

            $this->createStages($violation);

            /*Mail::to($student->studentid.'@adzu.edu.ph')->queue(new ViolationRecorded($violation));*/

            activity('violation')
                ->causedBy(auth()->user())
                ->performedOn($violation)
                ->withProperties([
                    'student_id' => $student->studentid,
                    'student_name' => $student->firstname.' '.$student->lastname,
                    'type_code' => $violationData['typeCode'],
                    'type_name' => $violationData['typeName'],
                    'classification' => $violationData['classification'],
                    'remark' => $violationData['remarkLabel'],
                    'is_escalated' => $violationData['isEscalated'],
                ])
                ->log('Violation recorded');

            return $violation;
        });
    }

    public function isDuplicate(int $studentId, string $typeCode): bool
    {
        return Violation::where('student_id', $studentId)
            ->where('type_code', $typeCode)
            ->where('school_year', SchoolYearHelper::current())
            ->whereDate('created_at', now(config('app.timezone')))
            ->exists();
    }

    public function checkAndEscalateForStudent(int $studentId, string $schoolYear): void
    {
        $threshold = self::MINOR_ESCALATION_THRESHOLD;

        $allMinors = Violation::where('student_id', $studentId)
            ->where('school_year', $schoolYear)
            ->where('classification', 'Minor')
            ->where('is_active', true)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        if ($allMinors->count() <= $threshold) {
            return;
        }

        $toEscalate = $allMinors->slice($threshold);

        $escalationType = ViolationType::where('code', self::ESCALATION_CODE)->firstOrFail();

        foreach ($toEscalate as $v) {
            if ($v->is_escalated) {
                continue;
            }

            DB::transaction(function () use ($v, $escalationType) {
                $v->update([
                    'is_escalated' => true,
                    'classification' => $escalationType->classification ?? 'Major - Suspension',
                ]);
                $v->stages()->delete();
                $this->createStages($v);
            });
        }
    }

    private function shouldEscalate(int $studentId, bool $lock = false): bool
    {
        $query = Violation::where('student_id', $studentId)
            ->where('classification', 'Minor')
            ->where('school_year', SchoolYearHelper::current())
            ->where('is_active', true);

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
            'typeCode' => $originalTypeCode,
            'typeName' => $originalTypeName,
            'remarkLabel' => $originalRemarkLabel,
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

        $action = $stage->is_complete ? 'completed' : 'reopened';

        activity('violation_stage')
            ->causedBy(auth()->user())
            ->performedOn($violation)
            ->withProperties([
                'stage_id' => $stage->id,
                'stage_name' => $stage->name,
                'stage_order' => $stage->order,
                'action' => $action,
            ])
            ->log("Stage \"{$stage->name}\" was {$action}");

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
