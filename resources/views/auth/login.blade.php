@extends('layouts.app')

@section('title', 'Masuk ke Sistem Perpustakaan')

@section('content')
    <div class="auth-header">
        <span class="auth-badge">Autentikasi</span>
        <h1>Masuk ke Sistem Perpustakaan</h1>
        <p>Silakan masukkan email dan password untuk mengakses portal.</p>
    </div>

    {{-- Pesan Sukses (dari registrasi atau logout) --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink: 0; margin-top: 1px;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Pesan Peringatan / Error (dari proteksi role atau middleware) --}}
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink: 0; margin-top: 1px;">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- Alert error validasi kredensial login --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink: 0; margin-top: 1px;">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST" novalidate>
        @csrf

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
                autofocus
            >
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label for="password" class="form-label">Password <span class="required">*</span></label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control @error('password') is-invalid @enderror" 
                placeholder="Masukkan password Anda"
                required
            >
        </div>

        {{-- Tombol Masuk --}}
        <button type="submit" class="btn-submit">
            Masuk
        </button>
    </form>

    <div class="auth-footer">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai Peminjam</a>
    </div>
@endsection
