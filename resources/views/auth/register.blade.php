@extends('layouts.app')

@section('title', 'Daftar Akun Peminjam')

@section('content')
    <div class="auth-header">
        <span class="auth-badge">Pendaftaran Peminjam</span>
        <h1>Sistem Peminjaman Buku</h1>
        <p>Silakan lengkapi data di bawah ini untuk membuat akun peminjam baru.</p>
    </div>

    {{-- Alert error jika ada validasi yang gagal --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <div>
                <strong>Terdapat kesalahan pada input data:</strong>
                <ul style="margin-left: 18px; margin-top: 6px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('register.submit') }}" method="POST" novalidate>
        @csrf

        {{-- Nama Lengkap --}}
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap <span class="required">*</span></label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name') }}" 
                placeholder="Contoh: Ahmad Fauzi"
                required
                autofocus
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email <span class="required">*</span></label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email') }}" 
                placeholder="nama@domain.com"
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Grid: Nomor Telepon & Nomor Identitas --}}
        <div class="form-grid-2">
            <div class="form-group">
                <label for="telepon" class="form-label">Nomor Telepon / WhatsApp <span class="required">*</span></label>
                <input 
                    type="text" 
                    name="telepon" 
                    id="telepon" 
                    class="form-control @error('telepon') is-invalid @enderror" 
                    value="{{ old('telepon') }}" 
                    placeholder="081234567890"
                    required
                >
                @error('telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nomor_identitas" class="form-label">Nomor Identitas (NIS/NIM/KTP) <span class="required">*</span></label>
                <input 
                    type="text" 
                    name="nomor_identitas" 
                    id="nomor_identitas" 
                    class="form-control @error('nomor_identitas') is-invalid @enderror" 
                    value="{{ old('nomor_identitas') }}" 
                    placeholder="Contoh: 3201234567890001"
                    required
                >
                @error('nomor_identitas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Grid: Password & Konfirmasi Password --}}
        <div class="form-grid-2">
            <div class="form-group">
                <label for="password" class="form-label">Password <span class="required">*</span></label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    placeholder="Minimal 6 karakter"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                    placeholder="Ulangi password"
                    required
                >
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Tombol Submit --}}
        <button type="submit" class="btn-submit">
            Daftar Sekarang
        </button>
    </form>

    <div class="auth-footer">
        Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
@endsection
