<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
|--------------------------------------------------------------------------
| TAG MODEL - MODEL TAG ARTIKEL
|--------------------------------------------------------------------------
|
| Model ini merepresentasikan tag/kategori artikel:
| - Hubungan many-to-many dengan artikel
| - Soft delete support
| - Auto-creation saat user menambah tag baru
|
*/

class Tag extends Model
{
    use SoftDeletes;

    /**
     * Kolom yang bisa diisi secara massal
     */
    protected $fillable = [
        'name',  // Nama tag (contoh: "teknologi", "olahraga")
    ];

    /**
     * Mendapatkan semua artikel yang menggunakan tag ini
     * Relasi: Tag dimiliki oleh banyak artikel (Many-to-Many)
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }

    /**
     * Helper method untuk mendapatkan jumlah artikel dengan tag ini
     *
     * @return int Jumlah artikel yang menggunakan tag ini
     */
    public function getPostCountAttribute()
    {
        return $this->posts()->count();
    }

    /**
     * Scope untuk mencari tag berdasarkan nama
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Accessor untuk format nama tag (lowercase dan trim)
     *
     * @param string $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return strtolower(trim($value));
    }

    /**
     * Mutator untuk menyimpan nama tag dengan format konsisten
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower(trim($value));
    }
}
