@extends('layouts.web')

@section('title', 'Booking Details | Fox Grooming')

@section('content')
    <section class="inner-header divider parallax layer-overlay overlay-dark-5"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <div class="container pt-70 pb-20">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title text-white">Booking Details: #{{ $booking->id }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="{{ route('web.profile.index') }}" class="list-group-item">My Profile</a>
                        <a href="{{ route('web.pets.index') }}" class="list-group-item">Pet Management</a>
                        <a href="{{ route('web.user.bookings.index') }}" class="list-group-item active">My Bookings</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="border-1px p-30">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <a href="{{ route('web.user.bookings.index') }}" class="btn btn-default btn-sm">Back to List</a>
                                </div>
                                <h3 class="text-theme-colored mt-0">Booking #{{ $booking->id }}</h3>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-theme-colored border-bottom pb-10">Booking Information</h4>
                                <p><strong>Status:</strong> <span class="label label-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">{{ strtoupper($booking->status) }}</span></p>
                                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>
                                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p>
                                <p><strong>Total Price:</strong> ${{ number_format($booking->total_price, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-theme-colored border-bottom pb-10">Pet & Franchise</h4>
                                <p><strong>Pet Name:</strong> <a href="{{ route('web.pets.show', $booking->pet_id) }}" class="text-theme-colored">{{ $booking->pet->name }}</a> ({{ $booking->pet->category->name ?? 'N/A' }})</p>
                                <p><strong>Franchise:</strong> {{ $booking->franchise->name }}</p>
                                <p><strong>Staff assigned:</strong> {{ $booking->staff->name ?? 'Not Assigned yet' }}</p>
                                @if($booking->staff && $booking->staff->phone)
                                    <p><strong>Staff contact:</strong> {{ $booking->staff->phone }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-30">
                            <div class="col-md-12">
                                <h4 class="text-theme-colored border-bottom pb-10">Services Breakdown</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Service</th>
                                                <th class="text-center">Time</th>
                                                <th class="text-right">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($booking->bookedServices as $bs)
                                                <tr>
                                                    <td>{{ $bs->service->name }}</td>
                                                    <td class="text-center">{{ $bs->minutes }} mins</td>
                                                    <td class="text-right">${{ number_format($bs->price, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-right">Subtotal</th>
                                                <th class="text-right">${{ number_format($booking->base_price + $booking->bookedServices->sum(function($bs){ return $bs->price * $bs->quantity; }), 2) }}</th>
                                            </tr>
                                            @if($booking->tax > 0)
                                            <tr>
                                                <th colspan="2" class="text-right">Tax</th>
                                                <th class="text-right">${{ number_format($booking->tax, 2) }}</th>
                                            </tr>
                                            @endif
                                            @if($booking->out_of_area_fee > 0)
                                            <tr>
                                                <th colspan="2" class="text-right">Out of Area Fee</th>
                                                <th class="text-right">${{ number_format($booking->out_of_area_fee, 2) }}</th>
                                            </tr>
                                            @endif
                                            <tr class="bg-light">
                                                <th colspan="2" class="text-right">Total Price</th>
                                                <th class="text-right text-theme-colored"><h4>${{ number_format($booking->total_price, 2) }}</h4></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if($booking->user_notes)
                            <div class="row mt-20">
                                <div class="col-md-12">
                                    <h4 class="text-theme-colored border-bottom pb-10">User Notes</h4>
                                    <div class="bg-light p-15 border-1px">
                                        {{ $booking->user_notes }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($booking->termAcceptance)
                            <div class="row mt-20">
                                <div class="col-md-12">
                                    <h4 class="text-theme-colored border-bottom pb-10">Terms Acceptance</h4>
                                    <div class="bg-light p-15 border-1px">
                                        <p><strong>Agreement:</strong> {{ $booking->termAcceptance->cmsPage->title ?? 'E-Signature Agreement' }}</p>
                                        <p><strong>Accepted:</strong> {{ $booking->termAcceptance->accepted ? 'Yes' : 'No' }}</p>
                                        <p><strong>Accepted At:</strong> {{ $booking->termAcceptance->created_at->format('M d, Y h:i A') }}</p>
                                        @if($booking->termAcceptance->image)
                                            <p class="mb-10"><strong>Signature:</strong></p>
                                            <img src="{{ $booking->termAcceptance->image }}" alt="Booking signature" class="img-responsive border-1px" style="max-width: 320px; background: #fff;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($booking->review)
                            <div class="row mt-30">
                                <div class="col-md-12">
                                    <h4 class="text-theme-colored border-bottom pb-10">Your Review</h4>
                                    <div class="p-20 bg-light border-1px">
                                        <div class="rating-stars mb-10">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $booking->review->rating ? 'text-warning' : 'text-silver' }}" style="font-size: 20px;"></i>
                                            @endfor
                                            <span class="ml-10">({{ $booking->review->rating }} / 5)</span>
                                        </div>
                                        <p class="mb-0"><strong>Comment:</strong> <em>"{{ $booking->review->comment ?? 'No comment provided.' }}"</em></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if($booking->status == 'completed')
                            <div class="row mt-30">
                                <div class="col-md-12 text-center">
                                    <p>You haven't reviewed this booking yet.</p>
                                    <button class="btn btn-theme-colored btn-sm btn-review" 
                                        data-toggle="modal" 
                                        data-target="#reviewModal" 
                                        data-action="{{ route('web.user.bookings.review', $booking->id) }}">
                                        Write a Review
                                    </button>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Include the review modal here since we might want to review from the show page too --}}
    @include('profile.bookings.partials.review_modal_fragment')

@endsection

@section('styles')
<style>
    .star-review {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .star-review input {
        display: none;
    }
    .star-review label {
        font-size: 30px;
        color: #ccc;
        cursor: pointer;
        transition: color 0.1s;
        margin: 0 5px;
        display: inline-block;
    }
    .star-review label:hover,
    .star-review label:hover ~ label,
    .star-review input:checked ~ label {
        color: #f39c12;
    }
    .star-review label i {
        pointer-events: none;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.btn-review').on('click', function() {
        var action = $(this).data('action');
        $('#reviewForm').attr('action', action);
        $('#reviewForm')[0].reset();
    });
});
</script>
@endsection
