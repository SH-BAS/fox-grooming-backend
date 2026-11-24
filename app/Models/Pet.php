<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'category_id',
        'category_type_id',
        'size_id',
        'name',
        'age',
        'weight',
        'gender',
        'is_vaccinated',
        'notes',
        'profile_image'
    ];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        $firstImage = $this->images()->first();
        return $firstImage ? 'storage/' . $firstImage->image : 'assets/images/default-pet.png';
    }

    public function getProfileImageAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        if ($this->category->name == 'Dog') {
            return asset('assets/images/dog_defualt.jpg');
        }
        return asset('assets/images/cat_defualt.jpg');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }
    public function type()
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id')->withTrashed();
    }
    public function images()
    {
        return $this->hasMany(PetImage::class);
    }
    public function documents()
    {
        return $this->hasMany(PetDocument::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
