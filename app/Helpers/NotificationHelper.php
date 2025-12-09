<?php

if (!function_exists('getNotificationColor')) {
    function getNotificationColor($tipe) {
        $colors = [
            'peminjaman_baru' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'peminjaman_disetujui' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
            'peminjaman_ditolak' => 'linear-gradient(135deg, #eb3349 0%, #f45c43 100%)',
            'perpanjangan_baru' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'perpanjangan_disetujui' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
            'perpanjangan_ditolak' => 'linear-gradient(135deg, #eb3349 0%, #f45c43 100%)',
            'reminder_deadline' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            'terlambat' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            'pengembalian_sukses' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
            'buku_tersedia' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'denda_belum_dibayar' => 'linear-gradient(135deg, #ffa751 0%, #ffe259 100%)',
            'user_baru' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'buku_baru' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'laporan_baru' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'stok_menipis' => 'linear-gradient(135deg, #ffa751 0%, #ffe259 100%)',
            'sistem' => 'linear-gradient(135deg, #a8caba 0%, #5d4e6d 100%)'
        ];

        return $colors[$tipe] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    }
}

if (!function_exists('getNotificationIcon')) {
    function getNotificationIcon($tipe) {
        $icons = [
            'peminjaman_baru' => 'book-fill',
            'peminjaman_disetujui' => 'check-circle-fill',
            'peminjaman_ditolak' => 'x-circle-fill',
            'perpanjangan_baru' => 'arrow-clockwise',
            'perpanjangan_disetujui' => 'check2-circle',
            'perpanjangan_ditolak' => 'x-octagon',
            'reminder_deadline' => 'alarm',
            'terlambat' => 'exclamation-triangle-fill',
            'pengembalian_sukses' => 'check-circle',
            'buku_tersedia' => 'bell-fill',
            'denda_belum_dibayar' => 'cash-coin',
            'user_baru' => 'person-plus-fill',
            'buku_baru' => 'journal-plus',
            'laporan_baru' => 'file-earmark-text',
            'stok_menipis' => 'exclamation-circle',
            'sistem' => 'info-circle-fill'
        ];

        return $icons[$tipe] ?? 'bell';
    }
}

if (!function_exists('getStatusBadgeClass')) {
    function getStatusBadgeClass($tipe) {
        return match ($tipe) {
            // Status INFO (biru)
            'peminjaman_baru',
            'perpanjangan_baru',
            'user_baru',
            'buku_baru',
            'laporan_baru',
            'buku_tersedia' => 'badge-info',

            // Status SUCCESS (hijau)
            'peminjaman_disetujui',
            'perpanjangan_disetujui',
            'pengembalian_sukses' => 'badge-success',

            // Status WARNING (kuning)
            'reminder_deadline',
            'stok_menipis' => 'badge-warning',

            // Status DANGER (merah)
            'peminjaman_ditolak',
            'perpanjangan_ditolak',
            'terlambat',
            'denda_belum_dibayar' => 'badge-danger',

            // Default
            default => 'badge-info',
        };
    }
}

