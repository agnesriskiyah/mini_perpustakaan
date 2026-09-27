@extends('layouts.dashboard')

@section('title', 'Data Peminjam - Petugas Perpustakaan')

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
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 280px;
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

    .user-avatar-initial {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
        border: 1px solid var(--primary-border);
    }

    .user-profile-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-detail-peminjam {
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--primary);
        background-color: var(--primary-light);
        border: 1px solid var(--primary-border);
        border-radius: var(--radius-sm);
        text-decoration: none;
        display: inline-block;
        transition: all 0.15s;
    }

    .btn-detail-peminjam:hover {
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Data Peminjam
        </h1>
        <p class="page-desc">Kelola dan pantau seluruh akun mahasiswa / peminjam perpustakaan yang terdaftar.</p>
    </div>
</div>

{{-- Filter Pencarian --}}
<div class="filter-card">
    <form action="{{ route('petugas.peminjam.index') }}" method="GET" class="filter-form">
        <div class="search-input-wrap">
            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ $search }}" class="input-filter" placeholder="Cari nama peminjam, email, atau nomor identitas (NIM/NIK)...">
        </div>

        <button type="submit" class="btn-submit-filter">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Cari Peminjam
        </button>

        @if (!empty($search))
            <a href="{{ route('petugas.peminjam.index') }}" class="btn-reset-filter" title="Reset filter">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Tabel Data Peminjam --}}
@if ($peminjams->count() > 0)
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Peminjam</th>
                    <th>Email</th>
                    <th>Nomor Identitas</th>
                    <th>Telepon</th>
                    <th style="text-align: center;">Sedang Dipinjam</th>
                    <th style="text-align: center;">Total Riwayat</th>
                    <th style="text-align: center; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peminjams as $index => $peminjam)
                    <tr>
                        <td>{{ $peminjams->firstItem() + $index }}</td>
                        <td>
                            <div class="user-profile-cell">
                                <div class="user-avatar-initial">
                                    {{ strtoupper(substr($peminjam->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong style="color: var(--text-main); font-weight: 600;">
                                        {{ $peminjam->name }}
                                    </strong>
                                    <div style="font-size: 11px; color: var(--text-muted);">
                                        Bergabung: {{ $peminjam->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $peminjam->email }}</td>
                        <td>
                            <code style="font-family: inherit; font-weight: 600; color: #334155; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">
                                {{ $peminjam->nomor_identitas ?? '-' }}
                            </code>
                        </td>
                        <td>{{ $peminjam->telepon ?? '-' }}</td>
                        <td style="text-align: center;">
                            @if ($peminjam->loans_active_count > 0)
                                <span class="badge badge-primary">{{ $peminjam->loans_active_count }} Buku</span>
                            @else
                                <span style="font-size: 12.5px; color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="badge" style="background: #f8fafc; border: 1px solid var(--border-color); color: var(--text-main);">
                                {{ $peminjam->loans_total_count }}x
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('petugas.peminjam.show', $peminjam->id) }}" class="btn-detail-peminjam">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $peminjams->links() }}
    </div>
@else
    <div class="empty-card">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin-bottom: 12px; color: var(--text-light);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Tidak Ada Data Peminjam</h3>
        <p style="font-size: 13.5px; color: var(--text-muted);">
            Belum ada anggota peminjam yang terdaftar atau data yang sesuai dengan pencarian Anda.
        </p>
    </div>
@endif

@endsection
