<?php

namespace Database\Seeders;

use App\Models\ViolationStageTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViolationStageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            'minor_1' => ['Oral Reprimand', 'Complete'],
            'minor_2' => ['Written Reprimand', 'Response', '3 Hours Community Service', 'Complete'],
            'minor_3' => ['2 Days Suspension', 'Complete'],
            'major_suspension' => ['Step 1', 'Step 2', 'Complete'],
            'major_dismissal'  => ['Step 1', 'Step 2', 'Complete'],
            'major_expulsion'  => ['Step 1', 'Step 2', 'Complete'],
        ];

        foreach ($stages as $key => $steps) {
            foreach ($steps as $order => $name) {
                ViolationStageTemplate::create([
                    'offense_key' => $key,
                    'order'       => $order + 1,
                    'name'        => $name,
                ]);
            }
        }
    }
}
