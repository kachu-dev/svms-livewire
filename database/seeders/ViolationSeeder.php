<?php

namespace Database\Seeders;

use App\Models\Student;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ViolationSeeder extends Seeder
{
    /**
     * Generates 500 violation records using REAL students from arturn_db.profile.
     * Program and year are resolved via collegedb.stud_program (same as Student model).
     *
     * Prerequisites:
     *   1. UserSeeder
     *   2. ViolationTypeSeeder
     *   3. ViolationStageTemplateSeeder
     */
    public function run(): void
    {
        $faker = Faker::create();

        // ── Guard: users ──────────────────────────────────────────────────────
        $userIds = DB::table('users')
            ->where('role', '!=', 'student')
            ->pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found. Run UserSeeder first.');

            return;
        }

        // ── Pull real students from arturn_db.profile ─────────────────────────
        // We only grab base profile columns here — program/year come from collegedb.
        $this->command->info('Loading real students from arturn_db.profile...');

        $rawStudents = DB::connection('arturn_db')
            ->table('profile')
            ->select('studentid', 'firstname', 'lastname', 'mi')
            ->inRandomOrder()
            ->limit(150)
            ->get();

        if ($rawStudents->isEmpty()) {
            $this->command->error('No students found in arturn_db.profile.');

            return;
        }

        // ── Resolve program + year for each student via collegedb ─────────────
        // Mirrors exactly what Student::getProgramAttribute() / getYearAttribute() does.
        $this->command->info('Resolving program and year from collegedb...');

        $studentIds = $rawStudents->pluck('studentid')->toArray();

        // Batch-load the latest stud_program rows for all student IDs at once
        // to avoid N+1 queries against collegedb.
        $latestPrograms = DB::connection('collegedb')
            ->table('stud_program as sp')
            ->join('prgram as p', 'sp.sp_pr_acronym', '=', 'p.pr_code')
            ->whereIn('sp.sp_idnum', $studentIds)
            ->select('sp.sp_idnum', 'p.pr_acronym as program', 'sp.sp_year as year')
            ->orderBy('sp.sp_idnum')
            ->orderByDesc('sp.ts')
            ->get()
            // Keep only the latest row per student (first after desc sort)
            ->groupBy('sp_idnum')
            ->map(fn ($rows) => $rows->first());

        // Build the final student pool, skipping any without program/year data
        $students = $rawStudents->map(function ($student) use ($latestPrograms) {
            $extra = $latestPrograms->get($student->studentid);

            if (! $extra) {
                return null; // no program record — skip
            }

            return [
                'studentid' => $student->studentid,
                'firstname' => $student->firstname,
                'lastname' => $student->lastname,
                'mi' => $student->mi,
                'program' => $extra->program,
                'year' => $extra->year,
            ];
        })
            ->filter()
            ->values()
            ->toArray();

        if (empty($students)) {
            $this->command->error('No students with program/year data found in collegedb.');

            return;
        }

        $this->command->info('Resolved '.count($students).' students with program/year data.');

        // ── Guard: violation types ────────────────────────────────────────────
        $allTypes = DB::table('violation_types')
            ->select('code', 'name', 'classification')
            ->get()
            ->groupBy('classification')
            ->map(fn ($g) => $g->toArray())
            ->toArray();

        if (empty($allTypes)) {
            $this->command->error('No violation types found. Run ViolationTypeSeeder first.');

            return;
        }

        // ── Guard: stage templates ────────────────────────────────────────────
        $stageTemplates = DB::table('violation_stage_templates')
            ->select('offense_key', 'order', 'name')
            ->orderBy('offense_key')
            ->orderBy('order')
            ->get()
            ->groupBy('offense_key')
            ->map(fn ($g) => $g->toArray())
            ->toArray();

        if (empty($stageTemplates)) {
            $this->command->error('No stage templates found. Run ViolationStageTemplateSeeder first.');

            return;
        }

        // ── Weighted classification pool ──────────────────────────────────────
        $classificationWeights = [
            'Minor' => 55,
            'Major - Suspension' => 25,
            'Major - Dismissal' => 12,
            'Major - Expulsion' => 8,
        ];

        $weightedClassifications = [];
        foreach ($classificationWeights as $class => $weight) {
            for ($i = 0; $i < $weight; $i++) {
                $weightedClassifications[] = $class;
            }
        }

        $offenseKeyMap = [
            'Major - Suspension' => 'major_suspension',
            'Major - Dismissal' => 'major_dismissal',
            'Major - Expulsion' => 'major_expulsion',
        ];

        // ── Static pools ──────────────────────────────────────────────────────
        $remarks = [
            'Student was warned verbally prior to this record.',
            'Incident occurred during final examination week.',
            'Parents / guardians have been notified.',
            'Under investigation by the Student Affairs Office.',
            'Second offense within the semester.',
            'Student submitted an explanation letter.',
            'Caught in the act by the proctor.',
            'Witnessed by two faculty members.',
            'CCTV footage reviewed and confirmed.',
            'Referred to the Guidance Center.',
            'Student acknowledged the violation in writing.',
            'Coordinated with the Academic Advisor.',
            null, null, null,
        ];

        $stageRemarks = [
            'Student appeared and acknowledged the offense.',
            'Formal notice sent to student and guardian.',
            'Hearing conducted — student was present.',
            'Student failed to appear at the scheduled hearing.',
            'Documentation submitted to the Dean\'s Office.',
            'Awaiting response from student.',
            'Community service completed and verified.',
            'Suspension order signed and served.',
            null, null,
        ];

        // ── Build violations ──────────────────────────────────────────────────
        // Escalation mirrors ViolationService::shouldEscalate():
        //   count() >= 3 before insert = this is the 4th+ Minor = escalate to Major - Suspension.
        // We track per-student Minor counts so the rule is applied correctly.
        //
        // violations.status = name of the current active stage, matching
        //   ViolationService: $violation->update(['status' => $templates->first()->name])

        $violationRecords = [];
        $stagedMeta = [];
        $minorCountPerStudent = []; // studentid => Minor violations inserted so far

        $now = now();
        $dateRanges = [
            'today' => 15,  // 15% — today
            'this_week' => 20,  // 20% — this week
            'this_month' => 25,  // 25% — this month
            'last_month' => 20,  // 20% — last month
            'this_year' => 20,  // 20% — this year
        ];

        $weightedRanges = [];
        foreach ($dateRanges as $range => $weight) {
            for ($w = 0; $w < $weight; $w++) {
                $weightedRanges[] = $range;
            }
        }

        for ($i = 0; $i < 70; $i++) {
            $student = $students[array_rand($students)];
            $studentId = $student['studentid'];
            $classification = $weightedClassifications[array_rand($weightedClassifications)];

            $typePool = $allTypes[$classification] ?? [];
            $type = (array) $typePool[array_rand($typePool)];

            // ── Escalation logic ──────────────────────────────────────────────
            // Matches ViolationService: if student already has >= 3 Minor violations,
            // this new one is their 4th (or more) and gets escalated.
            $currentMinorCount = $minorCountPerStudent[$studentId] ?? 0;
            $isEscalated = false;

            if ($classification === 'Minor') {
                if ($currentMinorCount >= 3) {
                    // 4th+ minor — escalate to Major - Suspension
                    $isEscalated = true;
                    $classification = 'Major - Suspension';
                    $typePool = $allTypes[$classification] ?? [];
                    $type = (array) $typePool[array_rand($typePool)];
                } else {
                    // Still a plain minor — track it
                    $minorCountPerStudent[$studentId] = $currentMinorCount + 1;
                }
            }

            // ── Offense key ───────────────────────────────────────────────────
            // Minor key reflects offense number (minor_1 / minor_2 / minor_3).
            // Escalated and non-minor violations map to their major key.
            if (! $isEscalated && $classification === 'Minor') {
                $minorNumber = $minorCountPerStudent[$studentId];
                $offenseKey = match (true) {
                    $minorNumber === 1 => 'minor_1',
                    $minorNumber === 2 => 'minor_2',
                    default => 'minor_3',
                };
            } else {
                $offenseKey = $offenseKeyMap[$classification] ?? 'major_suspension';
            }

            $templates = $stageTemplates[$offenseKey] ?? [];
            $totalStages = count($templates);

            $completedCount = $totalStages > 0
                ? $faker->numberBetween(0, $totalStages)
                : 0;

            if ($totalStages > 0) {
                if ($completedCount >= $totalStages) {
                    $status = 'Complete';
                } else {
                    $status = ((array) $templates[$completedCount])['name'];
                }
            } else {
                $status = 'pending';
            }
            $pickedRange = $weightedRanges[array_rand($weightedRanges)];

            $createdAt = match ($pickedRange) {
                'today' => $faker->dateTimeBetween($now->copy()->startOfDay(), $now),
                'this_week' => $faker->dateTimeBetween($now->copy()->startOfWeek(), $now),
                'this_month' => $faker->dateTimeBetween($now->copy()->startOfMonth(), $now),
                'last_month' => $faker->dateTimeBetween(
                    $now->copy()->subMonthNoOverflow()->startOfMonth(),
                    $now->copy()->subMonthNoOverflow()->endOfMonth()
                ),
                'this_year' => $faker->dateTimeBetween($now->copy()->startOfYear(), $now),
            };

            $updatedAt = $faker->dateTimeBetween($createdAt, $now->toDateTime());
            $deletedAt = $faker->boolean(10)
                ? $faker->dateTimeBetween($createdAt, $now->toDateTime())
                : null;

            $violationRecords[] = [
                'student_id' => $studentId,
                'st_first_name' => $student['firstname'],
                'st_last_name' => $student['lastname'],
                'st_mi' => $student['mi'] ?? null,
                'st_program' => $student['program'],
                'st_year' => (string) $student['year'],
                'classification' => $classification,
                'type_code' => $type['code'],
                'type_name' => $type['name'],
                'remark' => $remarks[array_rand($remarks)],
                'is_escalated' => $isEscalated,
                'status' => $status,
                'recorded_by' => $userIds[array_rand($userIds)],
                'deleted_at' => $deletedAt,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            $stagedMeta[] = [
                'offenseKey' => $offenseKey,
                'completedCount' => $completedCount,
                'createdAt' => $createdAt,
            ];
        }

        foreach (array_chunk($violationRecords, 100) as $chunk) {
            DB::table('violations')->insert($chunk);
        }

        // ── Seed ViolationStages ──────────────────────────────────────────────
        $insertedIds = DB::table('violations')
            ->orderBy('id', 'desc')
            ->limit(70)
            ->pluck('id')
            ->reverse()
            ->values()
            ->toArray();

        $stageRecords = [];

        foreach ($insertedIds as $idx => $violationId) {
            $meta = $stagedMeta[$idx];
            $offenseKey = $meta['offenseKey'];
            $completedCount = $meta['completedCount'];
            $violationCreatedAt = Carbon::parse($meta['createdAt']);
            $templates = $stageTemplates[$offenseKey] ?? [];

            if (empty($templates)) {
                continue;
            }

            $stageDate = $violationCreatedAt->copy();

            foreach ($templates as $index => $template) {
                $template = (array) $template;
                $isComplete = $index < $completedCount;

                $completedAt = null;
                if ($isComplete) {
                    $stageDate = $stageDate->copy()->addDays($faker->numberBetween(1, 7));
                    $completedAt = $stageDate->toDateTimeString();
                }

                $stageRecords[] = [
                    'violation_id' => $violationId,
                    'order' => $template['order'],
                    'name' => $template['name'],
                    'is_complete' => $isComplete,
                    'remark' => $isComplete ? $stageRemarks[array_rand($stageRemarks)] : null,
                    'file_path' => ($isComplete && $faker->boolean(40))
                        ? 'stages/'.$faker->uuid().'.pdf'
                        : null,
                    'completed_at' => $completedAt,
                    'created_at' => $violationCreatedAt->toDateTimeString(),
                    'updated_at' => $completedAt ?? $violationCreatedAt->toDateTimeString(),
                ];
            }
        }

        foreach (array_chunk($stageRecords, 200) as $chunk) {
            DB::table('violation_stages')->insert($chunk);
        }

        $this->command->info('Seeded '.count($stageRecords).' violation stage records.');
        $this->command->info('Done!');
    }
}
