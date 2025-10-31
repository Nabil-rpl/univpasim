<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Jika tidak ada guard yang dispesifikasi, gunakan default (null)
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Cek apakah user sudah login
            if (Auth::guard($guard)->check()) {
                // Ambil user yang login
                $user = Auth::guard($guard)->user();
                
                // Redirect berdasarkan role
                return match($user->role) {
                    'admin' => redirect('/admin/dashboard'),
                    'petugas' => redirect('/petugas/dashboard'),
                    'mahasiswa' => redirect('/mahasiswa/dashboard'),
                    'pengguna_luar' => redirect('/pengguna-luar/dashboard'),
                    default => redirect('/'),
                };
            }
        }

        return $next($request);
    }
}