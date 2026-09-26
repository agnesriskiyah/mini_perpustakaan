<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk role Peminjam.
     * Menggunakan data dummy terstruktur yang siap digantikan Eloquent Model nantinya.
     */
    public function peminjam()
    {
        $user = Auth::user();

        // Data ringkasan (Summary Cards)
        $statistics = [
            'sedang_dipinjam' => 2,
            'jatuh_tempo' => 1,
            'total_riwayat' => 14,
            'buku_tersedia' => 1250,
        ];

        // Data peminjaman yang sedang aktif
        $activeLoans = [
            [
                'id' => 'PJ-2026-089',
                'judul' => 'Struktur Data & Algoritma dengan Python',
                'penulis' => 'Dr. Indrajit & Tim Lab',
                'kategori' => 'Komputer & IT',
                'cover_color' => '#1e3a8a',
                'tgl_pinjam' => '20 Sep 2026',
                'tgl_jatuh_tempo' => '27 Sep 2026',
                'sisa_hari' => '1 hari lagi',
                'status' => 'Mendekati Batas',
                'badge_class' => 'badge-warning',
            ],
            [
                'id' => 'PJ-2026-074',
                'judul' => 'Clean Architecture: Software Craftsmanship',
                'penulis' => 'Robert C. Martin',
                'kategori' => 'Software Engineering',
                'cover_color' => '#0f766e',
                'tgl_pinjam' => '18 Sep 2026',
                'tgl_jatuh_tempo' => '02 Okt 2026',
                'sisa_hari' => '6 hari lagi',
                'status' => 'Sedang Dipinjam',
                'badge_class' => 'badge-primary',
            ],
        ];

        // Data katalog buku terbaru
        $latestBooks = [
            [
                'id' => 101,
                'judul' => 'Pemrograman Web Modern Laravel 10',
                'penulis' => 'Rian Pratama, M.Kom',
                'penerbit' => 'Informatika Press',
                'tahun' => '2025',
                'kategori' => 'Teknologi Informasi',
                'isbn' => '978-602-1234-56-1',
                'stok' => 4,
                'total_stok' => 5,
                'cover_bg' => 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
            ],
            [
                'id' => 102,
                'judul' => 'Machine Learning & Deep Learning Fundamental',
                'penulis' => 'Prof. Dr. Ir. Gunawan, M.T.',
                'penerbit' => 'Sains Komputasi',
                'tahun' => '2024',
                'kategori' => 'Kecerdasan Buatan',
                'isbn' => '978-602-9876-12-0',
                'stok' => 2,
                'total_stok' => 4,
                'cover_bg' => 'linear-gradient(135deg, #065f46 0%, #10b981 100%)',
            ],
            [
                'id' => 103,
                'judul' => 'Sistem Basis Data Relasional & NoSQL',
                'penulis' => 'Siti Nur Aini, S.Kom, M.T.',
                'penerbit' => 'Gava Media',
                'tahun' => '2023',
                'kategori' => 'Database',
                'isbn' => '978-602-5432-88-9',
                'stok' => 6,
                'total_stok' => 6,
                'cover_bg' => 'linear-gradient(135deg, #7c2d12 0%, #ea580c 100%)',
            ],
            [
                'id' => 104,
                'judul' => 'Metodologi Penelitian Ilmu Komputer',
                'penulis' => 'Dr. Eng. Wahyudi, S.T., M.Sc.',
                'penerbit' => 'Andi Publisher',
                'tahun' => '2024',
                'kategori' => 'Akademik & Riset',
                'isbn' => '978-602-7654-32-1',
                'stok' => 3,
                'total_stok' => 3,
                'cover_bg' => 'linear-gradient(135deg, #4c1d95 0%, #8b5cf6 100%)',
            ],
        ];

        // Rekomendasi buku untuk peminjam
        $recommendations = [
            [
                'id' => 201,
                'judul' => 'Cyber Security Essentials & Ethical Hacking',
                'penulis' => 'Bambang Sudarsono, CEH',
                'kategori' => 'Keamanan Jaringan',
                'rating' => 4.9,
                'dipinjam_count' => '42x dipinjam',
                'status' => 'Tersedia',
                'cover_accent' => '#0284c7',
            ],
            [
                'id' => 202,
                'judul' => 'Desain Interaksi UI/UX Aplikasi Web',
                'penulis' => 'Maya Anggraini, M.Ds.',
                'kategori' => 'Desain Grafis',
                'rating' => 4.8,
                'dipinjam_count' => '38x dipinjam',
                'status' => 'Tersedia',
                'cover_accent' => '#d97706',
            ],
            [
                'id' => 203,
                'judul' => 'Cloud Computing Architecture dengan AWS',
                'penulis' => 'Kevin Sanjaya, AWS Pro',
                'kategori' => 'Infrastruktur Cloud',
                'rating' => 4.7,
                'dipinjam_count' => '31x dipinjam',
                'status' => 'Tersedia',
                'cover_accent' => '#059669',
            ],
        ];

        return view('peminjam.dashboard', compact('user', 'statistics', 'activeLoans', 'latestBooks', 'recommendations'));
    }

    /**
     * Dashboard untuk role Petugas Perpustakaan.
     * Menggunakan data dummy terstruktur yang siap digantikan Eloquent Model nantinya.
     */
    public function petugas()
    {
        $user = Auth::user();

        // Data statistik administrasi perpustakaan
        $statistics = [
            'total_buku' => 3840,
            'total_peminjam' => 512,
            'sedang_dipinjam' => 48,
            'terlambat' => 5,
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
