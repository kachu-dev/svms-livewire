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
            'minor_2' => ['Written Reprimand', 'Response Letter', 'Start Community Service', 'Daily Time Record (DTR)'],
            'minor_3' => ['Assessment', 'Response Letter', 'Suspension Letter', 'Started Suspension', 'Visit CGCO', 'Visit OSA'],
            'major_suspension' => ['Incident/Complaint Letter', 'Response Letter', 'Assessment at OSA', 'BOD Discussion', 'Decide on a Sanction'],
            'major_dismissal' => ['Incident/Complaint Letter', 'Response Letter', 'Assessment at OSA', 'BOD Discussion', 'Decide on a Sanction'],
            'major_expulsion' => ['Incident/Complaint Letter', 'Response Letter', 'Assessment at OSA', 'BOD Discussion', 'Decide on a Sanction'],
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
