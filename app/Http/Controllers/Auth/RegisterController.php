<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/'
            ],
            'password' => ['required', 'confirmed', 'min:6'],
        ], [
            // ✅ Pesan error kustom
            'email.regex' => 'Email harus menggunakan domain @gmail.com',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan yang lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // ✅ Buat user baru dengan role default mahasiswa
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email), // ubah ke huruf kecil semua biar konsisten
            'role' => 'mahasiswa',
            'password' => Hash::make($request->password),
        ]);

        // ✅ Login otomatis setelah daftar
        Auth::login($user);

        // ✅ Redirect ke halaman utama dengan pesan sukses
        return redirect('/')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di sistem perpustakaan.');
    }
}
