<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamController extends Controller
{
    /**
     * Tampilkan data akun peminjam (khusus role peminjam).
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = User::where('role', 'peminjam')
            ->withCount([
                'loans as loans_active_count' => function ($q) {
                    $q->where('status', 'dipinjam');
                },
                'loans as loans_total_count'
            ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%");
            });
        }

        $peminjams = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('petugas.peminjam.index', compact('peminjams', 'search'));
    }

    /**
     * Tampilkan detail peminjam beserta riwayat peminjamannya.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Petugas hanya boleh melihat data user dengan role peminjam
        if ($user->role !== 'peminjam') {
            abort(403, 'Akses ditolak. Pengguna ini bukan akun peminjam.');
        }

        // Ambil riwayat peminjaman user ini
        $loans = $user->loans()
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeLoansCount = $loans->where('status', 'dipinjam')->count();
        $totalLoansCount = $loans->count();

        return view('petugas.peminjam.show', compact('user', 'loans', 'activeLoansCount', 'totalLoansCount'));
    }
}
