<div class="booking-details">
    <div class="row">
        <div class="col-md-6">
            <h4 class="text-theme-colored border-bottom pb-10">Booking Information</h4>
            <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
            <p><strong>Status:</strong> <span class="label label-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">{{ strtoupper($booking->status) }}</span></p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>
            <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p>
            <p><strong>Total Price:</strong> ${{ number_format($booking->total_price, 2) }}</p>
        </div>
        <div class="col-md-6">
            <h4 class="text-theme-colored border-bottom pb-10">Pet & Franchise</h4>
            <p><strong>Pet Name:</strong> {{ $booking->pet->name }} ({{ $booking->pet->category->name ?? 'N/A' }})</p>
            <p><strong>Franchise:</strong> {{ $booking->franchise->name }}</p>
            <p><strong>Staff assigned:</strong> {{ $booking->staff->name ?? 'Not Assigned yet' }}</p>
            @if($booking->staff && $booking->staff->phone)
                <p><strong>Staff contact:</strong> {{ $booking->staff->phone }}</p>
            @endif
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-md-12">
            <h4 class="text-theme-colored border-bottom pb-10">Services</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->bookedServices as $bs)
                        <tr>
                            <td>{{ $bs->service->name }}</td>
                            <td>${{ number_format($bs->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($booking->user_notes)
        <div class="row mt-10">
            <div class="col-md-12">
                <h4 class="text-theme-colored border-bottom pb-10">Your Notes</h4>
                <p class="bg-light p-10 border-1px">{{ $booking->user_notes }}</p>
            </div>
        </div>
    @endif

    @if($booking->review)
        <div class="row mt-20">
            <div class="col-md-12">
                <h4 class="text-theme-colored border-bottom pb-10">Your Review</h4>
                <div class="review-box p-15 border-1px bg-light">
                    <div class="star-rating-display mb-10">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $booking->review->rating ? 'text-warning' : 'text-silver' }}"></i>
                        @endfor
                        <span class="ml-10">({{ $booking->review->rating }} / 5)</span>
                    </div>
                    <p class="italic mb-0">"{{ $booking->review->comment ?? 'No comment provided.' }}"</p>
                </div>
            </div>
        </div>
    @endif
</div>
