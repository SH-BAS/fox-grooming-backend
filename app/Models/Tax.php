<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['out_of_area_fee', 'reschedule_fee', 'tax'];
}
