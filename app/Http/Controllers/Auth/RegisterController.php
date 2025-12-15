<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Notifikasi; // ✅ TAMBAHAN: Import Model Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // ✅ TAMBAHAN: Import DB untuk transaction

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

        // ✅ GUNAKAN TRANSACTION untuk memastikan semua proses berhasil
        DB::beginTransaction();
        try {
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

            // ✅ KIRIM NOTIFIKASI KE ADMIN & PETUGAS
            $this->kirimNotifikasiUserBaru($user, $request);

            // ✅ KIRIM NOTIFIKASI SELAMAT DATANG KE USER YANG BARU DAFTAR
            $this->kirimNotifikasiSelamatDatang($user);

            DB::commit();

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
     * ✅ PRIVATE METHOD: Kirim notifikasi user baru ke Admin & Petugas
     */
    private function kirimNotifikasiUserBaru(User $user, Request $request)
    {
        // Tentukan label role
        $roleLabel = match($user->role) {
            'mahasiswa' => 'Mahasiswa',
            'pengguna_luar' => 'Pengguna Luar',
            default => 'User'
        };

        // Buat detail informasi
        $detailInfo = "Pengguna baru telah mendaftar ke sistem perpustakaan.\n\n";
        $detailInfo .= "Nama: {$user->name}\n";
        $detailInfo .= "Email: {$user->email}\n";
        $detailInfo .= "Role: {$roleLabel}\n";

        if ($user->role === 'mahasiswa') {
            $detailInfo .= "NIM: {$user->nim}\n";
            $detailInfo .= "Jurusan: {$request->jurusan}\n";
        } elseif ($user->role === 'pengguna_luar') {
            $detailInfo .= "No HP: {$user->no_hp}\n";
            $detailInfo .= "Alamat: {$user->alamat}\n";
        }

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
                'role' => $user->role,
                'nim' => $user->nim ?? null,
                'no_hp' => $user->no_hp ?? null,
                'jurusan' => $request->jurusan ?? null,
                'jenis_pendaftaran' => 'registrasi_mandiri'
            ],
            route('admin.users.show', $user->id),
            'normal',
            null // Tidak ada pembuat karena self-registration
        );
    }

    /**
     * ✅ PRIVATE METHOD: Kirim notifikasi selamat datang ke user baru
     */
    private function kirimNotifikasiSelamatDatang(User $user)
    {
        $roleLabel = match($user->role) {
            'mahasiswa' => 'Mahasiswa',
            'pengguna_luar' => 'Pengguna Luar',
            default => 'User'
        };

        $pesanSelamatDatang = "Selamat datang di Sistem Perpustakaan Digital!\n\n";
        $pesanSelamatDatang .= "Halo {$user->name},\n\n";
        $pesanSelamatDatang .= "Akun Anda sebagai {$roleLabel} telah berhasil dibuat. ";
        $pesanSelamatDatang .= "Sekarang Anda dapat mengakses berbagai fitur perpustakaan:\n\n";
        
        if ($user->role === 'mahasiswa') {
            $pesanSelamatDatang .= "✓ Peminjaman buku\n";
            $pesanSelamatDatang .= "✓ Perpanjangan peminjaman\n";
            $pesanSelamatDatang .= "✓ Riwayat peminjaman\n";
            $pesanSelamatDatang .= "✓ Pencarian koleksi buku\n";
        } elseif ($user->role === 'pengguna_luar') {
            $pesanSelamatDatang .= "✓ Peminjaman buku\n";
            $pesanSelamatDatang .= "✓ Pencarian koleksi buku\n";
            $pesanSelamatDatang .= "✓ Riwayat peminjaman\n";
        }
        
        $pesanSelamatDatang .= "\nJika Anda memiliki pertanyaan, silakan hubungi petugas perpustakaan.\n\n";
        $pesanSelamatDatang .= "Terima kasih telah bergabung!\n";
        $pesanSelamatDatang .= "Tim Perpustakaan Digital";

        // Kirim notifikasi selamat datang ke user yang baru daftar
        Notifikasi::kirim(
            $user->id,
            'sistem',
            "Selamat Datang di Perpustakaan Digital!",
            $pesanSelamatDatang,
            [
                'jenis' => 'selamat_datang',
                'role' => $user->role
            ],
            null, // Tidak ada URL khusus
            'normal',
            null // Sistem otomatis
        );
    }
}