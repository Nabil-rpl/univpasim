<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input untuk pengguna umum saja
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/'
            ],
            'password' => ['required', 'confirmed', 'min:6'],
            'no_hp' => ['required', 'string', 'max:15'],
            'alamat' => ['required', 'string', 'max:500'],
        ];

        $request->validate($rules, [
            'email.regex' => 'Email harus menggunakan domain @gmail.com',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan yang lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        // Gunakan transaction untuk memastikan semua proses berhasil
        DB::beginTransaction();
        try {
            // Data user pengguna luar
            $userData = [
                'name' => $request->name,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'role' => 'pengguna_luar',
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ];

            // Buat user baru
            $user = User::create($userData);

            // Kirim notifikasi ke admin & petugas
            $this->kirimNotifikasiUserBaru($user);

            // Kirim notifikasi selamat datang ke user yang baru daftar
            $this->kirimNotifikasiSelamatDatang($user);

            DB::commit();

            // Login otomatis setelah daftar
            Auth::login($user);

            // Redirect ke dashboard pengguna luar
            return redirect('/pengguna-luar/dashboard')
                ->with('success', "Akun berhasil dibuat! Selamat datang di sistem perpustakaan.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error untuk debugging
            \Log::error('Registration Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat akun. Silakan coba lagi.');
        }
    }

    /**
     * Kirim notifikasi user baru ke Admin & Petugas
     */
    private function kirimNotifikasiUserBaru(User $user)
    {
        // Buat detail informasi
        $detailInfo = "Pengguna baru telah mendaftar ke sistem perpustakaan.\n\n";
        $detailInfo .= "Nama: {$user->name}\n";
        $detailInfo .= "Email: {$user->email}\n";
        $detailInfo .= "Role: Pengguna Umum\n";
        $detailInfo .= "No HP: {$user->no_hp}\n";
        $detailInfo .= "Alamat: {$user->alamat}\n";
        $detailInfo .= "\nWaktu Daftar: " . now()->format('d F Y, H:i:s');

        // Kirim notifikasi ke semua admin dan petugas
        Notifikasi::kirimKePetugas(
            'user_baru',
            "Pendaftaran Baru: {$user->name}",
            $detailInfo,
            [
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'role' => 'pengguna_luar',
                'no_hp' => $user->no_hp,
                'alamat' => $user->alamat,
                'jenis_pendaftaran' => 'registrasi_mandiri'
            ],
            route('admin.users.show', $user->id),
            'normal',
            null
        );
    }

    /**
     * Kirim notifikasi selamat datang ke user baru
     */
    private function kirimNotifikasiSelamatDatang(User $user)
    {
        $pesanSelamatDatang = "Selamat datang di Sistem Perpustakaan Digital!\n\n";
        $pesanSelamatDatang .= "Halo {$user->name},\n\n";
        $pesanSelamatDatang .= "Akun Anda sebagai Pengguna Umum telah berhasil dibuat. ";
        $pesanSelamatDatang .= "Sekarang Anda dapat mengakses berbagai fitur perpustakaan:\n\n";
        $pesanSelamatDatang .= "✓ Peminjaman buku\n";
        $pesanSelamatDatang .= "✓ Pencarian koleksi buku\n";
        $pesanSelamatDatang .= "✓ Riwayat peminjaman\n";
        $pesanSelamatDatang .= "\nJika Anda memiliki pertanyaan, silakan hubungi petugas perpustakaan.\n\n";
        $pesanSelamatDatang .= "Terima kasih telah bergabung!\n";
        $pesanSelamatDatang .= "Tim Perpustakaan Digital";

        // Kirim notifikasi selamat datang
        Notifikasi::kirim(
            $user->id,
            'sistem',
            "Selamat Datang di Perpustakaan Digital!",
            $pesanSelamatDatang,
            [
                'jenis' => 'selamat_datang',
                'role' => 'pengguna_luar'
            ],
            null,
            'normal',
            null
        );
    }
}