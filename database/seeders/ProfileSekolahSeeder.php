<?php

namespace Database\Seeders;

use App\Models\ProfilSekolah;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfileSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profile_sekolah = [
            [
                'id' => 1,
                'nama_sekolah' => 'Sekolah 1',
                'alamat' => 'Bukit Agung',
                'akreditas' => 'A',
                'foto' => '/img/logo.png',
                'no_hp' => '08736477388',
                'email' => 'sekolah@gmail.com',
                'latitude' => 0.4317996635673547,
                'longitude' => 101.81418000975802,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        ProfilSekolah::insert($profile_sekolah);
    }
}
