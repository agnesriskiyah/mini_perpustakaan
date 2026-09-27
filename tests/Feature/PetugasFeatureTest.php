<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\BookSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $petugas;
    protected User $peminjam;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BookSeeder::class);

        $this->petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Petugas Perpustakaan Test',
            'email' => 'petugas.test@perpustakaan.com',
        ]);

        $this->peminjam = User::factory()->create([
            'role' => 'peminjam',
            'name' => 'Ahmad Mahasiswa',
            'email' => 'ahmad@mahasiswa.ac.id',
            'nomor_identitas' => 'NIM-2026-001',
        ]);
    }

    /**
     * 1. Test akses halaman data buku oleh petugas.
     */
    public function test_petugas_can_access_books_index(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('petugas.buku.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Buku');
        $response->assertSee('+ Tambah Buku');
    }

    /**
     * 2. Test petugas dapat menambah buku baru.
     */
    public function test_petugas_can_create_new_book(): void
    {
        $bookData = [
            'judul' => 'Artificial Intelligence Modern',
            'penulis' => 'Stuart Russell',
            'penerbit' => 'Pearson',
            'tahun_terbit' => '2024',
            'kategori' => 'Kecerdasan Buatan',
            'deskripsi' => 'Buku standar industri mengenai AI, agen cerdas, dan pembelajaran mesin.',
            'stok' => 5,
        ];

        $response = $this->actingAs($this->petugas)->post(route('petugas.buku.store'), $bookData);

        $response->assertRedirect(route('petugas.buku.index'));
        $response->assertSessionHas('success', 'Data buku berhasil ditambahkan.');

        $this->assertDatabaseHas('books', [
            'judul' => 'Artificial Intelligence Modern',
            'penulis' => 'Stuart Russell',
            'stok' => 5,
        ]);
    }

    /**
     * 3. Test validasi form tambah buku.
     */
    public function test_validation_when_creating_book(): void
    {
        $response = $this->actingAs($this->petugas)->post(route('petugas.buku.store'), [
            'judul' => '',
            'penulis' => '',
            'stok' => -1,
        ]);

        $response->assertSessionHasErrors(['judul', 'penulis', 'kategori', 'tahun_terbit', 'deskripsi', 'stok']);
    }

    /**
     * 4. Test petugas dapat mengedit buku.
     */
    public function test_petugas_can_update_book(): void
    {
        $book = Book::first();

        $response = $this->actingAs($this->petugas)->put(route('petugas.buku.update', $book->id), [
            'judul' => 'Judul Buku Telah Diperbarui',
            'penulis' => $book->penulis,
            'penerbit' => $book->penerbit,
            'tahun_terbit' => '2025',
            'kategori' => $book->kategori,
            'deskripsi' => 'Deskripsi yang telah diperbarui oleh staf petugas.',
            'stok' => 10,
        ]);

        $response->assertRedirect(route('petugas.buku.index'));
        $response->assertSessionHas('success', 'Data buku berhasil diperbarui.');

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'judul' => 'Judul Buku Telah Diperbarui',
            'stok' => 10,
        ]);
    }

    /**
     * 5. Test pencegahan hapus buku jika masih memiliki peminjaman aktif.
     */
    public function test_cannot_delete_book_with_active_loans(): void
    {
        $book = Book::where('stok', '>', 0)->first();

        // Buat peminjaman aktif
        Loan::create([
            'user_id' => $this->peminjam->id,
            'book_id' => $book->id,
            'tanggal_pinjam' => Carbon::today(),
            'tanggal_jatuh_tempo' => Carbon::today()->addDays(7),
            'status' => 'dipinjam',
        ]);

        $response = $this->actingAs($this->petugas)->delete(route('petugas.buku.destroy', $book->id));

        $response->assertSessionHas('error', 'Tidak dapat menghapus buku karena masih sedang dipinjam.');
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    /**
     * 6. Test buku aman dapat dihapus jika tidak ada peminjaman aktif.
     */
    public function test_petugas_can_delete_book_without_active_loans(): void
    {
        $book = Book::create([
            'judul' => 'Buku Uji Hapus',
            'penulis' => 'Penulis X',
            'penerbit' => 'Penerbit Y',
            'tahun_terbit' => '2022',
            'kategori' => 'Umum',
            'deskripsi' => 'Deskripsi',
            'stok' => 2,
        ]);

        $response = $this->actingAs($this->petugas)->delete(route('petugas.buku.destroy', $book->id));

        $response->assertRedirect(route('petugas.buku.index'));
        $response->assertSessionHas('success', 'Data buku berhasil dihapus.');
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     * 7. Test petugas dapat melihat data peminjam (tanpa akun petugas).
     */
    public function test_petugas_can_access_peminjam_index(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('petugas.peminjam.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Peminjam');
        $response->assertSee($this->peminjam->name);
        $response->assertDontSee($this->petugas->email); // Akun petugas tidak boleh muncul di data peminjam
    }

    /**
     * 8. Test petugas melihat detail peminjam dan penolakan jika melihat akun petugas.
     */
    public function test_petugas_view_peminjam_detail_and_protect_petugas_id(): void
    {
        // Detail peminjam valid
        $response = $this->actingAs($this->petugas)->get(route('petugas.peminjam.show', $this->peminjam->id));
        $response->assertStatus(200);
        $response->assertSee($this->peminjam->name);
        $response->assertSee($this->peminjam->email);

        // Jika mencoba membuka ID petugas di rute detail peminjam
        $responsePetugas = $this->actingAs($this->petugas)->get(route('petugas.peminjam.show', $this->petugas->id));
        $responsePetugas->assertStatus(403);
    }

    /**
     * 9. Test daftar peminjaman dan proses pengembalian buku oleh petugas.
     */
    public function test_petugas_can_view_loans_and_process_return(): void
    {
        $book = Book::where('stok', '>', 0)->first();
        $initialStock = $book->stok;

        // Pinjam buku
        $loan = Loan::create([
            'user_id' => $this->peminjam->id,
            'book_id' => $book->id,
            'tanggal_pinjam' => Carbon::today()->subDays(3),
            'tanggal_jatuh_tempo' => Carbon::today()->addDays(4),
            'status' => 'dipinjam',
        ]);
        $book->decrement('stok', 1);

        // Petugas buka daftar peminjaman
        $responseIndex = $this->actingAs($this->petugas)->get(route('petugas.peminjaman.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Data Peminjaman');
        $responseIndex->assertSee($this->peminjam->name);
        $responseIndex->assertSee($book->judul);

        // Petugas buka detail peminjaman
        $responseShow = $this->actingAs($this->petugas)->get(route('petugas.peminjaman.show', $loan->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Proses Pengembalian Buku');

        // Petugas proses pengembalian
        $responseKembalikan = $this->actingAs($this->petugas)->post(route('petugas.peminjaman.kembalikan', $loan->id));
        $responseKembalikan->assertRedirect(route('petugas.peminjaman.show', $loan->id));
        $responseKembalikan->assertSessionHas('success', 'Buku berhasil dikembalikan.');

        // Cek status loan di DB
        $loanFresh = $loan->fresh();
        $this->assertEquals('dikembalikan', $loanFresh->status);
        $this->assertNotNull($loanFresh->tanggal_kembali);

        // Stok buku bertambah kembali 1
        $this->assertEquals($initialStock, $book->fresh()->stok);

        // Setelah dikembalikan, tombol pengembalian tidak muncul lagi
        $responseAfter = $this->actingAs($this->petugas)->get(route('petugas.peminjaman.show', $loan->id));
        $responseAfter->assertDontSee('Proses Pengembalian Buku');
        $responseAfter->assertSee('Dikembalikan pada:');
    }

    /**
     * 10. Test proteksi role: Peminjam TIDAK dapat mengakses menu Petugas (dialihkan ke Dashboard Peminjam).
     */
    public function test_peminjam_cannot_access_petugas_routes(): void
    {
        $responseBuku = $this->actingAs($this->peminjam)->get(route('petugas.buku.index'));
        $responseBuku->assertRedirect(route('peminjam.dashboard'));
        $responseBuku->assertSessionHas('error');

        $responsePeminjam = $this->actingAs($this->peminjam)->get(route('petugas.peminjam.index'));
        $responsePeminjam->assertRedirect(route('peminjam.dashboard'));
        $responsePeminjam->assertSessionHas('error');

        $responsePeminjaman = $this->actingAs($this->peminjam)->get(route('petugas.peminjaman.index'));
        $responsePeminjaman->assertRedirect(route('peminjam.dashboard'));
        $responsePeminjaman->assertSessionHas('error');
    }
}
