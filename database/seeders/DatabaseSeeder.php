<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(15)->create();

        User::create([
            'name' => 'Guard One',
            'username' => 'guard1',
            'role' => 'guard',
            'assigned_gate' => 1,
            'password' => Hash::make('qwe'),
        ]);

        User::create([
            'name' => 'OSA Staff',
            'username' => 'osa1',
            'role' => 'osa',
            'assigned_gate' => null,
            'password' => Hash::make('qwe'),
        ]);

        User::create([
            'name' => 'Matthew Caga-anan',
            'username' => '220802',
            'role' => 'student',
            'assigned_gate' => null,
            'password' => Hash::make('qwe'),
        ]);

        User::create([
            'name' => 'Rufia Napao',
            'username' => '220314',
            'role' => 'student',
            'assigned_gate' => null,
            'password' => Hash::make('qwe'),
        ]);

        $this->call([
            ViolationTypeSeeder::class,
            ViolationRemarkSeeder::class,
            ViolationStageTemplateSeeder::class,
        ]);
    }
}
