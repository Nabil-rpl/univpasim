<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Data Admin
        $admins = [
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Admin Kedua',
                'email' => 'admin2@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
        ];

        // Data Petugas
        $petugas = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.petugas@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.petugas@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ],
            [
                'name' => 'Ahmad Dahlan',
                'email' => 'ahmad.petugas@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ],
        ];

        // Data Mahasiswa
        $mahasiswa = [
            ['nama' => 'Andi Pratama', 'email' => 'andi.pratama@gmail.com', 'nim' => '2024001', 'jurusan' => 'Teknik Informatika'],
            ['nama' => 'Dewi Lestari', 'email' => 'dewi.lestari@gmail.com', 'nim' => '2024002', 'jurusan' => 'Sistem Informasi'],
            ['nama' => 'Rudi Hermawan', 'email' => 'rudi.hermawan@gmail.com', 'nim' => '2024003', 'jurusan' => 'Teknik Informatika'],
            ['nama' => 'Maya Sari', 'email' => 'maya.sari@gmail.com', 'nim' => '2024004', 'jurusan' => 'Sistem Informasi'],
            ['nama' => 'Agus Setiawan', 'email' => 'agus.setiawan@gmail.com', 'nim' => '2024005', 'jurusan' => 'Teknik Informatika'],
            ['nama' => 'Fitri Handayani', 'email' => 'fitri.handayani@gmail.com', 'nim' => '2024006', 'jurusan' => 'Sistem Informasi'],
            ['nama' => 'Deni Kurniawan', 'email' => 'deni.kurniawan@gmail.com', 'nim' => '2024007', 'jurusan' => 'Teknik Informatika'],
            ['nama' => 'Rina Wijaya', 'email' => 'rina.wijaya@gmail.com', 'nim' => '2024008', 'jurusan' => 'Sistem Informasi'],
            ['nama' => 'Bambang Susilo', 'email' => 'bambang.susilo@gmail.com', 'nim' => '2024009', 'jurusan' => 'Teknik Informatika'],
            ['nama' => 'Lina Marlina', 'email' => 'lina.marlina@gmail.com', 'nim' => '2024010', 'jurusan' => 'Sistem Informasi'],
            ['nama' => 'Hendra Gunawan', 'email' => 'hendra.gunawan@gmail.com', 'nim' => '2024011', 'jurusan' => 'Teknik Informatika'],
            ['nama' => 'Putri Ayu', 'email' => 'putri.ayu@gmail.com', 'nim' => '2024012', 'jurusan' => 'Sistem Informasi'],
            ['nama' => 'Fajar Ramadhan', 'email' => 'fajar.ramadhan@gmail.com', 'nim' => '2024013', 'jurusan' => 'Teknik Informatika'],
            ['nama' => 'Indah Permata', 'email' => 'indah.permata@gmail.com', 'nim' => '2024014', 'jurusan' => 'Sistem Informasi'],
            ['nama' => 'Rizky Maulana', 'email' => 'rizky.maulana@gmail.com', 'nim' => '2024015', 'jurusan' => 'Teknik Informatika'],
        ];

        // Insert Admin
        foreach ($admins as $admin) {
            User::create($admin);
        }

        // Insert Petugas
        foreach ($petugas as $ptgs) {
            User::create($ptgs);
        }

        // Insert Mahasiswa ke tabel mahasiswa dan users
        foreach ($mahasiswa as $mhs) {
            // Buat data di tabel mahasiswa
            Mahasiswa::create($mhs);

            // Buat user untuk mahasiswa
            User::create([
                'name' => $mhs['nama'],
                'email' => $mhs['email'],
                'nim' => $mhs['nim'],
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);
        }
    }
}