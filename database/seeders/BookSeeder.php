<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'judul' => 'Struktur Data & Algoritma dengan Python',
                'penulis' => 'Dr. Indrajit & Tim Lab',
                'penerbit' => 'Informatika Press',
                'tahun_terbit' => '2024',
                'kategori' => 'Ilmu Komputer',
                'deskripsi' => 'Buku ini membahas konsep dasar struktur data seperti array, linked list, stack, queue, tree, dan graph serta implementasi algoritma pencarian dan pengurutan menggunakan bahasa Python.',
                'stok' => 5,
                'cover' => null,
            ],
            [
                'judul' => 'Clean Architecture',
                'penulis' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun_terbit' => '2018',
                'kategori' => 'Software Engineering',
                'deskripsi' => 'Panduan praktis bagi para arsitek dan pengembang perangkat lunak untuk merancang sistem yang mudah dipelihara, fleksibel, teruji, dan tidak bergantung pada framework tertentu.',
                'stok' => 3,
                'cover' => null,
            ],
            [
                'judul' => 'Pemrograman Web dengan Laravel',
                'penulis' => 'Rian Pratama, M.Kom',
                'penerbit' => 'Gava Media',
                'tahun_terbit' => '2025',
                'kategori' => 'Pemrograman Web',
                'deskripsi' => 'Panduan lengkap membangun aplikasi web modern berskala enterprise mulai dari dasar routing, MVC, Eloquent ORM, validasi data, autentikasi, hingga deployment.',
                'stok' => 4,
                'cover' => null,
            ],
            [
                'judul' => 'Basis Data',
                'penulis' => 'Fathansyah, Ir.',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => '2022',
                'kategori' => 'Basis Data',
                'deskripsi' => 'Membahas teori dasar pemodelan data ERD, normalisasi relasional dari 1NF hingga BCNF, bahasa query SQL dasar hingga mahir, serta manajemen transaksi data.',
                'stok' => 6,
                'cover' => null,
            ],
            [
                'judul' => 'Dasar-Dasar Pemrograman',
                'penulis' => 'Rosa A.S. & M. Shalahuddin',
                'penerbit' => 'Modula',
                'tahun_terbit' => '2023',
                'kategori' => 'Dasar Pemrograman',
                'deskripsi' => 'Buku pengantar logika dan algoritma pemrograman komputer untuk pemula. Dilengkapi dengan diagram alir (flowchart), pseudocode, dan latihan studi kasus fundamental.',
                'stok' => 2,
                'cover' => null,
            ],
            [
                'judul' => 'Rekayasa Perangkat Lunak',
                'penulis' => 'Roger S. Pressman, Ph.D.',
                'penerbit' => 'McGraw-Hill Education',
                'tahun_terbit' => '2021',
                'kategori' => 'Software Engineering',
                'deskripsi' => 'Buku standar industri yang membahas siklus hidup pengembangan perangkat lunak (SDLC), metode Agile, Scrum, manajemen risiko, pengujian mutu perangkat lunak, dan arsitektur.',
                'stok' => 0, // Stok habis untuk menguji fitur stok habis & disabled button
                'cover' => null,
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(
                ['judul' => $book['judul']],
                $book
            );
        }
    }
}
