<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $timestamps = false;
    protected $fillable = ['category_id', 'image', 'title', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageAttribute($value)
    {
      if ($value) {
            return asset('storage/' . $value);
        }
        if ($this->category->name == 'Dog') {
            return asset('assets/images/dog_defualt.jpg');
        }
        return asset('assets/images/cat_defualt.jpg');

    }
}

