<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'nim' => null,
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin Kedua',
            'email' => 'admin2@gmail.com',
            'nim' => null,
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Data Petugas
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.petugas@gmail.com',
            'nim' => null,
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.petugas@gmail.com',
            'nim' => null,
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        User::create([
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad.petugas@gmail.com',
            'nim' => null,
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        // Data Mahasiswa
        $mahasiswaData = [
            [
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@gmail.com',
                'nim' => '2024001',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'nim' => '2024002',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'rudi.hermawan@gmail.com',
                'nim' => '2024003',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@gmail.com',
                'nim' => '2024004',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@gmail.com',
                'nim' => '2024005',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Fitri Handayani',
                'email' => 'fitri.handayani@gmail.com',
                'nim' => '2024006',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Deni Kurniawan',
                'email' => 'deni.kurniawan@gmail.com',
                'nim' => '2024007',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Rina Wijaya',
                'email' => 'rina.wijaya@gmail.com',
                'nim' => '2024008',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Bambang Susilo',
                'email' => 'bambang.susilo@gmail.com',
                'nim' => '2024009',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Lina Marlina',
                'email' => 'lina.marlina@gmail.com',
                'nim' => '2024010',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@gmail.com',
                'nim' => '2024011',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Putri Ayu',
                'email' => 'putri.ayu@gmail.com',
                'nim' => '2024012',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Fajar Ramadhan',
                'email' => 'fajar.ramadhan@gmail.com',
                'nim' => '2024013',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Indah Permata',
                'email' => 'indah.permata@gmail.com',
                'nim' => '2024014',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Rizky Maulana',
                'email' => 'rizky.maulana@gmail.com',
                'nim' => '2024015',
                'password' => Hash::make('mahasiswa123'),
            ],
        ];

        foreach ($mahasiswaData as $mahasiswa) {
            User::create(array_merge($mahasiswa, ['role' => 'mahasiswa']));
        }

        $this->command->info('User seeder berhasil dijalankan!');
        $this->command->info('Total users: ' . User::count());
        $this->command->info('- Admin: ' . User::where('role', 'admin')->count());
        $this->command->info('- Petugas: ' . User::where('role', 'petugas')->count());
        $this->command->info('- Mahasiswa: ' . User::where('role', 'mahasiswa')->count());
    }
}
