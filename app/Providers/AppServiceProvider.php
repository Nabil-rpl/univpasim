<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notifikasi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ TAMBAHKAN INI - Share unread notification count ke semua view mahasiswa
        View::composer('layouts.mahasiswa', function ($view) {
            if (auth()->check()) {
                $unreadNotifCount = Notifikasi::where('user_id', auth()->id())
                    ->where('dibaca', false)
                    ->count();
                
                $view->with('unreadNotifCount', $unreadNotifCount);
            }
        });

        // ✅ TAMBAHKAN INI - Share untuk layout pengguna luar
        View::composer('layouts.pengguna-luar', function ($view) {
            if (auth()->check()) {
                $unreadNotifCount = Notifikasi::where('user_id', auth()->id())
                    ->where('dibaca', false)
                    ->count();
                
                $view->with('unreadNotifCount', $unreadNotifCount);
            }
        });
    }
}