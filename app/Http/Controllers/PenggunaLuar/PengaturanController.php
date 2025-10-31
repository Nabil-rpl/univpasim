<?php

namespace App\Http\Controllers\PenggunaLuar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PengaturanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pengguna_luar']);
    }

    /**
     * Tampilkan halaman pengaturan.
     */
    public function index()
    {
        $user = Auth::user();
        return view('pengguna-luar.pengaturan.index', compact('user'));
    }

    /**
     * Update profil dan/atau password dalam satu form.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Aturan validasi dinamis
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ];

        $messages = [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter',
        ];

        // Jika password diisi, validasi
        if ($request->filled('current_password') || $request->filled('password')) {
            $rules += [
                'current_password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ];

            $messages += [
                'current_password.required' => 'Password lama wajib diisi',
                'password.required' => 'Password baru wajib diisi',
                'password.min' => 'Password baru minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ];
        }

        $request->validate($rules, $messages);

        try {
            // Update Profil
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_hp = $request->no_hp;
            $user->alamat = $request->alamat;

            // Update Password (jika diisi)
            if ($request->filled('current_password') && $request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->with('error', 'Password lama tidak sesuai!');
                }
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()
                ->route('pengguna-luar.pengaturan.index')
                ->with('success', 'Akun berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}