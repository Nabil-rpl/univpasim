<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of mahasiswa.
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        // Search berdasarkan nama, email, atau NIM
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan jurusan
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        $mahasiswas = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get unique jurusan for filter
        $jurusanList = Mahasiswa::select('jurusan')
            ->distinct()
            ->whereNotNull('jurusan')
            ->pluck('jurusan');
        
        return view('admin.mahasiswa.index', compact('mahasiswas', 'jurusanList'));
    }

    /**
     * Show the form for creating a new mahasiswa.
     */
    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    /**
     * Store a newly created mahasiswa in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:mahasiswa,email',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'jurusan' => 'nullable|string|max:100',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar',
        ]);

        DB::beginTransaction();
        try {
            // Create mahasiswa
            $mahasiswa = Mahasiswa::create($validated);

            // ✅ KIRIM NOTIFIKASI KE SEMUA ADMIN
            $adminUsers = User::where('role', 'admin')->pluck('id');
            
            foreach ($adminUsers as $adminId) {
                Notifikasi::create([
                    'user_id' => $adminId,
                    'tipe' => 'mahasiswa_baru',
                    'judul' => 'Mahasiswa Baru Terdaftar',
                    'isi' => "Mahasiswa baru dengan nama {$mahasiswa->nama} (NIM: {$mahasiswa->nim}) telah ditambahkan ke sistem.",
                    'related_type' => 'mahasiswa',
                    'related_id' => $mahasiswa->id,
                    'data' => json_encode([
                        'nama' => $mahasiswa->nama,
                        'nim' => $mahasiswa->nim,
                        'email' => $mahasiswa->email,
                        'jurusan' => $mahasiswa->jurusan ?? '-',
                    ]),
                    'dibaca' => false,
                    'prioritas' => 'normal',
                    'dibuat_oleh' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.mahasiswa.index')
                            ->with('success', 'Data mahasiswa berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified mahasiswa.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        // Load relasi user jika ada
        $mahasiswa->load('user');
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified mahasiswa.
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified mahasiswa in storage.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:mahasiswa,email,' . $mahasiswa->id,
            'nim' => 'required|string|max:20|unique:mahasiswa,nim,' . $mahasiswa->id,
            'jurusan' => 'nullable|string|max:100',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar',
        ]);

        DB::beginTransaction();
        try {
            $oldEmail = $mahasiswa->email;
            
            // Update mahasiswa
            $mahasiswa->update($validated);

            // Jika email berubah, update juga di tabel users
            if ($oldEmail !== $validated['email'] && $mahasiswa->user) {
                $mahasiswa->user->update([
                    'email' => $validated['email'],
                    'name' => $validated['nama'],
                ]);
            }

            // ✅ KIRIM NOTIFIKASI UPDATE KE ADMIN (OPSIONAL)
            if ($oldEmail !== $validated['email']) {
                $adminUsers = User::where('role', 'admin')->pluck('id');
                
                foreach ($adminUsers as $adminId) {
                    Notifikasi::create([
                        'user_id' => $adminId,
                        'tipe' => 'sistem',
                        'judul' => 'Data Mahasiswa Diupdate',
                        'isi' => "Data mahasiswa {$mahasiswa->nama} (NIM: {$mahasiswa->nim}) telah diperbarui.",
                        'related_type' => 'mahasiswa',
                        'related_id' => $mahasiswa->id,
                        'data' => json_encode([
                            'perubahan' => 'Email diubah dari ' . $oldEmail . ' ke ' . $validated['email'],
                        ]),
                        'dibaca' => false,
                        'prioritas' => 'rendah',
                        'dibuat_oleh' => auth()->id(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.mahasiswa.index')
                            ->with('success', 'Data mahasiswa berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified mahasiswa from storage.
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $namaMahasiswa = $mahasiswa->nama;
            $nimMahasiswa = $mahasiswa->nim;
            
            // Hapus user terkait jika ada
            if ($mahasiswa->user) {
                $mahasiswa->user->delete();
            }
            
            $mahasiswa->delete();

            // ✅ KIRIM NOTIFIKASI HAPUS KE ADMIN (OPSIONAL)
            $adminUsers = User::where('role', 'admin')
                             ->where('id', '!=', auth()->id()) // Exclude current admin
                             ->pluck('id');
            
            foreach ($adminUsers as $adminId) {
                Notifikasi::create([
                    'user_id' => $adminId,
                    'tipe' => 'sistem',
                    'judul' => 'Data Mahasiswa Dihapus',
                    'isi' => "Data mahasiswa {$namaMahasiswa} (NIM: {$nimMahasiswa}) telah dihapus dari sistem oleh " . auth()->user()->name . ".",
                    'related_type' => 'mahasiswa',
                    'related_id' => null,
                    'data' => json_encode([
                        'nama' => $namaMahasiswa,
                        'nim' => $nimMahasiswa,
                        'dihapus_oleh' => auth()->user()->name,
                    ]),
                    'dibaca' => false,
                    'prioritas' => 'normal',
                    'dibuat_oleh' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.mahasiswa.index')
                            ->with('success', "Data mahasiswa {$namaMahasiswa} berhasil dihapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.mahasiswa.index')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export data mahasiswa to CSV
     */
    public function export(Request $request)
    {
        $query = Mahasiswa::query();
    
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }
    
        $mahasiswas = $query->get();
    
        $filename = 'data_mahasiswa_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
    
        $callback = function() use ($mahasiswas) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['ID', 'Nama', 'Email', 'NIM', 'Jurusan', 'Tanggal Daftar']);
            
            // Data
            /** @var \App\Models\Mahasiswa $mhs */
            foreach ($mahasiswas as $mhs) {
                fputcsv($file, [
                    $mhs->id,
                    $mhs->nama,
                    $mhs->email,
                    $mhs->nim,
                    $mhs->jurusan ?? '-',
                    $mhs->created_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
}