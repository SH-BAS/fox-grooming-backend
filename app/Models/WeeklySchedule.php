<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;

class WeeklySchedule extends Model
{
    public $timestamps = false;

    protected $appends = ['opening_time_utc', 'closing_time_utc'];
    protected $fillable = [

        'franchise_id',
        'day_of_week',
        'is_open',
        'opening_time',
        'closing_time',
        'break_start',
        'break_end'
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }



    public function getOpeningTimeUtcAttribute()
    {
        return $this->attributes['opening_time'] ?? null;
    }

    public function getClosingTimeUtcAttribute()
    {
        return $this->attributes['closing_time'] ?? null;
    }

    public function getOpeningTimeAttribute($value)
    {
        $isApi = request()->is('api/*');

        if ($isApi) {
            return $value;
        }

        // Use today's date to shift the time correctly
        $date = date('Y-m-d');
        return Helpers::formatTimezoneDate($date . ' ' . $value, null, 'H:i:s');
    }

    public function getClosingTimeAttribute($value)
    {
        $isApi = request()->is('api/*');

        if ($isApi) {
            return $value;
        }

        $date = date('Y-m-d');
        return Helpers::formatTimezoneDate($date . ' ' . $value, null, 'H:i:s');
    }

    public function getBreakStartAttribute($value)
    {
        $isApi = request()->is('api/*');

        if ($isApi || empty($value)) {
            return $value;
        }

        $date = date('Y-m-d');
        return Helpers::formatTimezoneDate($date . ' ' . $value, null, 'H:i:s');
    }

    public function getBreakEndAttribute($value)
    {
        $isApi = request()->is('api/*');

        if ($isApi || empty($value)) {
            return $value;
        }

        $date = date('Y-m-d');
        return Helpers::formatTimezoneDate($date . ' ' . $value, null, 'H:i:s');
    }
}
