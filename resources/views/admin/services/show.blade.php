@extends('layouts.admin')

@section('title', ($service->name ?? 'Service') . ' | Service')

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

        .premium-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 22px 24px 0;
        }

        .premium-header h4 { margin: 0; color: #fff; font-weight: 700; }
        .premium-body { padding: 22px 24px 24px; }

        .premium-section-title {
            color: #fff;
            font-weight: 700;
            margin-bottom: 0;
        }

        .premium-title {
            margin: 0 0 8px;
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .premium-meta {
            margin: 0;
            color: rgba(255, 255, 255, 0.65);
            font-size: 14px;
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

        .premium-badge.active { color: #34d399; }
        .premium-badge.inactive { color: #fbbf24; }

        .premium-preview {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid rgba(255, 0, 0, 0.3);
            margin: 0 auto 16px;
            display: block;
        }

        .premium-preview-placeholder {
            width: 150px;
            height: 100px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.35);
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.35);
        }

        .premium-fixed-rate {
            padding: 18px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
        }

        .premium-fixed-rate .label {
            color: rgba(255, 255, 255, 0.55);
            font-size: 13px;
            margin-bottom: 6px;
        }

        .premium-fixed-rate .amount {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .premium-actions .btn {
            border-radius: 10px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .premium-table { color: #ffffff !important; }

        .premium-table thead th {
            background: rgba(0, 0, 0, 0.35) !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
            color: #ffffff !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .premium-table tbody td,
        .premium-table tbody td strong,
        .premium-table tbody td small {
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
            color: rgba(255, 255, 255, 0.45) !important;
            text-align: center;
            padding: 28px 12px;
        }

        .premium-footer {
            padding: 0 24px 22px;
            text-align: end;
        }
    </style>

    <div class="row g-4">
        <div class="col-xl-4 col-lg-4">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4><i class="fa fa-info-circle me-2"></i>Service Info</h4>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="premium-body text-center">
                    @if($service->image)
                        <img src="{{$service->image }}" class="premium-preview" alt="{{ $service->name }}">
                    @else
                        <div class="premium-preview-placeholder">
                            <i class="fa fa-image fa-2x"></i>
                        </div>
                    @endif
                    <h4 class="premium-title">{{ $service->name }}</h4>
                    <p class="premium-meta mb-2">{{ $service->category->name ?? 'N/A' }}</p>
                    <span class="premium-badge {{ $service->is_active ? 'active' : 'inactive' }} mb-3">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <div class="text-start mt-3">
                        <h6 class="premium-section-title" style="font-size:14px;">Description</h6>
                        <p class="premium-meta small mb-0">{{ $service->description ?? 'No description available.' }}</p>
                    </div>
                </div>
            </div>

            <div class="premium-panel mt-4">
                <div class="premium-header">
                    <h4><i class="fa fa-dollar-sign me-2"></i>Pricing Details</h4>
                </div>
                <div class="premium-body">
                    @if($service->pricing_type === 'sizeable')
                        <div class="table-responsive">
                            <table class="table table-sm premium-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Size</th>
                                        <th class="text-end">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($service->sizes as $sizePrice)
                                        <tr>
                                            <td>{{ $sizePrice->size->name ?? 'N/A' }}</td>
                                            <td class="text-end"><strong>${{ number_format($sizePrice->price, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @php $fixed = $service->sizes->first(); @endphp
                        <div class="premium-fixed-rate">
                            <div class="label">Fixed Rate</div>
                            <p class="amount">${{ number_format($fixed->price ?? 0, 2) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4 class="premium-section-title"><i class="fa fa-history me-2"></i>Booking History for {{ $service->name }}</h4>
                </div>
                <div class="premium-body">
                    <div class="table-responsive">
                        <table class="table premium-table mb-0">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Pet</th>
                                    <th>Staff</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($service->bookedServices as $booked)
                                    <tr>
                                        <td><strong>#{{ $booked->booking->id ?? 'N/A' }}</strong></td>
                                        <td>
                                            @if($booked->booking && $booked->booking->getRawOriginal('booking_date'))
                                                {{ \Carbon\Carbon::parse($booked->booking->booking_date)->format('M d, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span>{{ $booked->booking->user->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $booked->booking->pet->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($booked->booking && $booked->booking->staff)
                                                <div class="d-flex align-items-center">
                                                    <img src="https://placehold.co/30x30?text={{ strtoupper(substr($booked->booking->staff->name, 0, 1)) }}"
                                                        class="rounded-circle me-2" width="24" alt="Staff">
                                                    <span>{{ $booked->booking->staff->name }}</span>
                                                </div>
                                            @else
                                                <span class="premium-meta small">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($booked->booking)
                                                <a href="{{ route('admin.bookings.show', $booked->booking->id) }}"
                                                    class="btn btn-primary btn-xs sharp"><i class="fa fa-eye"></i></a>
                                            @else
                                                <span class="premium-meta small">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="premium-empty">No bookings found for this service.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="premium-footer premium-actions">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
