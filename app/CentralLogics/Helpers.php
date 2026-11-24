<?php
namespace App\CentralLogics;
use Carbon\Carbon;

class Helpers
{
    public static function formatTimezoneDate($datetime, ?string $timezone = null, $format = null): ?string
    {
        if (empty($datetime)) {
            return null;
        }

        try {
            $tz = $timezone ?? session('timezone', 'UTC');
            $date = Carbon::parse($datetime, 'UTC')->timezone($tz);
            return $format ? $date->format($format) : $date->toDateTimeString();
        } catch (\Exception $e) {
            return (string) $datetime;
        }
    }
}

