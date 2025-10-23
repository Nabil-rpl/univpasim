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
     * Proses update data mahasiswa.
     */
    public function update(Request $request)
    {
        /** @var User $user */ // <-- Tambahkan ini
        $user = Auth::user();

        // ✅ Validasi data agar tidak bentrok dengan user lain
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // biar tidak error duplikat
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            // ✅ Update data user
            $user->name = $request->name;
            $user->email = $request->email;

            // ✅ Update password hanya jika diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()
                ->route('mahasiswa.pengaturan.index')
                ->with('success', '✅ Pengaturan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }
}
