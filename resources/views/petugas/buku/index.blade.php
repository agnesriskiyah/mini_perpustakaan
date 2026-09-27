@extends('layouts.dashboard')

@section('title', 'Data Buku - Petugas Perpustakaan')

@section('content')
<style>
    .page-header-box {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-xs);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }

    .page-desc {
        font-size: 14px;
        color: var(--text-muted);
    }

    .btn-add-book {
        padding: 10px 20px;
        background-color: var(--primary);
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        border-radius: var(--radius-md);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.15s;
        box-shadow: 0 2px 4px rgba(29, 78, 216, 0.2);
    }

    .btn-add-book:hover {
        background-color: var(--primary-hover);
    }

    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 18px 24px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-xs);
    }

    .filter-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 240px;
    }

    .search-input-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        pointer-events: none;
    }

    .input-filter {
        width: 100%;
        padding: 10px 14px 10px 40px;
        font-size: 13.5px;
        font-family: inherit;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background-color: #f8fafc;
        color: var(--text-main);
        transition: all 0.2s;
    }

    .input-filter:focus {
        outline: none;
        background-color: #ffffff;
        border-color: var(--border-focus);
    }

    .select-filter {
        padding: 10px 16px;
        font-size: 13.5px;
        font-family: inherit;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background-color: #f8fafc;
        color: var(--text-main);
        cursor: pointer;
        min-width: 180px;
    }

    .btn-submit-filter {
        padding: 10px 18px;
        background-color: #0f172a;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 600;
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background-color 0.15s;
    }

    .btn-submit-filter:hover {
        background-color: #334155;
    }

    .btn-reset-filter {
        padding: 10px 16px;
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-reset-filter:hover {
        background-color: #e2e8f0;
        color: var(--text-main);
    }

    /* Table & Book Thumb */
    .book-thumb-small {
        width: 40px;
        height: 52px;
        border-radius: 4px;
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .book-thumb-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-buttons-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action-view {
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
        color: #0369a1;
        background-color: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-action-view:hover {
        background-color: #0284c7;
        color: #ffffff;
    }

    .btn-action-edit {
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
        color: #b45309;
        background-color: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-action-edit:hover {
        background-color: #d97706;
        color: #ffffff;
    }

    .btn-action-delete {
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
        color: #b91c1c;
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-action-delete:hover {
        background-color: #dc2626;
        color: #ffffff;
    }

    /* Empty state */
    .empty-card {
        background: #ffffff;
        border: 1.5px dashed var(--border-color);
        border-radius: var(--radius-lg);
        padding: 48px 20px;
        text-align: center;
        color: var(--text-muted);
    }
</style>

{{-- Header Halaman --}}
<div class="page-header-box">
    <div>
        <h1 class="page-title">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Data Buku
        </h1>
        <p class="page-desc">Kelola koleksi buku perpustakaan.</p>
    </div>
    <a href="{{ route('petugas.buku.create') }}" class="btn-add-book">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        + Tambah Buku
    </a>
</div>

{{-- Filter & Pencarian --}}
<div class="filter-card">
    <form action="{{ route('petugas.buku.index') }}" method="GET" class="filter-form">
        <div class="search-input-wrap">
            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ $search }}" class="input-filter" placeholder="Cari judul buku atau penulis...">
        </div>

        <select name="kategori" class="select-filter">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" {{ $kategori === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn-submit-filter">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Cari
        </button>

        @if (!empty($search) || !empty($kategori))
            <a href="{{ route('petugas.buku.index') }}" class="btn-reset-filter" title="Reset filter">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Tabel Buku --}}
@if ($books->count() > 0)
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 70px;">Cover</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="text-align: center; width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $index => $book)
                    <tr>
                        <td>{{ $books->firstItem() + $index }}</td>
                        <td>
                            <div class="book-thumb-small">
                                @if ($book->cover && file_exists(public_path('storage/' . $book->cover)))
                                    <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover">
                                @else
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--text-main); font-weight: 600;">
                                {{ $book->judul }}
                            </strong>
                            <div style="font-size: 11.5px; color: var(--text-muted);">
                                Penerbit: {{ $book->penerbit }}
                            </div>
                        </td>
                        <td>{{ $book->penulis }}</td>
                        <td>
                            <span class="badge" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border);">
                                {{ $book->kategori }}
                            </span>
                        </td>
                        <td>{{ $book->tahun_terbit }}</td>
                        <td>
                            <strong>{{ $book->stok }}</strong>
                            @if ($book->loans_active_count > 0)
                                <div style="font-size: 11px; color: var(--text-muted);">({{ $book->loans_active_count }} dipinjam)</div>
                            @endif
                        </td>
                        <td>
                            @if ($book->stok > 0)
                                <span class="badge badge-success">Tersedia</span>
                            @else
                                <span class="badge badge-danger">Habis</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div class="action-buttons-group" style="justify-content: center;">
                                <a href="{{ route('petugas.buku.show', $book->id) }}" class="btn-action-view" title="Lihat Detail">
                                    Lihat
                                </a>
                                <a href="{{ route('petugas.buku.edit', $book->id) }}" class="btn-action-edit" title="Edit Buku">
                                    Edit
                                </a>
                                <form action="{{ route('petugas.buku.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \'{{ addslashes($book->judul) }}\'?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" title="Hapus Buku">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $books->links() }}
    </div>
@else
    <div class="empty-card">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin-bottom: 12px; color: var(--text-light);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Tidak Ada Data Buku</h3>
        <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 16px;">
            Belum ada koleksi buku yang terdaftar atau tidak ada hasil yang sesuai dengan filter pencarian.
        </p>
        <a href="{{ route('petugas.buku.create') }}" class="btn-add-book">
            + Tambah Buku Pertama
        </a>
    </div>
@endif

@endsection
