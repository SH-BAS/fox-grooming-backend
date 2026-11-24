<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedService extends Model
{
    public $timestamps = false;
    protected $fillable = ['booking_id', 'service_id', 'size_id', 'price', 'quantity', 'minutes'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

}
