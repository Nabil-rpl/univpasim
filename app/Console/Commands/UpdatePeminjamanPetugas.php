<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\User;

class UpdatePeminjamanPetugas extends Command
{
    protected $signature = 'peminjaman:update-petugas';
    protected $description = 'Update petugas_id yang NULL di tabel peminjaman dengan petugas pertama';

    public function handle()
    {
        $this->info('🔄 Memulai update petugas_id...');

        // Cari petugas pertama
        $petugas = User::where('role', 'petugas')->first();

        if (!$petugas) {
            $this->error('❌ Tidak ada user dengan role petugas!');
            $this->info('💡 Silakan buat user petugas terlebih dahulu');
            return 1;
        }

        // Hitung data yang akan diupdate
        $jumlah = Peminjaman::whereNull('petugas_id')->count();

        if ($jumlah == 0) {
            $this->info('✅ Semua data peminjaman sudah memiliki petugas_id');
            return 0;
        }

        $this->info("📊 Ditemukan {$jumlah} data peminjaman tanpa petugas");
        $this->newLine();
        
        $this->table(
            ['ID', 'Nama', 'Role'],
            [[$petugas->id, $petugas->name, $petugas->role]]
        );
        
        if ($this->confirm("Update semua data dengan petugas di atas?", true)) {
            Peminjaman::whereNull('petugas_id')
                ->update(['petugas_id' => $petugas->id]);
            
            $this->newLine();
            $this->info("✅ Berhasil update {$jumlah} data peminjaman");
            $this->info("👤 Petugas: {$petugas->name} (ID: {$petugas->id})");
        } else {
            $this->warn('⚠️  Update dibatalkan');
        }

        return 0;
    }
}