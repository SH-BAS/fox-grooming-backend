<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Franchise;
use App\Models\Pet;
use App\Models\Review;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        $isSuperAdmin = ($admin && $admin->role && $admin->role->slug === 'super_admin');

        $bookingQuery = Booking::query();

        if (!$isSuperAdmin) {
            $bookingQuery->where('franchise_id', $admin->franchise_id);
        }

        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();

        $stats = [
            'total_bookings' => (clone $bookingQuery)->count(),
            'upcoming_bookings' => (clone $bookingQuery)
                ->where('booking_date', '>=', $today)
                ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
                ->count(),
            'pending_bookings' => (clone $bookingQuery)->where('status', 'pending')->count(),
            'confirmed_bookings' => (clone $bookingQuery)->where('status', 'confirmed')->count(),
            'completed_bookings' => (clone $bookingQuery)->where('status', 'completed')->count(),
            'cancelled_bookings' => (clone $bookingQuery)->whereIn('status', ['cancelled', 'rejected'])->count(),
            'today_bookings' => (clone $bookingQuery)->whereDate('booking_date', $today)->count(),
            'total_revenue' => (float) (clone $bookingQuery)->where('status', 'completed')->sum('total_price'),
            'month_revenue' => (float) (clone $bookingQuery)
                ->where('status', 'completed')
                ->whereDate('booking_date', '>=', $monthStart)
                ->sum('total_price'),
            'today_revenue' => (float) (clone $bookingQuery)
                ->where('status', 'completed')
                ->whereDate('booking_date', $today)
                ->sum('total_price'),
            'total_users' => User::count(),
            'total_staff' => Staff::count(),
            'total_pets' => Pet::count(),
            'total_services' => Service::count(),
            'total_franchises' => $isSuperAdmin ? Franchise::count() : 0,
            'pending_reviews' => Review::where('is_publish', false)->count(),
            'new_contacts' => Contact::whereDate('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Monthly chart — shows real booking volume (daily last-30 was mostly empty)
        $months = 6;
        $chartStart = now()->startOfMonth()->subMonths($months - 1);

        $bookingsByMonth = (clone $bookingQuery)
            ->select(
                DB::raw("DATE_FORMAT(booking_date, '%Y-%m') as month_key"),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN total_price ELSE 0 END) as revenue")
            )
            ->whereDate('booking_date', '>=', $chartStart->toDateString())
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key');

        $chartLabels = [];
        $chartBookings = [];
        $chartRevenue = [];

        for ($i = 0; $i < $months; $i++) {
            $date = $chartStart->copy()->addMonths($i);
            $key = $date->format('Y-m');
            $chartLabels[] = $date->format('M Y');
            $chartBookings[] = (int) ($bookingsByMonth[$key]->total ?? 0);
            $chartRevenue[] = round((float) ($bookingsByMonth[$key]->revenue ?? 0), 2);
        }

        $statusBreakdown = (clone $bookingQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $chart = [
            'labels' => $chartLabels,
            'bookings' => $chartBookings,
            'revenue' => $chartRevenue,
            'months' => $months,
        ];

        $recentBookings = (clone $bookingQuery)
            ->with(['user', 'bookedServices.service', 'franchise', 'pet'])
            ->latest()
            ->paginate(8);

        return view('admin.dashboard', compact(
            'stats',
            'recentBookings',
            'chart',
            'statusBreakdown',
            'isSuperAdmin'
        ));
    }
}
