<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User; // ✅ Tambahkan ini supaya $user dikenali

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil petugas.
     */
    public function index()
    {
        return view('petugas.profile.index', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update data profil petugas.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */  // ✅ bantu IDE tahu tipe variabel
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Update data user
        $user->name = $request->name;
        $user->save(); // ✅ sekarang tidak akan merah

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password petugas.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */ // ✅ bantu IDE juga
        $user = Auth::user();

        // Validasi input password
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password baru
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
