<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookedService;
use App\Models\Category;
use App\Models\CategoryStandard;
use App\Models\Service;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Booking\BookingStatusUpdated;
use App\Services\FcmService;

class BookingController extends Controller
{
    public function index()
    {
        $staff = auth()->guard('staff')->user();

        $bookings = Booking::with(['user', 'pet', 'staff', 'bookedServices.service'])
            ->where('staff_id', $staff->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Required by user: Apply full calculations when fetching bookings list
        // $bookings->getCollection()->each->refreshTotals();
        // dd($bookings);
        return view('staff.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $staff = auth()->guard('staff')->user();

        // Ensure staff can only view their own bookings
        if ($booking->staff_id !== $staff->id) {
            abort(403, 'Unauthorized action.');
        }

        $booking->load([
            'user',
            'user.bookings' => function ($query) {
                $query->with(['bookedServices.service', 'staff'])->orderBy('booking_date', 'desc');
            },
            'pet',
            'bookedServices.size',
            'bookedServices.service',
            'logs',
            'staff'
        ]);

        // Required by user: Apply full calculations on booking detail page
        // $booking->refreshTotals();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('staff.bookings.show', compact('booking', 'categories'));
    }

    public function addService(Request $request, Booking $booking)
    {
        $staff = auth()->guard('staff')->user();
        if ($booking->staff_id !== $staff->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status === 'completed') {
            return redirect()->back()->with('error', 'This booking is completed and cannot be modified.');
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'size_id' => 'nullable|exists:sizes,id',
        ]);

        // Add the service
        BookedService::create([
            'booking_id' => $booking->id,
            'service_id' => $request->service_id,
            'size_id' => $request->size_id,
            'price' => 0, // Will be updated by refreshTotals
        ]);

        // Recalculate Totals using centralized logic
        $booking->load('bookedServices.service');
        // $booking->refreshTotals();

        return redirect()->back()->with('success', 'Service added and totals updated successfully.');
    }

    public function updateBookedService(Request $request, BookedService $bookedService)
    {
        $staff = auth()->guard('staff')->user();
        $booking = $bookedService->booking;

        if ($booking->staff_id !== $staff->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status === 'completed') {
            return redirect()->back()->with('error', 'This booking is completed and cannot be modified.');
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'size_id' => 'nullable|exists:sizes,id',
        ]);

        $bookedService->update([
            'service_id' => $request->service_id,
            'size_id' => $request->size_id,
            'price' => null, // Reset to trigger recalculation
            'minutes' => null, // Reset to trigger recalculation
            'quantity' => 1, // Reset to default
        ]);

        // Explicitly reload relationships to ensure fresh data for refreshTotals
        $booking->unsetRelation('bookedServices');
        $booking->load(['pet', 'bookedServices.service']);
        // $booking->refreshTotals();

        return redirect()->back()->with('success', 'Service updated and totals recalculated.');
    }

    public function deleteBookedService(BookedService $bookedService)
    {
        $staff = auth()->guard('staff')->user();
        $booking = $bookedService->booking;

        if ($booking->staff_id !== $staff->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status === 'completed') {
            return redirect()->back()->with('error', 'This booking is completed and cannot be modified.');
        }

        $bookedService->delete();

        // Explicitly reload relationships to ensure fresh data for refreshTotals
        $booking->unsetRelation('bookedServices');
        $booking->load(['pet', 'bookedServices.service']);
        $booking->refreshTotals();

        return redirect()->back()->with('success', 'Service removed and totals recalculated.');
    }


    public function invoice(Booking $booking)
    {
        $staff = auth()->guard('staff')->user();
        if ($booking->staff_id !== $staff->id) {
            abort(403, 'Unauthorized action.');
        }

        $booking->load(['user', 'pet', 'staff', 'bookedServices.size', 'bookedServices.service']);

        // Required by user: Apply full calculations on staff invoice page
        // $booking->refreshTotals();

        return view('staff.bookings.invoice', compact('booking'));
    }

    public function getCategoryServices(Category $category)
    {
        return response()->json($category->services()->where('is_active', true)->get());
    }

    public function getServiceDetails(Service $service)
    {
        $service->load(['category.standards.size']);
        return response()->json($service);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $staff = auth()->guard('staff')->user();

        // Ensure staff can only update their own bookings
        if ($booking->staff_id !== $staff->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:assigned,ontheway,arrived,started,completed,cancelled',
            'payment_method' => 'nullable|required_if:status,completed|in:card,cheque,cash',
            'cancel_note' => 'nullable|required_if:status,cancelled',
        ]);

        $oldStatus = $booking->status;
        $booking->status = $request->status;

        if ($request->status === 'completed') {
            // $booking->refreshTotals();
            $booking->update([
                'received_amount' => $booking->total_price,
                'payment_method' => $request->payment_method,
                'received_type' => 'staff',
                'received_type_id' => auth()->guard('staff')->id(),
            ]);
        }

        if ($request->status === 'cancelled') {
            $booking->update([
                'cancel_note' => $request->cancel_note,
                'cancel_type' => 'staff',
                'cancel_id' => auth()->guard('staff')->id(),
            ]);
        }

        $booking->save();

        // Log the status change
        $this->logStatusChange($booking, $oldStatus, $request->status);

        // Send Email Notification
        try {
            Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking status update email (staff): ' . $e->getMessage());
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

        return redirect()->route('staff.bookings.show', $booking->id)
            ->with('success', 'Booking status updated successfully.');
    }

    public function uploadDocument(Request $request, Booking $booking)
    {
        $staff = auth()->guard('staff')->user();
        if ($booking->staff_id !== $staff->id) {
            abort(403, 'Unauthorized action.');
        }

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

    private function logStatusChange($booking, $oldStatus, $newStatus)
    {
        $logData = [
            'booking_id' => $booking->id,
            'updated_by_type' => 'Staff',
            'updated_by_id' => auth()->guard('staff')->id(),
        ];

        $columnMap = [
            'assigned' => 'assigned_at',
            'ontheway' => 'ontheway_at',
            'arrived' => 'arrived_at',
            'started' => 'started_at',
            'completed' => 'completed_at',
            'cancelled' => 'cancelled_at',
        ];

        if (isset($columnMap[$newStatus])) {
            $logData[$columnMap[$newStatus]] = now();
        }

        \App\Models\BookingLog::create($logData);
    }

    public function schedule()
    {
        $staff = auth()->guard('staff')->user();
        $bookings = Booking::with(['user', 'pet', 'bookedServices.service', 'staff'])
            ->where('staff_id', $staff->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('staff.bookings.schedule', compact('bookings'));
    }

    public function scheduleData(Request $request)
    {
        $staff = auth()->guard('staff')->user();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        // Get only staff's bookings for the requested month
        $bookings = Booking::with(['user', 'pet'])
            ->where('staff_id', $staff->id)
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->get()
            ->groupBy('booking_date');

        $events = [];
        foreach ($bookings as $date => $dateBookings) {
            $events[] = [
                'date' => $date,
                'count' => $dateBookings->count(),
                'bookings' => $dateBookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'customer' => $booking->user->name ?? 'N/A',
                        'pet' => $booking->pet->name ?? 'N/A',
                        'time' => $booking->booking_time,
                        'status' => $booking->status,
                    ];
                })
            ];
        }

        return response()->json($events);
    }
}
