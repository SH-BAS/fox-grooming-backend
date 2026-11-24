@extends('layouts.admin')

@section('title', $franchise->name . ' | Franchise')

@section('content')
    <style>
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
        }

        .premium-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.16), transparent 55%);
            pointer-events: none;
            z-index: -1;
        }

        .premium-hero {
            padding: 28px;
        }

        .premium-avatar {
            width: 110px;
            height: 110px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255, 0, 0, 0.35);
            box-shadow:
                0 10px 24px rgba(0, 0, 0, 0.35),
                0 0 18px rgba(255, 0, 0, 0.2);
            background: #1f1f1f;
        }

        .premium-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .premium-title {
            margin: 0 0 8px;
            color: #fff;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .premium-meta {
            margin: 0;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .premium-meta i {
            color: #ff4d5a;
            width: 18px;
        }

        .premium-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .premium-badge.active {
            color: #34d399;
        }

        .premium-badge.inactive {
            color: #fbbf24;
        }

        .premium-actions .btn {
            border-radius: 10px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .premium-section-title {
            color: #fff;
            font-weight: 700;
            margin-bottom: 0;
        }

        .premium-table {
            color: #ffffff !important;
        }

        .premium-table thead th {
            background: rgba(0, 0, 0, 0.35) !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
            color: #ffffff !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .premium-table tbody td,
        .premium-table tbody td strong,
        .premium-table tbody td small {
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
            vertical-align: middle;
            background: transparent !important;
        }

        .premium-table tbody tr:hover td {
            background: rgba(255, 0, 0, 0.06) !important;
            color: #ffffff !important;
        }

        .premium-empty {
            color: rgba(255, 255, 255, 0.45) !important;
            text-align: center;
            padding: 28px 12px;
        }
    </style>

    <div class="row g-4">
        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="premium-hero">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <div class="premium-avatar">
                                <img src="{{ $franchise->image }}" alt="{{ $franchise->name }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <h2 class="premium-title">{{ $franchise->name }}</h2>
                                <span class="premium-badge {{ $franchise->is_active ? 'active' : 'inactive' }}">
                                    {{ $franchise->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="premium-meta mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Zone: {{ $franchise->zone->name ?? 'N/A' }}
                            </p>
                            <p class="premium-meta mb-0">
                                <i class="fas fa-location-dot me-1"></i>
                                {{ collect([$franchise->address, $franchise->city, $franchise->state])->filter()->implode(', ') ?: 'No location set' }}
                            </p>
                        </div>
                        <div class="col-auto premium-actions d-flex gap-2">
                            <a href="{{ route('admin.franchises.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <a href="{{ route('admin.franchises.edit', $franchise->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-pencil-alt me-1"></i> Edit Franchise
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="card-header border-0 bg-transparent d-flex justify-content-between align-items-center">
                    <h4 class="premium-section-title">Bookings ({{ $bookings->count() }})</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table premium-table table-responsive-md mb-0">
                            <thead>
                                <tr>
                                    <th><strong>ID</strong></th>
                                    <th><strong>Customer</strong></th>
                                    <th><strong>Pet</strong></th>
                                    <th><strong>Date & Time</strong></th>
                                    <th><strong>Staff</strong></th>
                                    <th><strong>Status</strong></th>
                                    <th><strong>Total</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td><strong>#{{ $booking->id }}</strong></td>
                                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->pet->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $tz = session('timezone', 'UTC');
                                                $start = \Carbon\Carbon::parse($booking->getRawOriginal('booking_date').' '.$booking->getRawOriginal('start_time'), 'UTC')->timezone($tz);
                                                $end = \Carbon\Carbon::parse($booking->getRawOriginal('booking_date').' '.$booking->getRawOriginal('end_time'), 'UTC')->timezone($tz);
                                            @endphp
                                            {{ $start->format('Y-m-d') }}<br>
                                            <small>{{ $start->format('h:i A') }} - {{ $end->format('h:i A') }}</small>
                                        </td>
                                        <td>{{ $booking->staff->name ?? 'Unassigned' }}</td>
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
                                        <td><strong>${{ number_format($booking->total_price, 2) }}</strong></td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.bookings.chat', $booking->id) }}"
                                                    class="btn btn-info shadow btn-xs sharp me-1">
                                                    <i class="fas fa-comment-dots text-white"></i>
                                                </a>
                                                <a href="{{ route('admin.bookings.edit', $booking->id) }}"
                                                    class="btn btn-warning shadow btn-xs sharp me-1">
                                                    <i class="fas fa-pencil-alt text-white"></i>
                                                </a>
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                                    class="btn btn-primary shadow btn-xs sharp me-1"><i
                                                        class="fas fa-eye"></i></a>

                                                @php
                                                    $bookingDate = \Carbon\Carbon::parse($booking->booking_date);
                                                    $isPast = $bookingDate->isPast() && !$bookingDate->isToday();
                                                    $isCompleted = $booking->status == 'completed';
                                                @endphp

                                                @if(!$isPast && !$isCompleted)
                                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST"
                                                        data-confirm="Are you sure?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i
                                                                class="fa fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="premium-empty">No bookings found for this franchise.</td>
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
