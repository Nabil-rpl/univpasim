<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ✅ Cek peminjaman terlambat setiap hari jam 8 pagi
        $schedule->command('peminjaman:cek-terlambat')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta')
            ->appendOutputTo(storage_path('logs/cek-terlambat.log'));

        // 💡 Opsi lain (uncomment yang Anda butuhkan):
        
        // Cek 2x sehari (pagi dan sore)
        // $schedule->command('peminjaman:cek-terlambat')
        //     ->twiceDaily(8, 17) // Jam 8 pagi dan 5 sore
        //     ->timezone('Asia/Jakarta');

        // Cek setiap 6 jam (lebih responsif)
        // $schedule->command('peminjaman:cek-terlambat')
        //     ->everySixHours()
        //     ->timezone('Asia/Jakarta');

        // Cek setiap jam (untuk testing atau sistem yang sangat responsif)
        // $schedule->command('peminjaman:cek-terlambat')
        //     ->hourly()
        //     ->timezone('Asia/Jakarta');

        // Cek setiap 10 menit (untuk development/testing)
        // $schedule->command('peminjaman:cek-terlambat')
        //     ->everyTenMinutes()
        //     ->timezone('Asia/Jakarta');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}