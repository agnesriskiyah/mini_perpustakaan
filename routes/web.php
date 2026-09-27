<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama (mengarahkan ke login / dashboard)
Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');

// Autentikasi: Signup / Registrasi
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Autentikasi: Login & Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PeminjamanController;

// Dashboard & Fitur Peminjam (Diproteksi: Harus login & role peminjam)
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'peminjam'])->name('dashboard');

    // Katalog Buku & Detail
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

    // Proses Peminjaman Buku
    Route::post('/pinjam/{id}', [PeminjamanController::class, 'store'])->name('pinjam.store');

    // Peminjaman Saya & Riwayat
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
});

use App\Http\Controllers\Petugas\BukuController as PetugasBukuController;
use App\Http\Controllers\Petugas\PeminjamController as PetugasPeminjamController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;

// Dashboard & Manajemen Petugas Perpustakaan (Diproteksi: Harus login & role petugas)
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'petugas'])->name('dashboard');

    // 1. Menu Data Buku (CRUD Buku)
    Route::get('/buku', [PetugasBukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [PetugasBukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [PetugasBukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id}', [PetugasBukuController::class, 'show'])->name('buku.show');
    Route::get('/buku/{id}/edit', [PetugasBukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [PetugasBukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id}', [PetugasBukuController::class, 'destroy'])->name('buku.destroy');

    // 2. Menu Data Peminjam
    Route::get('/peminjam', [PetugasPeminjamController::class, 'index'])->name('peminjam.index');
    Route::get('/peminjam/{id}', [PetugasPeminjamController::class, 'show'])->name('peminjam.show');

    // 3. Menu Peminjaman & Pengembalian
    Route::get('/peminjaman', [PetugasPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PetugasPeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('/peminjaman/{id}/kembalikan', [PetugasPeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
});
