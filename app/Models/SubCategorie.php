<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategorie extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subcategory) {
            $subcategory->slug = \Illuminate\Support\Str::slug($subcategory->name);
        });

        static::updating(function ($subcategory) {
            $subcategory->slug = \Illuminate\Support\Str::slug($subcategory->name);
        });
    }
}
