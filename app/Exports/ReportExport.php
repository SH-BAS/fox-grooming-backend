<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $reportType;

    public function __construct($data, $reportType)
    {
        $this->data = $data;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'detailed_bookings':
                return [
                    'Booking ID', 'Customer Name', 'Staff Name', 'Pet Name', 'Franchise',
                    'Booking Date', 'Start Time', 'End Time', 'Service/Category',
                    'Base Price', 'Tax', 'Total Price', 'Out of Area', 'Status'
                ];
            case 'bookings_per_day':
                return ['Date', 'Staff / Agent', 'Total Bookings'];
            case 'revenue_per_day':
                return ['Date', 'Staff / Agent', 'Total Revenue'];
            case 'avg_revenue_per_booking':
                return ['Date', 'Staff / Agent', 'Total Bookings', 'Total Revenue', 'Avg Revenue / Booking'];
            case 'start_end_time':
                return ['Date', 'Staff / Agent', 'Earliest Start Time', 'Latest End Time'];
            case 'area_franchise':
                return ['Date', 'Franchise / Call Center', 'Total Bookings', 'Total Revenue'];
            case 'call_center_bookings':
                return ['Date', 'Call Center (Franchise)', 'Total Confirmed/Completed Bookings'];
            default:
                return [];
        }
    }

    public function map($row): array
    {
        switch ($this->reportType) {
            case 'detailed_bookings':
                $services = $row->bookedServices->map(function($bs) {
                    return ($bs->service ? $bs->service->name : 'N/A') . ($bs->quantity > 1 ? " ({$bs->quantity})" : "");
                })->implode(', ');

                return [
                    $row->id,
                    $row->user ? $row->user->name : 'N/A',
                    $row->staff ? $row->staff->name : 'N/A',
                    $row->pet ? $row->pet->name : 'N/A',
                    $row->franchise ? $row->franchise->name : 'N/A',
                    $row->booking_date,
                    $row->start_time,
                    $row->end_time,
                    $services,
                    $row->base_price,
                    $row->tax,
                    $row->total_price,
                    $row->out_of_area ? 'Yes' : 'No',
                    ucfirst($row->status)
                ];
            case 'bookings_per_day':
                return [
                    $row->booking_date,
                    $row->staff ? $row->staff->name : 'N/A',
                    $row->total_bookings,
                ];
            case 'revenue_per_day':
                return [
                    $row->booking_date,
                    $row->staff ? $row->staff->name : 'N/A',
                    '$' . number_format($row->total_revenue, 2),
                ];
            case 'avg_revenue_per_booking':
                return [
                    $row->booking_date,
                    $row->staff ? $row->staff->name : 'N/A',
                    $row->total_bookings,
                    '$' . number_format($row->total_revenue, 2),
                    '$' . number_format($row->avg_revenue, 2),
                ];
            case 'start_end_time':
                return [
                    $row->booking_date,
                    $row->staff ? $row->staff->name : 'N/A',
                    $row->earliest_start,
                    $row->latest_end,
                ];
            case 'area_franchise':
                return [
                    $row->booking_date,
                    $row->franchise ? $row->franchise->name : 'N/A',
                    $row->total_bookings,
                    '$' . number_format($row->total_revenue, 2),
                ];
            case 'call_center_bookings':
                return [
                    $row->booking_date,
                    $row->franchise ? $row->franchise->name : 'N/A',
                    $row->confirmed_bookings,
                ];
            default:
                return [];
        }
    }
}
