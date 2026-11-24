@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
    <style>
        .premium-stat {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background:
                linear-gradient(145deg, rgba(255, 0, 0, 0.14) 0%, rgba(0, 0, 0, 0.35) 42%, rgba(20, 20, 20, 0.95) 100%),
                linear-gradient(180deg, #1a1a1a 0%, #111111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22);
            border-left: 3px solid #ff0000;
            border-radius: 16px;
            padding: 20px;
            height: 100%;
            box-shadow:
                0 10px 28px rgba(0, 0, 0, 0.45),
                inset 0 0 40px rgba(255, 0, 0, 0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .premium-stat::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.18), transparent 55%);
            pointer-events: none;
            z-index: -1;
        }

        .premium-stat:hover {
            transform: translateY(-3px);
            box-shadow:
                0 16px 36px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(255, 0, 0, 0.18);
        }

        .premium-stat .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #ff4d5a;
            background: rgba(255, 0, 0, 0.12);
            border: 1px solid rgba(255, 0, 0, 0.25);
        }

        .premium-stat .stat-badge {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.7);
        }

        .premium-stat .stat-value {
            font-size: 1.65rem;
            font-weight: 700;
            color: #fff;
            margin: 14px 0 2px;
            letter-spacing: -0.02em;
        }

        .premium-stat .stat-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.55);
            font-weight: 500;
        }

        .premium-panel {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background:
                linear-gradient(145deg, rgba(255, 0, 0, 0.12) 0%, rgba(0, 0, 0, 0.3) 40%, rgba(18, 18, 18, 0.98) 100%),
                linear-gradient(180deg, #1a1a1a 0%, #111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22);
            border-left: 3px solid #ff0000;
            border-radius: 16px;
            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.4),
                inset 0 0 40px rgba(255, 0, 0, 0.04);
            margin-bottom: 20px;
        }

        .premium-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.16), transparent 55%);
            pointer-events: none;
            z-index: -1;
        }

        .premium-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 20px 22px 0;
            flex-wrap: wrap;
        }

        .premium-header h4,
        .premium-header h5 {
            margin: 0;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .premium-header .subtitle {
            display: block;
            margin-top: 4px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
            font-weight: 500;
        }

        .premium-body {
            padding: 18px 22px 22px;
        }

        .premium-panel .form-label {
            color: rgba(255, 255, 255, 0.65);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
        }

        .premium-panel .form-control,
        .premium-panel .form-select {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
            border-radius: 10px;
            min-height: 44px;
            box-shadow: none;
        }

        .premium-panel .form-control:focus,
        .premium-panel .form-select:focus {
            background: rgba(0, 0, 0, 0.45);
            border-color: rgba(255, 0, 0, 0.55);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15);
            color: #fff;
        }

        .premium-panel .form-select option {
            background: #1a1a1a;
            color: #fff;
        }

        .premium-actions .btn {
            border-radius: 10px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .premium-table {
            color: #ffffff !important;
            margin: 0;
        }

        .premium-table thead th {
            background: rgba(0, 0, 0, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
            color: #ffffff !important;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            white-space: nowrap;
            padding: 14px 16px;
        }

        .premium-table tbody td,
        .premium-table tbody td strong,
        .premium-table tbody td small,
        .premium-table tbody td span {
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
            vertical-align: middle;
            background: transparent !important;
            padding: 12px 16px;
        }

        .premium-table tbody tr:hover td {
            background: rgba(255, 0, 0, 0.06) !important;
        }

        .premium-empty {
            color: rgba(255, 255, 255, 0.45) !important;
            text-align: center;
            padding: 48px 16px !important;
        }

        .premium-footer {
            padding: 14px 22px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .report-type-pill {
            display: inline-block;
            margin-top: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #ff4d5a;
            background: rgba(255, 0, 0, 0.12);
            border: 1px solid rgba(255, 0, 0, 0.25);
        }
    </style>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="premium-stat">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <span class="stat-badge">Total</span>
                </div>
                <div class="stat-value">{{ number_format($totalBookings) }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-stat">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <span class="stat-badge">Income</span>
                </div>
                <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-stat">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <span class="stat-badge">Users</span>
                </div>
                <div class="stat-value">{{ number_format($totalCustomers) }}</div>
                <div class="stat-label">Total Customers</div>
            </div>
        </div>
    </div>

    <div class="premium-panel">
        <div class="premium-header">
            <div>
                <h5><i class="fas fa-filter me-2" style="color:#ff4d5a;"></i>Filter Reports</h5>
                <span class="subtitle">Refine by type, date range, staff, or franchise</span>
            </div>
        </div>
        <div class="premium-body">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Report Type</label>
                    <select name="report_type" id="report_type" class="form-select">
                        <option value="detailed_bookings" {{ $reportType == 'detailed_bookings' ? 'selected' : '' }}>
                            Full Booking Details (Default)</option>
                        <option value="bookings_per_day" {{ $reportType == 'bookings_per_day' ? 'selected' : '' }}>
                            Bookings per day (per agent)</option>
                        <option value="revenue_per_day" {{ $reportType == 'revenue_per_day' ? 'selected' : '' }}>
                            Revenue per day (per agent)</option>
                        <option value="avg_revenue_per_booking" {{ $reportType == 'avg_revenue_per_booking' ? 'selected' : '' }}>
                            Average revenue per booking</option>
                        <option value="start_end_time" {{ $reportType == 'start_end_time' ? 'selected' : '' }}>
                            Start and End time per day (per agent)</option>
                        <option value="area_franchise" {{ $reportType == 'area_franchise' ? 'selected' : '' }}>
                            Area / Franchise report (per day)</option>
                        <option value="call_center_bookings" {{ $reportType == 'call_center_bookings' ? 'selected' : '' }}>
                            Call Center bookings per day</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                </div>

                <div class="col-md-2" id="staff_filter_container">
                    <label class="form-label">Staff / Agent</label>
                    <select name="staff_id" class="form-select">
                        <option value="">All Staff</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ $staffId == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2" id="franchise_filter_container">
                    <label class="form-label">Franchise</label>
                    <select name="franchise_id" class="form-select">
                        <option value="">All Franchises</option>
                        @foreach($franchises as $franchise)
                            <option value="{{ $franchise->id }}" {{ $franchiseId == $franchise->id ? 'selected' : '' }}>
                                {{ $franchise->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 premium-actions d-flex flex-wrap gap-2 justify-content-end mt-2">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="premium-panel mb-0">
        <div class="premium-header">
            <div>
                <h5>Report Results</h5>
                <span class="report-type-pill">
                    @if($reportType == 'detailed_bookings') Full Booking Details
                    @elseif($reportType == 'bookings_per_day') Bookings per day
                    @elseif($reportType == 'revenue_per_day') Revenue per day
                    @elseif($reportType == 'avg_revenue_per_booking') Average revenue per booking
                    @elseif($reportType == 'start_end_time') Start / End time per day
                    @elseif($reportType == 'area_franchise') Area / Franchise report
                    @elseif($reportType == 'call_center_bookings') Call Center bookings
                    @endif
                </span>
            </div>
            <form action="{{ route('admin.reports.export') }}" method="GET" class="d-inline">
                <input type="hidden" name="report_type" value="{{ $reportType }}">
                <input type="hidden" name="from_date" value="{{ $fromDate }}">
                <input type="hidden" name="to_date" value="{{ $toDate }}">
                <input type="hidden" name="staff_id" value="{{ $staffId }}">
                <input type="hidden" name="franchise_id" value="{{ $franchiseId }}">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                </button>
            </form>
        </div>

        <div class="premium-body pt-3 px-0 pb-0">
            <div class="table-responsive">
                <table class="table premium-table align-middle mb-0">
                    <thead>
                        <tr>
                            @if($reportType == 'detailed_bookings')
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Pet</th>
                                <th>Staff</th>
                                <th>Franchise</th>
                                <th>Date/Time</th>
                                <th>Service</th>
                                <th class="text-end">Base</th>
                                <th class="text-end">Tax</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                            @else
                                <th>Date</th>

                                @if(in_array($reportType, ['bookings_per_day', 'revenue_per_day', 'avg_revenue_per_booking', 'start_end_time']))
                                    <th>Staff / Agent</th>
                                @endif

                                @if(in_array($reportType, ['area_franchise', 'call_center_bookings']))
                                    <th>Franchise / Call Center</th>
                                @endif

                                @if(in_array($reportType, ['bookings_per_day', 'avg_revenue_per_booking', 'area_franchise']))
                                    <th class="text-center">Total Bookings</th>
                                @endif

                                @if(in_array($reportType, ['revenue_per_day', 'avg_revenue_per_booking', 'area_franchise']))
                                    <th class="text-end">Total Revenue</th>
                                @endif

                                @if($reportType == 'avg_revenue_per_booking')
                                    <th class="text-end">Avg Revenue / Booking</th>
                                @endif

                                @if($reportType == 'start_end_time')
                                    <th>Earliest Start</th>
                                    <th>Latest End</th>
                                @endif

                                @if($reportType == 'call_center_bookings')
                                    <th class="text-center">Confirmed Bookings</th>
                                @endif
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                @if($reportType == 'detailed_bookings')
                                    <td><strong>#{{ $row->id }}</strong></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $row->user->name ?? 'N/A' }}</span>
                                            <small style="opacity:0.65;">{{ $row->user->phone ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $row->pet->name ?? 'N/A' }}</td>
                                    <td>{{ $row->staff->name ?? 'Unassigned' }}</td>
                                    <td>{{ $row->franchise->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ \Carbon\Carbon::parse($row->booking_date)->format('M d, Y') }}</span>
                                            <small style="opacity:0.65;">
                                                {{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}
                                                - {{ \Carbon\Carbon::parse($row->end_time)->format('h:i A') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <small style="opacity:0.8;">
                                            @foreach($row->bookedServices as $bs)
                                                {{ $bs->service->name ?? 'N/A' }}{{ !$loop->last ? ',' : '' }}
                                            @endforeach
                                        </small>
                                    </td>
                                    <td class="text-end">${{ number_format($row->base_price, 2) }}</td>
                                    <td class="text-end">${{ number_format($row->tax, 2) }}</td>
                                    <td class="text-end"><strong>${{ number_format($row->total_price, 2) }}</strong></td>
                                    <td>
                                        @php
                                            $badgeClass = match ($row->status) {
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                'pending' => 'bg-warning',
                                                default => 'bg-info'
                                            };
                                        @endphp
                                        <span class="badge light {{ $badgeClass }}">{{ ucfirst($row->status) }}</span>
                                        @if($row->out_of_area == 1)
                                            <span class="badge light bg-secondary">Out of Area</span>
                                        @endif
                                    </td>
                                @else
                                    <td>{{ \Carbon\Carbon::parse($row->booking_date)->format('M d, Y') }}</td>

                                    @if(in_array($reportType, ['bookings_per_day', 'revenue_per_day', 'avg_revenue_per_booking', 'start_end_time']))
                                        <td>
                                            @if($row->staff)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $row->staff->image }}" class="rounded me-2" width="28"
                                                        height="28" style="object-fit: cover; border-radius: 8px;">
                                                    <span>{{ $row->staff->name }}</span>
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    @endif

                                    @if(in_array($reportType, ['area_franchise', 'call_center_bookings']))
                                        <td>
                                            @if($row->franchise)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $row->franchise->image }}" class="rounded me-2" width="28"
                                                        height="28" style="object-fit: cover; border-radius: 8px;">
                                                    <span>{{ $row->franchise->name }}</span>
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    @endif

                                    @if(in_array($reportType, ['bookings_per_day', 'avg_revenue_per_booking', 'area_franchise']))
                                        <td class="text-center"><strong>{{ $row->total_bookings }}</strong></td>
                                    @endif

                                    @if(in_array($reportType, ['revenue_per_day', 'avg_revenue_per_booking', 'area_franchise']))
                                        <td class="text-end"><strong>${{ number_format($row->total_revenue, 2) }}</strong></td>
                                    @endif

                                    @if($reportType == 'avg_revenue_per_booking')
                                        <td class="text-end"><strong>${{ number_format($row->avg_revenue, 2) }}</strong></td>
                                    @endif

                                    @if($reportType == 'start_end_time')
                                        <td>{{ \Carbon\Carbon::parse($row->earliest_start)->format('h:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->latest_end)->format('h:i A') }}</td>
                                    @endif

                                    @if($reportType == 'call_center_bookings')
                                        <td class="text-center"><strong>{{ $row->confirmed_bookings }}</strong></td>
                                    @endif
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="premium-empty">
                                    <i class="fas fa-search fa-2x mb-3 d-block" style="opacity:0.35;"></i>
                                    No reports found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="premium-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small" style="color:rgba(255,255,255,0.5) !important;">
                    Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} results
                </div>
                <div>{{ $data->links() }}</div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            function toggleFilters() {
                var reportType = $('#report_type').val();
                if (['bookings_per_day', 'revenue_per_day', 'avg_revenue_per_booking', 'start_end_time'].includes(reportType)) {
                    $('#staff_filter_container').show();
                    $('#franchise_filter_container').hide();
                } else if (['area_franchise', 'call_center_bookings'].includes(reportType)) {
                    $('#staff_filter_container').hide();
                    $('#franchise_filter_container').show();
                } else {
                    $('#staff_filter_container').show();
                    $('#franchise_filter_container').show();
                }
            }

            $('#report_type').change(toggleFilters);
            toggleFilters();
        });
    </script>
@endpush
