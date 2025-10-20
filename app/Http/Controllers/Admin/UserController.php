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

        // Search berdasarkan nama, email, atau NIM
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
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
        
        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalAdmin',
            'totalPetugas',
            'totalMahasiswa'
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,petugas,mahasiswa',
            'nim' => [
                'required_if:role,mahasiswa',
                'nullable',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim')
            ],
            'jurusan' => 'required_if:role,mahasiswa|nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'role.required' => 'Role wajib dipilih',
            'nim.required_if' => 'NIM wajib diisi untuk mahasiswa',
            'nim.unique' => 'NIM sudah terdaftar',
            'jurusan.required_if' => 'Jurusan wajib diisi untuk mahasiswa',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        DB::beginTransaction();
        try {
            // Buat user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
            ]);

            // Jika role mahasiswa, simpan ke tabel mahasiswa
            if ($validated['role'] === 'mahasiswa') {
                Mahasiswa::create([
                    'nama' => $validated['name'],
                    'email' => $validated['email'],
                    'nim' => $validated['nim'],
                    'jurusan' => $validated['jurusan'] ?? null,
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'role' => 'required|in:admin,petugas,mahasiswa',
            'nim' => [
                'required_if:role,mahasiswa',
                'nullable',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim')->ignore($user->mahasiswa->id ?? null)
            ],
            'jurusan' => 'required_if:role,mahasiswa|nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'role.required' => 'Role wajib dipilih',
            'nim.required_if' => 'NIM wajib diisi untuk mahasiswa',
            'nim.unique' => 'NIM sudah terdaftar',
            'jurusan.required_if' => 'Jurusan wajib diisi untuk mahasiswa',
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
                        'jurusan' => $validated['jurusan'] ?? null,
                    ]);
                } else {
                    // Jika belum ada, buat baru
                    Mahasiswa::create([
                        'nama' => $validated['name'],
                        'email' => $validated['email'],
                        'nim' => $validated['nim'],
                        'jurusan' => $validated['jurusan'] ?? null,
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

    // Export data user (optional)
    public function export(Request $request)
    {
        $query = User::with('mahasiswa');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        $filename = 'users_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['ID', 'Nama', 'Email', 'NIM', 'Jurusan', 'Role', 'Tanggal Daftar']);
            
            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->display_name,
                    $user->email,
                    $user->mahasiswa->nim ?? '-',
                    $user->mahasiswa->jurusan ?? '-',
                    $user->role,
                    $user->created_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}