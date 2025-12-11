<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| POST MODEL - MODEL ARTIKEL
|--------------------------------------------------------------------------
|
| Model ini merepresentasikan artikel/berita dalam sistem:
| - Hubungan dengan user (penulis)
| - Hubungan dengan kategori dan subkategori
| - Hubungan dengan komentar
| - Hubungan dengan tag
| - Soft delete support
|
*/

class Post extends Model
{
    use SoftDeletes;

    /**
     * Kolom yang bisa diisi secara massal
     */
    protected $fillable = [
        'user_id',        // ID penulis artikel
        'category_id',    // ID kategori artikel
        'subcategory_id', // ID subkategori artikel
        'title',          // Judul artikel
        'slug',           // Slug untuk URL friendly
        'content',        // Isi konten artikel
        'image',          // Gambar utama artikel
        'status',         // Status (draft/published)
    ];

    /**
     * Casting tipe data untuk kolom tertentu
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Mendapatkan kategori dari artikel
     * Relasi: Artikel milik satu kategori
     */
    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }

    /**
     * Mendapatkan user/penulis artikel
     * Relasi: Artikel ditulis oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan subkategori dari artikel
     * Relasi: Artikel milik satu subkategori (opsional)
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategorie::class, 'subcategory_id');
    }

    /**
     * Mendapatkan semua komentar dari artikel
     * Relasi: Artikel memiliki banyak komentar
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->withTrashed();
    }

    /**
     * Mendapatkan semua tag dari artikel
     * Relasi: Artikel memiliki banyak tag (Many-to-Many)
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    /**
     * Boot method untuk mengatur event model
     * - Handle soft delete untuk komentar saat artikel dihapus
     */
    protected static function boot()
    {
        parent::boot();

        // Saat artikel dihapus
        static::deleting(function ($post) {
            if ($post->isForceDeleting()) {
                // Jika force delete, hapus permanen semua komentar
                $post->comments()->forceDelete();
            } else {
                // Jika soft delete, soft delete semua komentar
                $post->comments()->delete();
            }
        });

        // Saat artikel di-restore
        static::restoring(function ($post) {
            // Restore juga semua komentar yang terhapus
            $post->comments()->onlyTrashed()->restore();
        });
    }
}
