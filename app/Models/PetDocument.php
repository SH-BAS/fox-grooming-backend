<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetDocument extends Model
{
    public $timestamps = false;
    protected $fillable = ['pet_id', 'document_type', 'file'];

    public function pet() { return $this->belongsTo(Pet::class); }

    public function getDocumentAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
