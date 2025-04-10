<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PertemuanSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\ProfileSekolahSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            PertemuanSeeder::class,
            ProfileSekolahSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
