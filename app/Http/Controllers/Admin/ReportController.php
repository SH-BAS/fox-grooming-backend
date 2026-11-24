<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->get('report_type', 'detailed_bookings');
        $fromDate = $request->get('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());
        $staffId = $request->get('staff_id');
        $franchiseId = $request->get('franchise_id');

        $staffs = Staff::where('is_active', 1)->get();
        $franchises = Franchise::where('is_active', 1)->get();

        // Base Query for counts/summaries (unpaginated)
        $summaryQuery = Booking::query();
        if ($fromDate) $summaryQuery->whereDate('booking_date', '>=', $fromDate);
        if ($toDate) $summaryQuery->whereDate('booking_date', '<=', $toDate);
        if ($staffId) $summaryQuery->where('staff_id', $staffId);
        if ($franchiseId) $summaryQuery->where('franchise_id', $franchiseId);

        $totalBookings = $summaryQuery->count();
        $totalRevenue = $summaryQuery->sum('total_price');
        $totalCustomers = $summaryQuery->distinct('user_id')->count('user_id');

        $data = $this->getReportData($request);

        return view('admin.reports.index', compact(
            'staffs', 'franchises', 'data', 'fromDate', 'toDate',
            'reportType', 'staffId', 'franchiseId',
            'totalBookings', 'totalRevenue', 'totalCustomers'
        ));
    }

    public function export(Request $request)
    {
        $reportType = $request->get('report_type', 'detailed_bookings');
        
        $fileName = str_replace('_', ' ', $reportType) . '_' . now()->format('YmdHis') . '.xlsx';
        return Excel::download(new ReportExport($this->getReportData($request, true), $reportType), $fileName);
    }

    private function getReportData(Request $request, $forExport = false)
    {
        $reportType = $request->get('report_type', 'detailed_bookings');
        $fromDate = $request->get('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());
        $staffId = $request->get('staff_id');
        $franchiseId = $request->get('franchise_id');

        $query = Booking::query();

        if ($fromDate) {
            $query->whereDate('booking_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('booking_date', '<=', $toDate);
        }
        if ($staffId) {
            $query->where('staff_id', $staffId);
        }
        if ($franchiseId) {
            $query->where('franchise_id', $franchiseId);
        }

        switch ($reportType) {
            case 'detailed_bookings':
                $query->with(['user', 'staff', 'pet', 'franchise', 'bookedServices.service'])
                      ->orderBy('booking_date', 'desc')
                      ->orderBy('start_time', 'desc');
                return $forExport ? $query->get() : $query->paginate(25)->appends($request->all());

            case 'bookings_per_day':
                $results = $query->selectRaw('booking_date, staff_id, count(*) as total_bookings')
                    ->with('staff')
                    ->groupBy('booking_date', 'staff_id')
                    ->orderBy('booking_date', 'desc');
                return $forExport ? $results->get() : $results->paginate(25)->appends($request->all());

            case 'revenue_per_day':
                $results = $query->selectRaw('booking_date, staff_id, sum(total_price) as total_revenue')
                    ->with('staff')
                    ->groupBy('booking_date', 'staff_id')
                    ->orderBy('booking_date', 'desc');
                return $forExport ? $results->get() : $results->paginate(25)->appends($request->all());

            case 'avg_revenue_per_booking':
                $results = $query->selectRaw('booking_date, staff_id, count(*) as total_bookings, sum(total_price) as total_revenue')
                    ->with('staff')
                    ->groupBy('booking_date', 'staff_id')
                    ->orderBy('booking_date', 'desc')
                    ->get()
                    ->map(function ($item) {
                        $item->avg_revenue = $item->total_bookings > 0 ? $item->total_revenue / $item->total_bookings : 0;
                        return $item;
                    });
                return $results;

            case 'start_end_time':
                $results = $query->selectRaw('booking_date, staff_id, min(start_time) as earliest_start, max(end_time) as latest_end')
                    ->with('staff')
                    ->groupBy('booking_date', 'staff_id')
                    ->orderBy('booking_date', 'desc');
                return $forExport ? $results->get() : $results->paginate(25)->appends($request->all());

            case 'area_franchise':
                $results = $query->selectRaw('booking_date, franchise_id, count(*) as total_bookings, sum(total_price) as total_revenue')
                    ->with('franchise')
                    ->groupBy('booking_date', 'franchise_id')
                    ->orderBy('booking_date', 'desc');
                return $forExport ? $results->get() : $results->paginate(25)->appends($request->all());

            case 'call_center_bookings':
                $results = $query->whereIn('status', ['assigned', 'ontheway', 'arrived', 'started', 'completed']) // Exclude pending and cancelled
                    ->selectRaw('booking_date, franchise_id, count(*) as confirmed_bookings')
                    ->with('franchise')
                    ->groupBy('booking_date', 'franchise_id')
                    ->orderBy('booking_date', 'desc');
                return $forExport ? $results->get() : $results->paginate(25)->appends($request->all());

            default:
                return collect();
        }
    }
}
