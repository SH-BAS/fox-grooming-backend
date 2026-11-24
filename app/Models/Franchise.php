<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Tax;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Franchise extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zone_id',
        'name',
        'image',
        'address',
        'city',
        'state',
        'latitude',
        'longitude',
        'is_active',
    ];

    public static function getByLocation($latitude, $longitude)
    {
        $userPoint = new Point($latitude, $longitude);
        $zone = Zone::whereContains('area', $userPoint)->first();

        if ($zone) {
            $franchises = self::where('zone_id', $zone->id)->where('is_active', 1)->get();
            return [
                'out_of_area' => false,
                'franchises' => $franchises,
                'out_of_area_fee' => 0
            ];
        }

        $distanceExpr = 'ST_Distance_Sphere(POINT(longitude, latitude), POINT(?, ?))';
        $franchises = self::where('is_active', 1)
            ->selectRaw("id, name, image, address, city, state, latitude, longitude, {$distanceExpr} AS distance", [$longitude, $latitude])
            ->havingRaw('distance <= ?', [10000]) // 10 km = 10 000 m
            ->orderBy('distance')
            ->get();

        if ($franchises->isEmpty()) {
            return [
                'out_of_area' => true,
                'franchises' => collect(),
                'out_of_area_fee' => 0,
                'message' => 'Fox Grooming is not servicing your area. Please try another location.'
            ];
        }

        $tax = Tax::first();
        $out_of_area_fee = $tax ? $tax->out_of_area_fee : 0;

        return [
            'out_of_area' => true,
            'out_of_area_fee' => $out_of_area_fee,
            'franchises' => $franchises,
        ];
    }

    protected static function booted()
    {
        static::deleting(function ($franchise) {
            if ($franchise->isForceDeleting()) {
                $franchise->bookings()->forceDelete();
            } else {
                $franchise->bookings()->delete();
            }
        });

        static::restored(function ($franchise) {
            $franchise->bookings()->withTrashed()->restore();
        });
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function weeklySchedules()
    {
        return $this->hasMany(WeeklySchedule::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('assets/images/Logo_-_Black.png');
    }
}
