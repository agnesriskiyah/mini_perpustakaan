<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk role Peminjam.
     * Menggunakan data riil dari database (Book & Loan).
     */
    public function peminjam()
    {
        $user = Auth::user();

        // 1. Data ringkasan (Summary Cards) dari database
        $sedangDipinjamCount = \App\Models\Loan::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        $jatuhTempoCount = \App\Models\Loan::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<=', \Carbon\Carbon::today()->addDays(2)->format('Y-m-d'))
            ->count();

        $totalRiwayatCount = \App\Models\Loan::where('user_id', $user->id)->count();

        $bukuTersediaCount = \App\Models\Book::where('stok', '>', 0)->count();

        $statistics = [
            'sedang_dipinjam' => $sedangDipinjamCount,
            'jatuh_tempo' => $jatuhTempoCount,
            'total_riwayat' => $totalRiwayatCount,
            'buku_tersedia' => $bukuTersediaCount,
        ];

        // 2. Data peminjaman yang sedang aktif milik user login
        $activeLoans = \App\Models\Loan::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_pinjam', 'desc')
            ->take(5)
            ->get()
            ->map(function ($loan) {
                return PeminjamanController::formatLoanData($loan);
            });

        // 3. Data katalog buku terbaru dari database
        $latestBooks = \App\Models\Book::orderBy('created_at', 'desc')->take(4)->get();

        // Rekomendasi buku untuk peminjam (buku acak / populer dari database)
        $recommendations = \App\Models\Book::where('stok', '>', 0)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('peminjam.dashboard', compact('user', 'statistics', 'activeLoans', 'latestBooks', 'recommendations'));
    }

    /**
     * Dashboard untuk role Petugas Perpustakaan.
     */
    public function petugas()
    {
        $user = Auth::user();

        // Data statistik administrasi perpustakaan riil dari database
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $statistics = [
            'total_buku' => \App\Models\Book::count(),
            'total_peminjam' => \App\Models\User::where('role', 'peminjam')->count(),
            'sedang_dipinjam' => \App\Models\Loan::where('status', 'dipinjam')->count(),
            'terlambat' => \App\Models\Loan::where('status', 'dipinjam')->where('tanggal_jatuh_tempo', '<', $today)->count(),
        ];

        // Daftar peminjaman terbaru untuk tabel administrasi
        $recentLoans = [
            [
                'no' => 1,
                'kode_transaksi' => 'TRX-202609-001',
                'peminjam' => 'Ahmad Fauzi',
                'identitas' => 'NIM: 2105120401',
                'buku' => 'Basis Data Relasional & NoSQL',
                'tgl_pinjam' => '25 Sep 2026',
                'jatuh_tempo' => '02 Okt 2026',
                'status' => 'Dipinjam',
                'badge_class' => 'badge-primary',
            ],
            [
                'no' => 2,
                'kode_transaksi' => 'TRX-202609-002',
                'peminjam' => 'Dewi Lestari',
                'identitas' => 'NIM: 2105120415',
                'buku' => 'Kecerdasan Buatan Terapan',
                'tgl_pinjam' => '26 Sep 2026',
                'jatuh_tempo' => '03 Okt 2026',
                'status' => 'Menunggu',
                'badge_class' => 'badge-warning',
            ],
            [
                'no' => 3,
                'kode_transaksi' => 'TRX-202609-003',
                'peminjam' => 'Rizky Ramadhan',
                'identitas' => 'NIM: 2005120388',
                'buku' => 'Jaringan Komputer Lanjut',
                'tgl_pinjam' => '12 Sep 2026',
                'jatuh_tempo' => '19 Sep 2026',
                'status' => 'Terlambat',
                'badge_class' => 'badge-danger',
            ],
            [
                'no' => 4,
                'kode_transaksi' => 'TRX-202609-004',
                'peminjam' => 'Nadia Syahrini',
                'identitas' => 'NIM: 2205120520',
                'buku' => 'Desain Antarmuka UI/UX Modern',
                'tgl_pinjam' => '15 Sep 2026',
                'jatuh_tempo' => '22 Sep 2026',
                'status' => 'Dikembalikan',
                'badge_class' => 'badge-success',
            ],
            [
                'no' => 5,
                'kode_transaksi' => 'TRX-202609-005',
                'peminjam' => 'Bambang Kusuma',
                'identitas' => 'NIM: 2105120409',
                'buku' => 'Rekayasa Perangkat Lunak',
                'tgl_pinjam' => '24 Sep 2026',
                'jatuh_tempo' => '01 Okt 2026',
                'status' => 'Dipinjam',
                'badge_class' => 'badge-primary',
            ],
        ];

        // Aktivitas perpustakaan terkini (Timeline Activity)
        $activities = [
            [
                'id' => 1,
                'waktu' => '10 menit lalu',
                'tipe' => 'peminjaman',
                'judul' => 'Peminjaman Baru Diajukan',
                'deskripsi' => 'Dewi Lestari mengajukan peminjaman buku "Kecerdasan Buatan Terapan".',
                'badge_icon' => 'book',
                'color' => '#3b82f6',
            ],
            [
                'id' => 2,
                'waktu' => '45 menit lalu',
                'tipe' => 'pengembalian',
                'judul' => 'Buku Berhasil Dikembalikan',
                'deskripsi' => 'Nadia Syahrini mengembalikan buku "Desain Antarmuka UI/UX Modern" dalam kondisi baik.',
                'badge_icon' => 'check',
                'color' => '#10b981',
            ],
            [
                'id' => 3,
                'waktu' => '2 jam lalu',
                'tipe' => 'katalog',
                'judul' => 'Stok Eksemplar Buku Ditambahkan',
                'deskripsi' => 'Staf menambahkan 5 eksemplar baru untuk buku "Pemrograman Web Modern Laravel 10".',
                'badge_icon' => 'plus',
                'color' => '#8b5cf6',
            ],
            [
                'id' => 4,
                'waktu' => '4 jam lalu',
                'tipe' => 'notifikasi',
                'judul' => 'Peringatan Jatuh Tempo Dikirim',
                'deskripsi' => 'Notifikasi pengingat otomatis dikirim kepada Rizky Ramadhan (terlambat 7 hari).',
                'badge_icon' => 'alert',
                'color' => '#ef4444',
            ],
        ];

        return view('petugas.dashboard', compact('user', 'statistics', 'recentLoans', 'activities'));
    }
}
