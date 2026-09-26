<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role user sesuai dengan role yang dibutuhkan
        if (Auth::user()->role !== $role) {
            if (Auth::user()->role === 'petugas') {
                return redirect()->route('petugas.dashboard')->with('error', 'Akses ditolak. Anda dialihkan ke Dashboard Petugas.');
            }

            if (Auth::user()->role === 'peminjam') {
                return redirect()->route('peminjam.dashboard')->with('error', 'Akses ditolak. Anda dialihkan ke Dashboard Peminjam.');
            }

            abort(403, 'Akses tidak diizinkan untuk peran Anda.');
        }

        return $next($request);
    }
}
