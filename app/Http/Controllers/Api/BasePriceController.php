<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryStandard;
use App\Http\Resources\BasePriceResource;
use App\Models\Pet;
use App\Models\WeeklySchedule;
use App\Models\Tax;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class BasePriceController extends Controller
{
    public function basePrice(Request $request, $petId)
    {
        try {
            //authenticated user pet only
            $pet = Pet::find($petId);
            if (!$pet) {
                return $this->error('Pet not found', 404);
            }
            if ($pet->category->name == 'Dog') {
                $basePrice = CategoryStandard::where('category_id', $pet->category_id)->where('size_id', $pet->size_id)->first();
            } else {
                $basePrice = CategoryStandard::where('category_id', $pet->category_id)->where('size_id', null)->first();
            }
            if (!$basePrice) {
                return $this->error('Base price not found', 404);
            }
            $tax = Tax::first();
            $basePrice['tax'] = $tax->tax;
            return $this->success(BasePriceResource::make($basePrice), 'Base price fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function getTimeSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'franchise_id' => 'required|exists:franchises,id',
            'total_duration' => 'required|integer|min:1',
            'staff_id' => 'nullable|exists:staff,id',
            'timezone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $date = $request->date;
        $franchiseId = $request->franchise_id;
        $totalDuration = (int) $request->total_duration;
        $selectedStaffId = $request->staff_id;
        $tz = $request->timezone ?? session('timezone', 'UTC');

        $localDate = Carbon::parse($date);
        $dayOfWeek = strtolower(substr($localDate->dayName, 0, 3));

        $schedule = WeeklySchedule::where('franchise_id', $franchiseId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule || !$schedule->is_open) {
            return response()->json([
                'success' => false,
                'message' => 'Selected franchise is closed on this day',
                'slots' => []
            ]);
        }

        $opening = $schedule->getRawOriginal('opening_time') ?? '00:00:00';
        $closing = $schedule->getRawOriginal('closing_time') ?? '23:59:59';

        $franchiseStartLocal = Carbon::parse($date . ' ' . $opening, 'UTC')->timezone($tz);
        $franchiseEndLocal = Carbon::parse($date . ' ' . $closing, 'UTC')->timezone($tz);
        if ($franchiseStartLocal > $franchiseEndLocal) {
            $franchiseEndLocal->addDay();
        }

        // Fetch all relevant bookings for this franchise/day once (Optimization)
        $utcRangeStart = (clone $franchiseStartLocal)->setTimezone('UTC');
        $utcRangeEnd = (clone $franchiseEndLocal)->setTimezone('UTC');

        $existingBookings = \App\Models\Booking::whereNotIn('status', ['cancelled'])
            ->where('franchise_id', $franchiseId)
            ->where(function ($q) use ($utcRangeStart, $utcRangeEnd) {
                $q->where(function ($sq) use ($utcRangeStart, $utcRangeEnd) {
                    $sq->whereRaw("CONCAT(booking_date, ' ', start_time) < ?", [$utcRangeEnd->toDateTimeString()])
                        ->whereRaw("CONCAT(booking_date, ' ', end_time) > ?", [$utcRangeStart->toDateTimeString()]);
                });
            })
            ->get();

        $totalStaff = \App\Models\Staff::where('is_active', true)->count();
        $slots = [];
        $current = clone $franchiseStartLocal;

        // Use 5-minute intervals
        while ($current < $franchiseEndLocal) {
            $slotStart = clone $current;
            $slotEnd = (clone $slotStart)->addMinutes($totalDuration);

            // Ensure booking fits within hours
            if ($slotEnd > $franchiseEndLocal) {
                break;
            }

            $currentSlotUtcStartStr = (clone $slotStart)->setTimezone('UTC')->toDateTimeString();
            $currentSlotUtcEndStr = (clone $slotEnd)->setTimezone('UTC')->toDateTimeString();

            $isAvailable = false;
            if ($selectedStaffId) {
                $hasOverlap = $existingBookings->filter(function ($b) use ($currentSlotUtcStartStr, $currentSlotUtcEndStr, $selectedStaffId) {
                    if ($b->staff_id != $selectedStaffId)
                        return false;
                    $bStart = $b->getRawOriginal('booking_date') . ' ' . $b->getRawOriginal('start_time');
                    $bEnd = $b->getRawOriginal('booking_date') . ' ' . $b->getRawOriginal('end_time');
                    return $currentSlotUtcStartStr < $bEnd && $currentSlotUtcEndStr > $bStart;
                })->count() > 0;

                if (!$hasOverlap) {
                    $isAvailable = true;
                }
            } else {
                $overlappingCount = $existingBookings->filter(function ($b) use ($currentSlotUtcStartStr, $currentSlotUtcEndStr) {
                    $bStart = $b->getRawOriginal('booking_date') . ' ' . $b->getRawOriginal('start_time');
                    $bEnd = $b->getRawOriginal('booking_date') . ' ' . $b->getRawOriginal('end_time');
                    return $currentSlotUtcStartStr < $bEnd && $currentSlotUtcEndStr > $bStart;
                })->count();

                if ($overlappingCount < $totalStaff) {
                    $isAvailable = true;
                }
            }

            if ($isAvailable) {
                $slots[] = [
                    //12 hour format
                    'start_time' => $slotStart->format('h:i A'),
                    'end_time' => $slotEnd->format('h:i A')
                ];
            }

            $current->addMinutes(5);
        }

        return response()->json([
            'success' => true,
            'date' => $date,
            'duration' => $totalDuration,
            'slots' => $slots
        ]);
    }
}
