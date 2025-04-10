<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'menu-profile-sekolah',
            'menu-data-kurikulum',
            'menu-data-kelas',
            'menu-data-tahun-ajar',
            'menu-data-guru',
            'menu-data-siswa',
            'menu-pembelajaran',

            'menu-data-materi',
            'menu-data-tugas',
            'menu-data-kuis',
            'menu-submit-materi',
            'menu-submit-tugas',
            'menu-submit-kuis',
            'menu-daftar-siswa',

            'menu-data-operator',
            'menu-data-permission',
            'menu-data-role',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roles = [
            'Super Admin',
            'Admin',
            'Guru',
            'Siswa',
        ];

        foreach ($roles as $roleName) {
            $role = Role::create(['name' => $roleName]);

            if ($roleName == 'Super Admin') {
                $role->givePermissionTo([
                    'menu-data-operator',
                    'menu-data-permission',
                    'menu-data-role',
                ]);
            }

            if ($roleName == 'Admin') {
                $role->givePermissionTo([
                    'menu-profile-sekolah',
                    'menu-data-kurikulum',
                    'menu-data-kelas',
                    'menu-data-tahun-ajar',
                    'menu-data-guru',
                    'menu-data-siswa',
                    'menu-pembelajaran',
                ]);
            }

            if ($roleName == 'Guru') {
                $role->givePermissionTo([
                    'menu-data-materi',
                    'menu-data-tugas',
                    'menu-data-kuis',
                    'menu-submit-materi',
                    'menu-submit-tugas',
                    'menu-submit-kuis',
                    'menu-daftar-siswa',
                ]);
            }
        }
    }
}
