<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginAndDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman login dapat diakses.
     */
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Sistem Perpustakaan');
        $response->assertSee('Daftar sebagai Peminjam');
    }

    /**
     * Validasi: Field email dan password wajib diisi.
     */
    public function test_login_validation_required_fields(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email wajib diisi.',
            'password' => 'Password wajib diisi.',
        ]);

        $this->assertGuest();
    }

    /**
     * Validasi: Format email tidak valid.
     */
    public function test_login_validation_invalid_email_format(): void
    {
        $response = $this->post('/login', [
            'email' => 'bukan-format-email',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Format email tidak valid.',
        ]);

        $this->assertGuest();
    }

    /**
     * Login gagal jika password salah.
     */
    public function test_login_fails_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'peminjam@example.com',
            'role' => 'peminjam',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'peminjam@example.com',
            'password' => 'password-salah',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);

        $this->assertGuest();
    }

    /**
     * Login berhasil untuk role Peminjam -> dialihkan ke /peminjam/dashboard.
     */
    public function test_successful_login_redirects_peminjam_to_peminjam_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Peminjam',
            'email' => 'budi@example.com',
            'role' => 'peminjam',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('peminjam.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Login berhasil untuk role Petugas -> dialihkan ke /petugas/dashboard.
     */
    public function test_successful_login_redirects_petugas_to_petugas_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Pak Harun Petugas',
            'email' => 'harun@perpustakaan.com',
            'role' => 'petugas',
            'password' => Hash::make('petugas123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'harun@perpustakaan.com',
            'password' => 'petugas123',
        ]);

        $response->assertRedirect(route('petugas.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Dashboard Peminjam menampilkan info pengguna dan tombol logout.
     */
    public function test_peminjam_dashboard_displays_user_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Dewi Peminjam',
            'email' => 'dewi@example.com',
            'role' => 'peminjam',
        ]);

        $response = $this->actingAs($user)->get('/peminjam/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Peminjam');
        $response->assertSee('Dewi Peminjam');
        $response->assertSee('dewi@example.com');
        $response->assertSee('Peminjam');
        $response->assertSee('Logout');
        $response->assertSee('Halo, Dewi Peminjam');
        $response->assertSee('Peminjaman Aktif');
        $response->assertSee('Koleksi Buku Terbaru');
        $response->assertSee('Rekomendasi Populer Mahasiswa');
        $response->assertSee('Sedang Dipinjam');
        $response->assertSee('Jatuh Tempo');
    }

    /**
     * Dashboard Petugas Perpustakaan menampilkan info petugas dan tombol logout.
     */
    public function test_petugas_dashboard_displays_user_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Siti Petugas',
            'email' => 'siti@perpustakaan.com',
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($user)->get('/petugas/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Petugas Perpustakaan');
        $response->assertSee('Siti Petugas');
        $response->assertSee('siti@perpustakaan.com');
        $response->assertSee('Petugas Perpustakaan');
        $response->assertSee('Logout');
        $response->assertSee('Selamat datang, Siti Petugas');
        $response->assertSee('Menu Aksi Cepat');
        $response->assertSee('Peminjaman Terbaru');
        $response->assertSee('Aktivitas Terkini');
        $response->assertSee('Total Koleksi Buku');
        $response->assertSee('Total Peminjam');
    }

    /**
     * Proteksi Role: Pengguna belum login tidak bisa membuka dashboard.
     */
    public function test_unauthenticated_user_cannot_access_dashboards(): void
    {
        $responsePeminjam = $this->get('/peminjam/dashboard');
        $responsePeminjam->assertRedirect(route('login'));

        $responsePetugas = $this->get('/petugas/dashboard');
        $responsePetugas->assertRedirect(route('login'));
    }

    /**
     * Proteksi Role: User role Peminjam tidak bisa membuka Dashboard Petugas.
     */
    public function test_peminjam_cannot_access_petugas_dashboard(): void
    {
        $peminjam = User::factory()->create([
            'role' => 'peminjam',
        ]);

        $response = $this->actingAs($peminjam)->get('/petugas/dashboard');

        // Dialihkan ke dashboard peminjam dengan peringatan
        $response->assertRedirect(route('peminjam.dashboard'));
        $response->assertSessionHas('error');
    }

    /**
     * Proteksi Role: User role Petugas tidak bisa membuka Dashboard Peminjam.
     */
    public function test_petugas_cannot_access_peminjam_dashboard(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($petugas)->get('/peminjam/dashboard');

        // Dialihkan ke dashboard petugas dengan peringatan
        $response->assertRedirect(route('petugas.dashboard'));
        $response->assertSessionHas('error');
    }

    /**
     * Logout berhasil dan mengakhiri sesi autentikasi.
     */
    public function test_user_can_logout_successfully(): void
    {
        $user = User::factory()->create([
            'role' => 'peminjam',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Anda telah berhasil logout.');
        $this->assertGuest();
    }
}
