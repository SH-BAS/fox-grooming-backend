@extends('layouts.admin')

@section('title', $user->name . ' | Customer')

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

        .premium-tabs {
            border-bottom: 1px solid rgba(255, 0, 0, 0.18);
            padding: 0 8px;
            gap: 8px;
        }

        .premium-tabs .nav-link {
            background: transparent !important;
            border: none !important;
            color: rgba(255, 255, 255, 0.55) !important;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 14px 4px;
            margin-right: 22px;
            position: relative;
        }

        .premium-tabs .nav-link:hover {
            color: #fff !important;
        }

        .premium-tabs .nav-link.active {
            color: #fff !important;
        }

        .premium-tabs .nav-link.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 2px;
            background: linear-gradient(90deg, #ff0000, #E63946, transparent);
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }

        .premium-section-title {
            color: #fff;
            font-weight: 700;
            margin-bottom: 4px;
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
        .premium-table tbody td strong {
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
            color: rgba(255, 255, 255, 0.45);
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
                                <img src="{{ $user->image }}" alt="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <h2 class="premium-title">{{ $user->name }}</h2>
                                <span class="premium-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="premium-meta mb-2">
                                <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                            </p>
                            <p class="premium-meta mb-0">
                                <i class="fas fa-phone me-1"></i>{{ $user->phone ?? 'No phone provided' }}
                            </p>
                        </div>
                        <div class="col-auto premium-actions d-flex gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-pencil-alt me-1"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="card-header border-0 pb-0 bg-transparent">
                    <ul class="nav nav-tabs premium-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#pets" role="tab">
                                Registered Pets ({{ $user->pets->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#bookings" role="tab">
                                Booking History ({{ $user->bookings->count() }})
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pets" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <div>
                                    <h5 class="premium-section-title">Customer Pets</h5>
                                    <p class="mb-0" style="color: rgba(255,255,255,0.5);">
                                        Manage all pets registered under this customer.
                                    </p>
                                </div>
                                <a href="{{ route('admin.pets.create', ['user_id' => $user->id, 'redirect_user_id' => $user->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i>Add Pet
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table premium-table table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th><strong>Pet Name</strong></th>
                                            <th><strong>Type</strong></th>
                                            <th><strong>Age</strong></th>
                                            <th><strong>Weight</strong></th>
                                            <th class="text-end"><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->pets as $pet)
                                            <tr>
                                                <td><strong style="color:#fff;">{{ $pet->name }}</strong></td>
                                                <td>{{ $pet->type->name ?? 'N/A' }}</td>
                                                <td>{{ $pet->age }} years</td>
                                                <td>{{ $pet->weight }} kg</td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('admin.pets.show', $pet->id) }}"
                                                            class="btn btn-info btn-xs sharp shadow"><i
                                                                class="fas fa-eye"></i></a>
                                                        <a href="{{ route('admin.pets.edit', ['pet' => $pet->id, 'redirect_user_id' => $user->id]) }}"
                                                            class="btn btn-primary btn-xs sharp shadow"><i
                                                                class="fas fa-pencil-alt"></i></a>
                                                        <form action="{{ route('admin.pets.destroy', $pet->id) }}" method="POST"
                                                            data-confirm="Delete this pet for {{ $user->name }}?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="redirect_user_id" value="{{ $user->id }}">
                                                            <button type="submit" class="btn btn-danger btn-xs sharp shadow">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="premium-empty">No pets registered yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="bookings" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table premium-table table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th><strong>Booking ID</strong></th>
                                            <th><strong>Service</strong></th>
                                            <th><strong>Date</strong></th>
                                            <th><strong>Status</strong></th>
                                            <th><strong>Total</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->bookings as $booking)
                                            <tr>
                                                <td style="color:#fff;">#{{ $booking->id }}</td>
                                                <td>{{ $booking->pet->category->name ?? 'Pet' }} Grooming</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge light badge-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td><strong style="color:#fff;">${{ number_format($booking->total_price, 2) }}</strong></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="premium-empty">No bookings found.</td>
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
