<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    /**
     * Tampilkan katalog buku dengan fitur pencarian dan filter kategori.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $kategori = trim($request->input('kategori', ''));

        $query = Book::query();

        // Pencarian berdasarkan judul, penulis, atau kategori
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter kategori spesifik
        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        $books = $query->orderBy('judul', 'asc')->paginate(12)->withQueryString();

        // Daftar kategori unik untuk dropdown filter
        $categories = Book::distinct()->orderBy('kategori')->pluck('kategori');

        // Buku yang sedang dipinjam oleh user saat ini (untuk badge/disable tombol jika perlu)
        $borrowedBookIds = [];
        if (Auth::check()) {
            $borrowedBookIds = Loan::where('user_id', Auth::id())
                ->where('status', 'dipinjam')
                ->pluck('book_id')
                ->toArray();
        }

        return view('peminjam.katalog.index', compact('books', 'categories', 'search', 'kategori', 'borrowedBookIds'));
    }

    /**
     * Tampilkan detail buku lengkap.
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        // Periksa apakah peminjam yang sedang login sedang aktif meminjam buku ini
        $isCurrentlyBorrowed = false;
        if (Auth::check()) {
            $isCurrentlyBorrowed = Loan::where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->where('status', 'dipinjam')
                ->exists();
        }

        return view('peminjam.katalog.show', compact('book', 'isCurrentlyBorrowed'));
    }
}
