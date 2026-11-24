<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, \Illuminate\Database\Eloquent\Factories\HasFactory, SoftDeletes;

    protected $fillable = ['role_id', 'name', 'email', 'phone', 'image', 'password', 'fcm_token', 'is_active'];
    protected $hidden = ['password'];
    public $timestamps = true;

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return asset('assets/images/user_placeholder.jpg');
    }

    public function role()
    {
        return $this->belongsTo(Role::class)->withTrashed();
    }
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sentMessages()
    {
        return $this->morphMany(Chat::class, 'sender');
    }

    public function receivedMessages()
    {
        return $this->morphMany(Chat::class, 'receiver');
    }
}
