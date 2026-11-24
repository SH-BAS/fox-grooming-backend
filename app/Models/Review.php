<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $fillable = ['booking_id', 'staff_id', 'rating', 'comment', 'user_id', 'is_publish'];

    public function booking()
    {
        return $this->belongsTo(Booking::class)->withTrashed();
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class)->withTrashed();
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
