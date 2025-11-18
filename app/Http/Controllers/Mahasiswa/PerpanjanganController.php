<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Perpanjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerpanjanganController extends Controller
{
    /**
     * Tampilkan form perpanjangan
     */
    public function create($peminjamanId)
    {
        $peminjaman = Peminjaman::with(['buku', 'perpanjangan'])
            ->where('id', $peminjamanId)
            ->where('mahasiswa_id', Auth::id())
            ->firstOrFail();

        // Cek apakah bisa diperpanjang
        if (!$peminjaman->bisakahDiperpanjang()) {
            $alasan = $peminjaman->alasanTidakBisaDiperpanjang();
            return redirect()->back()->with('error', $alasan);
        }

        return view('mahasiswa.peminjaman.perpanjang', compact('peminjaman'));
    }

    /**
     * Proses pengajuan perpanjangan
     */
    public function store(Request $request, $peminjamanId)
    {
        $peminjaman = Peminjaman::where('id', $peminjamanId)
            ->where('mahasiswa_id', Auth::id())
            ->where('status', 'dipinjam')
            ->firstOrFail();

        // Validasi kelayakan perpanjangan
        if (!$peminjaman->bisakahDiperpanjang()) {
            $alasan = $peminjaman->alasanTidakBisaDiperpanjang();
            return redirect()->back()->with('error', $alasan);
        }

        // Validasi input form
        $validated = $request->validate([
            'durasi_tambahan' => 'required|integer|min:1|max:7',
            'alasan' => 'required|string|max:500',
        ], [
            'durasi_tambahan.required' => 'Durasi tambahan wajib diisi',
            'durasi_tambahan.min' => 'Durasi tambahan minimal 1 hari',
            'durasi_tambahan.max' => 'Durasi tambahan maksimal 7 hari',
            'alasan.required' => 'Alasan perpanjangan wajib diisi',
            'alasan.max' => 'Alasan maksimal 500 karakter',
        ]);

        DB::beginTransaction();
        try {
            $tanggalDeadlineBaru = Carbon::parse($peminjaman->tanggal_deadline)
                ->addDays($validated['durasi_tambahan']);

            Perpanjangan::create([
                'peminjaman_id' => $peminjaman->id,
                'tanggal_perpanjangan' => now(),
                'tanggal_deadline_lama' => $peminjaman->tanggal_deadline,
                'tanggal_deadline_baru' => $tanggalDeadlineBaru,
                'durasi_tambahan' => $validated['durasi_tambahan'],
                'alasan' => $validated['alasan'],
                'status' => 'menunggu',
            ]);

            DB::commit();

            return redirect()->route('mahasiswa.peminjaman.riwayat')
                ->with('success', 'Pengajuan perpanjangan berhasil dikirim. Menunggu persetujuan petugas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan pengajuan perpanjangan
     */
    public function cancel($perpanjanganId)
    {
        $perpanjangan = Perpanjangan::whereHas('peminjaman', function($query) {
            $query->where('mahasiswa_id', Auth::id());
        })
        ->where('id', $perpanjanganId)
        ->where('status', 'menunggu')
        ->firstOrFail();

        $perpanjangan->delete();

        return redirect()->back()->with('success', 'Pengajuan perpanjangan berhasil dibatalkan');
    }
}