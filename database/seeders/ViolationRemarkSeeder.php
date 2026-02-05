<?php

namespace Database\Seeders;

use App\Models\ViolationType;
use App\Models\ViolationRemark;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViolationRemarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'C.1.1' => [
                'Improper wearing of ID',
                'ID left at home',
                'No ID presented',
                'ID not visible',
            ],
            'C.1.2' => [
                'Talking loudly during class',
                'Unauthorized use of phone',
                'Interrupting the instructor',
                'Causing disturbance during school activity',
            ],
            'C.1.3' => [
                'Vape found in bag',
                'Vape confiscated at gate',
                'Possession of e-cigarette',
            ],
            'C.1.4' => [
                'Smoking in restroom',
                'Vaping in hallway',
                'Smoking in restricted area',
            ],
            'C.1.5' => [
                'Smell of alcohol',
                'Appeared intoxicated',
                'Admitted drinking before class',
            ],
            'C.1.6' => [
                'Alcohol found in bag',
                'Possession of liquor',
                'Possession of vape device',
            ],
            'C.1.7' => [
                'Unauthorized use of equipment',
                'Improper use of classroom',
                'Facility damage due to misuse',
            ],
            'C.1.8' => [
                'Verbal profanity',
                'Offensive online message',
                'Vulgar language toward student',
            ],
            'C.1.9' => [
                'Trash left in hallway',
                'Improper disposal of waste',
                'Littering in campus grounds',
            ],
            'C.1.10' => [
                'Styrofoam food container',
                'Styrofoam cup brought inside campus',
            ],
            'C.1.11' => [
                'Unauthorized switch operation',
                'Tampered electrical outlet',
                'Modified classroom equipment',
            ],
            'C.1.12' => [
                'Inappropriate physical contact',
                'Public display of affection',
            ],
            'C.1.13' => [
                'No reservation on record',
                'Unauthorized room usage',
            ],
            'C.1.14' => [
                'Food brought inside lab',
                'Eating during laboratory session',
            ],
        ];

        foreach ($data as $code => $remarks) {
            $violation = ViolationType::where('code', $code)->first();

            if (!$violation) {
                continue;
            }

            foreach ($remarks as $label) {
                ViolationRemark::create([
                    'violation_type_id' => $violation->id,
                    'label' => $label,
                ]);
            }
        }
    }
}
