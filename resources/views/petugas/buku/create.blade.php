@extends('layouts.dashboard')

@section('title', 'Tambah Buku Baru - Petugas')

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

    .form-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 32px;
        box-shadow: var(--shadow-xs);
        max-width: 800px;
    }

    .form-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-desc {
        font-size: 13.5px;
        color: var(--text-muted);
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-color);
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .form-label span.req {
        color: #dc2626;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        font-size: 13.5px;
        font-family: inherit;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background-color: #f8fafc;
        color: var(--text-main);
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        background-color: #ffffff;
        border-color: var(--border-focus);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .form-control.is-invalid {
        border-color: #dc2626;
        background-color: #fef2f2;
    }

    .invalid-feedback {
        display: block;
        font-size: 12px;
        color: #dc2626;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }

    .btn-submit-form {
        padding: 11px 26px;
        font-size: 14px;
        font-weight: 600;
        font-family: inherit;
        background-color: var(--primary);
        color: #ffffff;
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.15s;
    }

    .btn-submit-form:hover {
        background-color: var(--primary-hover);
    }

    .btn-cancel-form {
        padding: 11px 20px;
        font-size: 14px;
        font-weight: 600;
        font-family: inherit;
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.15s;
    }

    .btn-cancel-form:hover {
        background-color: #e2e8f0;
        color: var(--text-main);
    }

    @media (max-width: 640px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- Breadcrumb --}}
<nav class="breadcrumb-nav">
    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
    <span>&rsaquo;</span>
    <a href="{{ route('petugas.buku.index') }}">Data Buku</a>
    <span>&rsaquo;</span>
    <span>Tambah Buku</span>
</nav>

<div class="form-card">
    <h2 class="form-title">
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Koleksi Buku Baru
    </h2>
    <p class="form-desc">Isi formulir di bawah ini dengan lengkap untuk menambahkan buku baru ke dalam katalog perpustakaan.</p>

    <form action="{{ route('petugas.buku.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Judul Buku --}}
        <div class="form-group">
            <label class="form-label" for="judul">Judul Buku <span class="req">*</span></label>
            <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: Pemrograman Web dengan Laravel" required autofocus>
            @error('judul')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Penulis & Penerbit --}}
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="penulis">Penulis <span class="req">*</span></label>
                <input type="text" id="penulis" name="penulis" class="form-control @error('penulis') is-invalid @enderror" value="{{ old('penulis') }}" placeholder="Contoh: Rian Pratama, M.Kom" required>
                @error('penulis')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="penerbit">Penerbit <span class="req">*</span></label>
                <input type="text" id="penerbit" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror" value="{{ old('penerbit') }}" placeholder="Contoh: Gava Media" required>
                @error('penerbit')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Tahun Terbit, Kategori, Stok --}}
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="tahun_terbit">Tahun Terbit <span class="req">*</span></label>
                <input type="number" id="tahun_terbit" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror" value="{{ old('tahun_terbit', date('Y')) }}" placeholder="Contoh: 2025" min="1900" max="{{ date('Y') + 1 }}" required>
                @error('tahun_terbit')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="stok">Jumlah Stok <span class="req">*</span></label>
                <input type="number" id="stok" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 1) }}" placeholder="Contoh: 5" min="0" required>
                @error('stok')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Kategori Buku --}}
        <div class="form-group">
            <label class="form-label" for="kategori">Kategori Buku <span class="req">*</span></label>
            <input type="text" list="category-suggestions" id="kategori" name="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori') }}" placeholder="Pilih atau ketik kategori baru..." required>
            <datalist id="category-suggestions">
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">
                @endforeach
            </datalist>
            @error('kategori')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label class="form-label" for="deskripsi">Deskripsi / Sinopsis Buku <span class="req">*</span></label>
            <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Tuliskan rangkuman isi buku, topik bahasan, atau sinopsis singkat..." required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Cover Buku (Opsional) --}}
        <div class="form-group">
            <label class="form-label" for="cover">Gambar Cover Buku (Opsional)</label>
            <input type="file" id="cover" name="cover" class="form-control @error('cover') is-invalid @enderror" accept="image/*">
            <small style="font-size: 11.5px; color: var(--text-muted); display: block; margin-top: 4px;">
                Format yang didukung: JPG, PNG, WEBP (Maksimal 2MB). Jika dikosongkan, cover digital akan digenerate otomatis.
            </small>
            @error('cover')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit-form">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Buku
            </button>
            <a href="{{ route('petugas.buku.index') }}" class="btn-cancel-form">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
