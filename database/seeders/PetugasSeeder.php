<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat akun awal untuk Petugas Perpustakaan secara aman tanpa melalui Sign Up publik.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'petugas@perpustakaan.com'],
            [
                'name' => 'Siti Petugas Perpustakaan',
                'telepon' => '089876543210',
                'nomor_identitas' => 'NIP-19850101-001',
                'role' => 'petugas',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
