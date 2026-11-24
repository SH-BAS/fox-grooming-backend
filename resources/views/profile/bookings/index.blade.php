@extends('layouts.web')

@section('title', 'My Bookings | Fox Grooming')

@section('content')
    <section class="inner-header divider parallax layer-overlay overlay-dark-5"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <div class="container pt-70 pb-20">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title text-white">My Bookings</h2>
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
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#ongoing" data-toggle="tab">Ongoing Bookings</a></li>
                            <li><a href="#past" data-toggle="tab">Past Bookings</a></li>
                        </ul>

                        <div class="tab-content mt-20">
                            <div class="tab-pane fade in active" id="ongoing">
                                @forelse($ongoingBookings as $booking)
                                    <div class="booking-item border-1px p-20 mb-20">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img src="{{ $booking->pet->profile_image }}" alt="{{ $booking->pet->name }}"
                                                    class="img-thumbnail" style="width: 100%;">
                                            </div>
                                            <div class="col-md-7">
                                                <h4 class="mt-0 text-theme-colored">Booking #{{ $booking->id }} -
                                                    {{ $booking->pet->name }}</h4>
                                                <p><strong>Franchise:</strong> {{ $booking->franchise->name }}</p>
                                                <p><strong>Date & Time:</strong>
                                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }} at
                                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</p>
                                                <p><strong>Services:</strong>
                                                    @foreach($booking->bookedServices as $bs)
                                                        <span class="label label-info">{{ $bs->service->name }}</span>
                                                    @endforeach
                                                </p>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <span class="label label-warning">{{ strtoupper($booking->status) }}</span>
                                                <h4 class="text-theme-colored mt-10">
                                                    ${{ number_format($booking->total_price, 2) }}</h4>
                                                <a href="{{ route('web.user.bookings.show', $booking->id) }}" class="btn btn-default btn-sm mt-5">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-30">
                                        <p>No ongoing bookings. <a href="{{ route('web.book.franchise') }}"
                                                class="text-theme-colored">Book a service now!</a></p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="tab-pane fade" id="past">
                                @forelse($pastBookings as $booking)
                                    <div class="booking-item border-1px p-20 mb-20 bg-light">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img src="{{ $booking->pet->profile_image }}" alt="{{ $booking->pet->name }}"
                                                    class="img-thumbnail" style="width: 100%;">
                                            </div>
                                            <div class="col-md-7">
                                                <h4 class="mt-0 text-muted">Booking #{{ $booking->id }} -
                                                    {{ $booking->pet->name }}</h4>
                                                <p><strong>Franchise:</strong> {{ $booking->franchise->name }}</p>
                                                <p><strong>Date:</strong>
                                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>
                                                <p><strong>Status:</strong> <span
                                                        class="text-{{ $booking->status == 'completed' ? 'success' : 'danger' }}">{{ strtoupper($booking->status) }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <h4 class="text-muted mt-10">${{ number_format($booking->total_price, 2) }}</h4>
                                                @if($booking->status == 'completed' && !$booking->is_reviewed)
                                                    <button class="btn btn-theme-colored btn-sm mt-10 btn-review" 
                                                        data-toggle="modal" 
                                                        data-target="#reviewModal" 
                                                        data-action="{{ route('web.user.bookings.review', $booking->id) }}">
                                                        Write a Review
                                                    </button>
                                                @elseif($booking->is_reviewed)
                                                    <span class="label label-success mt-10">Reviewed</span>
                                                @endif
                                                <div class="mt-10">
                                                    <a href="{{ route('web.user.bookings.show', $booking->id) }}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-30">
                                        <p>No past bookings found.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
@include('profile.bookings.partials.review_modal_fragment')
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