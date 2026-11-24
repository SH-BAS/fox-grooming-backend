<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public $timestamps = false;
    protected $fillable = ['category_id', 'before_image', 'after_image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getBeforeImageAttribute($value)
    {
        return asset('storage/' . $value) ?: asset('assets/images/default_banner.webp');
    }
    public function getAfterImageAttribute($value)
    {
        return asset('storage/' . $value) ?: asset('assets/images/default_banner.webp');
    }
}
