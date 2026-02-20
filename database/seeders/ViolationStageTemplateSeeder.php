<?php

namespace Database\Seeders;

use App\Models\ViolationStageTemplate;
use Illuminate\Database\Seeder;

class ViolationStageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            'minor_1' => ['Oral Reprimand'],
            'minor_2' => ['Written Reprimand', 'Response', '3 Hours Community Service'],
            'minor_3' => ['2 Days Suspension'],
            'major_suspension' => ['Step 1', 'Step 2', 'Step 3', 'Step 4'],
            'major_dismissal' => ['Step 1', 'Step 2'],
            'major_expulsion' => ['Step 1', 'Step 2'],
        ];

        foreach ($stages as $key => $steps) {
            foreach ($steps as $order => $name) {
                ViolationStageTemplate::create([
                    'offense_key' => $key,
                    'order' => $order + 1,
                    'name' => $name,
                ]);
            }
        }
    }
}
