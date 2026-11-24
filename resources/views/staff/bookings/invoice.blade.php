@extends('layouts.staff')

@section('page-title', 'Booking Invoice')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg  text-dark">
                <div class="card-header  d-flex justify-content-between align-items-center p-4"
                    style="background-color: rgb(44 50 55) !important;">
                    <h3 class="mb-0 "><i class="fa fa-file-invoice me-2"></i>Invoice Details</h3>
                    <div>
                        <button onclick="window.print()" class="btn btn-light btn-sm me-2"><i class="fa fa-print me-1"></i>
                            Print</button>
                        <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-outline-light btn-sm">Back
                            to Booking</a>
                    </div>
                </div>

                <div class="card-body p-5">
                    <!-- Header Info -->
                    <div class="row mb-5">
                        <div class="col-sm-6">
                            <h5 class="mb-3 text-uppercase text-muted small font-weight-bold">Billed To:</h5>
                            <h4 class="text-primary mb-1">{{ $booking->user->name ?? 'N/A' }}</h4>
                            <p class="mb-0 text-muted">{{ $booking->user->email ?? '-' }}</p>
                            <p class="mb-0 text-muted">{{ $booking->user->phone ?? '-' }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <h5 class="mb-3 text-uppercase text-muted small font-weight-bold">Invoice Info:</h5>
                            <h4 class="mb-1">#{{ $booking->id }}</h4>
                            <p class="mb-0 text-muted">Date: {{ $booking->booking_date }}</p>
                            <p class="mb-0 text-muted">Time:
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                            </p>
                            <p class="mb-0 text-muted">Status: <span
                                    class="badge badge-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">{{ strtoupper($booking->status) }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Pet Info -->
                    <div class="row mb-5 bg-light rounded p-3 mx-0">
                        <div class="col-12 d-flex align-items-center">
                            <div class="p-3 bg-white rounded-circle shadow-sm me-3">
                                <i class="fa fa-paw text-warning fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-muted">Pet: {{ $booking->pet->name ?? 'N/A' }}</h5>
                                <small class="text-muted">{{ $booking->pet->category->name ?? 'Pet' }} •
                                    {{ $booking->pet->age ?? '-' }} yrs • {{ $booking->pet->gender ?? '-' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Services Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class=" ">
                                <tr>
                                    <th>Service Description</th>
                                    <th>Size/Variant</th>
                                    <th class="text-end" style="width: 150px;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->bookedServices as $item)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold">{{ $item->service->name ?? 'Service' }}</span>
                                        </td>
                                        <td>{{ $item->size->name ?? 'Standard' }}</td>
                                        <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                    </tr>
                                @endforeach

                                <!-- Spacing Row -->
                                <tr style="border-bottom: 2px solid #dee2e6;"></tr>

                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Additional service charges</td>
                                    <td class="text-end fw-bold">
                                        ${{ number_format($booking->bookedServices->sum('price'), 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Base Charges</td>
                                    <td class="text-end fw-bold">
                                        ${{ number_format($booking->base_price, 2) }}</td>
                                </tr>
                                <tr>
                                    @php $taxRate = \App\Models\Tax::first()->tax ?? 0; @endphp
                                    <td colspan="2" class="text-end text-muted">Tax ({{ $taxRate }}%)</td>
                                    <td class="text-end text-muted">${{ number_format($booking->tax, 2) }}</td>
                                </tr>
                                <tr class=" mt-2" style="background-color: rgb(44 50 55) !important;">
                                    <td colspan="2" class="text-end h5 border-0  mb-0 py-3"><strong>Total
                                            Due</strong></td>
                                    <td class="text-end h5 border-0  mb-0 py-3">
                                        <strong>${{ number_format($booking->total_price, 2) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted small">Thank you for choosing Fox Grooming!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection