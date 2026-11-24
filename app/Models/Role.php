<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

    public function admins() { return $this->hasMany(Admin::class); }
    public function staff() { return $this->hasMany(Staff::class); }
    public function users() { return $this->hasMany(User::class); }
}
