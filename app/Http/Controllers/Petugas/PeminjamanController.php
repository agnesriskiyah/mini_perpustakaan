<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan seluruh data transaksi peminjaman buku perpustakaan.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $statusFilter = trim($request->input('status', ''));
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $query = Loan::with(['user', 'book']);

        // Search nama peminjam atau judul buku
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('book', function ($bq) use ($search) {
                    $bq->where('judul', 'like', "%{$search}%");
                });
            });
        }

        // Filter status
        if (!empty($statusFilter)) {
            $today = Carbon::today()->format('Y-m-d');
            if ($statusFilter === 'terlambat') {
                $query->where('status', 'dipinjam')
                      ->where('tanggal_jatuh_tempo', '<', $today);
            } elseif ($statusFilter === 'dipinjam') {
                $query->where('status', 'dipinjam')
                      ->where('tanggal_jatuh_tempo', '>=', $today);
            } elseif ($statusFilter === 'dikembalikan') {
                $query->where('status', 'dikembalikan');
            }
        }

        // Filter tanggal pinjam jika diisi
        if (!empty($tanggalMulai)) {
            $query->whereDate('tanggal_pinjam', '>=', $tanggalMulai);
        }
        if (!empty($tanggalSelesai)) {
            $query->whereDate('tanggal_pinjam', '<=', $tanggalSelesai);
        }

        $loans = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();

        // Hitung status display untuk setiap item
        $loans->getCollection()->transform(function ($loan) {
            return $this->formatLoanDisplay($loan);
        });

        return view('petugas.peminjaman.index', compact('loans', 'search', 'statusFilter', 'tanggalMulai', 'tanggalSelesai'));
    }

    /**
     * Tampilkan detail transaksi peminjaman.
     */
    public function show($id)
    {
        $loan = Loan::with(['user', 'book'])->findOrFail($id);
        $loan = $this->formatLoanDisplay($loan);

        return view('petugas.peminjaman.show', compact('loan'));
    }

    /**
     * Proses pengembalian buku oleh Petugas.
     */
    public function kembalikan($id)
    {
        $loan = Loan::with('book')->findOrFail($id);

        if ($loan->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah berstatus dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($loan) {
            $loan->update([
                'tanggal_kembali' => Carbon::today(),
                'status' => 'dikembalikan',
            ]);

            // Stok buku bertambah 1
            if ($loan->book) {
                $loan->book->increment('stok', 1);
            }
        });

        return redirect()->route('petugas.peminjaman.show', $loan->id)
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Helper untuk menghitung status tampilan dinamis (Dipinjam / Terlambat / Dikembalikan).
     */
    private function formatLoanDisplay(Loan $loan): Loan
    {
        $today = Carbon::today();
        $dueDate = Carbon::parse($loan->tanggal_jatuh_tempo);

        if ($loan->status === 'dikembalikan') {
            $loan->display_status = 'Dikembalikan';
            $loan->badge_class = 'badge-success';
        } else {
            // Status di DB masih 'dipinjam'
            if ($today->gt($dueDate)) {
                $diff = $dueDate->diffInDays($today);
                $loan->display_status = 'Terlambat (' . $diff . ' hari)';
                $loan->badge_class = 'badge-danger';
            } else {
                $loan->display_status = 'Dipinjam';
                $loan->badge_class = 'badge-primary';
            }
        }

        return $loan;
    }
}
