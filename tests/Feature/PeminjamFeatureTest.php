<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Database\Seeders\BookSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeminjamFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BookSeeder::class);
    }
    /**
     * Test akses halaman katalog buku oleh peminjam.
     */
    public function test_peminjam_can_access_catalog(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);

        $response = $this->actingAs($user)->get(route('peminjam.katalog.index'));

        $response->assertStatus(200);
        $response->assertSee('Katalog Buku');
    }

    /**
     * Test pencarian buku di katalog.
     */
    public function test_peminjam_can_search_catalog(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $book = Book::first();

        $response = $this->actingAs($user)->get(route('peminjam.katalog.index', ['search' => $book->judul]));

        $response->assertStatus(200);
        $response->assertSee($book->judul);
    }

    /**
     * Test akses detail buku.
     */
    public function test_peminjam_can_view_book_detail(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $book = Book::first();

        $response = $this->actingAs($user)->get(route('peminjam.katalog.show', $book->id));

        $response->assertStatus(200);
        $response->assertSee($book->judul);
        $response->assertSee($book->penulis);
    }

    /**
     * Test proses pinjam buku berhasil, stok berkurang 1, dan redirect ke peminjaman saya.
     */
    public function test_peminjam_can_borrow_book_successfully(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $book = Book::where('stok', '>', 0)->first();
        $initialStock = $book->stok;

        $response = $this->actingAs($user)->post(route('peminjam.pinjam.store', $book->id));

        $response->assertRedirect(route('peminjam.peminjaman.index'));
        $response->assertSessionHas('success', 'Buku "' . $book->judul . '" berhasil dipinjam.');

        // Stok berkurang 1
        $this->assertEquals($initialStock - 1, $book->fresh()->stok);

        // Data loan tercipta
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'dipinjam',
        ]);
    }

    /**
     * Test pencegahan peminjaman ganda untuk buku yang sama saat masih dipinjam.
     */
    public function test_prevent_duplicate_borrow_for_same_book(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $book = Book::where('stok', '>', 0)->first();

        // Peminjaman pertama
        $this->actingAs($user)->post(route('peminjam.pinjam.store', $book->id));

        $stockAfterFirst = $book->fresh()->stok;

        // Peminjaman kedua buku yang sama
        $response = $this->actingAs($user)->post(route('peminjam.pinjam.store', $book->id));

        $response->assertSessionHas('error', 'Anda masih memiliki peminjaman aktif untuk buku ini.');

        // Stok tidak berkurang lagi
        $this->assertEquals($stockAfterFirst, $book->fresh()->stok);

        // Hanya ada 1 record peminjaman
        $this->assertEquals(1, Loan::where('user_id', $user->id)->where('book_id', $book->id)->count());
    }

    /**
     * Test tidak bisa meminjam jika stok habis.
     */
    public function test_cannot_borrow_book_with_zero_stock(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $outOfStockBook = Book::where('stok', 0)->first();

        if (!$outOfStockBook) {
            $outOfStockBook = Book::create([
                'judul' => 'Buku Habis',
                'penulis' => 'Penulis',
                'penerbit' => 'Penerbit',
                'tahun_terbit' => '2023',
                'kategori' => 'Umum',
                'deskripsi' => 'Deskripsi',
                'stok' => 0,
            ]);
        }

        $response = $this->actingAs($user)->post(route('peminjam.pinjam.store', $outOfStockBook->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('loans', [
            'user_id' => $user->id,
            'book_id' => $outOfStockBook->id,
        ]);
    }

    /**
     * Test halaman peminjaman saya menampilkan buku yang sedang dipinjam.
     */
    public function test_my_loans_page_displays_borrowed_books(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $book = Book::where('stok', '>', 0)->first();

        $this->actingAs($user)->post(route('peminjam.pinjam.store', $book->id));

        $response = $this->actingAs($user)->get(route('peminjam.peminjaman.index'));

        $response->assertStatus(200);
        $response->assertSee($book->judul);
        $response->assertSee('Sedang Dipinjam');
    }

    /**
     * Test dashboard peminjam menampilkan statistik riil dari database.
     */
    public function test_peminjam_dashboard_shows_real_statistics(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $book = Book::where('stok', '>', 0)->first();

        $this->actingAs($user)->post(route('peminjam.pinjam.store', $book->id));

        $response = $this->actingAs($user)->get(route('peminjam.dashboard'));

        $response->assertStatus(200);
        $response->assertSee($book->judul);
        $response->assertSee('Sedang Dipinjam');
    }
}
