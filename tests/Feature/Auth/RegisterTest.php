<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman registrasi hanya untuk Peminjam dan tidak menampilkan pilihan Petugas.
     */
    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Sistem Peminjaman Buku');
        $response->assertSee('Pendaftaran Peminjam');
        $response->assertDontSee('Petugas Perpustakaan');
    }

    /**
     * Validasi: Semua field utama wajib diisi (tanpa input role).
     */
    public function test_validation_required_fields(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'telepon' => '',
            'nomor_identitas' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'Nama lengkap wajib diisi.',
            'email' => 'Email wajib diisi.',
            'telepon' => 'Nomor telepon wajib diisi.',
            'nomor_identitas' => 'Nomor identitas wajib diisi.',
            'password' => 'Password wajib diisi.',
        ]);

        $this->assertDatabaseCount('users', 0);
    }

    /**
     * Validasi: Format email tidak valid.
     */
    public function test_validation_invalid_email_format(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'bukan-email',
            'telepon' => '08123456789',
            'nomor_identitas' => '12345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Format email tidak valid.',
        ]);
    }

    /**
     * Validasi: Email sudah terdaftar (unique).
     */
    public function test_validation_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'budi@example.com',
            'telepon' => '0811111111',
            'nomor_identitas' => '99999999',
            'role' => 'peminjam',
        ]);

        $response = $this->post('/register', [
            'name' => 'Budi Baru',
            'email' => 'budi@example.com',
            'telepon' => '0822222222',
            'nomor_identitas' => '88888888',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email sudah terdaftar, silakan gunakan email lain.',
        ]);
    }

    /**
     * Validasi: Password minimal 6 karakter.
     */
    public function test_validation_password_min_6_characters(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'telepon' => '081234567890',
            'nomor_identitas' => '12345678',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Password minimal harus 6 karakter.',
        ]);
    }

    /**
     * Validasi: Konfirmasi password harus cocok.
     */
    public function test_validation_password_confirmation_mismatch(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'telepon' => '081234567890',
            'nomor_identitas' => '12345678',
            'password' => 'rahasia123',
            'password_confirmation' => 'berbeda456',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
        ]);
    }

    /**
     * Pendaftaran berhasil otomatis menghasilkan role peminjam.
     */
    public function test_successful_registration_automatically_creates_peminjam(): void
    {
        $response = $this->post('/register', [
            'name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'telepon' => '081234567899',
            'nomor_identitas' => '3201015509990001',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Registrasi berhasil! Akun peminjam Anda telah terdaftar. Silakan login.');

        $this->assertDatabaseHas('users', [
            'name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'telepon' => '081234567899',
            'nomor_identitas' => '3201015509990001',
            'role' => 'peminjam',
        ]);

        $user = User::where('email', 'dewi@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('peminjam', $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotEquals('password123', $user->password);
    }

    /**
     * Security: User tidak dapat memanipulasi role lewat request (role selalu peminjam).
     */
    public function test_security_role_injection_is_prevented(): void
    {
        $response = $this->post('/register', [
            'name' => 'Hacker Coba Petugas',
            'email' => 'hacker@example.com',
            'telepon' => '081234567800',
            'nomor_identitas' => '3201015509999999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'petugas', // Percobaan manipulasi input role
        ]);

        $response->assertRedirect(route('login'));

        $user = User::where('email', 'hacker@example.com')->first();
        $this->assertNotNull($user);
        // Memastikan role tetap 'peminjam' dan TIDAK menjadi 'petugas'
        $this->assertEquals('peminjam', $user->role);
    }
}
