<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Daftar pengguna dengan 3 role (Project Leader, Accounting, Admin Site)
        $users = [
            [
                'Kode_Karyawan' => 'PL001',
                'nama' => 'Project Leader 1',
                'email' => 'pl1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'PROJECT_LEADER'
            ],
            [
                'Kode_Karyawan' => 'PL002',
                'nama' => 'Project Leader 2',
                'email' => 'pl2@example.com',
                'password' => Hash::make('password123'),
                'role' => 'PROJECT_LEADER'
            ],
            [
                'Kode_Karyawan' => 'AC001',
                'nama' => 'Accounting 1',
                'email' => 'accounting1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'ACCOUNTING'
            ],
            [
                'Kode_Karyawan' => 'AC002',
                'nama' => 'Accounting 2',
                'email' => 'accounting2@example.com',
                'password' => Hash::make('password123'),
                'role' => 'ACCOUNTING'
            ],
            [
                'Kode_Karyawan' => 'AD001',
                'nama' => 'Admin Site 1',
                'email' => 'admin1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'ADMIN_SITE'
            ],
            [
                'Kode_Karyawan' => 'AD002',
                'nama' => 'Admin Site 2',
                'email' => 'admin2@example.com',
                'password' => Hash::make('password123'),
                'role' => 'ADMIN_SITE'
            ],
            [
                'Kode_Karyawan' => 'KRY1001',
                'nama' => 'Karyawan Site 1',
                'email' => 'karyawan1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'KARYAWAN_SITE'
            ],
            [
                'Kode_Karyawan' => 'KRY2001',
                'nama' => 'Karyawan Site 2',
                'email' => 'karyawan2@example.com',
                'password' => Hash::make('password123'),
                'role' => 'KARYAWAN_SITE'
            ],
            [
                'Kode_Karyawan' => 'DEVS',
                'nama' => 'Karyawan Site 2',
                'email' => 'faris.collegespace@gmail.com',
                'password' => Hash::make('faris'),
                'role' => 'DEVELOPER'
            ],
        ];

        //create users
        foreach ($users as $user) {
            DB::table('users')->insert([
                'Kode_Karyawan' => $user['Kode_Karyawan'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'password' => $user['password'],
                'role' => $user['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
