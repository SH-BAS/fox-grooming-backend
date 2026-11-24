<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetImage extends Model
{
    public $timestamps = false;
    protected $fillable = ['pet_id', 'image'];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
    }
}

