<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookedService;
use App\Models\CategoryStandard;
use App\Models\WeeklySchedule;
use App\Models\Pet;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Booking\BookingCreated;
use App\Models\TermAcceptance;
use App\Services\FileUploadService;
use App\Models\CmsPage;
use App\Services\FcmService;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        //ongoing booking
        $bookings = $request->user()->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'assigned', 'ontheway', 'arrived', 'started'])
            ->with(['pet.category', 'pet.type', 'pet.size', 'pet.images', 'pet.documents', 'staff', 'bookedServices.service'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->success(BookingResource::collection($bookings), 'Bookings retrieved');
    }

    //order history
    public function orderHistory(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->whereIn('status', ['completed', 'cancelled'])
            ->with([
                'pet.category',
                'pet.type',
                'pet.size',
                'pet.images',
                'pet.documents',
                'staff',
                'bookedServices.service'
            ])
            ->latest('id')
            ->paginate(10);
        if ($bookings->isEmpty()) {
            return $this->success([], 'No bookings found', 200);
        }
        return $this->success(BookingResource::collection($bookings), 'Bookings retrieved');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'franchise_id' => 'required|exists:franchises,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'user_notes' => 'nullable|string',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required|exists:services,id',
            'timezone' => 'required|string',
            'address' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'out_of_area' => 'required|boolean',
            'cms_page_id' => 'required|exists:cms_pages,id',
            'accepted' => 'required|accepted',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        // dd($request->services);
        try {
            $user = $request->user();
            $pet = Pet::with('category')->find($request->pet_id);

            if (!$pet)
                return $this->error('Pet not found', 404);
            if ($pet->user_id !== $user->id)
                return $this->error('Unauthorized pet selection', 403);

            // Timezone & availability
            $tz = $request->timezone ?? session('timezone', 'UTC');
            $localDateTime = Carbon::parse($request->booking_date . ' ' . $request->start_time, $tz);
            $startUTC = $localDateTime->copy()->setTimezone('UTC');
            $endUTC = Carbon::parse($request->booking_date . ' ' . $request->end_time, $tz)->setTimezone('UTC');

            $utcStartTimeStr = $startUTC->format('H:i:s');
            $utcEndTimeStr = $endUTC->format('H:i:s');

            $dayOfWeek = strtolower(substr($localDateTime->dayName, 0, 3));
            $schedule = WeeklySchedule::where('franchise_id', $request->franchise_id)
                ->where('day_of_week', $dayOfWeek)
                ->first();

            if (!$schedule || !$schedule->is_open) {
                return response()->json(['success' => false, 'message' => 'Selected franchise is closed on this day'], 400);
            }

            if ($utcStartTimeStr < $schedule->opening_time_utc || $utcEndTimeStr > $schedule->closing_time_utc) {
                return response()->json(['success' => false, 'message' => 'Booking time must be within business hours'], 400);
            }

            // Overlap & Capacity
            $overlaps = Booking::whereNotIn('status', ['cancelled'])
                ->where('franchise_id', $request->franchise_id)
                ->where(function ($query) use ($startUTC, $endUTC) {
                    $query->whereRaw("CONCAT(booking_date, ' ', start_time) < ?", [$endUTC->toDateTimeString()])
                        ->whereRaw("CONCAT(booking_date, ' ', end_time) > ?", [$startUTC->toDateTimeString()]);
                })->get();

            $totalStaffCount = \App\Models\Staff::where('is_active', true)->count();
            $bookedStaffIds = $overlaps->whereNotNull('staff_id')->pluck('staff_id')->unique();
            $unassignedCount = $overlaps->whereNull('staff_id')->count();

            if (($bookedStaffIds->count() + $unassignedCount) >= $totalStaffCount) {
                return response()->json(['success' => false, 'message' => 'Selected time slot is no longer available'], 400);
            }

            DB::beginTransaction();

            // Create Booking (initial base_price = 0, total = 0)
            $bookingData = [
                'user_id' => $user->id,
                'pet_id' => $pet->id,
                'franchise_id' => $request->franchise_id,
                'booking_date' => $startUTC->format('Y-m-d'),
                'start_time' => $utcStartTimeStr,
                'end_time' => $utcEndTimeStr,
                'status' => 'pending',
                'base_price' => 0,
                'tax' => 0,
                'total_price' => 0,
                'out_of_area' => $request->out_of_area,
                'out_of_area_fee' => 0,
                'user_notes' => $request->user_notes,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];

            if ($request->hasFile('document')) {
                $bookingData['document'] = $request->file('document')->store('booking_documents', 'public');
            }

            $booking = Booking::create($bookingData);

            // c. Calculate Add-on Services Prices

            $finalValidatedServices = [];
            if ($request->has('services')) {
                foreach ($request->services as $s) {
                    $service = Service::with('sizes')->find($s['service_id']);
                    if (!$service)
                        continue;

                    $servicePrice = 0;
                    if ($service->pricing_type === 'fixable') {
                        $sz = $service->sizes()->whereNull('size_id')->first();
                        $servicePrice = $sz ? $sz->price : 0;
                    } else {
                        // Use pet's size for service pricing by default
                        $sz = $service->sizes()->where('size_id', $pet->size_id)->first();
                        $servicePrice = $sz ? $sz->price : 0;
                    }

                    if ($servicePrice > 0) {
                        $finalValidatedServices[] = [
                            'service_id' => $service->id,
                            'size_id' => ($service->pricing_type === 'fixable') ? null : $pet->size_id,
                            'price' => $servicePrice,
                            'minutes' => $service->minutes ?? 0,
                        ];
                    }
                }
            }
            foreach ($finalValidatedServices as $s) {
                BookedService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $s['service_id'],
                    'size_id' => $s['size_id'] ?? null,
                    'price' => $s['price'],
                    'minutes' => $s['minutes'],
                    'quantity' => 1,
                ]);
            }

            // ✅ Refresh Totals with out-of-area fee handling
            $booking->refreshTotals($pet->category_id);

            $signaturePath = FileUploadService::uploadSingle($request, 'image', 'term-acceptances');

            TermAcceptance::create([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'cms_page_id' => $request->cms_page_id,
                'image' => $signaturePath,
                'accepted' => true,
            ]);

            DB::commit();

            // Send Email Notification
            try {
                Mail::to($user->email)->send(new BookingCreated($booking));
            } catch (\Exception $e) {
                \Log::error('Failed to send booking creation email: ' . $e->getMessage());
            }

            app(FcmService::class)->send(
                $user->fcm_token,
                'Booking Created',
                'Your booking #' . $booking->id . ' has been created successfully.',
                [
                    'type' => 'booking',
                    'booking_id' => (string) $booking->id,
                ],
                $user->id
            );

            // Notify admins (bell / admin notifications page)
            try {
                \App\Models\Notification::notifyAdminsForBooking($booking);
            } catch (\Exception $e) {
                \Log::error('Failed to create admin booking notifications: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_date' => $localDateTime->format('Y-m-d'),
                    'start_time' => $localDateTime->format('H:i'),
                    'end_time' => Carbon::parse($request->end_time)->format('H:i'),
                    'price_breakdown' => [
                        'base_price' => (float) $booking->base_price,
                        'services_total' => (float) ($booking->total_price - $booking->base_price - $booking->tax - $booking->out_of_area_fee),
                        'tax' => (float) $booking->tax,
                        'out_of_area' => (bool) $booking->out_of_area,
                        'out_of_area_fee' => (float) $booking->out_of_area_fee,
                        'total' => (float) $booking->total_price,
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->error('Booking not found', 404);
        }
        if ($booking->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        return $this->success(new BookingResource($booking->load(['pet.category', 'pet.type', 'pet.size', 'staff', 'bookedServices.service', 'review', 'termAcceptance.cmsPage'])), 'Booking details retrieved');
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->error('Booking not found', 404);
        }
        if ($booking->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        if ($booking->status !== 'pending') {
            return $this->error('Only pending bookings can be updated', 400);
        }

        $validator = Validator::make($request->all(), [
            'booking_date' => 'sometimes|date|after_or_equal:today',
            'booking_time' => 'sometimes',
            'user_notes' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        try {
            DB::beginTransaction();

            if ($request->has('booking_date') || $request->has('booking_time')) {
                $tz = session('timezone', 'UTC');
                $date = $request->booking_date ?? $booking->getRawOriginal('booking_date');
                $time = $request->booking_time ?? $booking->getRawOriginal('booking_time');

                $localDateTime = Carbon::parse($date . ' ' . $time, $tz);
                $utcDateTime = $localDateTime->setTimezone('UTC');

                $booking->booking_date = $utcDateTime->format('Y-m-d');
                $booking->booking_time = $utcDateTime->format('H:i:s');
            }

            if ($request->has('user_notes')) {
                $booking->user_notes = $request->user_notes;
            }

            if ($request->has('services')) {
                $booking->bookedServices()->delete();
                foreach ($request->services as $s) {
                    BookedService::create([
                        'booking_id' => $booking->id,
                        'service_id' => $s['service_id'],
                        'quantity' => $s['quantity'] ?? 1,
                    ]);
                }
            }

            $booking->save();
            $booking->refreshTotals();

            DB::commit();

            return $this->success(new BookingResource($booking->load(['pet', 'bookedServices.service'])), 'Booking updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->error('Booking not found', 404);
        }
        if ($booking->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        if ($booking->status === 'completed') {
            return $this->error('Completed bookings cannot be deleted', 400);
        }

        $booking->delete(); // Soft delete
        return $this->success([], 'Booking deleted successfully');
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->error('Booking not found', 404);
        }
        if ($booking->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        if ($booking->status === 'pending') {
            $booking->status = 'cancelled';
            $booking->save();
            return $this->success([], 'Booking cancelled successfully');
        }
        return $this->error('Booking cannot be cancelled', 400);
    }

    public function getESignatureAgreement(Request $request)
    {
        $cmsPage = CmsPage::where('page_key', 'e_signature')->first();
        if (!$cmsPage) {
            return $this->error('E-signature agreement not found', 404);
        }
        return $this->success($cmsPage, 'E-signature agreement retrieved');
    }
}
