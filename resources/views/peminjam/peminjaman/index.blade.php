@extends('layouts.dashboard')

@section('title', 'Peminjaman Saya - Perpustakaan')

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
        gap: 8px;
        margin-bottom: 4px;
    }

    .page-desc {
        font-size: 14px;
        color: var(--text-muted);
    }

    /* Tabs Styling */
    .tabs-container {
        display: flex;
        gap: 8px;
        border-bottom: 2px solid var(--border-color);
        margin-bottom: 24px;
    }

    .tab-btn {
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        color: var(--text-muted);
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s;
    }

    .tab-btn:hover {
        color: var(--primary);
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        font-weight: 700;
    }

    .tab-count-badge {
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 9999px;
        background-color: #f1f5f9;
        color: #475569;
    }

    .tab-btn.active .tab-count-badge {
        background-color: var(--primary-light);
        color: var(--primary);
    }

    /* Loan Card Item */
    .loan-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 32px;
    }

    .loan-card-detail {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 22px 24px;
        box-shadow: var(--shadow-xs);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .loan-card-detail:hover {
        border-color: #cbd5e1;
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }

    .loan-meta-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 1;
    }

    .loan-cover-badge {
        width: 54px;
        height: 72px;
        border-radius: 8px;
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .loan-cover-badge svg {
        opacity: 0.9;
    }

    .loan-book-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .loan-book-author {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .loan-book-cat {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        color: var(--primary);
        background: var(--primary-light);
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .loan-dates-right {
        display: flex;
        align-items: center;
        gap: 28px;
        flex-shrink: 0;
    }

    .date-box {
        text-align: right;
    }

    .date-box-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 3px;
    }

    .date-box-val {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-main);
    }

    .status-box {
        text-align: center;
        min-width: 140px;
    }

    .sisa-hari-subtext {
        font-size: 11.5px;
        color: var(--text-muted);
        margin-top: 4px;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state-card {
        background: #ffffff;
        border: 1.5px dashed var(--border-color);
        border-radius: var(--radius-lg);
        padding: 56px 24px;
        text-align: center;
        color: var(--text-muted);
        margin: 20px 0;
    }

    .empty-state-card svg {
        color: var(--text-light);
        margin-bottom: 16px;
    }

    .empty-state-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .btn-explore {
        margin-top: 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background-color: var(--primary);
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 600;
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: background-color 0.15s;
    }

    .btn-explore:hover {
        background-color: var(--primary-hover);
    }

    @media (max-width: 840px) {
        .loan-card-detail {
            flex-direction: column;
            align-items: flex-start;
        }
        .loan-dates-right {
            width: 100%;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }
        .date-box {
            text-align: left;
        }
    }
</style>

{{-- Page Header --}}
<div class="page-header-box">
    <div>
        <h1 class="page-title">
            <span>📌</span> Peminjaman Saya
        </h1>
        <p class="page-desc">Kelola buku yang sedang Anda pinjam dan pantau riwayat sirkulasi peminjaman Anda.</p>
    </div>
    <a href="{{ route('peminjam.katalog.index') }}" class="btn-explore" style="margin-top: 0;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        Jelajahi Katalog
    </a>
</div>

{{-- Tab Navigasi --}}
<div class="tabs-container">
    <a href="{{ route('peminjam.peminjaman.index', ['tab' => 'aktif']) }}" class="tab-btn {{ $tab !== 'riwayat' ? 'active' : '' }}">
        <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        Sedang Dipinjam
        <span class="tab-count-badge">{{ $activeLoans->count() }}</span>
    </a>
    <a href="{{ route('peminjam.peminjaman.index', ['tab' => 'riwayat']) }}" class="tab-btn {{ $tab === 'riwayat' ? 'active' : '' }}">
        <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Riwayat Peminjaman
        <span class="tab-count-badge">{{ $historyLoans->count() }}</span>
    </a>
</div>

{{-- Content: Tab 1. Sedang Dipinjam --}}
@if ($tab !== 'riwayat')
    @if ($activeLoans->count() > 0)
        <div class="loan-list">
            @foreach ($activeLoans as $loan)
                <div class="loan-card-detail">
                    <div class="loan-meta-left">
                        <div class="loan-cover-badge">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="loan-book-title">{{ $loan->book ? $loan->book->judul : 'Buku #' . $loan->book_id }}</h2>
                            <p class="loan-book-author">Penulis: {{ $loan->book ? $loan->book->penulis : '-' }} &bull; Penerbit: {{ $loan->book ? $loan->book->penerbit : '-' }}</p>
                            @if($loan->book)
                                <span class="loan-book-cat">{{ $loan->book->kategori }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="loan-dates-right">
                        <div class="date-box">
                            <span class="date-box-label">Tanggal Pinjam</span>
                            <span class="date-box-val">{{ $loan->tanggal_pinjam->translatedFormat('d M Y') }}</span>
                        </div>

                        <div class="date-box">
                            <span class="date-box-label">Batas Pengembalian</span>
                            <span class="date-box-val" style="color: #dc2626;">{{ $loan->tanggal_jatuh_tempo->translatedFormat('d M Y') }}</span>
                        </div>

                        <div class="status-box">
                            <span class="badge {{ $loan->badge_class }}">
                                {{ $loan->status_label }}
                            </span>
                            <div class="sisa-hari-subtext">
                                {{ $loan->sisa_hari_text }}
                            </div>
                        </div>

                        @if($loan->book)
                            <a href="{{ route('peminjam.katalog.show', $loan->book->id) }}" class="btn-sm-action">
                                Detail Buku
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State untuk Sedang Dipinjam --}}
        <div class="empty-state-card">
            <svg width="56" height="56" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3>Belum ada buku yang sedang Anda pinjam.</h3>
            <p>Silakan jelajahi katalog perpustakaan untuk meminjam buku referensi yang Anda perlukan.</p>
            <a href="{{ route('peminjam.katalog.index') }}" class="btn-explore">
                Jelajahi Katalog
            </a>
        </div>
    @endif

{{-- Content: Tab 2. Riwayat --}}
@else
    @if ($historyLoans->count() > 0)
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Kategori</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historyLoans as $hist)
                        <tr>
                            <td>
                                <strong style="font-weight: 600; color: var(--text-main);">
                                    {{ $hist->book ? $hist->book->judul : 'Buku #' . $hist->book_id }}
                                </strong>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    {{ $hist->book ? $hist->book->penulis : '-' }}
                                </div>
                            </td>
                            <td>
                                <span class="loan-book-cat">{{ $hist->book ? $hist->book->kategori : '-' }}</span>
                            </td>
                            <td>{{ $hist->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                            <td>
                                {{ $hist->tanggal_kembali ? $hist->tanggal_kembali->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td>
                                <span class="badge {{ $hist->badge_class }}">
                                    {{ $hist->status_label }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- Empty State untuk Riwayat --}}
        <div class="empty-state-card">
            <svg width="56" height="56" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3>Belum Ada Riwayat Peminjaman</h3>
            <p>Riwayat pengembalian buku Anda yang telah selesai diproses akan tercatat di sini.</p>
            <a href="{{ route('peminjam.katalog.index') }}" class="btn-explore">
                Jelajahi Katalog
            </a>
        </div>
    @endif
@endif

@endsection
