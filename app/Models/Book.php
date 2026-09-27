<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori',
        'deskripsi',
        'stok',
        'cover',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    /**
     * Relasi ke peminjaman / loans
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'book_id');
    }

    /**
     * Alias relasi ke peminjaman
     */
    public function peminjaman(): HasMany
    {
        return $this->loans();
    }
}
