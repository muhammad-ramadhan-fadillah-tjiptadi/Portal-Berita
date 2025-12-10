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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
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

    /**
     * Get all of the comments for the post.
     */
    /**
     * Get all of the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->withTrashed();
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            if ($post->isForceDeleting()) {
                // If force deleting, also force delete all comments
                $post->comments()->forceDelete();
            } else {
                // If soft deleting, soft delete all comments
                $post->comments()->delete();
            }
        });

        static::restoring(function ($post) {
            // When restoring a post, also restore its comments
            $post->comments()->onlyTrashed()->restore();
        });
    }
}
