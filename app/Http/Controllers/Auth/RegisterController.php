<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
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
        // Validasi input berdasarkan tipe pendaftaran
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/'
            ],
            'password' => ['required', 'confirmed', 'min:6'],
            'role_type' => ['required', 'in:mahasiswa,pengguna_luar'],
        ];

        // Validasi tambahan untuk mahasiswa
        if ($request->role_type === 'mahasiswa') {
            $rules['nim'] = ['required', 'unique:users,nim', 'unique:mahasiswa,nim'];
            $rules['jurusan'] = ['required', 'string', 'max:255'];
        }

        // Validasi tambahan untuk pengguna luar
        if ($request->role_type === 'pengguna_luar') {
            $rules['no_hp'] = ['required', 'string', 'max:15'];
            $rules['alamat'] = ['required', 'string', 'max:500'];
        }

        $request->validate($rules, [
            'email.regex' => 'Email harus menggunakan domain @gmail.com',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan yang lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'role_type.required' => 'Silakan pilih tipe pendaftaran.',
        ]);

        // Data dasar user
        $userData = [
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => $request->role_type,
        ];

        // Tambahan data berdasarkan role
        if ($request->role_type === 'mahasiswa') {
            $userData['nim'] = $request->nim;

            // Buat data mahasiswa di tabel mahasiswa
            Mahasiswa::create([
                'nama' => $request->name,
                'email' => strtolower($request->email),
                'nim' => $request->nim,
                'jurusan' => $request->jurusan,
            ]);
        } elseif ($request->role_type === 'pengguna_luar') {
            $userData['no_hp'] = $request->no_hp;
            $userData['alamat'] = $request->alamat;
        }

        // Buat user baru
        $user = User::create($userData);

        // Login otomatis setelah daftar
        Auth::login($user);

        // Redirect berdasarkan role yang baru dibuat
        $roleLabel = $request->role_type === 'mahasiswa' ? 'Mahasiswa' : 'Pengguna Luar';
        
        return match($user->role) {
            'mahasiswa' => redirect('/mahasiswa/dashboard')
                ->with('success', "Akun {$roleLabel} berhasil dibuat! Selamat datang di sistem perpustakaan."),
            'pengguna_luar' => redirect('/pengguna-luar/dashboard')
                ->with('success', "Akun {$roleLabel} berhasil dibuat! Selamat datang di sistem perpustakaan."),
            default => redirect('/')
                ->with('success', "Akun berhasil dibuat! Selamat datang di sistem perpustakaan."),
        };
    }
}