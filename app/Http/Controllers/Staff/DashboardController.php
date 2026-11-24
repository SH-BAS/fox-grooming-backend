<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $staff = auth()->guard('staff')->user();
        
        // Get stats for the logged-in staff
        $totalBookings = Booking::where('staff_id', $staff->id)->count();
        $pendingBookings = Booking::where('staff_id', $staff->id)->where('status', 'pending')->count();
        $completedBookings = Booking::where('staff_id', $staff->id)->where('status', 'completed')->count();
        $todayBookings = Booking::where('staff_id', $staff->id)
            ->whereDate('booking_date', today())
            ->count();
        
        // Get recent bookings
        $recentBookings = Booking::with(['user', 'pet'])
            ->where('staff_id', $staff->id)
            ->orderBy('booking_date', 'desc')
            ->limit(10)
            ->get();
        
        return view('staff.dashboard', compact(
            'totalBookings',
            'pendingBookings',
            'completedBookings',
            'todayBookings',
            'recentBookings'
        ));
    }
}
