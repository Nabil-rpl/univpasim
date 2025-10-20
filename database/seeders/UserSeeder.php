<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === ADMIN ===
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

        // === PETUGAS ===
        $petugasList = [
            ['Budi Santoso', 'budi.petugas@gmail.com'],
            ['Siti Nurhaliza', 'siti.petugas@gmail.com'],
            ['Ahmad Dahlan', 'ahmad.petugas@gmail.com'],
        ];

        foreach ($petugasList as [$name, $email]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'nim' => null,
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
            ]);
        }

        // === MAHASISWA ===
        $mahasiswaData = [
            ['Andi Pratama', 'andi.pratama@gmail.com', '2024001'],
            ['Dewi Lestari', 'dewi.lestari@gmail.com', '2024002'],
            ['Rudi Hermawan', 'rudi.hermawan@gmail.com', '2024003'],
            ['Maya Sari', 'maya.sari@gmail.com', '2024004'],
            ['Agus Setiawan', 'agus.setiawan@gmail.com', '2024005'],
            ['Fitri Handayani', 'fitri.handayani@gmail.com', '2024006'],
            ['Deni Kurniawan', 'deni.kurniawan@gmail.com', '2024007'],
            ['Rina Wijaya', 'rina.wijaya@gmail.com', '2024008'],
            ['Bambang Susilo', 'bambang.susilo@gmail.com', '2024009'],
            ['Lina Marlina', 'lina.marlina@gmail.com', '2024010'],
            ['Hendra Gunawan', 'hendra.gunawan@gmail.com', '2024011'],
            ['Putri Ayu', 'putri.ayu@gmail.com', '2024012'],
            ['Fajar Ramadhan', 'fajar.ramadhan@gmail.com', '2024013'],
            ['Indah Permata', 'indah.permata@gmail.com', '2024014'],
            ['Rizky Maulana', 'rizky.maulana@gmail.com', '2024015'],
        ];

        foreach ($mahasiswaData as [$nama, $email, $nim]) {
            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'nim' => $nim,
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
            ]);

            // Tambahkan ke tabel mahasiswa juga
            Mahasiswa::create([
                'nama' => $nama,
                'email' => $email,
                'nim' => $nim,
                'jurusan' => 'Teknik Informatika', // bisa disesuaikan
            ]);
        }

        $this->command->info('✅ UserSeeder berhasil dijalankan!');
        $this->command->info('Total users: ' . User::count());
        $this->command->info('- Admin: ' . User::where('role', 'admin')->count());
        $this->command->info('- Petugas: ' . User::where('role', 'petugas')->count());
        $this->command->info('- Mahasiswa: ' . User::where('role', 'mahasiswa')->count());
        $this->command->info('Total mahasiswa di tabel mahasiswa: ' . Mahasiswa::count());
    }
}
