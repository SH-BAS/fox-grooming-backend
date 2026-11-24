@extends('layouts.admin')

@section('title', 'Pet Detail - ' . $pet->name)
@section('page-title', 'Pet Detail')

@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-4">
                            @if($pet->images->count() > 0)
                                <img src="{{ $pet->images->first()->image }}" alt="{{ $pet->name }}" class="rounded shadow"
                                    style="width: 100%; max-height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                    style="width: 100%; height: 200px;">
                                    <i class="fas fa-dog fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <h3 class="mb-1 text-primary">{{ $pet->name }}</h3>
                        <p class="text-muted mb-3">{{ $pet->type->name ?? 'N/A' }} ({{ $pet->category->name ?? 'N/A' }})</p>
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <span class="badge light badge-info">{{ ucfirst($pet->gender) }}</span>
                            <span class="badge light badge-{{ $pet->is_vaccinated ? 'success' : 'danger' }}">
                                {{ $pet->is_vaccinated ? 'Vaccinated' : 'Not Vaccinated' }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h4 class="card-title mb-3">Owner Information</h4>
                        <div class="d-flex align-items-center">
                            <img src="{{ $pet->user->image }}" alt="{{ $pet->user->name }}" class="rounded-circle me-3"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0">{{ $pet->user->name }}</h6>
                                <p class="text-muted mb-0 small">{{ $pet->user->email }}</p>
                            </div>
                            <a href="{{ route('admin.pets.index') }}" class="ms-auto btn btn-primary btn-xs sharp shadow"><i
                                    class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('admin.pets.edit', $pet->id) }}" class="btn btn-primary shadow btn-sm">Edit Pet
                            Details</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Physical Details</h4>
                            <div class="row mb-3">
                                <div class="col-sm-5 text-muted">Age</div>
                                <div class="col-sm-7 fw-bold">{{ $pet->age }} Years</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-5 text-muted">Weight</div>
                                <div class="col-sm-7 fw-bold">{{ $pet->weight }} kg</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-5 text-muted">Size Category</div>
                                <div class="col-sm-7 fw-bold">{{ $pet->size->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Medical & Notes</h4>
                            <div class="mb-3">
                                <span class="text-muted d-block mb-1">Notes:</span>
                                <p class="mb-0">{{ $pet->notes ?? 'No special notes.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Pet Gallery</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($pet->images as $image)
                            <div class="col-md-3 col-sm-4 col-6">
                                <a href="{{ $image->image }}" target="_blank">
                                    <div class="rounded shadow-sm overflow-hidden" style="height: 100px;">
                                        <img src="{{ $image->image }}" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted">
                                <i class="fas fa-images fa-2x mb-2 opacity-25"></i>
                                <p>No gallery images uploaded for this pet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Booking History for {{ $pet->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>ID</strong></th>
                                    <th><strong>Date</strong></th>
                                    <th><strong>Status</strong></th>
                                    <th><strong>Total</strong></th>
                                    <th class="text-end"><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pet->bookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td><strong>${{ number_format($booking->total_price, 2) }}</strong></td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                                class="btn btn-primary btn-xs sharp shadow"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No bookings found for this pet.</td>
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