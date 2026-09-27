<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan halaman Peminjaman Saya (tab: Sedang Dipinjam & Riwayat).
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $tab = $request->query('tab', 'aktif'); // 'aktif' atau 'riwayat'

        // Peminjaman yang sedang aktif (status = 'dipinjam')
        $activeLoans = Loan::with('book')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get()
            ->map(function ($loan) {
                return $this->formatLoanData($loan);
            });

        // Riwayat peminjaman yang sudah selesai (status selain 'dipinjam')
        $historyLoans = Loan::with('book')
            ->where('user_id', $userId)
            ->whereIn('status', ['dikembalikan', 'terlambat'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($loan) {
                return $this->formatLoanData($loan);
            });

        return view('peminjam.peminjaman.index', compact('activeLoans', 'historyLoans', 'tab'));
    }

    /**
     * Proses transaksi peminjaman buku oleh Peminjam yang sedang login.
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);

        // 1. Pastikan stok buku > 0
        if ($book->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku "' . $book->judul . '" saat ini sedang habis.');
        }

        // 2. Cegah peminjaman ganda: peminjam tidak boleh meminjam buku yang sama jika masih berstatus 'dipinjam'
        $activeLoan = Loan::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->first();

        if ($activeLoan) {
            return back()->with('error', 'Anda masih memiliki peminjaman aktif untuk buku ini.');
        }

        // 3. Simpan peminjaman & kurangi stok buku dalam database transaction
        DB::transaction(function () use ($user, $book) {
            $today = Carbon::today();
            $dueDate = Carbon::today()->addDays(7);

            Loan::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'tanggal_pinjam' => $today->format('Y-m-d'),
                'tanggal_jatuh_tempo' => $dueDate->format('Y-m-d'),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            $book->decrement('stok', 1);
        });

        return redirect()->route('peminjam.peminjaman.index')
            ->with('success', 'Buku "' . $book->judul . '" berhasil dipinjam.');
    }

    /**
     * Helper untuk format status & perhitungan sisa hari jatuh tempo.
     */
    public static function formatLoanData(Loan $loan): Loan
    {
        $today = Carbon::today();
        $dueDate = Carbon::parse($loan->tanggal_jatuh_tempo);

        if ($loan->status === 'dipinjam') {
            $diffDays = $today->diffInDays($dueDate, false); // positif jika due date di masa depan, negatif jika terlewat

            if ($diffDays < 0) {
                $daysOver = abs($diffDays);
                $loan->sisa_hari_text = 'Terlewat ' . $daysOver . ' hari';
                $loan->status_label = 'Terlewat ' . $daysOver . ' hari';
                $loan->badge_class = 'badge-danger';
            } elseif ($diffDays == 0) {
                $loan->sisa_hari_text = 'Jatuh tempo hari ini';
                $loan->status_label = 'Jatuh tempo hari ini';
                $loan->badge_class = 'badge-danger';
            } elseif ($diffDays <= 3) {
                $loan->sisa_hari_text = $diffDays . ' hari lagi';
                $loan->status_label = 'Jatuh tempo ' . $diffDays . ' hari lagi';
                $loan->badge_class = 'badge-warning';
            } else {
                $loan->sisa_hari_text = $diffDays . ' hari lagi';
                $loan->status_label = 'Sedang Dipinjam';
                $loan->badge_class = 'badge-primary';
            }
        } elseif ($loan->status === 'dikembalikan') {
            $loan->status_label = 'Dikembalikan';
            $loan->badge_class = 'badge-success';
            $loan->sisa_hari_text = 'Selesai';
        } else {
            $loan->status_label = ucfirst($loan->status);
            $loan->badge_class = 'badge-danger';
            $loan->sisa_hari_text = 'Selesai';
        }

        return $loan;
    }
}
