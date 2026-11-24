<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'booking_id',
        'assigned_at',
        'ontheway_at',
        'arrived_at',
        'cancelled_at',
        'started_at',
        'updated_by_type',
        'updated_by_id',
        'completed_at'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    // updated_by_type admin then show admin name else show staff name
    public function updatedBy()
    {
        if ($this->updated_by_type == 'Admin') {
            return \App\Models\Admin::find($this->updated_by_id);
        }
        return \App\Models\Staff::find($this->updated_by_id);
    }
}
