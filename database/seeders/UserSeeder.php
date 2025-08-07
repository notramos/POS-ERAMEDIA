<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $karyawanRole = Role::where('name', 'karyawan')->first();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Ganti dengan yang aman di produksi
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Karyawan User',
            'email' => 'karyawan@example.com',
            'password' => bcrypt('password'),
            'role_id' => $karyawanRole->id,
        ]);
    }
}
