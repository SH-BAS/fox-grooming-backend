<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'minutes', 'pricing_type', 'image', 'icon', 'is_active'];

    public function types()
    {
        return $this->hasMany(CategoryType::class);
    }
    public function standards()
    {
        return $this->hasMany(CategoryStandard::class);
    }
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
    public function services()
    {
        return $this->hasMany(Service::class);
    }


    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }

        $name = strtolower($this->name ?? '');
        if ($name === 'dog') {
            return asset('assets/images/dog_defualt.jpg');
        }
        return asset('assets/images/cat_defualt.jpg');
    }

    public function getIconAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return null;
    }

}
