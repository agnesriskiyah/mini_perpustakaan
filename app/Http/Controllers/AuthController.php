<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan form registrasi / signup.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'petugas'
                ? redirect()->route('petugas.dashboard')
                : redirect()->route('peminjam.dashboard');
        }

        return view('auth.register');
    }

    /**
     * Memproses data registrasi / signup.
     */
    public function register(Request $request)
    {
        // Validasi input form sesuai kebutuhan dengan pesan Bahasa Indonesia
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telepon' => ['required', 'string', 'max:20'],
            'nomor_identitas' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',

            'telepon.required' => 'Nomor telepon wajib diisi.',
            'telepon.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',

            'nomor_identitas.required' => 'Nomor identitas wajib diisi.',
            'nomor_identitas.max' => 'Nomor identitas tidak boleh lebih dari 50 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
        ]);

        // Simpan data user ke database dengan password di-hash.
        // Role selalu diset secara otomatis ke 'peminjam' demi keamanan sistem.
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'],
            'nomor_identitas' => $validated['nomor_identitas'],
            'role' => 'peminjam',
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun peminjam Anda telah terdaftar. Silakan login.');
    }

    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'petugas'
                ? redirect()->route('petugas.dashboard')
                : redirect()->route('peminjam.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi / login user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Autentikasi dengan Auth::attempt (verifikasi password hash otomatis)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (Auth::user()->role === 'petugas') {
                return redirect()->intended(route('petugas.dashboard'));
            }

            return redirect()->intended(route('peminjam.dashboard'));
        }

        // Jika autentikasi gagal
        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Memproses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
