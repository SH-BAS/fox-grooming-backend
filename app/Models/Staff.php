<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'staff';
    protected $fillable = ['role_id', 'name', 'email', 'phone', 'image', 'password', 'is_active'];
    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return "https://placehold.co/100x100?text=" . urlencode(substr($this->name ?? 'S', 0, 1));
    }

    public function role()
    {
        return $this->belongsTo(Role::class)->withTrashed();
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
