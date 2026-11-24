<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermAcceptance extends Model
{
    protected $fillable = ['user_id', 'booking_id', 'cms_page_id', 'image', 'accepted'];

    protected $casts = [
        'accepted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function cmsPage()
    {
        return $this->belongsTo(CmsPage::class);
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }

        return null;
    }
}
