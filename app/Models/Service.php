<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    protected $fillable = ['category_id', 'name', 'description', 'minutes', 'image', 'pricing_type', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function sizes()
    {
        return $this->hasMany(ServiceSize::class);
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

    public function bookedServices()
    {
        return $this->hasMany(BookedService::class);
    }
}
