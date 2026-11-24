@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')

@section('content')
    <style>
        .admin-content {
            padding: 16px 0 20px !important;
            background:rgb(222, 222, 226) !important;
        }

        .dash-page {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-left: 28px;
            margin-right: 28px;
        }

        .dash-page > .row {
            margin-left: -6px;
            margin-right: -6px;
            margin-bottom: 0 !important;
        }

        .dash-page > .row > [class*="col-"] {
            padding-left: 6px;
            padding-right: 6px;
        }

        .premium-stat {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 12px;
            padding: 12px 14px;
            height: 100%;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .premium-stat::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(230, 57, 70, 0.08), transparent 55%);
            pointer-events: none;
            z-index: -1;
        }

        .premium-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
        }

        .premium-stat .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #e63946;
            background: rgba(230, 57, 70, 0.1);
            border: 1px solid rgba(230, 57, 70, 0.18);
        }

        .premium-stat .stat-icon.amber { color: #d97706; background: rgba(245, 158, 11, 0.12); border-color: rgba(245, 158, 11, 0.25); }
        .premium-stat .stat-icon.green { color: #059669; background: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.25); }
        .premium-stat .stat-icon.blue { color: #2563eb; background: rgba(59, 130, 246, 0.12); border-color: rgba(59, 130, 246, 0.25); }
        .premium-stat .stat-icon.violet { color: #7c3aed; background: rgba(139, 92, 246, 0.12); border-color: rgba(139, 92, 246, 0.25); }

        .premium-stat .stat-badge {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 999px;
            background: #f3f4f6;
            border: 1px solid rgba(15, 23, 42, 0.06);
            color: #6b7280;
        }

        .premium-stat .stat-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #111827;
            margin: 8px 0 1px;
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .premium-stat .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .premium-stat.compact {
            padding: 10px 12px;
        }

        .premium-stat.compact .stat-value {
            margin-top: 0;
            font-size: 1.2rem;
        }

        .premium-panel {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .premium-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(230, 57, 70, 0.06), transparent 55%);
            pointer-events: none;
            z-index: -1;
        }

        .premium-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            flex-wrap: wrap;
            flex-shrink: 0;
            background: #fafafa;
        }

        .premium-header h4 {
            margin: 0;
            color: #111827;
            font-weight: 700;
            font-size: 0.98rem;
        }

        .premium-header .subtitle {
            display: block;
            margin-top: 2px;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 500;
        }

        .premium-body {
            padding: 12px 16px 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .status-chip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px;
            border-radius: 8px;
            background: #f9fafb;
            border: 1px solid rgba(15, 23, 42, 0.06);
            margin-bottom: 6px;
        }

        .status-chip:last-child { margin-bottom: 0; }

        .status-chip .label {
            color: #374151;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-chip .count {
            color: #111827;
            font-weight: 700;
            font-size: 13px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 7px;
        }

        .status-dot.pending { background: #f59e0b; }
        .status-dot.confirmed { background: #3b82f6; }
        .status-dot.completed { background: #10b981; }
        .status-dot.cancelled,
        .status-dot.rejected { background: #ef4444; }
        .status-dot.assigned { background: #e63946; }
        .status-dot.default { background: #8b5cf6; }

        .quick-action {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            background: #f9fafb;
            border: 1px solid rgba(15, 23, 42, 0.06);
            color: #111827;
            text-decoration: none;
            margin-bottom: 6px;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .quick-action:last-child { margin-bottom: 0; }

        .quick-action:hover {
            color: #111827;
            border-color: rgba(230, 57, 70, 0.35);
            background: rgba(230, 57, 70, 0.08);
        }

        .quick-action i {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(230, 57, 70, 0.1);
            color: #e63946;
            border: 1px solid rgba(230, 57, 70, 0.18);
            font-size: 13px;
        }

        .quick-action span {
            font-size: 12px;
            font-weight: 600;
        }

        .premium-table {
            color: #111827 !important;
            margin: 0;
        }

        .premium-table thead {
            background: transparent !important;
        }

        .premium-table thead th {
            background: linear-gradient(90deg, #e63946, #c62e3a) !important;
            border-color: transparent !important;
            color: #fff !important;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            white-space: nowrap;
            padding: 10px 14px;
        }

        .premium-table tbody td,
        .premium-table tbody td strong,
        .premium-table tbody td small,
        .premium-table tbody td span {
            color: #374151 !important;
            border-color: rgba(15, 23, 42, 0.06) !important;
            vertical-align: middle;
            background: #fff !important;
            padding: 10px 14px;
        }

        .premium-table tbody td strong {
            color: #111827 !important;
        }

        .premium-table tbody tr {
            background: #fff !important;
        }

        .premium-table tbody tr:hover td {
            background: #fef2f2 !important;
        }

        .user-avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff4d5a, #c62e3a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 11px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(230, 57, 70, 0.3);
        }

        .chart-wrap {
            position: relative;
            flex: 1;
            min-height: 210px;
            height: 100%;
        }

        .dash-pagination {
            padding: 10px 14px;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafafa;
            flex-shrink: 0;
        }

        .dash-pagination .pagination {
            margin: 0;
            justify-content: flex-end;
        }

        .dash-side-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
            height: 100%;
        }

        .dash-side-stack .premium-panel {
            height: auto;
            flex: 1;
        }

        .dash-mid-row {
            align-items: stretch;
        }

        .dash-mid-row > [class*="col-"] {
            display: flex;
        }

        .dash-mid-row > [class*="col-"] > .premium-panel,
        .dash-mid-row > [class*="col-"] > .dash-side-stack {
            width: 100%;
        }

        .dash-muted {
            color: #9ca3af !important;
        }
    </style>

    <div class="dash-page">
        {{-- Primary KPIs --}}
        <div class="row gx-2 gy-2">
            <div class="col-6 col-md-4 col-xl">
                <div class="premium-stat">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                        <span class="stat-badge">Total</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['total_bookings']) }}</div>
                    <div class="stat-label">Total Bookings</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl">
                <div class="premium-stat">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-icon amber"><i class="fas fa-clock"></i></div>
                        <span class="stat-badge">Active</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['upcoming_bookings']) }}</div>
                    <div class="stat-label">Upcoming</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl">
                <div class="premium-stat">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
                        <span class="stat-badge">Income</span>
                    </div>
                    <div class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl">
                <div class="premium-stat">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                        <span class="stat-badge">Users</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                    <div class="stat-label">Customers</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl">
                <div class="premium-stat">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-icon violet"><i class="fas fa-user-tie"></i></div>
                        <span class="stat-badge">Staff</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['total_staff']) }}</div>
                    <div class="stat-label">Professional Staff</div>
                </div>
            </div>
        </div>

        {{-- Secondary metrics --}}
        <!-- <div class="row gx-2 gy-2">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="premium-stat compact">
                    <div class="stat-value">{{ number_format($stats['today_bookings']) }}</div>
                    <div class="stat-label">Today's Bookings</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="premium-stat compact">
                    <div class="stat-value">{{ number_format($stats['pending_bookings']) }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="premium-stat compact">
                    <div class="stat-value">{{ number_format($stats['completed_bookings']) }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="premium-stat compact">
                    <div class="stat-value">${{ number_format($stats['month_revenue'], 2) }}</div>
                    <div class="stat-label">This Month</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="premium-stat compact">
                    <div class="stat-value">{{ number_format($stats['total_pets']) }}</div>
                    <div class="stat-label">Pets</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="premium-stat compact">
                    <div class="stat-value">
                        @if($isSuperAdmin)
                            {{ number_format($stats['total_franchises']) }}
                        @else
                            {{ number_format($stats['total_services']) }}
                        @endif
                    </div>
                    <div class="stat-label">{{ $isSuperAdmin ? 'Franchises' : 'Services' }}</div>
                </div>
            </div>
        </div> -->

        {{-- Chart + status / quick actions — stretched so no gap under chart --}}
        <div class="row gx-2 gy-2 dash-mid-row">
            <div class="col-xl-8">
                <div class="premium-panel">
                    <div class="premium-header">
                        <div>
                            <h4>Bookings Overview</h4>
                            <span class="subtitle">Last {{ $chart['months'] }} months — bookings & completed revenue</span>
                        </div>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="premium-body">
                        <div class="chart-wrap">
                            <canvas id="bookingsLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="dash-side-stack">

                @if($isSuperAdmin)
                        <div class="premium-panel">
                            <div class="premium-header">
                                <div>
                                    <h4>Quick Actions</h4>
                                    <span class="subtitle">Create new records</span>
                                </div>
                            </div>
                            <div class="premium-body">
                                <a href="{{ route('admin.bookings.create') }}" class="quick-action">
                                    <i class="fas fa-calendar-plus"></i>
                                    <span>Create Booking</span>
                                </a>
                                <a href="{{ route('admin.staff.create') }}" class="quick-action">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Create Staff</span>
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="quick-action">
                                    <i class="fas fa-users"></i>
                                    <span>Create Customer</span>
                                </a>
                                <a href="{{ route('admin.categories.create') }}" class="quick-action">
                                    <i class="fas fa-tags"></i>
                                    <span>Create Category</span>
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="premium-panel">
                        <div class="premium-header">
                            <div>
                                <h4>Booking Status</h4>
                                <span class="subtitle">Current distribution</span>
                            </div>
                        </div>
                        <div class="premium-body">
                            @php
                                $statusOrder = ['pending', 'confirmed', 'assigned', 'completed', 'cancelled', 'rejected'];
                            @endphp
                            @foreach($statusOrder as $status)
                                @php $count = (int) ($statusBreakdown[$status] ?? 0); @endphp
                                @if($count > 0 || in_array($status, ['pending', 'confirmed', 'completed']))
                                    <div class="status-chip">
                                        <span class="label">
                                            <span class="status-dot {{ $status }}"></span>{{ $status }}
                                        </span>
                                        <span class="count">{{ number_format($count) }}</span>
                                    </div>
                                @endif
                            @endforeach
                            @foreach($statusBreakdown as $status => $count)
                                @if(!in_array($status, $statusOrder) && $count > 0)
                                    <div class="status-chip">
                                        <span class="label">
                                            <span class="status-dot default"></span>{{ $status }}
                                        </span>
                                        <span class="count">{{ number_format($count) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>

        {{-- Recent bookings --}}
        <div class="premium-panel" style="height: auto;">
            <div class="premium-header">
                <div>
                    <h4>Recent Bookings</h4>
                    <span class="subtitle">Latest booking activity</span>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table premium-table mb-0">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td>
                                    <strong>#BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-sm me-2">
                                            {{ strtoupper(substr($booking->user?->name ?? 'C', 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $booking->user?->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="dash-muted">{{ $booking->user?->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</strong>
                                    <br>
                                    <small class="dash-muted">
                                        @if($booking->start_time)
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($booking->status) {
                                            'completed' => 'success',
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'cancelled', 'rejected' => 'danger',
                                            default => 'primary'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">{{ ucfirst($booking->status) }}</span>
                                </td>
                                <td>
                                    <strong>${{ number_format($booking->total_price, 2) }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.bookings.chat', $booking->id) }}"
                                            class="btn btn-info shadow btn-xs sharp me-1" title="Chat">
                                            <i class="fas fa-comment-dots text-white"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.edit', $booking->id) }}"
                                            class="btn btn-warning shadow btn-xs sharp me-1" title="Edit">
                                            <i class="fas fa-pencil-alt text-white"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                            class="btn btn-primary shadow btn-xs sharp me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 dash-muted">
                                    No recent bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($recentBookings->hasPages())
                <div class="dash-pagination">
                    {!! $recentBookings->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const canvas = document.getElementById('bookingsLineChart');
            if (!canvas) return;

            const labels = @json($chart['labels']);
            const bookings = @json($chart['bookings']);
            const revenue = @json($chart['revenue']);
            const ctx = canvas.getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(230, 57, 70, 0.22)');
            gradient.addColorStop(1, 'rgba(230, 57, 70, 0.02)');

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Bookings',
                            data: bookings,
                            borderColor: '#e63946',
                            backgroundColor: gradient,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#e63946',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Revenue ($)',
                            data: revenue,
                            borderColor: '#059669',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.35,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBackgroundColor: '#059669',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderDash: [5, 4],
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#6b7280',
                                usePointStyle: true,
                                padding: 12
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            borderColor: 'rgba(15, 23, 42, 0.1)',
                            borderWidth: 1,
                            titleColor: '#111827',
                            bodyColor: '#4b5563',
                            padding: 10,
                            callbacks: {
                                label: function (context) {
                                    if (context.dataset.yAxisID === 'y1') {
                                        return context.dataset.label + ': $' + Number(context.raw).toLocaleString();
                                    }
                                    return context.dataset.label + ': ' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#9ca3af', maxRotation: 0 },
                            grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false }
                        },
                        y: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            ticks: { color: '#9ca3af', precision: 0 },
                            grid: { color: 'rgba(15, 23, 42, 0.06)', drawBorder: false },
                            title: {
                                display: true,
                                text: 'Bookings',
                                color: '#9ca3af'
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            ticks: {
                                color: '#059669',
                                callback: function (value) {
                                    return '$' + value;
                                }
                            },
                            grid: { drawOnChartArea: false, drawBorder: false },
                            title: {
                                display: true,
                                text: 'Revenue',
                                color: '#059669'
                            }
                        }
                    }
                }
            });
        })();
    </script>
@endpush
