<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $input   = trim($request->input('login_id'));
        $isEmail = filter_var($input, FILTER_VALIDATE_EMAIL);

        // Validasi dasar
        $rules    = ['login_id' => 'required|string', 'password' => 'required'];
        $messages = [
            'login_id.required' => 'Email atau NIM wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ];

        // Tambahan validasi jika input berformat email
        if ($isEmail) {
            $rules['login_id'] = [
                'required', 'email',
                'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/'
            ];
            $messages['login_id.email'] = 'Format email tidak valid.';
            $messages['login_id.regex'] = 'Email harus menggunakan domain @gmail.com.';
        }

        $request->validate($rules, $messages);

        if ($isEmail) {
            // Login Email — hanya untuk admin & petugas
            $user = User::where('email', $input)
                        ->whereIn('role', ['admin', 'petugas', 'pengguna_luar'])
                        ->first();

            if (!$user) {
                return back()->withErrors([
                    'login_id' => 'Email tidak ditemukan atau bukan akun admin/petugas/pengguna luar.',
                ])->withInput($request->only('login_id'));
            }

        } else {
            // Login NIM — hanya untuk mahasiswa
            $user = User::where('nim', $input)
                        ->where('role', 'mahasiswa')
                        ->first();

            if (!$user) {
                return back()->withErrors([
                    'login_id' => 'NIM tidak ditemukan atau bukan akun mahasiswa.',
                ])->withInput($request->only('login_id'));
            }
        }

        // Autentikasi melalui email yang terikat pada akun tersebut
        $credentials = [
            'email'    => $user->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return match(Auth::user()->role) {
                'admin'         => redirect()->intended('/admin/dashboard'),
                'petugas'       => redirect()->intended('/petugas/dashboard'),
                'pengguna_luar' => redirect()->intended('/pengguna-luar/dashboard'),
                default         => redirect()->intended('/mahasiswa/dashboard'),
            };
        }

        return back()->withErrors([
            'login_id' => 'Email/NIM atau password salah.',
        ])->withInput($request->only('login_id'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}