<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Franchise;
use App\Models\Staff;
use App\Models\WeeklySchedule;
use App\Models\Service;
use App\Models\BookedService;
use App\Models\Pet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Booking\StaffAssigned;
use App\Mail\Booking\BookingStatusUpdated;
use App\Services\FcmService;

class BookingController extends Controller
{


    public function index()
    {
        $admin = auth()->guard('admin')->user();
        $isSuperAdmin = ($admin && $admin->role && $admin->role->slug === 'super_admin');
        if ($isSuperAdmin) {
            $bookings = Booking::withTrashed()->with(['user', 'pet', 'staff', 'bookedServices.service'])->orderBy('id', 'desc')
                ->withCount([
                    'chats' => function ($query) {
                        $query->where('read', 0);
                    }
                ])
                ->paginate(10);
        } else {
            $bookings = Booking::with(['user', 'pet', 'staff', 'bookedServices.service'])->where('franchise_id', $admin->franchise_id)->orderBy('id', 'desc')
                ->withCount([
                    'chats' => function ($query) {
                        $query->where('read', 0);
                    }
                ])
                ->paginate(10);
        }

        // Ensure consistency
        // $bookings->each->refreshTotals();

        return view('admin.bookings.index', compact('bookings'));
    }
    public function create(Request $request)
    {
        $selectedDate = $request->get('date', date('Y-m-d'));
        $selectedTime = $request->get('time', '');

        // Pre-fetch slots if date is provided in URL to avoid redundant AJAX
        $slots = [];
        if ($request->has(['date', 'franchise_id'])) {
            $localDate = Carbon::parse($selectedDate);
            $dayOfWeek = strtolower(substr($localDate->dayName, 0, 3));
            $schedule = WeeklySchedule::where('franchise_id', $request->franchise_id)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_open', true)
                ->first();

            if ($schedule) {
                $opening = $schedule->getRawOriginal('opening_time') ?? '00:00:00';
                $closing = $schedule->getRawOriginal('closing_time') ?? '23:59:59';

                $tz = session('timezone', 'UTC');
                $start = Carbon::parse($selectedDate . ' ' . $opening, 'UTC')->timezone($tz);
                $end = Carbon::parse($selectedDate . ' ' . $closing, 'UTC')->timezone($tz);

                if ($start > $end) {
                    $end->addDay();
                }

                while ($start < $end) {
                    $slots[] = $start->format('H:i');
                    $start->addHour();
                }
            }
        }

        $users = \App\Models\User::orderBy('name')->get();
        $pets = \App\Models\Pet::with('category', 'size')->get();
        $staff = Staff::where('is_active', true)->get();
        $franchises = Franchise::where('is_active', true)->orderBy('name')->get();
        $categories = \App\Models\Category::with('standards')->where('is_active', true)->orderBy('name')->get();
        $tax = \App\Models\Tax::first();

        $staffCount = $staff->count();
        $bookingCount = Booking::where('status', '!=', 'completed')->whereDate('booking_date', $selectedDate)->count();
        $availableBooking = ($staffCount - $bookingCount);

        return view('admin.bookings.create', compact('users', 'pets', 'staff', 'franchises', 'categories', 'tax', 'availableBooking', 'selectedDate', 'selectedTime', 'slots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'franchise_id' => 'required|exists:franchises,id',
            'base_category_id' => 'required|exists:categories,id',
            'staff_id' => 'nullable|exists:staff,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.size_id' => 'nullable|exists:sizes,id',
            'user_notes' => 'nullable|string',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Prepare Timezone and Date Objects
        $tz = session('timezone', 'UTC');
        $localDateTime = Carbon::parse($request->booking_date . ' ' . $request->start_time, $tz);
        $startUTC = $localDateTime->copy()->setTimezone('UTC');
        $endUTC = Carbon::parse($request->booking_date . ' ' . $request->end_time, $tz)->setTimezone('UTC');

        $utcStartDateTime = $startUTC;
        $utcEndDateTime = $endUTC;

        $utcStartTime = $utcStartDateTime->format('H:i:s');
        $utcEndTime = $utcEndDateTime->format('H:i:s');

        // Check Weekly Schedule & Business Hours
        $dayOfWeek = strtolower(substr($localDateTime->dayName, 0, 3));
        $weeklySchedule = WeeklySchedule::where('franchise_id', $request->franchise_id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$weeklySchedule || !$weeklySchedule->is_open) {
            return redirect()->back()->withInput()->with('error', 'The shop is closed on ' . $localDateTime->dayName);
        }

        $bizStart = $weeklySchedule->opening_time_utc;
        $bizEnd = $weeklySchedule->closing_time_utc;
        if ($utcStartTime < $bizStart || $utcEndTime > $bizEnd) {
            return redirect()->back()->withInput()->with('error', 'Booking time exceeds business hours.');
        }

        // Check Overlaps
        $overlaps = Booking::whereNotIn('status', ['cancelled'])
            ->where('franchise_id', $request->franchise_id)
            ->where(function ($query) use ($utcStartDateTime, $utcEndDateTime) {
                $query->whereRaw("CONCAT(booking_date, ' ', start_time) < ?", [$utcEndDateTime->toDateTimeString()])
                    ->whereRaw("CONCAT(booking_date, ' ', end_time) > ?", [$utcStartDateTime->toDateTimeString()]);
            })->get();

        if ($request->staff_id) {
            if ($overlaps->where('staff_id', $request->staff_id)->count() > 0) {
                return redirect()->back()->withInput()->with('error', 'The selected staff member has an overlapping booking.');
            }
        } else {
            $totalStaffCount = Staff::where('is_active', true)->count();
            $bookedStaffIds = $overlaps->whereNotNull('staff_id')->pluck('staff_id')->unique();
            $unassignedCount = $overlaps->whereNull('staff_id')->count();

            if (($bookedStaffIds->count() + $unassignedCount) >= $totalStaffCount) {
                return redirect()->back()->withInput()->with('error', 'No staff available for this duration.');
            }
        }

        // Create Booking
        $bookingData = [
            'user_id' => $request->user_id,
            'pet_id' => $request->pet_id,
            'franchise_id' => $request->franchise_id,
            'staff_id' => $request->staff_id,
            'booking_date' => $utcStartDateTime->format('Y-m-d'),
            'start_time' => $utcStartTime,
            'end_time' => $utcEndTime,
            'status' => $request->staff_id ? 'assigned' : 'pending',
            'base_price' => 0, // refreshTotals() will calculate
            'tax' => 0,
            'total_price' => 0,
            'out_of_area' => $request->out_of_area ?? 0,
            'user_notes' => $request->user_notes,
        ];

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('booking_documents', $filename, 'public');
            $bookingData['document'] = $path;
        }

        $booking = Booking::create($bookingData);
        $pet = Pet::find($request->pet_id);
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

        // Calculate Totals using refreshTotals()
        $booking->refreshTotals($request->base_category_id);

        $booking->loadMissing('user');
        app(FcmService::class)->send(
            $booking->user?->fcm_token,
            'Booking Created',
            'Your booking #' . $booking->id . ' has been created successfully.',
            [
                'type' => 'booking',
                'booking_id' => (string) $booking->id,
            ],
            $booking->user_id
        );

        return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully.');
    }


    public function show(Booking $booking)
    {
        $booking->load([
            'user',
            'user.bookings' => function ($query) {
                $query->with(['bookedServices.service', 'staff'])->orderBy('booking_date', 'desc');
            },
            'pet',
            'staff',
            'bookedServices.size',
            'bookedServices.service.category',
            'logs',
            'review'
        ]);
        $staff = Staff::where('is_active', true)->get();
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.bookings.show', compact('booking', 'staff', 'categories'));
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['user', 'pet', 'staff', 'bookedServices.size', 'bookedServices.service']);

        // Ensure consistency
        // $booking->refreshTotals();

        return view('admin.bookings.invoice', compact('booking'));
    }

    public function updateBasePrice(Request $request, Booking $booking)
    {
        $request->validate([
            'base_price' => 'required|numeric|min:0',
        ]);

        $booking->load(['bookedServices.service', 'pet.category']);
        $booking->base_price = round((float) $request->base_price, 2);
        $booking->refreshTotals(null, true, true);

        return redirect()
            ->route('admin.bookings.invoice', $booking->id)
            ->with('success', 'Base charges updated and booking total recalculated.');
    }

    public function updateOutOfAreaFee(Request $request, Booking $booking)
    {
        $request->validate([
            'out_of_area_fee' => 'required|numeric|min:0',
        ]);

        if (!$booking->out_of_area && (float) $booking->out_of_area_fee <= 0) {
            return redirect()
                ->route('admin.bookings.invoice', $booking->id)
                ->with('error', 'This booking has no out of area fee.');
        }

        $booking->load(['bookedServices.service', 'pet.category']);
        $booking->out_of_area = 1;
        $booking->out_of_area_fee = round((float) $request->out_of_area_fee, 2);
        $booking->refreshTotals(null, true, true);

        return redirect()
            ->route('admin.bookings.invoice', $booking->id)
            ->with('success', 'Out of area fee updated and booking total recalculated.');
    }

    public function edit(Booking $booking)
    {
        $users = \App\Models\User::orderBy('name')->get();
        $pets = \App\Models\Pet::with('category', 'size')->get();
        $staff = Staff::where('is_active', true)->get();
        $franchises = Franchise::where('is_active', true)->orderBy('name')->get();
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.bookings.edit', compact('booking', 'users', 'pets', 'staff', 'franchises', 'categories'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'franchise_id' => 'required|exists:franchises,id',
            'staff_id' => 'nullable|exists:staff,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'status' => 'required|in:pending,assigned,ontheway,arrived,cancelled,started,completed',
            'user_notes' => 'nullable|string',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $oldStatus = $booking->status;

        // 1. Prepare Timezone and Date Objects
        $tz = session('timezone', 'UTC');
        $localDateTime = Carbon::parse($request->booking_date . ' ' . $request->start_time, $tz);
        $utcStartDateTime = $localDateTime->copy()->setTimezone('UTC');

        // 2. Calculate current total duration if services weren't submitted (edit form usually has services though)
        // For simplicity in update, we'll use refreshTotals logic or just re-calculate from DB if needed.
        // But the booking already has services.
        $totalMinutes = 0;
        $pet = \App\Models\Pet::findOrFail($request->pet_id);
        if ($pet->category) {
            $totalMinutes += (int) $pet->category->minutes;
        }
        foreach ($booking->bookedServices as $bs) {
            $totalMinutes += (int) ($bs->minutes * $bs->quantity);
        }

        $utcEndDateTime = (clone $utcStartDateTime)->addMinutes($totalMinutes);
        $utcStartTime = $utcStartDateTime->format('H:i:s');
        $utcEndTime = $utcEndDateTime->format('H:i:s');

        // 3. Overlap Check (Exclude current booking)
        $overlaps = Booking::where('id', '!=', $booking->id)
            ->where('franchise_id', $request->franchise_id)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($query) use ($utcStartDateTime, $utcEndDateTime) {
                $query->whereRaw("CONCAT(booking_date, ' ', start_time) < ?", [$utcEndDateTime->toDateTimeString()])
                    ->whereRaw("CONCAT(booking_date, ' ', end_time) > ?", [$utcStartDateTime->toDateTimeString()]);
            })->get();

        if ($request->staff_id) {
            if ($overlaps->where('staff_id', $request->staff_id)->count() > 0) {
                return redirect()->back()->withInput()->with('error', 'The selected staff member has an overlapping booking.');
            }
        } else {
            $totalStaffCount = Staff::where('is_active', true)->count();
            $bookedStaffIds = $overlaps->whereNotNull('staff_id')->pluck('staff_id')->unique();
            $unassignedCount = $overlaps->whereNull('staff_id')->count();

            if (($bookedStaffIds->count() + $unassignedCount) >= $totalStaffCount) {
                return redirect()->back()->withInput()->with('error', 'No staff available for this duration.');
            }
        }

        $data = $request->only(['user_id', 'pet_id', 'franchise_id', 'staff_id', 'status', 'user_notes']);
        if ($request->staff_id) {
            $data['status'] = 'assigned';
        } else {
            $data['status'] = 'pending';
        }
        $data['booking_date'] = $utcStartDateTime->format('Y-m-d');
        $data['start_time'] = $utcStartTime;
        $data['end_time'] = $utcEndTime;

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('booking_documents', $filename, 'public');
            $data['document'] = $path;
        }

        if ($booking->pet_id != $request->pet_id) {
            $booking->base_price = null;
        }

        $booking->update($data);
        $booking->refreshTotals($pet->base_category_id);

        if ($oldStatus != $booking->status) {
            $this->logStatusChange($booking, $booking->status);

            // Send Email Notification
            try {
                if ($booking->status === 'assigned' && $booking->staff_id) {
                    Mail::to($booking->user->email)->send(new StaffAssigned($booking));
                } else {
                    Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send booking update email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function assignStaff(Request $request, Booking $booking)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);

        $booking->staff_id = $request->staff_id;
        $booking->status = 'assigned'; // Auto update status to assigned? Or let user do it? Logic says "Change booking status only after staff is assigned".
        // Usually assigning staff implies 'assigned' status.
        // But user might want to assign staff first, then move to assigned.
        // Let's just assign staff.
        // If status was pending, maybe move to assigned?
        if ($booking->status == 'pending') {
            $booking->status = 'assigned';
            $this->logStatusChange($booking, 'assigned');
        }

        $booking->save();

        // Send Email Notification
        try {
            Mail::to($booking->user->email)->send(new StaffAssigned($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send staff assignment email: ' . $e->getMessage());
        }

        $booking->loadMissing('user');
        app(FcmService::class)->send(
            $booking->user?->fcm_token,
            'Crew Assigned',
            'A crew member has been assigned to your booking #' . $booking->id . '.',
            [
                'type' => 'booking',
                'booking_id' => (string) $booking->id,
            ],
            $booking->user_id
        );

        return redirect()->back()->with('success', 'Staff assigned successfully.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,ontheway,arrived,cancelled,started,completed',
            'payment_method' => 'nullable|required_if:status,completed|in:card,cheque,cash',
            'cancel_note' => 'nullable|required_if:status,cancelled',
        ]);

        if (in_array($request->status, ['assigned', 'ontheway', 'arrived', 'started', 'completed']) && !$booking->staff_id) {
            return redirect()->back()->with('error', 'You cannot update status to ' . $request->status . ' without assigning a staff member first.');
        }

        $oldStatus = $booking->status;
        $booking->status = $request->status;

        if ($request->status === 'completed') {
            $booking->refreshTotals();
            $booking->update([
                'received_amount' => $booking->total_price,
                'payment_method' => $request->payment_method,
                'received_type' => 'admin',
                'received_type_id' => auth()->guard('admin')->id(),
            ]);
        }

        if ($request->status === 'cancelled') {
            $booking->update([
                'cancel_note' => $request->cancel_note,
                'cancel_type' => 'admin',
                'cancel_id' => auth()->guard('admin')->id(),
            ]);
        }

        $booking->save();

        $this->logStatusChange($booking, $request->status);

        // Send Email Notification
        try {
            Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking status update email: ' . $e->getMessage());
        }

        $statusLabel = ucfirst(str_replace('_', ' ', $request->status));
        $booking->loadMissing('user');
        app(FcmService::class)->send(
            $booking->user?->fcm_token,
            'Booking Status Updated',
            'Your booking #' . $booking->id . ' status has been updated to ' . $statusLabel . '.',
            [
                'type' => 'booking',
                'booking_id' => (string) $booking->id,
            ],
            $booking->user_id
        );

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    protected function logStatusChange($booking, $status)
    {
        $logData = [
            'booking_id' => $booking->id,
            'updated_by_type' => 'Admin',
            'updated_by_id' => auth()->guard('admin')->id(),
        ];

        $statusColumns = [
            'assigned' => 'assigned_at',
            'ontheway' => 'ontheway_at',
            'arrived' => 'arrived_at',
            'cancelled' => 'cancelled_at',
            'started' => 'started_at',
            'completed' => 'completed_at',
        ];

        if (isset($statusColumns[$status])) {
            $col = $statusColumns[$status];
            $logData[$col] = now();
        }

        \App\Models\BookingLog::create($logData);
    }

    public function updateDate(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:' . date('Y-m-d'),
            'start_time' => 'required',
        ], [
            'booking_date.after_or_equal' => 'You cannot move a booking to a past date.'
        ]);

        // 1. Prepare Timezone and Date Objects
        $tz = session('timezone', 'UTC');
        $localDateTime = Carbon::parse($request->booking_date . ' ' . $request->start_time, $tz);
        $utcStartDateTime = $localDateTime->copy()->setTimezone('UTC');

        // 2. Calculate current total duration
        $totalMinutes = 0;
        if ($booking->pet && $booking->pet->category) {
            $totalMinutes += (int) $booking->pet->category->minutes;
        }
        foreach ($booking->bookedServices as $bs) {
            $totalMinutes += (int) ($bs->minutes * $bs->quantity);
        }

        $utcEndDateTime = (clone $utcStartDateTime)->addMinutes($totalMinutes);
        $utcStartTime = $utcStartDateTime->format('H:i:s');
        $utcEndTime = $utcEndDateTime->format('H:i:s');

        // Schedule check
        $dayOfWeek = strtolower(substr($localDateTime->dayName, 0, 3));
        $weeklySchedule = \App\Models\WeeklySchedule::where('franchise_id', $booking->franchise_id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$weeklySchedule || !$weeklySchedule->is_open) {
            return response()->json([
                'success' => false,
                'message' => 'Selected franchise is closed on this day'
            ]);
        }

        $bizStart = $weeklySchedule->opening_time_utc;
        $bizEnd = $weeklySchedule->closing_time_utc;
        if ($utcStartTime < $bizStart || $utcEndTime > $bizEnd) {
            return response()->json([
                'success' => false,
                'message' => 'Booking time exceeds business hours'
            ]);
        }

        // 3. Overlap Check (Exclude current booking)
        $overlaps = Booking::where('id', '!=', $booking->id)
            ->where('franchise_id', $booking->franchise_id)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($query) use ($utcStartDateTime, $utcEndDateTime) {
                // Using raw datetime comparison across boundaries just like booking creation
                $query->whereRaw("CONCAT(booking_date, ' ', start_time) < ?", [$utcEndDateTime->toDateTimeString()])
                    ->whereRaw("CONCAT(booking_date, ' ', end_time) > ?", [$utcStartDateTime->toDateTimeString()]);
            })->get();

        if ($booking->staff_id) {
            if ($overlaps->where('staff_id', $booking->staff_id)->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected slot is not available for this staff'
                ]);
            }
        } else {
            $totalStaffCount = Staff::where('is_active', true)->count();
            $bookedStaffIds = $overlaps->whereNotNull('staff_id')->pluck('staff_id')->unique();
            $unassignedCount = $overlaps->whereNull('staff_id')->count();

            if (($bookedStaffIds->count() + $unassignedCount) >= $totalStaffCount) {
                return response()->json([
                    'success' => false,
                    'message' => 'No staff available for this duration on the selected time.'
                ]);
            }
        }

        $booking->update([
            'booking_date' => $utcStartDateTime->format('Y-m-d'),
            'start_time' => $utcStartTime,
            'end_time' => $utcEndTime,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking date updated successfully.'
        ]);
    }

    public function schedule()
    {
        $admin = auth()->guard('admin')->user();
        $isSuperAdmin = ($admin && $admin->role && $admin->role->slug === 'super_admin');
        if ($isSuperAdmin) {
            $bookings = Booking::with(['user', 'staff', 'pet', 'bookedServices.service'])->orderBy('created_at', 'desc')->get();
        } else {
            $bookings = Booking::with(['user', 'staff', 'pet', 'bookedServices.service'])->where('franchise_id', $admin->franchise_id)->orderBy('created_at', 'desc')->get();
        }

        $tz = session('timezone', 'UTC');
        $bookings->transform(function ($booking) use ($tz) {
            $start = \Carbon\Carbon::parse($booking->getRawOriginal('booking_date') . ' ' . $booking->getRawOriginal('start_time'), 'UTC')->timezone($tz);
            $end = \Carbon\Carbon::parse($booking->getRawOriginal('booking_date') . ' ' . $booking->getRawOriginal('end_time'), 'UTC')->timezone($tz);

            $booking->formatted_start = $start->toDateTimeString();
            $booking->formatted_end = $end->toDateTimeString();

            return $booking;
        });

        $staffs = Staff::where('is_active', 1)->orderBy('created_at', 'desc')->get();
        return view('admin.bookings.schedule', compact('bookings', 'staffs'));
    }

    public function scheduleData(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $admin = auth()->guard('admin')->user();
        $isSuperAdmin = ($admin && $admin->role && $admin->role->slug === 'super_admin');
        if ($isSuperAdmin) {
            $query = Booking::with(['user', 'staff', 'pet', 'bookedServices.service']);
        } else {
            $query = Booking::with(['user', 'staff', 'pet', 'bookedServices.service'])->where('franchise_id', $admin->franchise_id);
        }

        if ($start && $end) {
            $query->whereBetween('booking_date', [
                date('Y-m-d', strtotime($start)),
                date('Y-m-d', strtotime($end))
            ]);
        } else {
            $year = $request->get('year', date('Y'));
            $month = $request->get('month', date('m'));
            $query->whereYear('booking_date', $year)
                ->whereMonth('booking_date', $month);
        }

        $bookings = $query->get();

        $events = $bookings->map(function ($booking) {
            $tz = session('timezone', 'UTC');
            $start = \Carbon\Carbon::parse($booking->getRawOriginal('booking_date') . ' ' . $booking->getRawOriginal('start_time'), 'UTC')->timezone($tz);
            $end = \Carbon\Carbon::parse($booking->getRawOriginal('booking_date') . ' ' . $booking->getRawOriginal('end_time'), 'UTC')->timezone($tz);

            $startDateTime = $start->toDateTimeString();
            $endDateTime = $end->toDateTimeString();

            // Color selection based on status
            $colors = [
                'pending' => '#f59e0b',
                'assigned' => '#3b82f6',
                'started' => '#8b5cf6',
                'completed' => '#10b981',
                'cancelled' => '#ef4444',
                'ontheway' => '#06b6d4',
                'arrived' => '#6366f1',
            ];

            return [
                'id' => $booking->id,
                'title' => ($booking->pet->name ?? 'N/A') . ' - ' . ($booking->user->name ?? 'N/A'),
                'start' => $startDateTime,
                'end' => $endDateTime,
                'className' => 'fc-event-status-' . $booking->status,
                'backgroundColor' => $colors[$booking->status] ?? '#3b82f6',
                'borderColor' => $colors[$booking->status] ?? '#6c757d',
                // FullCalendar supports 'url' parameter to automatically make events clickable
                'url' => route('admin.bookings.show', $booking->id),
                'extendedProps' => [
                    'pet' => $booking->pet->name ?? 'N/A',
                    'services' => $booking->bookedServices->map(function ($bs) {
                        return $bs->service->name ?? 'N/A';
                    })->implode(', '),
                    'status' => $booking->status,
                    'duration' => (Carbon::parse($booking->end_time)->diffInMinutes(Carbon::parse($booking->start_time)))
                ]
            ];
        });

        return response()->json($events);
    }

    public function destroy(Booking $booking)
    {
        // 1. Check if booking is completed
        if ($booking->status === 'completed') {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Completed bookings cannot be deleted.']);
            }
            return redirect()->back()->with('error', 'Completed bookings cannot be deleted.');
        }

        // 2. Check if booking is in the past
        $bookingDate = Carbon::parse($booking->booking_date);
        if ($bookingDate->isPast() && !$bookingDate->isToday()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Past bookings cannot be deleted.']);
            }
            return redirect()->back()->with('error', 'Past bookings cannot be deleted.');
        }

        $booking->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully.'
            ]);
        }
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }

    public function addService(Request $request, Booking $booking)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'size_id' => 'nullable|exists:sizes,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        $service = Service::with('sizes')->findOrFail($request->service_id);
        $pet = $booking->pet;

        $servicePrice = 0;
        $sizeId = null;

        if ($service->pricing_type === 'fixable') {
            $sz = $service->sizes()->whereNull('size_id')->first();
            $servicePrice = $sz ? $sz->price : 0;
            $sizeId = null;
        } else {
            $sizeId = $request->size_id ?: optional($pet)->size_id;
            $sz = $service->sizes()->where('size_id', $sizeId)->first();
            $servicePrice = $sz ? $sz->price : 0;
        }

        if ($request->filled('price')) {
            $servicePrice = (float) $request->price;
        }

        BookedService::create([
            'booking_id' => $booking->id,
            'service_id' => $service->id,
            'size_id' => $sizeId,
            'price' => $servicePrice,
            'minutes' => $service->minutes ?? 0,
        ]);

        // Recalculate Totals
        $booking->load(['bookedServices.service', 'pet']);
        $booking->refreshTotals(null, true, true);

        return redirect()->back()->with('success', 'Service added and totals updated successfully.');
    }

    public function updateBookedService(Request $request, BookedService $bookedService)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'size_id' => 'nullable|exists:sizes,id',
        ]);

        $service = Service::with('sizes')->findOrFail($request->service_id);
        $servicePrice = 0;
        $sizeId = null;

        if ($service->pricing_type === 'fixable') {
            $sz = $service->sizes()->whereNull('size_id')->first();
            $servicePrice = $sz ? $sz->price : 0;
            $sizeId = null;
        } else {
            $sizeId = $request->size_id ?: optional($bookedService->booking->pet)->size_id;
            $sz = $service->sizes()->where('size_id', $sizeId)->first();
            $servicePrice = $sz ? $sz->price : 0;
        }

        $bookedService->update([
            'service_id' => $service->id,
            'size_id' => $sizeId,
            'price' => $servicePrice,
            'quantity' => 1,
            'minutes' => $service->minutes ?? 0,
        ]);

        // Reload relationships and recalculate
        $booking = $bookedService->booking;
        $booking->unsetRelation('bookedServices');
        $booking->load(['pet', 'bookedServices.service']);
        $booking->refreshTotals();

        return redirect()->back()->with('success', 'Service updated and totals recalculated.');
    }

    public function deleteBookedService(\App\Models\BookedService $bookedService)
    {
        $booking = $bookedService->booking;
        $bookedService->delete();

        // Recalculate Totals
        $booking->unsetRelation('bookedServices');
        $booking->load(['pet', 'bookedServices.service']);
        $booking->refreshTotals();

        return redirect()->back()->with('success', 'Service removed and totals recalculated.');
    }

    public function uploadDocument(Request $request, Booking $booking)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('booking_documents', $filename, 'public');
            $booking->document = $path;
            $booking->save();
        }

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function getTimeSlots(Request $request)
    {
        $date = $request->get('date');
        $franchiseId = $request->get('franchise_id');
        $totalDuration = (int) $request->get('total_duration', 0);
        $selectedStaffId = $request->get('staff_id');

        if (!$date || !$franchiseId || $totalDuration <= 0) {
            return response()->json(['success' => false, 'message' => 'Date, Franchise and Services are required.']);
        }

        $localDate = Carbon::parse($date);
        $dayOfWeek = strtolower(substr($localDate->dayName, 0, 3));

        $schedule = WeeklySchedule::where('franchise_id', $franchiseId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule || !$schedule->is_open) {
            return response()->json(['success' => false, 'message' => 'Selected franchise is closed on this day']);
        }

        $opening = $schedule->getRawOriginal('opening_time') ?? '00:00:00';
        $closing = $schedule->getRawOriginal('closing_time') ?? '23:59:59';
        $tz = session('timezone', 'UTC');

        $franchiseStartLocal = Carbon::parse($date . ' ' . $opening, 'UTC')->timezone($tz);
        $franchiseEndLocal = Carbon::parse($date . ' ' . $closing, 'UTC')->timezone($tz);
        if ($franchiseStartLocal > $franchiseEndLocal) {
            $franchiseEndLocal->addDay();
        }

        // Get absolute UTC range for the business day to fetch all relevant bookings
        $utcRangeStart = (clone $franchiseStartLocal)->setTimezone('UTC');
        $utcRangeEnd = (clone $franchiseEndLocal)->setTimezone('UTC');

        // Fetch existing bookings that overlap with this business day
        // Standard overlap: (B.start < Range.end) AND (B.end > Range.start)
        $existingBookings = Booking::whereNotIn('status', ['cancelled'])
            ->where('franchise_id', $franchiseId)
            ->where(function ($q) use ($utcRangeStart, $utcRangeEnd) {
                // Note: we can query the actual date range or use the start_time/end_time Logic.
                // A safer way since date crosses boundaries:
                $q->where(function ($sq) use ($utcRangeStart, $utcRangeEnd) {
                    $sq->whereRaw("CONCAT(booking_date, ' ', start_time) < ?", [$utcRangeEnd->toDateTimeString()])
                        ->whereRaw("CONCAT(booking_date, ' ', end_time) > ?", [$utcRangeStart->toDateTimeString()]);
                });
            })
            ->get();

        $totalStaff = Staff::where('is_active', 1)->count();
        $slots = [];
        $current = clone $franchiseStartLocal;

        // Interval set to 5 minutes as requested
        while ($current < $franchiseEndLocal) {
            $slotStart = clone $current;
            $slotEnd = (clone $slotStart)->addMinutes($totalDuration);

            // Ensure booking fits within franchise hours
            if ($slotEnd > $franchiseEndLocal) {
                break;
            }

            $currentSlotUtcStartStr = (clone $slotStart)->setTimezone('UTC')->toDateTimeString();
            $currentSlotUtcEndStr = (clone $slotEnd)->setTimezone('UTC')->toDateTimeString();

            $isAvailable = false;
            if ($selectedStaffId) {
                // Check if THE SELECTED staff member has any overlapping booking
                $hasOverlap = $existingBookings->filter(function ($b) use ($currentSlotUtcStartStr, $currentSlotUtcEndStr, $selectedStaffId) {
                    if ($b->staff_id != $selectedStaffId)
                        return false;
                    $bStart = $b->getRawOriginal('booking_date') . ' ' . $b->getRawOriginal('start_time');
                    $bEnd = $b->getRawOriginal('booking_date') . ' ' . $b->getRawOriginal('end_time');
                    // Period overlap: (Start1 < End2) AND (End1 > Start2)
                    return $currentSlotUtcStartStr < $bEnd && $currentSlotUtcEndStr > $bStart;
                })->count() > 0;

                if (!$hasOverlap) {
                    $isAvailable = true;
                }
            } else {
                // Check general capacity (number of concurrent bookings reaching staff count)
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
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i')
                ];
            }

            $current->addMinutes(5);
        }

        return response()->json([
            'success' => true,
            'slots' => $slots,
            'date' => $date,
            'duration' => $totalDuration
        ]);
    }
}
