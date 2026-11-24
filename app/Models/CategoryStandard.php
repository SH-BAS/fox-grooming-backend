<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryStandard extends Model
{
    protected $fillable = ['category_id','title', 'size_id', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
