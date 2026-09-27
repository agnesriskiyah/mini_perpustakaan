@extends('layouts.dashboard')

@section('title', $book->judul . ' - Detail Buku')

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

    .breadcrumb-nav a:hover {
        text-decoration: underline;
    }

    .book-detail-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-xs);
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 32px;
        padding: 32px;
        margin-bottom: 30px;
    }

    /* Cover Area */
    .book-cover-large {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border-radius: var(--radius-md);
        min-height: 420px;
        padding: 28px;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.25);
    }

    .cover-brand-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cover-year-pill {
        font-size: 12px;
        font-weight: 700;
        background: rgba(0, 0, 0, 0.3);
        padding: 4px 10px;
        border-radius: 6px;
        letter-spacing: 0.5px;
    }

    .cover-center-content {
        text-align: center;
        padding: 20px 0;
    }

    .cover-big-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .cover-book-title {
        font-size: 20px;
        font-weight: 700;
        line-height: 1.35;
        letter-spacing: -0.3px;
    }

    .cover-publisher {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.85;
        text-align: center;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 14px;
    }

    /* Content Area */
    .detail-info-area {
        display: flex;
        flex-direction: column;
    }

    .detail-category-badge {
        align-self: flex-start;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--primary);
        background: var(--primary-light);
        padding: 4px 12px;
        border-radius: 9999px;
        border: 1px solid var(--primary-border);
        margin-bottom: 12px;
    }

    .detail-title {
        font-size: 26px;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.3;
        letter-spacing: -0.4px;
        margin-bottom: 8px;
    }

    .detail-author {
        font-size: 15px;
        color: var(--text-muted);
        margin-bottom: 24px;
    }

    .meta-spec-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        margin-bottom: 28px;
    }

    .spec-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .spec-label {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .spec-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .detail-section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 10px;
    }

    .detail-description {
        font-size: 14.5px;
        line-height: 1.7;
        color: #334155;
        margin-bottom: 32px;
        white-space: pre-line;
    }

    .action-panel {
        margin-top: auto;
        padding-top: 24px;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .stock-summary {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .stock-number-pill {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
    }

    .btn-pinjam-lg {
        padding: 12px 28px;
        font-size: 15px;
        font-weight: 600;
        font-family: inherit;
        color: #ffffff;
        background-color: var(--primary);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
        transition: all 0.15s;
    }

    .btn-pinjam-lg:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(29, 78, 216, 0.35);
    }

    .btn-pinjam-lg:disabled {
        background-color: #cbd5e1;
        color: #64748b;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .alert-status-borrowed {
        background-color: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        padding: 12px 18px;
        border-radius: var(--radius-md);
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    @media (max-width: 900px) {
        .book-detail-card {
            grid-template-columns: 1fr;
        }
        .book-cover-large {
            min-height: 280px;
        }
        .meta-spec-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

{{-- Breadcrumb --}}
<nav class="breadcrumb-nav">
    <a href="{{ route('peminjam.dashboard') }}">Dashboard</a>
    <span>&rsaquo;</span>
    <a href="{{ route('peminjam.katalog.index') }}">Katalog Buku</a>
    <span>&rsaquo;</span>
    <span>{{ $book->judul }}</span>
</nav>

{{-- Card Detail Buku --}}
<div class="book-detail-card">
    {{-- Sisi Kiri: Cover Visual --}}
    <div class="book-cover-large">
        <div class="cover-brand-top">
            <span class="cover-year-pill">Tahun {{ $book->tahun_terbit }}</span>
            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>

        <div class="cover-center-content">
            <div class="cover-big-icon">
                <svg width="38" height="38" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h2 class="cover-book-title">{{ $book->judul }}</h2>
        </div>

        <div class="cover-publisher">
            Penerbit: {{ $book->penerbit }}
        </div>
    </div>

    {{-- Sisi Kanan: Informasi & Form Aksi --}}
    <div class="detail-info-area">
        <span class="detail-category-badge">{{ $book->kategori }}</span>
        <h1 class="detail-title">{{ $book->judul }}</h1>
        <p class="detail-author">Ditulis oleh <strong>{{ $book->penulis }}</strong></p>

        {{-- Spesifikasi Ringkas --}}
        <div class="meta-spec-grid">
            <div class="spec-item">
                <span class="spec-label">Penerbit</span>
                <span class="spec-value">{{ $book->penerbit }}</span>
            </div>
            <div class="spec-item">
                <span class="spec-label">Tahun Terbit</span>
                <span class="spec-value">{{ $book->tahun_terbit }}</span>
            </div>
            <div class="spec-item">
                <span class="spec-label">Ketersediaan Stok</span>
                <span class="spec-value" style="color: {{ $book->stok > 0 ? '#15803d' : '#b91c1c' }};">
                    {{ $book->stok > 0 ? $book->stok . ' Eksemplar' : 'Habis' }}
                </span>
            </div>
        </div>

        {{-- Deskripsi Buku --}}
        <h3 class="detail-section-title">Deskripsi &amp; Sinopsis Buku</h3>
        <p class="detail-description">{{ $book->deskripsi }}</p>

        {{-- Panel Peminjaman & Tombol Aksi --}}
        <div class="action-panel">
            <div class="stock-summary">
                <div>
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Status Ketersediaan:</span>
                    @if ($book->stok > 0)
                        <span class="badge badge-success" style="font-size: 13px;">
                            ✓ Tersedia ({{ $book->stok }} buku)
                        </span>
                    @else
                        <span class="badge badge-danger" style="font-size: 13px;">
                            Stok Habis
                        </span>
                    @endif
                </div>
            </div>

            <div>
                @if ($book->stok <= 0)
                    <button type="button" class="btn-pinjam-lg" disabled>
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        Stok Habis
                    </button>
                @elseif ($isCurrentlyBorrowed)
                    <div class="alert-status-borrowed">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Anda masih memiliki peminjaman aktif untuk buku ini.</span>
                    </div>
                @else
                    <form action="{{ route('peminjam.pinjam.store', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin meminjam buku \'{{ addslashes($book->judul) }}\'? Masa pinjam berlaku selama 7 hari.');">
                        @csrf
                        <button type="submit" class="btn-pinjam-lg">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Pinjam Buku Ini
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div style="margin-top: 16px;">
    <a href="{{ route('peminjam.katalog.index') }}" class="btn-reset" style="text-decoration: none;">
        &larr; Kembali ke Katalog
    </a>
</div>

@endsection
