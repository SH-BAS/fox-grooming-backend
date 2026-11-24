@extends('layouts.staff')

@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- Stats Cards -->
        <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
            <div class="card bg-primary-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-calendar-check text-primary"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $totalBookings }}</h2>
                            <p class="mb-0 text-muted">Total Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
            <div class="card bg-warning-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $pendingBookings }}</h2>
                            <p class="mb-0 text-muted">Pending Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
            <div class="card bg-success-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $completedBookings }}</h2>
                            <p class="mb-0 text-muted">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
            <div class="card bg-info-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-calendar-day text-info"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $todayBookings }}</h2>
                            <p class="mb-0 text-muted">Today's Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Recent Bookings</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>ID</strong></th>
                                    <th><strong>Customer</strong></th>
                                    <th><strong>Pet</strong></th>
                                    <th><strong>Date & Time</strong></th>
                                    <th><strong>Status</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $booking)
                                    <tr>
                                        <td><strong>#{{ $booking->id }}</strong></td>
                                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->pet->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->booking_date }} {{ $booking->booking_time }}</td>
                                        <td>
                                            @php
                                                $badgeClass = 'bg-warning';
                                                if ($booking->status == 'completed')
                                                    $badgeClass = 'bg-success';
                                                elseif ($booking->status == 'cancelled')
                                                    $badgeClass = 'bg-danger';
                                                elseif ($booking->status == 'assigned')
                                                    $badgeClass = 'bg-info';
                                                elseif ($booking->status == 'ontheway')
                                                    $badgeClass = 'bg-secondary';
                                                elseif ($booking->status == 'arrived')
                                                    $badgeClass = 'bg-primary';
                                                elseif ($booking->status == 'started')
                                                    $badgeClass = '';
                                            @endphp
                                            <span class="badge light {{ $badgeClass }}">{{ ucfirst($booking->status) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('staff.bookings.show', $booking->id) }}"
                                                    class="btn btn-primary shadow btn-xs sharp me-1"><i
                                                        class="fas fa-eye"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent bookings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-primary-light {
            background-color: rgba(72, 133, 237, 0.1);
        }

        .bg-warning-light {
            background-color: rgba(255, 184, 0, 0.1);
        }

        .bg-success-light {
            background-color: rgba(43, 193, 85, 0.1);
        }

        .bg-info-light {
            background-color: rgba(5, 190, 255, 0.1);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
    </style>
@endpush