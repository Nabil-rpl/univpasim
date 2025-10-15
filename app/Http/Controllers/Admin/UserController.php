<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,petugas,mahasiswa',
            'nim' => [
                'required_if:role,mahasiswa',
                'nullable',
                'string',
                'max:20',
                'unique:users,nim,NULL'
            ],
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nim' => $request->nim,
            'password' => $request->password, // Auto-hashed via model cast
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Menampilkan detail user
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,mahasiswa',
            'nim' => [
                'required_if:role,mahasiswa',
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'nim')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Array data untuk update (base fields)
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nim' => $request->nim,
        ];

        // Hanya tambahkan password jika diisi (tidak kosong/null)
        if ($request->filled('password')) {
            $updateData['password'] = $request->password; // Auto-hashed via model cast
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    // Hapus user
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}