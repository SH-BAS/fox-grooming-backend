<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['role_id', 'franchise_id', 'name', 'email', 'password', 'is_active'];
    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
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
