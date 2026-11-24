@extends('layouts.admin')

@section('content')
    <div class="row">

        <!-- Staff Statistics -->
        <div class="col-xl-6 col-md-6 col-sm-12">
            <div class="card cardT stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary-light me-3">
                            <i class="fas fa-calendar-check text-primary"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $staff->total_bookings_count }}</div>
                            <div class="stat-label">Total Bookings Assigned</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-sm-12">
            <div class="card cardT stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success-light me-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $staff->completed_bookings_count }}</div>
                            <div class="stat-label">Completed Bookings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{{ $staff->image }}" alt="{{ $staff->name }}" class="rounded-circle shadow"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center mb-1">
                                <h2 class="mb-0 text-primary me-3">{{ $staff->name }}</h2>
                                <span class="badge light badge-{{ $staff->is_active ? 'success' : 'danger' }}">
                                    {{ $staff->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="mb-2"><i class="fas fa-envelope me-2"></i>{{ $staff->email }}</p>
                            <p class="mb-0 text-muted"><i
                                    class="fas fa-id-badge me-2"></i>{{ $staff->role->name ?? 'Staff' }}</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.staff.index') }}"
                                class="ms-auto btn btn-primary btn-xs sharp shadow "><i class="fas fa-arrow-left"></i></a>
                            <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-primary btn-sm">Edit Staff
                                details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <style>
            .stat-card {
                border: 1px solid rgba(255, 255, 255, 0.05);
                transition: transform 0.3s ease;
                background-color: var(--bg-card);
                border-radius: 12px;
            }

            .stat-card:hover {
                transform: translateY(-3px);
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }

            .bg-primary-light {
                background: rgba(230, 57, 70, 0.1);
            }

            .bg-success-light {
                background: rgba(16, 185, 129, 0.1);
            }

            .stat-value {
                font-size: 24px;
                font-weight: 700;
                color: #fff;
                line-height: 1;
                margin-bottom: 2px;
            }

            .stat-label {
                font-size: 13px;
                color: var(--text-muted);
                font-weight: 500;
            }
        </style>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#jobs" role="tab">Completed Jobs
                                ({{ $staff->completed_bookings_count }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#reviews" role="tab">Customer Reviews
                                ({{ $staff->reviews->count() }})</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Jobs Tab -->
                        <div class="tab-pane fade show active" id="jobs" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th><strong>ID</strong></th>
                                            <th><strong>Customer</strong></th>
                                            <th><strong>Date</strong></th>
                                            <th><strong>Status</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($staff->bookings as $booking)
                                            <tr>
                                                <td>#{{ $booking->id }}</td>
                                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                                <td>{{ $booking->booking_date }}</td>
                                                <td>
                                                    <span
                                                        class="badge light badge-{{ $booking->status == 'completed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No jobs found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th><strong>Customer</strong></th>
                                            <th><strong>Rating</strong></th>
                                            <th><strong>Comment</strong></th>
                                            <th><strong>Date</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($staff->reviews as $review)
                                            <tr>
                                                <td>{{ $review->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                                        @endfor
                                                    </div>
                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit($review->comment, 50) }}</td>
                                                <td>{{ $review->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No reviews found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection