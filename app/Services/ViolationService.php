<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Violation;
use App\Models\ViolationStages;
use App\Models\ViolationStageTemplate;
use App\Models\ViolationType;
use App\Mail\ViolationRecorded;
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
                originalTypeLabel: $typeLabel,
                originalRemarkLabel: $remarkLabel,
            );
        }

        return [
            'typeCode'       => $typeCode,
            'typeName'       => $typeName,
            'classification' => $classification,
            'remarkLabel'    => $remarkLabel,
            'isEscalated'    => false,
        ];
    }

    public function create(
        int $studentId,
        string $studentName,
        array $violationData,
    ): Violation {
        return DB::transaction(function () use ($studentId, $studentName, $violationData) {
            // Re-check inside transaction with lock to prevent race conditions
            if (! $violationData['isEscalated'] && $violationData['classification'] === 'Minor') {
                if ($this->shouldEscalate($studentId, lock: true)) {
                    $violationData = $this->buildEscalatedPayload(
                        originalTypeCode: $violationData['typeCode'],
                        originalTypeName: $violationData['typeName'],
                        originalTypeLabel: $violationData['typeName'],
                        originalRemarkLabel: $violationData['remarkLabel'],
                    );
                }
            }

            $violation = Violation::create([
                'student_id'                   => $studentId,
                'student_name'                 => $studentName,
                'classification_snapshot'      => $violationData['classification'],
                'violation_type_code_snapshot' => $violationData['typeCode'],
                'violation_type_name_snapshot' => $violationData['typeName'],
                'violation_remark_snapshot'    => $violationData['remarkLabel'],
                'is_escalated'                 => $violationData['isEscalated'],
                'recorded_by'                  => auth()->id(),
            ]);

            $this->createStages($violation);

            Mail::to($studentId . '@adzu.edu.ph')->queue(new ViolationRecorded($violation));

            return $violation;
        });
    }

    public function isDuplicate(int $studentId, string $typeCode): bool
    {
        return Violation::where('student_id', $studentId)
            ->where('violation_type_code_snapshot', $typeCode)
            ->whereDate('created_at', now(config('app.timezone')))
            ->exists();
    }

    private function shouldEscalate(int $studentId, bool $lock = false): bool
    {
        $query = Violation::where('student_id', $studentId)
            ->where('classification_snapshot', 'Minor');

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
            'order'        => $template->order,
            'name'         => $template->name,
        ]));

        $violation->update(['status' => $templates->first()->name]);
    }

    private function buildEscalatedPayload(
        string $originalTypeCode,
        string $originalTypeName,
        string $originalTypeLabel,
        ?string $originalRemarkLabel,
    ): array {
        $escalationType = ViolationType::where('code', self::ESCALATION_CODE)->firstOrFail();

        return [
            'typeCode'       => $originalTypeCode,       // keep original — what they actually did
            'typeName'       => $originalTypeName,       // keep original — what they actually did
            'remarkLabel'    => $originalRemarkLabel,    // keep original — untouched
            'classification' => $escalationType->classification ?? 'Major - Suspension',
            'isEscalated'    => true,
        ];
    }
}
