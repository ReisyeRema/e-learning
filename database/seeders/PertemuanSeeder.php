<?php

namespace Database\Seeders;

use App\Models\Pertemuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PertemuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pertemuan = [];
        for ($i = 1; $i <= 50; $i++) {
            $pertemuan[] = [
                'judul' => 'Pertemuan ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Pertemuan::insert($pertemuan);
    }
}
