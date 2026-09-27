@extends('layouts.dashboard')

@section('title', 'Detail Transaksi Peminjaman #' . $loan->id . ' - Petugas')

@section('content')
<style>
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .breadcrumb-nav a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .loan-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .detail-card-panel {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        box-shadow: var(--shadow-xs);
    }

    .panel-header-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 18px;
    }

    .info-list-rows {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .info-row-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13.5px;
    }

    .info-row-label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .info-row-val {
        color: var(--text-main);
        font-weight: 600;
        text-align: right;
    }

    .book-preview-strip {
        display: flex;
        gap: 16px;
        align-items: center;
        margin-bottom: 18px;
    }

    .book-cover-square {
        width: 60px;
        height: 80px;
        border-radius: 6px;
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .book-cover-square img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Return Action Banner */
    .return-action-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        box-shadow: var(--shadow-xs);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .return-info-text h3 {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .return-info-text p {
        font-size: 13.5px;
        color: var(--text-muted);
    }

    .btn-process-return {
        padding: 12px 26px;
        font-size: 14px;
        font-weight: 600;
        font-family: inherit;
        background-color: var(--success);
        color: #ffffff;
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 4px rgba(21, 128, 61, 0.25);
        transition: background-color 0.15s;
    }

    .btn-process-return:hover {
        background-color: #166534;
    }

    .returned-badge-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: var(--radius-md);
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        font-weight: 600;
        font-size: 13.5px;
    }

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        background-color: #f1f5f9;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        text-decoration: none;
    }

    .btn-back-link:hover {
        background-color: #e2e8f0;
        color: var(--text-main);
    }

    @media (max-width: 800px) {
        .loan-detail-grid {
            grid-template-columns: 1fr;
        }
        .return-action-card {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

{{-- Breadcrumb --}}
<nav class="breadcrumb-nav">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <span>&rsaquo;</span>
    <a href="{{ route('petugas.peminjaman.index') }}">Data Peminjaman</a>
    <span>&rsaquo;</span>
    <span>TRX-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</span>
</nav>

{{-- Aksi Pengembalian --}}
<div class="return-action-card">
    <div class="return-info-text">
        <h3>Status Sirkulasi: <span class="badge {{ $loan->badge_class }}">{{ $loan->display_status }}</span></h3>
        <p>
            @if ($loan->status === 'dipinjam')
                Buku saat ini masih berada di peminjam. Klik tombol di samping untuk memproses pengembalian ke rak perpustakaan.
            @else
                Buku telah dikembalikan dan stok eksemplar sudah ditambahkan kembali ke inventaris perpustakaan.
            @endif
        </p>
    </div>

    <div>
        @if ($loan->status === 'dipinjam')
            <form action="{{ route('petugas.peminjaman.kembalikan', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses pengembalian buku ini? Stok buku akan bertambah 1.');">
                @csrf
                <button type="submit" class="btn-process-return">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Proses Pengembalian Buku
                </button>
            </form>
        @else
            <div class="returned-badge-box">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Dikembalikan pada: {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->translatedFormat('d F Y') : '-' }}
            </div>
        @endif
    </div>
</div>

{{-- 2 Kolom Informasi: Peminjam & Buku --}}
<div class="loan-detail-grid">
    {{-- 1. INFORMASI PEMINJAM --}}
    <div class="detail-card-panel">
        <h3 class="panel-header-title">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Informasi Peminjam
        </h3>

        <div class="info-list-rows">
            <div class="info-row-item">
                <span class="info-row-label">Nama Lengkap</span>
                <span class="info-row-val">{{ $loan->user ? $loan->user->name : '-' }}</span>
            </div>
            <div class="info-row-item">
                <span class="info-row-label">Alamat Email</span>
                <span class="info-row-val">{{ $loan->user ? $loan->user->email : '-' }}</span>
            </div>
            <div class="info-row-item">
                <span class="info-row-label">Nomor Identitas</span>
                <span class="info-row-val">{{ $loan->user ? $loan->user->nomor_identitas : '-' }}</span>
            </div>
            <div class="info-row-item">
                <span class="info-row-label">Nomor Telepon</span>
                <span class="info-row-val">{{ $loan->user ? $loan->user->telepon : '-' }}</span>
            </div>
            @if ($loan->user)
                <div style="margin-top: 10px; text-align: right;">
                    <a href="{{ route('petugas.peminjam.show', $loan->user->id) }}" class="btn-sm-action">
                        Lihat Profil Peminjam &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- 2. INFORMASI BUKU --}}
    <div class="detail-card-panel">
        <h3 class="panel-header-title">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Informasi Buku
        </h3>

        @if ($loan->book)
            <div class="book-preview-strip">
                <div class="book-cover-square">
                    @if ($loan->book->cover && file_exists(public_path('storage/' . $loan->book->cover)))
                        <img src="{{ asset('storage/' . $loan->book->cover) }}" alt="Cover">
                    @else
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    @endif
                </div>
                <div>
                    <h4 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 3px;">
                        {{ $loan->book->judul }}
                    </h4>
                    <p style="font-size: 12.5px; color: var(--text-muted);">
                        Oleh: {{ $loan->book->penulis }}
                    </p>
                </div>
            </div>

            <div class="info-list-rows">
                <div class="info-row-item">
                    <span class="info-row-label">Kategori</span>
                    <span class="info-row-val">{{ $loan->book->kategori }}</span>
                </div>
                <div class="info-row-item">
                    <span class="info-row-label">Penerbit</span>
                    <span class="info-row-val">{{ $loan->book->penerbit }}</span>
                </div>
                <div class="info-row-item">
                    <span class="info-row-label">Tahun Terbit</span>
                    <span class="info-row-val">{{ $loan->book->tahun_terbit }}</span>
                </div>
                <div class="info-row-item">
                    <span class="info-row-label">Stok di Rak Saat Ini</span>
                    <span class="info-row-val" style="color: {{ $loan->book->stok > 0 ? '#15803d' : '#b91c1c' }};">
                        {{ $loan->book->stok }} Eksemplar
                    </span>
                </div>
                <div style="margin-top: 10px; text-align: right;">
                    <a href="{{ route('petugas.buku.show', $loan->book->id) }}" class="btn-sm-action">
                        Lihat Data Buku &rarr;
                    </a>
                </div>
            </div>
        @else
            <p style="color: var(--text-muted);">Data buku tidak ditemukan / telah dihapus.</p>
        @endif
    </div>
</div>

{{-- 3. INFORMASI TRANSAKSI --}}
<div class="detail-card-panel" style="margin-bottom: 24px;">
    <h3 class="panel-header-title">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Informasi Transaksi Peminjaman
    </h3>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
        <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px 18px;">
            <span style="font-size: 11px; font-weight: 600; color: var(--text-light); text-transform: uppercase; display: block; margin-bottom: 4px;">ID Peminjaman</span>
            <span style="font-size: 15px; font-weight: 700; color: var(--primary);">TRX-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px 18px;">
            <span style="font-size: 11px; font-weight: 600; color: var(--text-light); text-transform: uppercase; display: block; margin-bottom: 4px;">Tanggal Pinjam</span>
            <span style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $loan->tanggal_pinjam->translatedFormat('d F Y') }}</span>
        </div>

        <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px 18px;">
            <span style="font-size: 11px; font-weight: 600; color: var(--text-light); text-transform: uppercase; display: block; margin-bottom: 4px;">Batas Jatuh Tempo</span>
            <span style="font-size: 14px; font-weight: 700; color: #dc2626;">{{ $loan->tanggal_jatuh_tempo->translatedFormat('d F Y') }}</span>
        </div>

        <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px 18px;">
            <span style="font-size: 11px; font-weight: 600; color: var(--text-light); text-transform: uppercase; display: block; margin-bottom: 4px;">Tanggal Pengembalian</span>
            <span style="font-size: 14px; font-weight: 700; color: {{ $loan->tanggal_kembali ? '#15803d' : '#94a3b8' }};">
                {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->translatedFormat('d F Y') : 'Belum Dikembalikan' }}
            </span>
        </div>
    </div>
</div>

<a href="{{ route('petugas.peminjaman.index') }}" class="btn-back-link">
    &larr; Kembali ke Data Peminjaman
</a>

@endsection
