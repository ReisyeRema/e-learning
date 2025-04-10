<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_admin = User::create([
            'name' => 'Super Admin',
            'email' => 'reisyerema19@gmail.com',
            'username' => 'reisye123',
            'password' => Hash::make('12345'),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // User::insert($super_admin);
        $super_admin->assignRole('Super Admin');
    }
}
