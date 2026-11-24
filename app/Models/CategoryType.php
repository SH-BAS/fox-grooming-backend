<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryType extends Model
{
    protected $fillable = ['category_id', 'name', 'image', 'description', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function pets()
    {
        return $this->hasMany(Pet::class);
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
