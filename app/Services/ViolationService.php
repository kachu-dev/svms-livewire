<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Violation;
use App\Models\ViolationStages;
use App\Models\ViolationStageTemplate;
use App\Models\ViolationType;
use Illuminate\Support\Facades\DB;

class ViolationService
{
    private const ESCALATION_CODE = 'C.3.9';
    private const MINOR_ESCALATION_THRESHOLD = 3;

    public function prepareViolationData(
        int $studentId,
        int $typeId,
        string $typeCode,
        string $typeName,
        string $classification,
        string $typeLabel,
        ?int $remarkId,
        ?string $remarkLabel,
    ): array {
        $originalTypeId = $typeId;

        if ($classification === 'Minor') {
            $minorCount = Violation::where('student_id', $studentId)
                ->where('classification_snapshot', 'Minor')
                ->count();

            if ($minorCount >= self::MINOR_ESCALATION_THRESHOLD) {
                return $this->buildEscalatedPayload(
                    originalTypeId: $originalTypeId,
                    originalTypeLabel: $typeLabel,
                    originalRemarkLabel: $remarkLabel,
                );
            }
        }

        return [
            'originalTypeId' => $originalTypeId,
            'typeId'         => $typeId,
            'typeCode'       => $typeCode,
            'typeName'       => $typeName,
            'classification' => $classification,
            'typeLabel'      => $typeLabel,
            'remarkId'       => $remarkId,
            'remarkLabel'    => $remarkLabel,
        ];
    }

    public function create(
        int $studentId,
        string $studentName,
        array $violationData,
    ): Violation {
        return DB::transaction(function () use ($studentId, $studentName, $violationData) {
            // Re-check inside transaction with lock to prevent race conditions
            if ($violationData['classification'] === 'Minor') {
                $minorCount = Violation::where('student_id', $studentId)
                    ->where('classification_snapshot', 'Minor')
                    ->lockForUpdate()
                    ->count();

                if ($minorCount >= self::MINOR_ESCALATION_THRESHOLD) {
                    // Recalculate in case it changed between prepare and save
                    $violationData = $this->buildEscalatedPayload(
                        originalTypeId: $violationData['originalTypeId'],
                        originalTypeLabel: $violationData['typeLabel'],
                        originalRemarkLabel: $violationData['remarkLabel'],
                    );
                }
            }

            $violation = Violation::create([
                'student_id'                 => $studentId,
                'student_name'               => $studentName,
                'classification_snapshot'             => $violationData['classification'],
                'violation_type_id'          => $violationData['typeId'],
                'original_violation_type_id' => $violationData['originalTypeId'],
                'violation_type_code_snapshot'    => $violationData['typeCode'],
                'violation_type_name_snapshot'    => $violationData['typeName'],
                'violation_remark_id'        => $violationData['remarkId'],
                'violation_remark_snapshot'  => $violationData['remarkLabel'],
                'recorded_by' => auth()->id(),
            ]);

            $this->createStages($violation);

            return $violation;
        });
    }

    public function isDuplicate(int $studentId, int $typeId): bool
    {
        return Violation::where('student_id', $studentId)
            ->where('original_violation_type_id', $typeId)
            ->whereDate('created_at', now(config('app.timezone')))
            ->exists();
    }

    private function createStages(Violation $violation): void
    {
        $offenseKey = $violation->resolveOffenseKey();

        $templates = ViolationStageTemplate::where('offense_key', $offenseKey)
            ->orderBy('order')
            ->get();

        if ($templates->isEmpty()) {
            throw new \RuntimeException("No stage templates found for offense key: {$offenseKey}");
        }

        $templates->each(fn ($template) => ViolationStages::create([
            'violation_id' => $violation->id,
            'order'        => $template->order,
            'name'         => $template->name,
        ]));

        $violation->update(['status' => $templates->first()->name]);
    }

    private function buildEscalatedPayload(
        int $originalTypeId,
        string $originalTypeLabel,
        ?string $originalRemarkLabel,
    ): array {
        $escalationType = ViolationType::where('code', self::ESCALATION_CODE)->firstOrFail();

        $remarkLabel = $originalRemarkLabel
            ? "{$originalTypeLabel} - {$originalRemarkLabel}"
            : $originalTypeLabel;

        return [
            'originalTypeId' => $originalTypeId,
            'typeId'         => $escalationType->id,
            'typeCode'       => $escalationType->code,
            'typeName'       => $escalationType->name,
            'typeLabel'      => $escalationType->name,
            'classification' => 'Major - Suspension',
            'remarkId'       => null,
            'remarkLabel'    => $remarkLabel,
        ];
    }
}
