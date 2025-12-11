<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
|--------------------------------------------------------------------------
| USER MODEL - MODEL PENGGUNA
|--------------------------------------------------------------------------
|
| Model ini merepresentasikan user/pengguna dalam sistem:
| - Autentikasi dan otorisasi
| - Role management (admin/user)
| - Profile management
| - Soft delete support
| - Helper methods untuk initials dan profile photo
|
*/

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Kolom yang bisa diisi secara massal
     */
    protected $fillable = [
        'name',          // Nama lengkap user
        'email',         // Email user (unique)
        'password',      // Password user (hashed)
        'role',          // Role user (admin/user)
        'profile_photo', // Foto profil user (opsional)
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',      // Password tidak ditampilkan
        'remember_token', // Token remember me
    ];

    /**
     * Casting tipe data untuk kolom tertentu
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Mendapatkan semua artikel yang ditulis oleh user
     * Relasi: User memiliki banyak artikel
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Mendapatkan semua komentar yang dibuat oleh user
     * Relasi: User memiliki banyak komentar
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Mendapatkan inisial dari nama user
     * Digunakan untuk avatar initials
     *
     * @return string Inisial user (contoh: "JS" untuk "John Smith")
     */
    public function getInitials()
    {
        $nameParts = explode(' ', trim($this->name));
        $initials = '';

        if (count($nameParts) >= 2) {
            // Ambil huruf pertama nama depan dan huruf pertama nama belakang
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        } elseif (count($nameParts) == 1) {
            // Jika hanya satu nama, ambil 2 huruf pertama
            $initials = strtoupper(substr($nameParts[0], 0, 2));
        }

        return $initials;
    }

    /**
     * Mendapatkan URL foto profil user
     * Jika ada foto profil yang diupload, gunakan itu
     * Jika tidak, gunakan UI Avatars API
     *
     * @return string URL foto profil
     */
    public function getProfilePhotoUrl()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        // Return default avatar dengan inisial dari UI Avatars API
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=fff&background=0d6efd&size=40";
    }

    /**
     * Cek apakah user adalah admin
     *
     * @return bool true jika role = admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah user biasa
     *
     * @return bool true jika role = user
     */
    public function isUser()
    {
        return $this->role === 'user';
    }
}
