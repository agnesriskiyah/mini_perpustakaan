@extends('layouts.dashboard')

@section('title', 'Data Peminjaman - Petugas Perpustakaan')

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
        align-items: center;
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
        min-width: 150px;
    }

    .date-filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: var(--text-muted);
    }

    .date-input {
        padding: 9px 12px;
        font-size: 13px;
        font-family: inherit;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background-color: #f8fafc;
        color: var(--text-main);
    }

    .btn-submit-filter {
        padding: 10px 20px;
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
    }

    .btn-reset-filter:hover {
        background-color: #e2e8f0;
        color: var(--text-main);
    }

    .btn-detail-action {
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
        background-color: var(--primary-light);
        border: 1px solid var(--primary-border);
        border-radius: var(--radius-sm);
        text-decoration: none;
        display: inline-block;
        transition: all 0.15s;
    }

    .btn-detail-action:hover {
        background-color: var(--primary);
        color: #ffffff;
    }

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
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Data Peminjaman
        </h1>
        <p class="page-desc">Kelola dan pantau seluruh transaksi peminjaman buku perpustakaan secara terpadu.</p>
    </div>
</div>

{{-- Filter Pencarian & Status --}}
<div class="filter-card">
    <form action="{{ route('petugas.peminjaman.index') }}" method="GET" class="filter-form">
        <div class="search-input-wrap">
            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ $search }}" class="input-filter" placeholder="Cari nama peminjam atau judul buku...">
        </div>

        <select name="status" class="select-filter">
            <option value="">Semua Status</option>
            <option value="dipinjam" {{ $statusFilter === 'dipinjam' ? 'selected' : '' }}>Dipinjam (Aktif)</option>
            <option value="terlambat" {{ $statusFilter === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
            <option value="dikembalikan" {{ $statusFilter === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
        </select>

        <div class="date-filter-group">
            <span>Tgl Pinjam:</span>
            <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="date-input" title="Tanggal Mulai">
            <span>s/d</span>
            <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="date-input" title="Tanggal Selesai">
        </div>

        <button type="submit" class="btn-submit-filter">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Filter
        </button>

        @if (!empty($search) || !empty($statusFilter) || !empty($tanggalMulai) || !empty($tanggalSelesai))
            <a href="{{ route('petugas.peminjaman.index') }}" class="btn-reset-filter" title="Reset filter">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Tabel Data Transaksi Peminjaman --}}
@if ($loans->count() > 0)
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>ID Pinjam</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th style="text-align: center; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $index => $loan)
                    <tr>
                        <td>{{ $loans->firstItem() + $index }}</td>
                        <td>
                            <code style="font-family: inherit; font-weight: 700; color: #1e40af; background: #eff6ff; padding: 3px 8px; border-radius: 4px;">
                                TRX-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                            </code>
                        </td>
                        <td>
                            <strong style="color: var(--text-main); font-weight: 600;">
                                {{ $loan->user ? $loan->user->name : 'User Terhapus' }}
                            </strong>
                            <div style="font-size: 11.5px; color: var(--text-muted);">
                                {{ $loan->user ? $loan->user->email : '-' }}
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--text-main); font-weight: 600;">
                                {{ $loan->book ? $loan->book->judul : 'Buku #' . $loan->book_id }}
                            </strong>
                            <div style="font-size: 11.5px; color: var(--text-muted);">
                                {{ $loan->book ? $loan->book->kategori : '-' }}
                            </div>
                        </td>
                        <td>{{ $loan->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                        <td>
                            <span style="color: #dc2626; font-weight: 600;">
                                {{ $loan->tanggal_jatuh_tempo->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        <td>
                            @if ($loan->tanggal_kembali)
                                {{ $loan->tanggal_kembali->translatedFormat('d M Y') }}
                            @else
                                <span style="color: var(--text-light);">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $loan->badge_class }}">
                                {{ $loan->display_status }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('petugas.peminjaman.show', $loan->id) }}" class="btn-detail-action">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $loans->links() }}
    </div>
@else
    <div class="empty-card">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin-bottom: 12px; color: var(--text-light);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Tidak Ada Transaksi Peminjaman</h3>
        <p style="font-size: 13.5px; color: var(--text-muted);">
            Belum ada catatan peminjaman buku atau tidak ada hasil yang sesuai dengan kriteria filter yang diterapkan.
        </p>
    </div>
@endif

@endsection
