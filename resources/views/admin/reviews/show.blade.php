@extends('layouts.admin')

@section('title', 'Review Details')

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

        .premium-body {
            padding: 18px 20px;
        }

        .premium-title {
            margin: 0 0 4px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .premium-subtitle {
            margin: 0 0 12px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
        }

        .premium-avatar {
            width: 72px;
            height: 72px;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(255, 0, 0, 0.35);
            box-shadow:
                0 8px 18px rgba(0, 0, 0, 0.35),
                0 0 14px rgba(255, 0, 0, 0.18);
            background: #1f1f1f;
            margin: 0 auto 12px;
        }

        .premium-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .premium-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .premium-badge.published {
            color: #34d399;
        }

        .premium-badge.draft {
            color: #fbbf24;
        }

        .review-stars {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .review-stars i {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.2);
        }

        .review-stars i.filled {
            color: #fbbf24;
            text-shadow: 0 0 12px rgba(251, 191, 36, 0.4);
        }

        .rating-big {
            color: #fff;
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .meta-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .meta-list li {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 7px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 13px;
        }

        .meta-list li:last-child {
            border-bottom: none;
        }

        .meta-list .label {
            color: rgba(255, 255, 255, 0.45);
        }

        .meta-list .value {
            color: #fff;
            text-align: right;
            font-weight: 500;
        }

        .comment-box {
            margin-top: 12px;
            padding: 12px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            line-height: 1.5;
            font-style: italic;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row i {
            color: #ff4d5a;
            width: 20px;
            margin-top: 2px;
        }

        .info-row h6 {
            margin: 0;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .info-row small {
            color: rgba(255, 255, 255, 0.45);
        }

        .premium-actions {
            margin-top: 14px;
        }

        .premium-actions .btn {
            border-radius: 10px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }
    </style>

    <div class="row g-3 align-items-start">
        <div class="col-xl-4 col-lg-5">
            <div class="premium-panel mb-3">
                <div class="premium-body text-center">
                    <h5 class="premium-title">Review Summary</h5>
                    <p class="premium-subtitle">Customer feedback overview</p>

                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                        @endfor
                    </div>
                    <p class="rating-big">{{ number_format($review->rating, 1) }}</p>
                    <p class="premium-subtitle mb-2">Overall Rating</p>

                    <span class="premium-badge {{ $review->is_publish ? 'published' : 'draft' }} mb-2">
                        {{ $review->is_publish ? 'Published' : 'Not Published' }}
                    </span>

                    <ul class="meta-list text-start mt-2">
                        <li>
                            <span class="label">Review Date</span>
                            <span class="value">{{ $review->created_at->format('M d, Y h:i A') }}</span>
                        </li>
                        <li>
                            <span class="label">Booking</span>
                            <span class="value">#{{ $review->booking_id }}</span>
                        </li>
                    </ul>

                    <div class="comment-box text-start">
                        {{ $review->comment ?: 'No comment provided.' }}
                    </div>

                    <div class="premium-actions d-grid gap-2">
                        <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $review->is_publish ? 'secondary' : 'primary' }} w-100">
                                <i class="fas fa-sync me-1"></i>
                                {{ $review->is_publish ? 'Unpublish' : 'Publish' }} Review
                            </button>
                        </form>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-arrow-left me-1"></i> Back to Reviews
                        </a>
                    </div>
                </div>
            </div>

            <div class="premium-panel">
                <div class="premium-body">
                    <h5 class="premium-title">Customer Info</h5>
                    <p class="premium-subtitle">Who left this review</p>

                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="premium-avatar mb-0" style="width:56px;height:56px;margin:0;">
                            <img src="{{ $review->user->image ?? asset('assets/images/Logo_-_Black.png') }}"
                                alt="{{ $review->user->name ?? 'Customer' }}">
                        </div>
                        <div>
                            <h6 class="mb-0" style="color:#fff;font-weight:700;">{{ $review->user->name ?? 'N/A' }}</h6>
                            <small style="color:rgba(255,255,255,0.45);">Customer #{{ $review->user_id ?? 'N/A' }}</small>
                        </div>
                    </div>

                    <ul class="meta-list">
                        <li>
                            <span class="label">Email</span>
                            <span class="value">{{ $review->user->email ?? 'N/A' }}</span>
                        </li>
                        <li>
                            <span class="label">Phone</span>
                            <span class="value">{{ $review->user->phone ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="premium-panel mb-3">
                <div class="premium-body">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <div class="premium-avatar mb-0">
                                <img src="{{ $review->staff->image ?? asset('assets/images/Logo_-_Black.png') }}"
                                    alt="{{ $review->staff->name ?? 'Staff' }}">
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="premium-title mb-1">{{ $review->staff->name ?? 'N/A' }}</h5>
                            <p class="premium-subtitle mb-2">Staff reviewed for this booking</p>
                            <span class="premium-badge published">
                                Rating {{ number_format($review->rating, 1) }} / 5
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="premium-panel">
                <div class="premium-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <div>
                            <h5 class="premium-title mb-0">Booking Details</h5>
                            <p class="premium-subtitle mb-0">Linked grooming appointment</p>
                        </div>
                        <a href="{{ route('admin.bookings.show', $review->booking_id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> View Full Booking
                        </a>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="info-row">
                                <i class="fas fa-id-card"></i>
                                <div>
                                    <h6>#{{ $review->booking_id }}</h6>
                                    <small>Booking Reference</small>
                                </div>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-paw"></i>
                                <div>
                                    <h6>{{ $review->booking->pet->name ?? 'N/A' }}</h6>
                                    <small>Pet Name</small>
                                </div>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-concierge-bell"></i>
                                <div>
                                    <h6>
                                        @forelse($review->booking->bookedServices ?? [] as $bs)
                                            {{ $bs->service->name ?? 'N/A' }}{{ !$loop->last ? ', ' : '' }}
                                        @empty
                                            N/A
                                        @endforelse
                                    </h6>
                                    <small>Services Provided</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <h6>
                                        {{ $review->booking?->booking_date ? \Carbon\Carbon::parse($review->booking->booking_date)->format('M d, Y') : 'N/A' }}
                                    </h6>
                                    <small>
                                        @if($review->booking?->start_time && $review->booking?->end_time)
                                            {{ \Carbon\Carbon::parse($review->booking->start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($review->booking->end_time)->format('h:i A') }}
                                        @else
                                            Time N/A
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-wallet"></i>
                                <div>
                                    <h6>${{ number_format($review->booking->total_price ?? 0, 2) }}</h6>
                                    <small>Total Paid</small>
                                </div>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-circle-check"></i>
                                <div>
                                    <h6>{{ ucfirst($review->booking->status ?? 'N/A') }}</h6>
                                    <small>Booking Status</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
