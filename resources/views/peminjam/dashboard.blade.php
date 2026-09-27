@extends('layouts.dashboard')

@section('title', 'Dashboard Peminjam')

@section('content')
    <style>
        /* Card Books Grid */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .book-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
            display: flex;
            flex-direction: column;
            transition: all 0.2s ease;
        }

        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: var(--border-focus);
        }

        .book-cover {
            height: 140px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
            position: relative;
        }

        .book-cover-badge {
            align-self: flex-start;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 8px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(4px);
        }

        .book-cover-icon {
            align-self: flex-end;
            opacity: 0.8;
        }

        .book-body {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .book-category {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .book-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.35;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-author {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .book-footer {
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .book-stock {
            font-size: 12px;
            font-weight: 600;
            color: var(--success);
        }

        .btn-action-loan {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.15s;
        }

        .btn-action-loan:hover {
            background-color: var(--primary-hover);
        }

        /* Active Loans Card Style */
        .loan-card-item {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: var(--shadow-xs);
            transition: border-color 0.2s;
        }

        .loan-card-item:hover {
            border-color: #cbd5e1;
        }

        .loan-card-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .loan-book-avatar {
            width: 44px;
            height: 56px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
            font-weight: 700;
            font-size: 12px;
            text-align: center;
            padding: 4px;
        }

        .loan-info h4 {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .loan-info p {
            font-size: 12.5px;
            color: var(--text-muted);
        }

        .loan-timeline {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .loan-time-box {
            text-align: right;
        }

        .loan-time-box span {
            display: block;
            font-size: 11px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .loan-time-box strong {
            font-size: 13px;
            color: var(--text-main);
        }

        /* Recommendation horizontal cards */
        .recommendation-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .rec-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 16px;
            display: flex;
            gap: 14px;
            align-items: center;
            box-shadow: var(--shadow-xs);
            transition: all 0.2s;
        }

        .rec-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
            border-color: var(--border-focus);
        }

        .rec-thumbnail {
            width: 48px;
            height: 64px;
            border-radius: 6px;
            background: linear-gradient(135deg, #1e293b, #334155);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
            text-align: center;
            padding: 4px;
        }

        .rec-body {
            flex: 1;
            overflow: hidden;
        }

        .rec-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 3px;
        }

        .rec-meta {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .rec-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11.5px;
        }

        /* Empty state */
        .empty-state {
            background: #ffffff;
            border: 1.5px dashed var(--border-color);
            border-radius: var(--radius-lg);
            padding: 36px 20px;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-state svg {
            margin-bottom: 12px;
            color: var(--text-light);
        }

        @media (max-width: 1024px) {
            .books-grid { grid-template-columns: repeat(2, 1fr); }
            .recommendation-grid { grid-template-columns: 1fr; }
            .loan-timeline { gap: 14px; }
        }

        @media (max-width: 640px) {
            .books-grid { grid-template-columns: 1fr; }
            .loan-card-item { flex-direction: column; align-items: flex-start; }
            .loan-timeline { width: 100%; justify-content: space-between; }
        }
    </style>

    {{-- 1. Greeting Banner --}}
    <section class="greeting-banner">
        <div class="greeting-text">
            <h1>Halo, {{ $user->name }} 👋</h1>
            <p>Selamat datang kembali di Perpustakaan. Kelola peminjaman dan jelajahi ribuan koleksi buku kami.</p>
            <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 6px;">
                <span>Email: <strong>{{ $user->email }}</strong></span> &bull; 
                <span>Peran: <strong class="badge-role-peminjam">Peminjam</strong></span> &bull; 
                <span>No. Identitas: <strong>{{ $user->nomor_identitas }}</strong></span>
            </div>
        </div>
        <div class="greeting-date">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </section>

    {{-- 2. Summary Cards --}}
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['sedang_dipinjam'] }}</h3>
                <p>Sedang Dipinjam</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['jatuh_tempo'] }}</h3>
                <p>Jatuh Tempo</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['total_riwayat'] }}</h3>
                <p>Total Riwayat</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($statistics['buku_tersedia'], 0, ',', '.') }}</h3>
                <p>Buku Tersedia</p>
            </div>
        </div>
    </section>

    {{-- 3. Section Peminjaman Aktif --}}
    <section style="margin-bottom: 36px;" id="peminjaman-aktif">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Peminjaman Aktif
            </h2>
            <a href="{{ route('peminjam.peminjaman.index', ['tab' => 'riwayat']) }}" class="section-link">Lihat Semua Riwayat &rarr;</a>
        </div>

        @if ($activeLoans->count() > 0)
            @php
                $avatarColors = ['#1e3a8a', '#0f766e', '#7c2d12', '#4c1d95', '#be123c'];
            @endphp
            @foreach ($activeLoans as $idx => $loan)
                <div class="loan-card-item">
                    <div class="loan-card-left">
                        <div class="loan-book-avatar" style="background-color: {{ $avatarColors[$idx % count($avatarColors)] }};">
                            BUKU
                        </div>
                        <div class="loan-info">
                            <h4>{{ $loan->book ? $loan->book->judul : 'Buku #' . $loan->book_id }}</h4>
                            <p>
                                {{ $loan->book ? $loan->book->penulis : '-' }} &bull; 
                                <span style="color: var(--primary);">{{ $loan->book ? $loan->book->kategori : '-' }}</span> &bull; 
                                ID Pinjam: PJ-{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                    </div>

                    <div class="loan-timeline">
                        <div class="loan-time-box">
                            <span>Tgl Pinjam</span>
                            <strong>{{ $loan->tanggal_pinjam->translatedFormat('d M Y') }}</strong>
                        </div>

                        <div class="loan-time-box">
                            <span>Batas Kembali</span>
                            <strong style="color: #dc2626;">{{ $loan->tanggal_jatuh_tempo->translatedFormat('d M Y') }}</strong>
                        </div>

                        <div>
                            <span class="badge {{ $loan->badge_class }}">{{ $loan->status_label }}</span>
                            <div style="font-size: 11px; color: var(--text-muted); text-align: center; margin-top: 3px;">{{ $loan->sisa_hari_text }}</div>
                        </div>

                        @if ($loan->book)
                            <a href="{{ route('peminjam.katalog.show', $loan->book->id) }}" class="btn-sm-action">Detail</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            {{-- Empty State jika tidak ada peminjaman --}}
            <div class="empty-state">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3>Tidak Ada Peminjaman Aktif</h3>
                <p>Anda belum meminjam buku saat ini. Silakan jelajahi katalog buku untuk melakukan peminjaman.</p>
                <div style="margin-top: 14px;">
                    <a href="{{ route('peminjam.katalog.index') }}" class="btn-action-loan" style="display: inline-block;">
                        Jelajahi Katalog Buku
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- 4. Section Buku Terbaru --}}
    <section style="margin-bottom: 36px;" id="katalog">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Koleksi Buku Terbaru
            </h2>
            <a href="{{ route('peminjam.katalog.index') }}" class="section-link">Buka Katalog Lengkap &rarr;</a>
        </div>

        <div class="books-grid">
            @php
                $gradients = [
                    'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
                    'linear-gradient(135deg, #065f46 0%, #10b981 100%)',
                    'linear-gradient(135deg, #7c2d12 0%, #ea580c 100%)',
                    'linear-gradient(135deg, #4c1d95 0%, #8b5cf6 100%)',
                ];
            @endphp
            @foreach ($latestBooks as $idx => $book)
                <div class="book-card">
                    <div class="book-cover" style="background: {{ $gradients[$idx % count($gradients)] }};">
                        <span class="book-cover-badge">{{ $book->tahun_terbit }}</span>
                        <div class="book-cover-icon">
                            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                    <div class="book-body">
                        <span class="book-category">{{ $book->kategori }}</span>
                        <h3 class="book-title" title="{{ $book->judul }}">{{ $book->judul }}</h3>
                        <p class="book-author">Oleh {{ $book->penulis }}</p>

                        <div class="book-footer">
                            @if ($book->stok > 0)
                                <span class="book-stock">✓ Stok ({{ $book->stok }})</span>
                                <a href="{{ route('peminjam.katalog.show', $book->id) }}" class="btn-action-loan">Lihat</a>
                            @else
                                <span style="font-size: 12px; font-weight: 600; color: #dc2626;">Stok Habis</span>
                                <a href="{{ route('peminjam.katalog.show', $book->id) }}" class="btn-action-loan" style="background-color: #94a3b8;">Detail</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 5. Section Rekomendasi Buku --}}
    <section style="margin-bottom: 20px;">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Rekomendasi Populer Mahasiswa
            </h2>
        </div>

        <div class="recommendation-grid">
            @php
                $recColors = ['#0284c7', '#d97706', '#059669'];
            @endphp
            @foreach ($recommendations as $idx => $rec)
                <div class="rec-card">
                    <div class="rec-thumbnail" style="background: linear-gradient(135deg, {{ $recColors[$idx % count($recColors)] }}, #1e293b);">
                        BUKU
                    </div>
                    <div class="rec-body">
                        <h4 class="rec-title" title="{{ $rec->judul }}">{{ $rec->judul }}</h4>
                        <div class="rec-meta">{{ $rec->penulis }} &bull; {{ $rec->kategori }}</div>
                        <div class="rec-bottom">
                            <span class="badge badge-success">Tersedia ({{ $rec->stok }})</span>
                            <a href="{{ route('peminjam.katalog.show', $rec->id) }}" class="btn-sm-action">Lihat</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
