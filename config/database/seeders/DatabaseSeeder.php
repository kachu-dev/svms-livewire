<?php

declare(strict_types=1);

namespace config\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $this->call([
            ViolationTypeSeeder::class,
            ViolationRemarkSeeder::class,
        ]);
    }
}
