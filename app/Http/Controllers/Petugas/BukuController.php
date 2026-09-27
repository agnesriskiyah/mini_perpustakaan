<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Tampilkan daftar koleksi buku perpustakaan untuk Petugas.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $kategori = trim($request->input('kategori', ''));

        $query = Book::withCount([
            'loans as loans_active_count' => function ($q) {
                $q->where('status', 'dipinjam');
            }
        ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        $books = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $categories = Book::distinct()->orderBy('kategori')->pluck('kategori');

        return view('petugas.buku.index', compact('books', 'categories', 'search', 'kategori'));
    }

    /**
     * Tampilkan form penambahan buku baru.
     */
    public function create()
    {
        $categories = Book::distinct()->pluck('kategori');
        return view('petugas.buku.create', compact('categories'));
    }

    /**
     * Simpan buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Penulis buku wajib diisi.',
            'penerbit.required' => 'Penerbit buku wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.digits' => 'Tahun terbit harus berupa 4 digit angka tahun yang valid.',
            'kategori.required' => 'Kategori buku wajib diisi.',
            'deskripsi.required' => 'Deskripsi buku wajib diisi.',
            'stok.required' => 'Jumlah stok buku wajib diisi.',
            'stok.min' => 'Stok buku harus berupa angka dan bernilai minimal 0.',
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $validated['cover'] = $path;
        }

        Book::create($validated);

        return redirect()->route('petugas.buku.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail buku.
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        $activeLoansCount = $book->loans()->where('status', 'dipinjam')->count();
        $totalLoansCount = $book->loans()->count();

        return view('petugas.buku.show', compact('book', 'activeLoansCount', 'totalLoansCount'));
    }

    /**
     * Tampilkan form edit data buku.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Book::distinct()->pluck('kategori');

        return view('petugas.buku.edit', compact('book', 'categories'));
    }

    /**
     * Perbarui data buku di database.
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Penulis buku wajib diisi.',
            'penerbit.required' => 'Penerbit buku wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.digits' => 'Tahun terbit harus berupa 4 digit angka tahun yang valid.',
            'kategori.required' => 'Kategori buku wajib diisi.',
            'deskripsi.required' => 'Deskripsi buku wajib diisi.',
            'stok.required' => 'Jumlah stok buku wajib diisi.',
            'stok.min' => 'Stok buku harus berupa angka dan bernilai minimal 0.',
        ]);

        if ($request->hasFile('cover')) {
            // Hapus file cover lama jika ada
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('petugas.buku.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Hapus buku dari database jika tidak ada peminjaman aktif.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Jangan mengizinkan penghapusan jika masih memiliki peminjaman aktif
        $hasActiveLoans = $book->loans()->where('status', 'dipinjam')->exists();

        if ($hasActiveLoans) {
            return back()->with('error', 'Tidak dapat menghapus buku karena masih sedang dipinjam.');
        }

        // Hapus file cover fisik jika tersimpan di disk public
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('petugas.buku.index')
            ->with('success', 'Data buku berhasil dihapus.');
    }
}
