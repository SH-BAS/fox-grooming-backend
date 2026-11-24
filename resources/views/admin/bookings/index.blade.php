@extends('layouts.admin')

@section('content')

    <style>
        .chat-btn {
            position: relative;
        }

        .chat-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 10px;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Bookings List</h4>
                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-sm">Create Booking</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>ID</strong></th>
                                    <th><strong>Customer</strong></th>
                                    <th><strong>Pet</strong></th>
                                    <th><strong>Franchise</strong></th>
                                    <th><strong>Date & Time</strong></th>
                                    <th><strong>Staff</strong></th>
                                    <th><strong>Status</strong></th>
                                    <th><strong>Total</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $index => $booking)
                                    <tr>
                                        <td><strong>{{ $index + 1 }}</strong></td>
                                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->pet->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->franchise->name ?? 'N/A' }}</td>
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
                                        <td>${{ number_format($booking->total_price, 2) }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.bookings.chat', $booking->id) }}"
                                                    class="btn btn-info shadow btn-xs sharp me-1 chat-btn">

                                                    <i class="fas fa-comment-dots text-white"></i>

                                                    @if($booking->chats_count > 0)
                                                        <span class="chat-badge chat-{{ $booking->id }}">{{ $booking->chats_count }}</span>
                                                    @endif

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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $bookings->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection