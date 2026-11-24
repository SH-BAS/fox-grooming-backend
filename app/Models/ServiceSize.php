<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSize extends Model
{
    protected $fillable = ['service_id', 'size_id', 'price'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
