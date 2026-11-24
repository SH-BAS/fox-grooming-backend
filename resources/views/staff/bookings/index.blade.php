@extends('layouts.staff')

@section('page-title', 'My Bookings')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Bookings List</h4>
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
                                                $start = \Carbon\Carbon::parse($booking->getRawOriginal('booking_date') . ' ' . $booking->getRawOriginal('start_time'), 'UTC')->timezone($tz);
                                                $end = \Carbon\Carbon::parse($booking->getRawOriginal('booking_date') . ' ' . $booking->getRawOriginal('end_time'), 'UTC')->timezone($tz);
                                            @endphp
                                            {{ $start->format('Y-m-d') }}<br>
                                            <small class="text-muted">{{ $start->format('h:i A') }} -
                                                {{ $end->format('h:i A') }}</small>
                                        </td>
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
                                        <td>${{ number_format($booking->total_price, 2) }}</td>
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
                                        <td colspan="7" class="text-center">No bookings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection