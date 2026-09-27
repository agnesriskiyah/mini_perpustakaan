@extends('layouts.dashboard')

@section('title', 'Detail Buku: ' . $book->judul . ' - Petugas')

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

    .book-detail-wrapper {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-xs);
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 32px;
        padding: 32px;
        margin-bottom: 24px;
    }

    .detail-cover-box {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border-radius: var(--radius-md);
        min-height: 400px;
        padding: 24px;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .detail-cover-box img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cover-badge-category {
        align-self: flex-start;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        background: rgba(0, 0, 0, 0.4);
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 2;
    }

    .cover-center-symbol {
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .cover-footer-publisher {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        text-align: center;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 12px;
        position: relative;
        z-index: 2;
    }

    .detail-content-area {
        display: flex;
        flex-direction: column;
    }

    .detail-heading h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.3;
        margin-bottom: 6px;
    }

    .detail-heading p {
        font-size: 14px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .stats-info-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 16px;
        margin-bottom: 24px;
    }

    .stat-mini-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .stat-mini-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-mini-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .synopsis-text {
        font-size: 14px;
        line-height: 1.7;
        color: #334155;
        white-space: pre-line;
        margin-bottom: 28px;
    }

    .detail-actions-footer {
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-edit-action {
        padding: 10px 22px;
        background-color: var(--primary);
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 600;
        border-radius: var(--radius-md);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.15s;
    }

    .btn-edit-action:hover {
        background-color: var(--primary-hover);
    }

    .btn-back-action {
        padding: 10px 18px;
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.15s;
    }

    .btn-back-action:hover {
        background-color: #e2e8f0;
        color: var(--text-main);
    }

    @media (max-width: 900px) {
        .book-detail-wrapper {
            grid-template-columns: 1fr;
        }
        .detail-cover-box {
            min-height: 260px;
        }
        .stats-info-bar {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

{{-- Breadcrumb --}}
<nav class="breadcrumb-nav">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <span>&rsaquo;</span>
    <a href="{{ route('petugas.buku.index') }}">Data Buku</a>
    <span>&rsaquo;</span>
    <span>{{ $book->judul }}</span>
</nav>

<div class="book-detail-wrapper">
    {{-- Cover Box --}}
    <div class="detail-cover-box">
        @if ($book->cover && file_exists(public_path('storage/' . $book->cover)))
            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}">
        @else
            <span class="cover-badge-category">{{ $book->kategori }}</span>
            <div class="cover-center-symbol">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" style="margin: 0 auto 12px; opacity: 0.9;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 style="font-size: 18px; font-weight: 700; line-height: 1.35;">{{ $book->judul }}</h3>
            </div>
            <div class="cover-footer-publisher">
                Tahun {{ $book->tahun_terbit }} &bull; {{ $book->penerbit }}
            </div>
        @endif
    </div>

    {{-- Detail Info --}}
    <div class="detail-content-area">
        <div class="detail-heading">
            <span class="badge" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); margin-bottom: 8px;">
                {{ $book->kategori }}
            </span>
            <h1>{{ $book->judul }}</h1>
            <p>Ditulis oleh <strong>{{ $book->penulis }}</strong> &bull; Diterbitkan oleh <strong>{{ $book->penerbit }}</strong></p>
        </div>

        {{-- Baris Statistik Stok & Sirkulasi --}}
        <div class="stats-info-bar">
            <div class="stat-mini-item">
                <span class="stat-mini-label">Tahun Terbit</span>
                <span class="stat-mini-value">{{ $book->tahun_terbit }}</span>
            </div>
            <div class="stat-mini-item">
                <span class="stat-mini-label">Total Stok Tersedia</span>
                <span class="stat-mini-value" style="color: {{ $book->stok > 0 ? '#15803d' : '#b91c1c' }};">
                    {{ $book->stok }} Eksemplar
                </span>
            </div>
            <div class="stat-mini-item">
                <span class="stat-mini-label">Status Stok</span>
                <span class="stat-mini-value">
                    @if ($book->stok > 0)
                        <span class="badge badge-success">Tersedia</span>
                    @else
                        <span class="badge badge-danger">Habis</span>
                    @endif
                </span>
            </div>
            <div class="stat-mini-item">
                <span class="stat-mini-label">Sedang Dipinjam</span>
                <span class="stat-mini-value" style="color: #2563eb;">
                    {{ $activeLoansCount }} Buku
                </span>
            </div>
        </div>

        {{-- Sinopsis / Deskripsi --}}
        <h4 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Deskripsi Buku</h4>
        <div class="synopsis-text">
            {{ $book->deskripsi }}
        </div>

        {{-- Aksi --}}
        <div class="detail-actions-footer">
            <a href="{{ route('petugas.buku.edit', $book->id) }}" class="btn-edit-action">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Buku
            </a>
            <a href="{{ route('petugas.buku.index') }}" class="btn-back-action">
                &larr; Kembali ke Data Buku
            </a>
        </div>
    </div>
</div>

@endsection
