@extends('layouts.dashboard')

@section('title', 'Katalog Buku Perpustakaan')

@section('content')
<style>
    /* Hero / Search Section */
    .catalog-header {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: var(--shadow-xs);
    }

    .catalog-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.3px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .catalog-desc {
        font-size: 14px;
        color: var(--text-muted);
        margin-bottom: 24px;
    }

    .search-filter-box {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 260px;
    }

    .search-input-wrapper svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        pointer-events: none;
    }

    .search-input-field {
        width: 100%;
        padding: 11px 16px 11px 42px;
        font-size: 14px;
        font-family: inherit;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background-color: #f8fafc;
        color: var(--text-main);
        transition: all 0.2s;
    }

    .search-input-field:focus {
        outline: none;
        background-color: #ffffff;
        border-color: var(--border-focus);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .category-select {
        padding: 11px 16px;
        font-size: 14px;
        font-family: inherit;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background-color: #f8fafc;
        color: var(--text-main);
        cursor: pointer;
        min-width: 180px;
        transition: all 0.2s;
    }

    .category-select:focus {
        outline: none;
        background-color: #ffffff;
        border-color: var(--border-focus);
    }

    .btn-search {
        padding: 11px 22px;
        background-color: var(--primary);
        color: #ffffff;
        border: none;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.15s;
    }

    .btn-search:hover {
        background-color: var(--primary-hover);
    }

    .btn-reset {
        padding: 11px 18px;
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }

    .btn-reset:hover {
        background-color: #e2e8f0;
        color: var(--text-main);
    }

    /* Grid Buku */
    .catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 36px;
    }

    .book-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-xs);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: #cbd5e1;
    }

    .book-card-cover {
        height: 160px;
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        padding: 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #ffffff;
        position: relative;
    }

    .book-card-cover.c1 { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); }
    .book-card-cover.c2 { background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%); }
    .book-card-cover.c3 { background: linear-gradient(135deg, #7c2d12 0%, #f97316 100%); }
    .book-card-cover.c4 { background: linear-gradient(135deg, #4c1d95 0%, #8b5cf6 100%); }
    .book-card-cover.c5 { background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); }
    .book-card-cover.c6 { background: linear-gradient(135deg, #334155 0%, #64748b 100%); }

    .cover-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cover-badge-year {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(4px);
        border-radius: 4px;
        letter-spacing: 0.5px;
    }

    .cover-icon {
        opacity: 0.85;
    }

    .cover-bottom {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        opacity: 0.9;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-card-body {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .book-cat-tag {
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
    }

    .book-title-link {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
        text-decoration: none;
        line-height: 1.35;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-title-link:hover {
        color: var(--primary);
    }

    .book-author-name {
        font-size: 12.5px;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .book-desc-snippet {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-card-footer {
        margin-top: auto;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .stock-status-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
    }

    .stock-badge-available {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        color: #15803d;
        background-color: #f0fdf4;
        padding: 4px 9px;
        border-radius: 9999px;
        border: 1px solid #bbf7d0;
    }

    .stock-badge-empty {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        color: #b91c1c;
        background-color: #fef2f2;
        padding: 4px 9px;
        border-radius: 9999px;
        border: 1px solid #fecaca;
    }

    .card-actions-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .btn-detail {
        padding: 8px 12px;
        text-align: center;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-detail:hover {
        background: #f1f5f9;
        color: var(--primary);
        border-color: var(--primary-border);
    }

    .btn-pinjam {
        padding: 8px 12px;
        text-align: center;
        font-size: 12.5px;
        font-weight: 600;
        color: #ffffff;
        background: var(--primary);
        border: 1px solid var(--primary);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: background-color 0.15s;
        width: 100%;
    }

    .btn-pinjam:hover {
        background: var(--primary-hover);
    }

    .btn-pinjam:disabled, .btn-pinjam[disabled] {
        background: #e2e8f0;
        color: #94a3b8;
        border-color: #cbd5e1;
        cursor: not-allowed;
    }

    /* Empty state */
    .empty-catalog {
        background: #ffffff;
        border: 1.5px dashed var(--border-color);
        border-radius: var(--radius-lg);
        padding: 50px 20px;
        text-align: center;
        color: var(--text-muted);
        margin: 20px 0;
    }

    .empty-catalog svg {
        color: var(--text-light);
        margin-bottom: 14px;
    }

    .empty-catalog h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 6px;
    }
</style>

{{-- 1. Header & Filter Pencarian --}}
<section class="catalog-header">
    <h1 class="catalog-title">
        <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        Katalog Buku Perpustakaan
    </h1>
    <p class="catalog-desc">
        Temukan referensi buku akademik, pemrograman, rekayasa perangkat lunak, dan literatur umum untuk kebutuhan studi Anda.
    </p>

    <form action="{{ route('peminjam.katalog.index') }}" method="GET" class="search-filter-box">
        <div class="search-input-wrapper">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ $search }}" class="search-input-field" placeholder="Cari judul buku, penulis, atau kategori...">
        </div>

        <select name="kategori" class="category-select">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" {{ $kategori === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn-search">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Cari
        </button>

        @if(!empty($search) || !empty($kategori))
            <a href="{{ route('peminjam.katalog.index') }}" class="btn-reset" title="Hapus filter pencarian">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Reset
            </a>
        @endif
    </form>
</section>

{{-- 2. Daftar Buku (Card Grid) --}}
@if ($books->count() > 0)
    <div class="catalog-grid">
        @php
            $colorClasses = ['c1', 'c2', 'c3', 'c4', 'c5', 'c6'];
        @endphp

        @foreach ($books as $index => $book)
            @php
                $bgClass = $colorClasses[$index % count($colorClasses)];
                $isBorrowedByMe = in_array($book->id, $borrowedBookIds);
            @endphp
            <div class="book-card">
                {{-- Cover Visual --}}
                <div class="book-card-cover {{ $bgClass }}">
                    <div class="cover-top">
                        <span class="cover-badge-year">{{ $book->tahun_terbit }}</span>
                        <div class="cover-icon">
                            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                    <div class="cover-bottom">
                        {{ $book->penerbit }}
                    </div>
                </div>

                {{-- Content Body --}}
                <div class="book-card-body">
                    <span class="book-cat-tag">{{ $book->kategori }}</span>
                    <a href="{{ route('peminjam.katalog.show', $book->id) }}" class="book-title-link" title="{{ $book->judul }}">
                        {{ $book->judul }}
                    </a>
                    <p class="book-author-name">Penulis: {{ $book->penulis }}</p>
                    <p class="book-desc-snippet">{{ $book->deskripsi }}</p>

                    <div class="book-card-footer">
                        <div class="stock-status-row">
                            <span>Status:</span>
                            @if ($book->stok > 0)
                                <span class="stock-badge-available">
                                    <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    ✓ Tersedia ({{ $book->stok }})
                                </span>
                            @else
                                <span class="stock-badge-empty">
                                    <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Stok Habis
                                </span>
                            @endif
                        </div>

                        <div class="card-actions-row">
                            <a href="{{ route('peminjam.katalog.show', $book->id) }}" class="btn-detail">
                                Lihat Detail
                            </a>

                            @if ($book->stok <= 0)
                                <button type="button" class="btn-pinjam" disabled title="Stok buku sedang habis">
                                    Pinjam
                                </button>
                            @elseif ($isBorrowedByMe)
                                <button type="button" class="btn-pinjam" disabled title="Anda masih memiliki peminjaman aktif untuk buku ini" style="background: #e0e7ff; color: #4338ca; border-color: #c7d2fe;">
                                    Dipinjam
                                </button>
                            @else
                                <form action="{{ route('peminjam.pinjam.store', $book->id) }}" method="POST" onsubmit="return confirm('Konfirmasi peminjaman buku: \'{{ addslashes($book->judul) }}\'?');">
                                    @csrf
                                    <button type="submit" class="btn-pinjam">
                                        Pinjam
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination jika ada banyak buku --}}
    <div>
        {{ $books->links() }}
    </div>
@else
    {{-- Empty State jika pencarian tidak ada hasil --}}
    <div class="empty-catalog">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <h3>Buku Tidak Ditemukan</h3>
        <p>Tidak ada buku yang sesuai dengan kata kunci atau filter kategori yang Anda pilih.</p>
        <div style="margin-top: 16px;">
            <a href="{{ route('peminjam.katalog.index') }}" class="btn-search" style="text-decoration: none;">
                Tampilkan Semua Buku
            </a>
        </div>
    </div>
@endif

@endsection
