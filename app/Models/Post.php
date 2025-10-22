<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'title',
        'slug',
        'content',
        'image',
        'status',
    ];

    /**
     * Get the category that owns the post.
     */
    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subcategory that owns the post.
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategorie::class, 'subcategory_id');
    }
}
