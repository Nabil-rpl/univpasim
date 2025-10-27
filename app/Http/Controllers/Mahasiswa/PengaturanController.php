<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PengaturanController extends Controller
{
    /**
     * Tampilkan halaman pengaturan mahasiswa.
     */
    public function index()
    {
        $user = Auth::user();
        return view('mahasiswa.pengaturan.index', compact('user'));
    }

    /**
     * Proses update password mahasiswa.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // ✅ Validasi hanya untuk password
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password baru minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            // ✅ Cek apakah password lama benar
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()
                    ->back()
                    ->with('error', '❌ Password lama tidak sesuai!');
            }

            // ✅ Update password
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()
                ->route('mahasiswa.pengaturan.index')
                ->with('success', '✅ Password berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }
}