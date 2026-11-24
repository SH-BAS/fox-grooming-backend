<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Franchise;
use App\Models\Pet;
use App\Models\Service;
use App\Models\Staff;
use App\Models\WeeklySchedule;
use App\Models\BookedService;
use App\Models\Tax;
use App\Models\CmsPage;
use App\Models\TermAcceptance;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ongoingBookings = $user->bookings()
            ->with(['pet', 'franchise', 'staff', 'bookedServices.service'])
            ->whereIn('status', ['pending', 'assigned', 'ontheway', 'arrived', 'started'])
            ->orderBy('booking_date', 'asc')
            ->get();

        $pastBookings = $user->bookings()
            ->with(['pet', 'franchise', 'staff', 'bookedServices.service'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('profile.bookings.index', compact('ongoingBookings', 'pastBookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['pet', 'franchise', 'staff', 'bookedServices.service', 'review', 'termAcceptance.cmsPage']);
        return view('profile.bookings.show', compact('booking'));
    }

    public function selectFranchise(Request $request)
    {
        $franchises = collect();
        $out_of_area = false;
        $out_of_area_fee = 0;
        $message = null;

        if ($request->has('latitude') && $request->has('longitude')) {
            $result = Franchise::getByLocation($request->latitude, $request->longitude);
            if (!$result['franchises']->isEmpty()) {
                $franchises = $result['franchises'];
                $out_of_area = $result['out_of_area'] ?? false;
                $out_of_area_fee = $result['out_of_area_fee'] ?? 0;
            }
            $message = $result['message'] ?? null;
        }

        return view('booking.franchise', compact('franchises', 'out_of_area', 'out_of_area_fee', 'message'));
    }

    public function searchFranchises(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $result = Franchise::getByLocation($request->latitude, $request->longitude);

        // We return the view fragment or JSON. 
        // Returning JSON is better for dynamic updates.
        return response()->json([
            'success' => true,
            'html' => view('booking.partials.franchise_list', [
                'franchises' => $result['franchises'],
                'out_of_area' => $result['out_of_area'] ?? false,
                'out_of_area_fee' => $result['out_of_area_fee'] ?? 0,
                'message' => $result['message'] ?? null
            ])->render(),
            'data' => $result
        ]);
    }

    public function create(Request $request, Franchise $franchise)
    {
        $user = Auth::user();
        $pets = $user->pets()->with('category', 'size')->get();
        $tax = Tax::first();
        $outOfArea = $request->get('out_of_area', 0);
        $categoryStandards = \App\Models\CategoryStandard::all();
        $eSignaturePage = CmsPage::where('page_key', 'e_signature')->first();

        return view('booking.create', compact('franchise', 'pets', 'tax', 'outOfArea', 'categoryStandards', 'eSignaturePage'));
    }

    public function getServices(Request $request)
    {
        $categoryId = $request->get('category_id');
        $services = Service::with('sizes')->where('category_id', $categoryId)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'franchise_id' => 'required|exists:franchises,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'terms_accepted' => 'accepted',
            'cms_page_id' => 'required|exists:cms_pages,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $pet = Pet::findOrFail($request->pet_id);
        if ($pet->user_id !== Auth::id()) {
            abort(403);
        }

        // Calculate total duration like in AdminController
        $totalMinutes = 0;
        if ($pet->category) {
            $totalMinutes += (int) $pet->category->minutes;
        }

        $selectedServices = Service::whereIn('id', $request->services)->get();
        foreach ($selectedServices as $service) {
            $totalMinutes += (int) $service->minutes;
        }

        // Prepare Timezone and Date Objects
        $tz = session('timezone', 'UTC');
        $localDateTime = Carbon::parse($request->booking_date . ' ' . $request->start_time, $tz);
        $startUTC = $localDateTime->copy()->setTimezone('UTC');
        $endUTC = (clone $startUTC)->addMinutes($totalMinutes);

        $utcStartTime = $startUTC->format('H:i:s');
        $utcEndTime = $endUTC->format('H:i:s');
        $utcBookingDate = $startUTC->format('Y-m-d');

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'pet_id' => $pet->id,
                'franchise_id' => $request->franchise_id,
                'booking_date' => $utcBookingDate,
                'start_time' => $utcStartTime,
                'end_time' => $utcEndTime,
                'status' => 'pending',
                'base_price' => 0,
                'tax' => 0,
                'out_of_area' => (int) ($request->out_of_area ?? 0),
                'total_price' => 0,
                'user_notes' => $request->user_notes,
            ]);

            foreach ($selectedServices as $service) {
                $servicePrice = 0;
                if ($service->pricing_type === 'fixable') {
                    $sz = $service->sizes()->whereNull('size_id')->first();
                    $servicePrice = $sz ? $sz->price : 0;
                } else {
                    $sz = $service->sizes()->where('size_id', $pet->size_id)->first();
                    $servicePrice = $sz ? $sz->price : 0;
                }

                BookedService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $service->id,
                    'size_id' => ($service->pricing_type === 'fixable') ? null : $pet->size_id,
                    'price' => $servicePrice,
                    'minutes' => $service->minutes ?? 0,
                    'quantity' => 1,
                ]);
            }

            $booking->refreshTotals($pet->category_id);

            $signaturePath = FileUploadService::uploadSingle($request, 'image', 'term-acceptances');

            TermAcceptance::create([
                'user_id' => Auth::id(),
                'booking_id' => $booking->id,
                'cms_page_id' => $request->cms_page_id,
                'image' => $signaturePath,
                'accepted' => true,
            ]);

            DB::commit();

            return redirect()->route('web.user.bookings.index')->with('success', 'Booking created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create booking. ' . $e->getMessage());
        }
    }

    public function getTimeSlots(Request $request)
    {
        $date = $request->get('date');
        $franchiseId = $request->get('franchise_id');
        $totalDuration = (int) $request->get('total_duration', 0);

        if (!$date || !$franchiseId || $totalDuration <= 0) {
            return response()->json(['success' => false, 'message' => 'Required parameters missing.']);
        }

        $localDate = Carbon::parse($date);
        $dayOfWeek = strtolower(substr($localDate->dayName, 0, 3));

        $schedule = WeeklySchedule::where('franchise_id', $franchiseId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule || !$schedule->is_open) {
            return response()->json(['success' => false, 'message' => 'Closed on this day.']);
        }

        $opening = $schedule->getRawOriginal('opening_time') ?? '00:00:00';
        $closing = $schedule->getRawOriginal('closing_time') ?? '23:59:59';
        $tz = session('timezone', 'UTC');

        $franchiseStartLocal = Carbon::parse($date . ' ' . $opening, 'UTC')->timezone($tz);
        $franchiseEndLocal = Carbon::parse($date . ' ' . $closing, 'UTC')->timezone($tz);

        if ($franchiseStartLocal > $franchiseEndLocal) {
            $franchiseEndLocal->addDay();
        }

        $utcRangeStart = (clone $franchiseStartLocal)->setTimezone('UTC');
        $utcRangeEnd = (clone $franchiseEndLocal)->setTimezone('UTC');

        $existingBookings = Booking::whereNotIn('status', ['cancelled'])
            ->where('franchise_id', $franchiseId)
            ->where(function ($q) use ($utcRangeStart, $utcRangeEnd) {
                $q->whereRaw("CONCAT(booking_date, ' ', start_time) < ?", [$utcRangeEnd->toDateTimeString()])
                    ->whereRaw("CONCAT(booking_date, ' ', end_time) > ?", [$utcRangeStart->toDateTimeString()]);
            })
            ->get();

        $totalStaff = Staff::where('is_active', 1)->count();
        $slots = [];
        $current = clone $franchiseStartLocal;

        $now = Carbon::now($tz);

        while ($current < $franchiseEndLocal) {
            $slotStart = clone $current;
            $slotEnd = (clone $slotStart)->addMinutes($totalDuration);

            if ($slotEnd > $franchiseEndLocal) {
                break;
            }

            if ($slotStart <= $now) {
                $current->addMinutes(5);
                continue;
            }

            $currentSlotUtcStartStr = (clone $slotStart)->setTimezone('UTC')->toDateTimeString();
            $currentSlotUtcEndStr = (clone $slotEnd)->setTimezone('UTC')->toDateTimeString();

            $overlappingCount = $existingBookings->filter(function ($b) use ($currentSlotUtcStartStr, $currentSlotUtcEndStr) {
                $bStart = $b->getRawOriginal('booking_date') . ' ' . $b->getRawOriginal('start_time');
                $bEnd = $b->getRawOriginal('booking_date') . ' ' . $b->getRawOriginal('end_time');
                return $currentSlotUtcStartStr < $bEnd && $currentSlotUtcEndStr > $bStart;
            })->count();

            if ($overlappingCount < $totalStaff) {
                $slots[] = [
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    'display' => $slotStart->format('h:i A')
                ];
            }

            $current->addMinutes(5);
        }

        return response()->json([
            'success' => true,
            'slots' => $slots
        ]);
    }
    public function storeReview(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed bookings.');
        }

        if ($booking->is_reviewed) {
            return back()->with('error', 'You have already reviewed this booking.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            \App\Models\Review::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'staff_id' => $booking->staff_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_publish' => 0,
            ]);

            $booking->update(['is_reviewed' => 1]);

            DB::commit();

            return back()->with('success', 'Thank you for your review!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit review. ' . $e->getMessage());
        }
    }
}
