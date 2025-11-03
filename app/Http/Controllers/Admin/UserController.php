<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar user dengan filter dan search
    public function index(Request $request)
    {
        $query = User::with('mahasiswa');

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search berdasarkan nama, email, NIM, atau no_hp
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function($mq) use ($search) {
                      $mq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        // Pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Hitung statistik total (tanpa filter)
        $totalUsers = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalPenggunaLuar = User::where('role', 'pengguna_luar')->count();
        
        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalAdmin',
            'totalPetugas',
            'totalMahasiswa',
            'totalPenggunaLuar'
        ));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,petugas,mahasiswa,pengguna_luar',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Validasi khusus mahasiswa
        if ($request->role === 'mahasiswa') {
            $rules['nim'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'nim'),
                Rule::unique('mahasiswa', 'nim')
            ];
            $rules['jurusan'] = 'required|string|max:100';
        }

        // Validasi khusus pengguna luar
        if ($request->role === 'pengguna_luar') {
            $rules['no_hp'] = 'required|string|max:15';
            $rules['alamat'] = 'required|string';
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'role.required' => 'Role wajib dipilih',
            'nim.required' => 'NIM wajib diisi untuk mahasiswa',
            'nim.unique' => 'NIM sudah terdaftar',
            'jurusan.required' => 'Jurusan wajib diisi untuk mahasiswa',
            'no_hp.required' => 'No HP wajib diisi untuk pengguna luar',
            'alamat.required' => 'Alamat wajib diisi untuk pengguna luar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        DB::beginTransaction();
        try {
            // Data user dasar
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
            ];

            // Tambahkan data spesifik berdasarkan role
            if ($validated['role'] === 'mahasiswa') {
                $userData['nim'] = $validated['nim'];
            } elseif ($validated['role'] === 'pengguna_luar') {
                $userData['no_hp'] = $validated['no_hp'];
                $userData['alamat'] = $validated['alamat'];
            }

            // Buat user
            $user = User::create($userData);

            // Jika role mahasiswa, simpan ke tabel mahasiswa
            if ($validated['role'] === 'mahasiswa') {
                Mahasiswa::create([
                    'nama' => $validated['name'],
                    'email' => $validated['email'],
                    'nim' => $validated['nim'],
                    'jurusan' => $validated['jurusan'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.users.index')
                            ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menampilkan detail user
    public function show(User $user)
    {
        $user->load('mahasiswa');
        return view('admin.users.show', compact('user'));
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        $user->load('mahasiswa');
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        // Validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'role' => 'required|in:admin,petugas,mahasiswa,pengguna_luar',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Validasi khusus mahasiswa
        if ($request->role === 'mahasiswa') {
            $rules['nim'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'nim')->ignore($user->id),
                Rule::unique('mahasiswa', 'nim')->ignore($user->mahasiswa->id ?? null)
            ];
            $rules['jurusan'] = 'required|string|max:100';
        }

        // Validasi khusus pengguna luar
        if ($request->role === 'pengguna_luar') {
            $rules['no_hp'] = 'required|string|max:15';
            $rules['alamat'] = 'required|string';
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'role.required' => 'Role wajib dipilih',
            'nim.required' => 'NIM wajib diisi untuk mahasiswa',
            'nim.unique' => 'NIM sudah terdaftar',
            'jurusan.required' => 'Jurusan wajib diisi untuk mahasiswa',
            'no_hp.required' => 'No HP wajib diisi untuk pengguna luar',
            'alamat.required' => 'Alamat wajib diisi untuk pengguna luar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        DB::beginTransaction();
        try {
            // Update data user
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];

            // Reset field yang tidak digunakan
            $updateData['nim'] = null;
            $updateData['no_hp'] = null;
            $updateData['alamat'] = null;

            // Set data spesifik berdasarkan role
            if ($validated['role'] === 'mahasiswa') {
                $updateData['nim'] = $validated['nim'];
            } elseif ($validated['role'] === 'pengguna_luar') {
                $updateData['no_hp'] = $validated['no_hp'];
                $updateData['alamat'] = $validated['alamat'];
            }

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Handle perubahan role
            if ($validated['role'] === 'mahasiswa') {
                // Jika sudah ada data mahasiswa, update
                if ($user->mahasiswa) {
                    $user->mahasiswa->update([
                        'nama' => $validated['name'],
                        'email' => $validated['email'],
                        'nim' => $validated['nim'],
                        'jurusan' => $validated['jurusan'],
                    ]);
                } else {
                    // Jika belum ada, buat baru
                    Mahasiswa::create([
                        'nama' => $validated['name'],
                        'email' => $validated['email'],
                        'nim' => $validated['nim'],
                        'jurusan' => $validated['jurusan'],
                    ]);
                }
            } else {
                // Jika role bukan mahasiswa, hapus data mahasiswa jika ada
                if ($user->mahasiswa) {
                    $user->mahasiswa->delete();
                }
            }

            DB::commit();
            return redirect()->route('admin.users.index')
                            ->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Cegah hapus akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;
            
            // Hapus data mahasiswa jika ada
            if ($user->mahasiswa) {
                $user->mahasiswa->delete();
            }
            
            $user->delete();

            DB::commit();
            return redirect()->route('admin.users.index')
                            ->with('success', "User {$userName} berhasil dihapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.users.index')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Bulk delete users
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        
        // Filter: jangan hapus akun sendiri
        $userIds = array_filter($userIds, function($id) {
            return $id != auth()->id();
        });

        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak ada user yang dihapus');
        }

        DB::beginTransaction();
        try {
            // Hapus data mahasiswa terkait
            $emails = User::whereIn('id', $userIds)->pluck('email');
            Mahasiswa::whereIn('email', $emails)->delete();
            
            // Hapus user
            $deletedCount = User::whereIn('id', $userIds)->delete();

            DB::commit();
            return redirect()->route('admin.users.index')
                            ->with('success', "{$deletedCount} user berhasil dihapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.users.index')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Export data user
    public function export(Request $request)
    {
        $query = User::with('mahasiswa');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $filename = 'users_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['ID', 'Nama', 'Email', 'Role', 'NIM', 'Jurusan', 'No HP', 'Alamat', 'Tanggal Daftar']);
            
            // Data
            foreach ($users as $user) {
                $nim = '-';
                $jurusan = '-';
                
                if ($user->role === 'mahasiswa') {
                    if ($user->mahasiswa) {
                        $nim = $user->mahasiswa->nim;
                        $jurusan = $user->mahasiswa->jurusan ?? '-';
                    } elseif ($user->nim) {
                        $nim = $user->nim;
                    }
                }
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst(str_replace('_', ' ', $user->role)),
                    $nim,
                    $jurusan,
                    $user->no_hp ?? '-',
                    $user->alamat ?? '-',
                    $user->created_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}