@extends('layouts.staff')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Booking #{{ $booking->id }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Assign Staff</label>
                        <select name="staff_id" class="form-control">
                            <option value="">Unassigned</option>
                            @foreach($staff as $s)
                                <option value="{{ $s->id }}" {{ $booking->staff_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="assigned" {{ $booking->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="ontheway" {{ $booking->status == 'ontheway' ? 'selected' : '' }}>On The Way</option>
                            <option value="arrived" {{ $booking->status == 'arrived' ? 'selected' : '' }}>Arrived</option>
                            <option value="started" {{ $booking->status == 'started' ? 'selected' : '' }}>Started</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <p><strong>Customer:</strong> {{ $booking->user->name ?? 'N/A' }}</p>
                        <p><strong>Pet:</strong> {{ $booking->pet->name ?? 'N/A' }} ({{ $booking->pet->category->name ?? 'N/A' }})</p>
                        <p><strong>Date/Time:</strong> {{ $booking->booking_date }} at {{ $booking->booking_time }}</p>
                        <p><strong>Total Price:</strong> ${{ $booking->total_price }}</p>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Booking</button>
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
