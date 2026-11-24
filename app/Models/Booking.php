<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\CentralLogics\Helpers;

class Booking extends Model
{
    use SoftDeletes;
    public $with = ['bookedServices'];
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'pet_id',
        'franchise_id',
        'staff_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'base_price',
        'tax',
        'out_of_area',
        'out_of_area_fee',
        'total_price',
        'received_amount',
        'payment_method',
        'received_type',
        'received_type_id',
        'cancel_note',
        'cancel_type',
        'cancel_id',
        'user_notes',
        'document',
        'is_reviewed',
        'address',
        'zip_code',
        'latitude',
        'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function pet()
    {
        return $this->belongsTo(Pet::class)->withTrashed();
    }
    public function franchise()
    {
        return $this->belongsTo(Franchise::class)->withTrashed();
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class)->withTrashed();
    }
    public function bookedServices()
    {
        return $this->hasMany(BookedService::class);
    }
    public function logs()
    {
        return $this->hasMany(BookingLog::class);
    }
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function termAcceptance()
    {
        return $this->hasOne(TermAcceptance::class);
    }
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function getBookingTimeAttribute()
    {
        return $this->start_time;
    }

    public function refreshTotals($baseCategoryId = null, $forceKeepBasePrice = false, $forceKeepOutOfAreaFee = false)
    {
        if (!$this->relationLoaded('bookedServices') || !$this->relationLoaded('pet')) {
            $this->load(['bookedServices.service', 'pet']);
        }

        $tax = Tax::first();
        $taxRate = $tax ? $tax->tax : 0;
        $outOfAreaFee = $tax ? $tax->out_of_area_fee : 0;

        // 1️⃣ Base Price
        $pet = $this->pet;

        $categoryId = $baseCategoryId ?? ($pet->category_id ?? null);
        $keepBase = $forceKeepBasePrice === true;
        $keepOutOfAreaFee = $forceKeepOutOfAreaFee === true;

        if (!$keepBase && $pet && ($this->base_price == 0 || $this->base_price === null) && $categoryId) {
            $baseStandard = CategoryStandard::where('category_id', $categoryId)
                ->where('size_id', $pet->size_id)
                ->first();
            if (!$baseStandard) {
                $baseStandard = CategoryStandard::where('category_id', $categoryId)
                    ->whereNull('size_id')
                    ->first();
            }
            $this->base_price = $baseStandard ? $baseStandard->price : 0;
        }

        // 2️⃣ Services Total
        $servicesTotal = 0;
        $totalMinutes = 0;
        foreach ($this->bookedServices as $bs) {
            $service = $bs->service;

            if (!$service)
                continue;

            if ($bs->price == 0 || $bs->price === null) {
                $sSizeId = $bs->size_id ?: ($pet->size_id ?? null);

                $sStandard = CategoryStandard::where('category_id', $service->category_id)
                    ->where('size_id', $sSizeId)
                    ->first();
                if (!$sStandard) {
                    $sStandard = CategoryStandard::where('category_id', $service->category_id)
                        ->whereNull('size_id')
                        ->first();
                }
                $bs->price = $sStandard ? $sStandard->price : 0;
            }

            if (!$bs->quantity)
                $bs->quantity = 1;
            if (!$bs->minutes && $service)
                $bs->minutes = $service->minutes;

            $bs->save();

            $servicesTotal += ($bs->price * $bs->quantity);
            $totalMinutes += ($bs->minutes * $bs->quantity);
        }

        // 3️⃣ Add base category minutes
        if ($pet && $pet->category) {
            $totalMinutes += (int) $pet->category->minutes;
        }

        // 4️⃣ Final Price Calculation
        $subtotal = $this->base_price + $servicesTotal;

        $this->tax = ($subtotal * $taxRate) / 100;

        // Add Out-of-Area Fee if applicable (preserve manual override when requested)
        if ($keepOutOfAreaFee) {
            if (!$this->out_of_area) {
                $this->out_of_area_fee = 0;
            } else {
                $this->out_of_area_fee = max(0, (float) $this->out_of_area_fee);
            }
        } else {
            $this->out_of_area_fee = $this->out_of_area ? $outOfAreaFee : 0;
        }

        $this->total_price = $subtotal + $this->tax + $this->out_of_area_fee;

        // 5️⃣ Update end_time based on total minutes
        if ($this->getRawOriginal('start_time') && $totalMinutes > 0) {
            $this->end_time = \Carbon\Carbon::parse($this->getRawOriginal('start_time'))
                ->addMinutes($totalMinutes)
                ->format('H:i:s');
        }

        $this->save();
        return $this;
    }

    // Accessor for formatted booking date
    public function getBookingDateAttribute($value)
    {
        $isApi = request()->is('api/*');

        if ($isApi) {
            return $value;
        }

        // Combine date with time to ensure correct timezone shift
        $time = $this->attributes['start_time'] ?? '00:00:00';
        return Helpers::formatTimezoneDate($value . ' ' . $time, null, 'Y-m-d');
    }

    // Accessor for formatted start time
    public function getStartTimeAttribute($value)
    {
        $isApi = request()->is('api/*');

        if ($isApi) {
            return $value;
        }

        $date = $this->attributes['booking_date'] ?? date('Y-m-d');
        return Helpers::formatTimezoneDate($date . ' ' . $value, null, 'H:i:s');
    }

    // Accessor for formatted end time
    public function getEndTimeAttribute($value)
    {
        if (!$value)
            return null;
        $isApi = request()->is('api/*');

        if ($isApi) {
            return $value;
        }

        $date = $this->attributes['booking_date'] ?? date('Y-m-d');
        return Helpers::formatTimezoneDate($date . ' ' . $value, null, 'H:i:s');
    }
}
