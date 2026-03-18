<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* User::factory(15)->create(); */

        /*User::create([
            'name' => 'Guard One',
            'username' => 'guard1',
            'role' => 'guard',
            'assigned_gate' => 1,
            'password' => Hash::make('qwe'),
        ]);*/

        User::create([
            'name' => 'Heart Ramos',
            'username' => 'ramos.hart',
            'role' => 'osa',
            'assigned_gate' => null,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Guard Gate 2',
            'username' => 'guard2',
            'role' => 'guard',
            'assigned_gate' => 2,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Guard Gate 4',
            'username' => 'guard4',
            'role' => 'guard',
            'assigned_gate' => 4,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Guard Gate 6',
            'username' => 'guard6',
            'role' => 'guard',
            'assigned_gate' => 6,
            'password' => Hash::make('password'),
        ]);

        DB::table('settings')->insert([
            'key' => 'school_year',
            'value' => '2025-2026',
        ]);

        $this->call([
            ViolationTypeSeeder::class,
            ViolationRemarkSeeder::class,
            ViolationStageTemplateSeeder::class,
            /* ViolationSeeder::class, */
            ViolationRequestReasonSeeder::class,
        ]);
    }
}
