@extends('layouts.dashboard')

@section('title', 'Dashboard Petugas Perpustakaan')

@section('content')
    <style>
        /* Quick Actions Grid */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .quick-action-btn {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--text-main);
            box-shadow: var(--shadow-xs);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            border-color: var(--border-focus);
            box-shadow: var(--shadow-md);
            background-color: #fafbfc;
        }

        .quick-action-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .quick-action-icon.blue { background-color: #eff6ff; color: #2563eb; }
        .quick-action-icon.indigo { background-color: #eef2ff; color: #4f46e5; }
        .quick-action-icon.emerald { background-color: #ecfdf5; color: #059669; }
        .quick-action-icon.slate { background-color: #f1f5f9; color: #475569; }

        .quick-action-text h4 {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .quick-action-text p {
            font-size: 11.5px;
            color: var(--text-muted);
        }

        /* 2-column layout for Table and Activities */
        .admin-main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        /* Activity Card */
        .activity-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 22px;
            box-shadow: var(--shadow-xs);
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
            margin-top: 14px;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            position: relative;
        }

        .activity-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .activity-desc {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .activity-time {
            font-size: 11px;
            color: var(--text-light);
            font-weight: 500;
        }

        @media (max-width: 1024px) {
            .quick-actions-grid { grid-template-columns: repeat(2, 1fr); }
            .admin-main-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 640px) {
            .quick-actions-grid { grid-template-columns: 1fr; }
        }
    </style>

    {{-- 1. Greeting Banner Petugas --}}
    <section class="greeting-banner">
        <div class="greeting-text">
            <h1>Selamat datang, {{ $user->name }}</h1>
            <p>Berikut ringkasan aktivitas perpustakaan hari ini dan pemantauan sirkulasi buku.</p>
            <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 6px;">
                <span>Email: <strong>{{ $user->email }}</strong></span> &bull; 
                <span>Peran: <strong class="badge-role-petugas">Petugas Perpustakaan</strong></span> &bull; 
                <span>ID Pegawai: <strong>{{ $user->nomor_identitas }}</strong></span>
            </div>
        </div>
        <div class="greeting-date">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </section>

    {{-- 2. Summary Cards Statistik Administrasi --}}
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($statistics['total_buku'], 0, ',', '.') }}</h3>
                <p>Total Koleksi Buku</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($statistics['total_peminjam'], 0, ',', '.') }}</h3>
                <p>Total Peminjam</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['sedang_dipinjam'] }}</h3>
                <p>Sedang Dipinjam</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon rose">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['terlambat'] }}</h3>
                <p>Terlambat Kembali</p>
            </div>
        </div>
    </section>

    {{-- 5. Quick Actions (Aksi Cepat) --}}
    <section>
        <div class="section-header">
            <h2 class="section-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Menu Aksi Cepat
            </h2>
        </div>

        <div class="quick-actions-grid">
            <a href="{{ route('petugas.buku.create') }}" class="quick-action-btn">
                <div class="quick-action-icon blue">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div class="quick-action-text">
                    <h4>Tambah Buku</h4>
                    <p>Input judul &amp; eksemplar baru</p>
                </div>
            </a>

            <a href="{{ route('petugas.peminjaman.index') }}" class="quick-action-btn">
                <div class="quick-action-icon indigo">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div class="quick-action-text">
                    <h4>Kelola Peminjaman</h4>
                    <p>Pantau seluruh sirkulasi buku</p>
                </div>
            </a>

            <a href="{{ route('petugas.peminjaman.index', ['status' => 'dipinjam']) }}" class="quick-action-btn">
                <div class="quick-action-icon emerald">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="quick-action-text">
                    <h4>Proses Pengembalian</h4>
                    <p>Catat pengembalian buku</p>
                </div>
            </a>

            <a href="javascript:void(0)" class="quick-action-btn">
                <div class="quick-action-icon slate">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="quick-action-text">
                    <h4>Lihat Laporan</h4>
                    <p>Rekap sirkulasi &amp; denda</p>
                </div>
            </a>
        </div>
    </section>

    {{-- Main 2-column Grid: Table and Recent Activities --}}
    <div class="admin-main-grid">
        {{-- 3. Section Peminjaman Terbaru (Tabel) --}}
        <div>
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Peminjaman Terbaru
                </h2>
                <a href="javascript:void(0)" class="section-link">Kelola Semua Transaksi &rarr;</a>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentLoans as $loan)
                            <tr>
                                <td>{{ $loan['no'] }}</td>
                                <td>
                                    <strong>{{ $loan['peminjam'] }}</strong>
                                    <div style="font-size: 11.5px; color: var(--text-muted);">{{ $loan['identitas'] }}</div>
                                </td>
                                <td>
                                    <span>{{ $loan['buku'] }}</span>
                                    <div style="font-size: 11px; color: var(--text-light);">{{ $loan['kode_transaksi'] }}</div>
                                </td>
                                <td>{{ $loan['tgl_pinjam'] }}</td>
                                <td>
                                    @if ($loan['status'] === 'Terlambat')
                                        <span style="color: #dc2626; font-weight: 600;">{{ $loan['jatuh_tempo'] }}</span>
                                    @else
                                        <span>{{ $loan['jatuh_tempo'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $loan['badge_class'] }}">{{ $loan['status'] }}</span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="javascript:void(0)" class="btn-sm-action">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. Section Aktivitas Perpustakaan --}}
        <div>
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Aktivitas Terkini
                </h2>
            </div>

            <div class="activity-card">
                <div class="activity-list">
                    @foreach ($activities as $act)
                        <div class="activity-item">
                            <div class="activity-dot" style="background-color: {{ $act['color'] }};">
                                @if ($act['badge_icon'] === 'book')
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                                    </svg>
                                @elseif ($act['badge_icon'] === 'check')
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                @elseif ($act['badge_icon'] === 'plus')
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                @else
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01" />
                                    </svg>
                                @endif
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $act['judul'] }}</div>
                                <div class="activity-desc">{{ $act['deskripsi'] }}</div>
                                <div class="activity-time">{{ $act['waktu'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
