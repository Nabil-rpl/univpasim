<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\User;

class UpdatePeminjamanPetugas extends Command
{
    protected $signature = 'peminjaman:update-petugas';
    protected $description = 'Update petugas_id yang NULL di tabel peminjaman dengan distribusi merata ke semua petugas';

    public function handle()
    {
        $this->info('🔄 Memulai update petugas_id...');

        // Ambil SEMUA petugas
        $petugasList = User::where('role', 'petugas')->get();

        if ($petugasList->isEmpty()) {
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
        $this->info("👥 Akan didistribusikan ke {$petugasList->count()} petugas");
        $this->newLine();
        
        // Tampilkan tabel semua petugas
        $tableData = $petugasList->map(function($petugas) {
            return [$petugas->id, $petugas->name, $petugas->role];
        })->toArray();
        
        $this->table(
            ['ID', 'Nama', 'Role'],
            $tableData
        );
        
        if ($this->confirm("Update dan distribusikan data ke semua petugas di atas?", true)) {
            $peminjaman = Peminjaman::whereNull('petugas_id')->get();
            $bar = $this->output->createProgressBar($peminjaman->count());
            $bar->start();
            
            // Distribusi merata menggunakan round-robin
            foreach ($peminjaman as $index => $item) {
                $petugas = $petugasList[$index % $petugasList->count()];
                $item->update(['petugas_id' => $petugas->id]);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine(2);
            $this->info("✅ Berhasil update {$jumlah} data peminjaman");
            $this->info("📌 Data didistribusikan secara merata ke {$petugasList->count()} petugas");
        } else {
            $this->warn('⚠️  Update dibatalkan');
        }

        return 0;
    }
}