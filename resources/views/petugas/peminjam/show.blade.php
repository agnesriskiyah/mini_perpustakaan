@extends('layouts.dashboard')

@section('title', 'Detail Peminjam: ' . $user->name . ' - Petugas')

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

    .profile-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: var(--shadow-xs);
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 20px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 24px;
    }

    .profile-avatar-big {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), #3b82f6);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .profile-title-area h2 {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .profile-title-area p {
        font-size: 13.5px;
        color: var(--text-muted);
    }

    .user-info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }

    .info-box-item {
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 14px 18px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-box-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-box-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .history-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--shadow-xs);
    }

    .history-header {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
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
        margin-top: 20px;
    }

    .btn-back-link:hover {
        background-color: #e2e8f0;
        color: var(--text-main);
    }

    @media (max-width: 900px) {
        .user-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

{{-- Breadcrumb --}}
<nav class="breadcrumb-nav">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <span>&rsaquo;</span>
    <a href="{{ route('petugas.peminjam.index') }}">Data Peminjam</a>
    <span>&rsaquo;</span>
    <span>{{ $user->name }}</span>
</nav>

{{-- Profil Singkat Peminjam --}}
<div class="profile-card">
    <div class="profile-header">
        <div class="profile-avatar-big">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="profile-title-area">
            <h2>{{ $user->name }}</h2>
            <p>Email: <strong>{{ $user->email }}</strong> &bull; Peran: <span class="badge" style="background: var(--primary-light); color: var(--primary);">Peminjam</span></p>
        </div>
    </div>

    <div class="user-info-grid">
        <div class="info-box-item">
            <span class="info-box-label">Nomor Identitas</span>
            <span class="info-box-value">{{ $user->nomor_identitas ?? '-' }}</span>
        </div>
        <div class="info-box-item">
            <span class="info-box-label">Nomor Telepon</span>
            <span class="info-box-value">{{ $user->telepon ?? '-' }}</span>
        </div>
        <div class="info-box-item">
            <span class="info-box-label">Tanggal Bergabung</span>
            <span class="info-box-value">{{ $user->created_at->translatedFormat('d F Y') }}</span>
        </div>
        <div class="info-box-item">
            <span class="info-box-label">Buku Sedang Dipinjam</span>
            <span class="info-box-value" style="color: {{ $activeLoansCount > 0 ? '#1d4ed8' : '#64748b' }};">
                {{ $activeLoansCount }} Buku
            </span>
        </div>
    </div>
</div>

{{-- Riwayat Peminjaman --}}
<div class="history-card">
    <h3 class="history-header">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Riwayat Peminjaman (Total: {{ $totalLoansCount }}x)
    </h3>

    @if ($loans->count() > 0)
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Buku</th>
                        <th>Kategori</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th style="text-align: center; width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loans as $index => $loan)
                        @php
                            $today = \Carbon\Carbon::today();
                            $dueDate = \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo);
                            if ($loan->status === 'dikembalikan') {
                                $badgeClass = 'badge-success';
                                $statusText = 'Dikembalikan';
                            } elseif ($today->gt($dueDate)) {
                                $badgeClass = 'badge-danger';
                                $statusText = 'Terlambat';
                            } else {
                                $badgeClass = 'badge-primary';
                                $statusText = 'Dipinjam';
                            }
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong style="color: var(--text-main); font-weight: 600;">
                                    {{ $loan->book ? $loan->book->judul : 'Buku #' . $loan->book_id }}
                                </strong>
                                <div style="font-size: 11.5px; color: var(--text-muted);">
                                    Penulis: {{ $loan->book ? $loan->book->penulis : '-' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #475569;">
                                    {{ $loan->book ? $loan->book->kategori : '-' }}
                                </span>
                            </td>
                            <td>{{ $loan->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                            <td>
                                <span style="color: #dc2626; font-weight: 600;">
                                    {{ $loan->tanggal_jatuh_tempo->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td>
                                {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('petugas.peminjaman.show', $loan->id) }}" class="btn-sm-action">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 36px 16px; color: var(--text-muted);">
            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin-bottom: 8px; color: var(--text-light);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <p>Peminjam ini belum memiliki riwayat peminjaman buku.</p>
        </div>
    @endif
</div>

<a href="{{ route('petugas.peminjam.index') }}" class="btn-back-link">
    &larr; Kembali ke Data Peminjam
</a>

@endsection
