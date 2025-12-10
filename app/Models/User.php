<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get user initials from name
     * Returns first letter of first name and first letter of last name (uppercase)
     *
     * @return string
     */
    public function getInitials()
    {
        $nameParts = explode(' ', trim($this->name));
        $initials = '';

        if (count($nameParts) >= 2) {
            // Take first letter of first name and first letter of last name
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        } elseif (count($nameParts) == 1) {
            // If only one name, take first 2 letters
            $initials = strtoupper(substr($nameParts[0], 0, 2));
        }

        return $initials;
    }

    /**
     * Get profile photo URL or return default avatar
     *
     * @return string
     */
    public function getProfilePhotoUrl()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        // Return default avatar with initials
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=fff&background=0d6efd&size=40";
    }
}
