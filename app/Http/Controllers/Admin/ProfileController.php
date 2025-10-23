<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User; // ✅ Tambahkan ini supaya $user dikenali

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil admin
     */
    public function index()
    {
        return view('admin.pengaturan.index', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update data profil admin
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */ // ✅ bantu IDE tahu tipe datanya
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim'  => ['nullable', 'string', 'max:50'],
        ]);

        // Update nama
        $user->name = $request->name;

        // Update NIM hanya jika role mahasiswa
        if ($user->role === 'mahasiswa') {
            $user->nim = $request->nim;
        }

        $user->save(); // ✅ Sekarang IDE tidak error

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update password admin
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini harus diisi.',
            'password.required' => 'Password baru harus diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ]);
        }

        // Update password baru
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
