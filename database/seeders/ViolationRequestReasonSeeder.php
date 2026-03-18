<?php

namespace Database\Seeders;

use App\Models\ViolationRequestReason;
use Illuminate\Database\Seeder;

class ViolationRequestReasonSeeder extends Seeder
{
    public function run(): void
    {
        ViolationRequestReason::insert([
            ['type' => 'delete', 'label' => 'Violation was recorded in error.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'delete', 'label' => 'Student was misidentified.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'delete', 'label' => 'Violation was already resolved.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'delete', 'label' => 'Duplicate entry.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'update', 'label' => 'Remark was entered incorrectly.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'update', 'label' => 'Remark does not match the actual incident.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'update', 'label' => 'Additional context needs to be added.', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'update', 'label' => 'Spelling or grammar correction needed.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
