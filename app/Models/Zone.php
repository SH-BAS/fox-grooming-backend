<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Zone extends Model
{
    use HasSpatial, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'area',
    ];

    protected $casts = [
        'location' => Point::class,
        'area' => Polygon::class,
    ];

    protected static function booted()
    {
        static::deleting(function ($zone) {
            if ($zone->isForceDeleting()) {
                $zone->franchises()->forceDelete();
            } else {
                $zone->franchises()->delete();
            }
        });

        static::restored(function ($zone) {
            $zone->franchises()->withTrashed()->restore();
        });
    }

    public function franchises()
    {
        return $this->hasMany(Franchise::class);
    }
}
