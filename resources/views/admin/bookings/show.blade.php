@extends('layouts.admin')

@section('content')
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card cardT" data-bs-toggle="modal" data-bs-target="#ContactUserModal">
                <div class="card-body text-center">
                    <div class="bookingTopCards">
                        <div class="bookingTopCardIcon bg-danger">
                            <i class="fa-solid fa-circle-user text-white"></i>
                        </div>
                        <h4 class="mb-0 mt-3">Contact User</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card cardT" data-bs-toggle="modal" data-bs-target="#ViewMapModal">
                <div class="card-body text-center">
                    <div class="bookingTopCards">
                        <div class="bookingTopCardIcon bg-danger">
                            <i class="fa fa-map-marker text-white"></i>
                        </div>
                        <h4 class="mb-0 mt-3">View Map</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.bookings.schedule') }}">
                <div class="card cardT">
                    <div class="card-body text-center">
                        <div class="bookingTopCards">
                            <div class="bookingTopCardIcon bg-danger">
                                <i class="fa fa-calendar text-white"></i>
                            </div>
                            <h4 class="mb-0 mt-3">Scheduler</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-8">
            <a href="{{url()->previous() }}" class="ms-auto btn btn-primary btn-xs sharp shadow mb-3 "><i
                    class="fas fa-arrow-left"></i></a>
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header  border-0 pb-0">
                            <h4 class="card-title text-primary"><i class="fa fa-user me-2"></i>Customer Info</h4>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $booking->user->image }}" class="rounded-circle mb-3 mx-auto shadow-sm" width="80"
                                height="80" style="object-fit: cover;" alt="User">
                            <h5 class="mb-1">{{ $booking->user->name ?? 'N/A' }}</h5>
                            <p class="text-muted mb-3">
                                {{ $booking->user->email ?? '-' }}<br>{{ $booking->user->phone ?? '-' }}
                            </p>
                            <a href="{{ route('admin.users.show', $booking->user->id ?? 0) }}"
                                class="btn btn-outline-primary btn-xs rounded-pill px-3">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header  border-0 pb-0">
                            <h4 class="card-title text-warning"><i class="fa fa-paw me-2"></i>Pet Info</h4>
                        </div>
                        @if (isset($booking->pet))
                            <div class="card-body text-center">
                                <img src="{{ $booking->pet->profile_image }}" class="rounded-circle mb-3 mx-auto" style="width: 80px; height: 80px; object-fit: cover;"
                                    alt="Pet">
                                <h5 class="mb-1">{{ $booking->pet->name ?? 'N/A' }}</h5>
                                <p class="text-muted mb-3">{{ $booking->pet->category->name ?? 'Pet' }} •
                                    {{ $booking->pet->age ?? '-' }} yrs • {{ $booking->pet->gender ?? '-' }}
                                </p>
                                <a href="{{ route('admin.pets.show', $booking->pet->id ?? 0) }}"
                                    class="btn btn-outline-warning btn-xs rounded-pill px-3">View Pet</a>
                            </div>
                        @else
                            <div class="card-body text-center">
                                <p class="text-muted mb-3">No Pet Found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header  text-center">
                    <h4 class="card-title">Booking Details & Services</h4>
                    <button type="button" class="btn btn-primary btn-xs sharp shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#editBookingDateTimeModal">
                        <i class="fa fa-edit"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="p-3 bg-danger rounded-circle me-3 d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fa fa-calendar text-white fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-muted small text-uppercase font-weight-bold">Date & Day</p>
                                    <!-- call helper function -->
                                    <h5 class="mb-0" id="display_booking_date">
                                        {{$booking->booking_date}} - {{date('l', strtotime($booking->booking_date))}}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="p-3 bg-danger rounded-circle me-3 d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fa fa-clock text-white fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-muted small text-uppercase font-weight-bold">Time</p>
                                    <h5 class="mb-0" id="display_booking_time">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Current Tasks</h5>
                        <a href="{{ route('admin.bookings.invoice', $booking->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-file-invoice me-2"></i>Invoice
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Service Item</th>
                                    <th>Size/Variant</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->bookedServices as $item)
                                    <tr>
                                        <td>{{ $item->service->name ?? 'Service' }}</td>
                                        <td>{{ $item->size->name ?? 'Standard' }}</td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-primary btn-xs sharp me-1 edit-service-btn"
                                                    data-id="{{ $item->id }}" data-service-id="{{ $item->service_id }}"
                                                    data-category-id="{{ $item->service->category_id ?? '' }}"
                                                    data-size-id="{{ $item->size_id ?? '' }}"
                                                    data-service-name="{{ $item->service->name ?? 'Service' }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.bookings.deleteService', $item->id) }}"
                                                    method="POST"
                                                    data-confirm="Are you sure you want to delete this service?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs sharp"><i
                                                            class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr class="bg-primary-light">
                                    <td colspan="2" class="text-end text-primary"><strong>Grand Total</strong></td>
                                    <td colspan="2" class="text-end text-primary">
                                        <h4 class="mb-0 font-weight-bold">${{ number_format($booking->total_price, 2) }}
                                        </h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer -light border-top border-secondary py-4">
                    <h5 class="mb-3 text-primary"><i class="fa fa-plus-circle me-2"></i>Add Additional Service</h5>
                    <form action="{{ route('admin.bookings.addService', $booking->id) }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-3">
                                <select id="temp_category_id" class="form-control  border-secondary " required>
                                    <option value="">-- Selecet Category --</option>
                                    @if (isset($booking->pet->category))
                                        <option value="{{ $booking->pet->category->id }}">
                                            {{ $booking->pet->category->name }}
                                        </option>
                                    @else
                                        <option value="">pet deleted from users side</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="temp_service_id" name="service_id" class="form-control  border-secondary "
                                    required disabled>
                                    <option value="">-- Choose Service --</option>
                                </select>
                            </div>
                            <div id="temp_size_wrapper" class="col-md-3" style="display:none;">
                                <input type="hidden" name="size_id" id="temp_size_id_hidden" value="">
                                <select id="temp_size_id" class="form-control border-secondary" disabled
                                    style="pointer-events: none; background-color: #e9ecef;">
                                    <option value="">-- Size --</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text  border-secondary ">$</span>
                                    <input type="number" id="temp_price" name="price"
                                        class="form-control  border-secondary " step="0.01" placeholder="0.00" required
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100 h-100 shadow-sm"><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4">
            <div class="card">
                <div class="card-header  ">
                    <h4 class="card-title  mb-0">Booking Action #{{ $booking->id }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted mb-1">Current Status</label>
                        <div>
                            <span
                                class="badge badge-lg badge-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }} w-100 p-3">{{ strtoupper($booking->status) }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted mb-1">Assigned Staff</label>
                        <div class="d-flex align-items-center p-3 border rounded ">
                            <div class="me-4"> <!-- Increased margin -->
                                <img src="{{ $booking->staff ? $booking->staff->image : 'https://placehold.co/50x50?text=?' }}"
                                    class="rounded-circle shadow-sm" width="50" height="50" style="object-fit: cover;"
                                    alt="Staff">
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold">{{ $booking->staff->name ?? 'Unassigned' }}</h6>
                                <small class="text-muted">{{ $booking->staff->role->name ?? 'Staff' }}</small>
                            </div>
                        </div>
                    </div>
                    <!-- Assign Staff -->
                    <form action="{{ route('admin.bookings.assignStaff', $booking->id) }}" method="POST" class="mb-5">
                        @csrf
                        @method('PUT')
                        <label class="form-label -50 small text-uppercase font-weight-bold">Crew Assign</label>
                        <div class="form-group">
                            <select name="staff_id" class="form-control  border-secondary  mb-3" required>
                                <option value="">Choose Staff...</option>
                                @foreach($staff as $s)
                                    <option value="{{ $s->id }}" {{ $booking->staff_id == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary w-100 shadow" type="submit">Update Assignment</button>
                        </div>
                    </form>
                    <!-- Update Status -->
                    <form id="statusUpdateForm" action="{{ route('admin.bookings.updateStatus', $booking->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="payment_method" id="payment_method_hidden">
                        <input type="hidden" name="cancel_note" id="cancel_note_hidden">
                        <label class="form-label -50 small text-uppercase font-weight-bold">Update Booking
                            Status</label>
                        @if(!$booking->staff_id)
                            <div class="alert alert-warning p-2 small mb-2 border-0 shadow-sm">
                                <i class="fa fa-exclamation-circle me-1"></i> Staff assignment required.
                            </div>
                        @endif
                        <div class="form-group">
                            <select id="statusSelect" name="status" class="form-control  border-secondary  mb-3" {{ !$booking->staff_id ? 'disabled' : '' }}>
                                @foreach(['pending', 'assigned', 'ontheway', 'arrived', 'started', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $booking->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary w-100 shadow-sm" type="submit" {{ !$booking->staff_id ? 'disabled' : '' }}>Change Status</button>
                        </div>
                    </form>
                    <hr class="my-4 border-light opacity-10">
                    <!-- Document Upload -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Document / Image</strong></label>
                        @if($booking->document)
                            <div class="mb-3">
                                <a href="{{ asset('storage/' . $booking->document) }}" target="_blank"
                                    class="btn btn-outline-info btn-sm w-100">
                                    <i class="fa fa-eye me-2"></i>View Document
                                </a>
                            </div>
                        @endif
                        <form action="{{ route('admin.bookings.uploadDocument', $booking->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" name="document" class="form-control" required>
                                <button type="submit" class="btn btn-secondary shadow-none">Upload</button>
                            </div>
                            <small class="text-muted">Allowed: JPG, PNG, PDF, DOC (Max 2MB)</small>
                        </form>
                    </div>
                </div>
                <div class="card-footer text-center border-top">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary w-100">Back to All Bookings</a>
                </div>
            </div>
        </div>
        <!-- Booking Logs Card -->
        <div class="col-xl-6 col-lg-6">
            <div class="card mt-4">
                <div class="card-header border-bottom ">
                    <h4 class="card-title text-primary m-0"><i class="fa fa-history me-2"></i>Booking Timeline</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm text-center">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                    <th>Updated By</th>
                                </tr>
                            </thead>
                            @php
                                $log = $booking->logs->first();

                                $statuses = [
                                    ['key' => 'assigned_at', 'label' => 'Assigned', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['key' => 'ontheway_at', 'label' => 'On The Way', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                    ['key' => 'arrived_at', 'label' => 'Arrived at Pickup', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                                    ['key' => 'started_at', 'label' => 'Started', 'icon' => 'M5 13l4 4L19 7'],
                                    ['key' => 'completed_at', 'label' => 'Completed', 'icon' => 'M5 13l4 4L19 7'],
                                    ['key' => 'cancelled_at', 'label' => 'Cancelled', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ];
                            @endphp
                            <tbody>
                                @php $hasData = false; @endphp
                                @foreach($booking->logs->sortByDesc('id') as $history)
                                    @foreach($statuses as $status)
                                        @if($history->{$status['key']})
                                            @php $hasData = true; @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <svg width="18" height="18" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="{{ $status['icon'] }}"></path>
                                                        </svg>
                                                        <span class="badge badge-xs light badge-secondary">
                                                            {{ $status['label'] }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($history->{$status['key']})->format('M d, Y H:i:s') }}
                                                </td>
                                                <td>
                                                    {{ ucfirst($history->updated_by_type ?? 'Staff') }} (
                                                    {{ $history->updatedBy()->name ?? '-' }})
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach

                                @if(!$hasData)
                                    <tr>
                                        <td colspan="3" class="text-muted py-3">
                                            No timeline events recorded yet.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- User Notes Card -->
        <div class="col-xl-6 col-lg-6">
            <div class="card mt-4">
                <div class="card-header border-bottom ">
                    <h4 class="card-title text-primary m-0"><i class="fa fa-sticky-note me-2"></i>User Notes</h4>
                </div>
                <div class="card-body p-4 -light">
                    @if($booking->user_notes)
                        <p class="mb-0  font-italic">"{{ $booking->user_notes }}"</p>
                    @else
                        <p class="mb-0 text-muted">No special notes or instructions provided for this booking.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- user bookings history -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card mt-4">
                <div class="card-header border-bottom ">
                    <h4 class="card-title text-primary m-0"><i class="fa fa-history me-2"></i>User Bookings History</h4>
                </div>
                <div class="card-body p-4 -light">
                    @if($booking->user->bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Staff</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->user->bookings as $userBooking)
                                        <tr>
                                            <td>#{{ $userBooking->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($userBooking->booking_date)->format('M d, Y') }}</td>
                                            <td>
                                                @if($userBooking->staff)
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://placehold.co/30x30?text={{ substr($userBooking->staff->name ?? '?', 0, 1) }}"
                                                            class="rounded-circle me-2" width="24" alt="Staff">
                                                        <span>{{ $userBooking->staff->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-xs light badge-{{ $userBooking->status == 'completed' ? 'success' : ($userBooking->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ strtoupper($userBooking->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.bookings.show', $userBooking->id) }}"
                                                    class="btn btn-primary btn-xs sharp"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-0 text-muted">No bookings found for this user.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card mt-4">
                <div class="card-header border-bottom ">
                    <h4 class="card-title text-primary m-0"><i class="fa fa-star me-2"></i>Reviews</h4>
                </div>
                <div class="card-body p-4 -light">
                    @if(isset($booking->review) && $booking->review->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>user</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>publish</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <!-- <td>#{{$booking->review->id }}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($booking->review->booking_date)->format('M d, Y') }}</td> -->
                                        <td>

                                            <div class="d-flex align-items-center">
                                                <img src="https://placehold.co/30x30?text={{ substr($booking->review->user->name ?? '?', 0, 1) }}"
                                                    class="rounded-circle me-2" width="24" alt="Staff">
                                                <span>{{ $booking->review->user->name }}</span>
                                            </div>

                                        </td>
                                        <td>
                                            @for ($i = 1; $i <= $booking->review->rating; $i++)
                                                <i class="fa fa-star text-warning"></i>
                                            @endfor
                                        </td>
                                        <td class="text-center">
                                            {{ $booking->review->comment }}
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.reviews.update', $booking->review->id) }}"
                                                method="POST" class="me-1">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn {{ $booking->review->is_publish ? 'btn-danger' : 'btn-success' }} shadow btn-xs sharp"
                                                    title="Toggle Status">
                                                    <i class="fa fa-sync">
                                                        {{ $booking->review->is_publish ? 'Unpublish' : 'Publish' }}</i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.reviews.show', $booking->review->id) }}"
                                                class="btn btn-primary btn-xs sharp"><i class="fa fa-eye"></i></a>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-0 text-muted">No bookings found for this user.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                const petSizeId = @json(optional($booking->pet)->size_id);

                function formatPrice(price) {
                    const value = parseFloat(price);
                    if (isNaN(value)) return '';
                    return Number.isInteger(value) ? String(value) : value.toFixed(2);
                }

                function applySelectedSizePrice($sizeSelect) {
                    const $selected = $sizeSelect.find(':selected');
                    const price = $selected.data('price');
                    const sizeId = $selected.val() || '';
                    $('#temp_size_id_hidden').val(sizeId);

                    if (price !== undefined && price !== null && sizeId) {
                        $('#temp_price').val(formatPrice(price));
                    } else {
                        $('#temp_price').val('');
                    }
                }

                // Auto-trigger category change for Add Service form
                if ($('#temp_category_id').val()) {
                    $('#temp_category_id').trigger('change');
                }

                // Add-on Category -> Service Filtering
                $('#temp_category_id').on('change', function () {
                    const categoryId = $(this).val();
                    const $serviceSelect = $('#temp_service_id');
                    const $sizeWrapper = $('#temp_size_wrapper');
                    const $priceInput = $('#temp_price');
                    const $sizeSelect = $('#temp_size_id');

                    $serviceSelect.empty().append('<option value="">-- Choose Service --</option>').prop('disabled', true);
                    $sizeWrapper.hide();
                    $sizeSelect.empty().append('<option value="">-- Size --</option>').prop('disabled', true);
                    $('#temp_size_id_hidden').val('');
                    $priceInput.val('');

                    if (!categoryId) return;

                    $.ajax({
                        url: `{{ url('admin/categories') }}/${categoryId}/services`,
                        method: 'GET',
                        success: function (services) {
                            if (services.length > 0) {
                                services.forEach(service => {
                                    $serviceSelect.append(`<option value="${service.id}">${service.name}</option>`);
                                });
                                $serviceSelect.prop('disabled', false);
                            } else {
                                $serviceSelect.append('<option value="">No services found</option>');
                            }
                        }
                    });
                });

                // Service Details Fetch -> auto-select size & price (size remains read-only)
                $('#temp_service_id').on('change', function () {
                    const serviceId = $(this).val();
                    const $sizeWrapper = $('#temp_size_wrapper');
                    const $sizeSelect = $('#temp_size_id');
                    const $priceInput = $('#temp_price');

                    $sizeWrapper.hide();
                    $priceInput.val('');
                    $sizeSelect.empty().append('<option value="">-- Size --</option>').prop('disabled', true);
                    $('#temp_size_id_hidden').val('');

                    if (!serviceId) return;

                    $.ajax({
                        url: `{{ url('admin/services') }}/${serviceId}/details`,
                        method: 'GET',
                        success: function (service) {
                            if (service.pricing_type === 'fixable') {
                                const fixedPrice = (service.sizes || []).find(s => s.size_id === null);
                                if (fixedPrice) {
                                    $priceInput.val(formatPrice(fixedPrice.price));
                                }
                                return;
                            }

                            if (service.pricing_type === 'sizeable') {
                                const sizeOptions = (service.sizes || []).filter(sz => sz.size);
                                $sizeSelect.empty().append('<option value="">-- Size --</option>');

                                sizeOptions.forEach(sz => {
                                    $sizeSelect.append(
                                        `<option value="${sz.size.id}" data-price="${sz.price}">${sz.size.name} ($${formatPrice(sz.price)})</option>`
                                    );
                                });

                                if (sizeOptions.length) {
                                    $sizeWrapper.show();

                                    // Prefer pet size for this category; otherwise first available size
                                    const hasPetSize = petSizeId && $sizeSelect.find(`option[value="${petSizeId}"]`).length;
                                    const selectedSizeId = hasPetSize ? String(petSizeId) : String(sizeOptions[0].size.id);
                                    $sizeSelect.val(selectedSizeId).prop('disabled', true);
                                    applySelectedSizePrice($sizeSelect);
                                }
                            }
                        }
                    });
                });

                // Size is read-only; price still syncs if value is set programmatically
                $('#temp_size_id').on('change', function () {
                    applySelectedSizePrice($(this));
                });

                // Status Update Interception
                $('#statusUpdateForm').on('submit', function (e) {
                    const status = $('#statusSelect').val();

                    if (status === 'completed') {
                        e.preventDefault();
                        $('#paymentModal').modal('show');
                    } else if (status === 'cancelled') {
                        e.preventDefault();
                        $('#cancelModal').modal('show');
                    }
                });

                $('#confirmPaymentBtn').on('click', function () {
                    const pm = $('input[name="payment_method_modal"]:checked').val();
                    $('#payment_method_hidden').val(pm);
                    $('#statusUpdateForm').off('submit').submit();
                });

                $('#confirmCancelBtn').on('click', function () {
                    const note = $('#cancel_note_modal').val();
                    if (!note.trim()) {
                        alert('Please enter a cancellation reason.');
                        return;
                    }
                    $('#cancel_note_hidden').val(note);
                    $('#statusUpdateForm').off('submit').submit();
                });

                // Edit Service Modal Population
                $('.edit-service-btn').on('click', function () {
                    const id = $(this).attr('data-id');
                    const categoryId = String($(this).attr('data-category-id') || '');
                    const serviceId = String($(this).attr('data-service-id') || '');
                    const sizeId = String($(this).attr('data-size-id') || '');
                    const serviceName = $(this).attr('data-service-name') || 'Service';

                    $('#edit_service_name_title').text(serviceName);
                    $('#editServiceForm').attr('action', `{{ url('admin/bookings/services') }}/${id}`);

                    // Reset fields first
                    $('#edit_service_id').empty().append('<option value="">-- Choose Service --</option>').prop('disabled', true);
                    $('#edit_size_wrapper').hide();
                    $('#edit_size_id').empty().append('<option value="">-- Choose Size --</option>').prop('required', false);

                    const petCategoryId = @json(optional($booking->pet->category)->id);
                    const resolvedCategoryId = categoryId || (petCategoryId ? String(petCategoryId) : '');

                    if (resolvedCategoryId && $('#edit_category_id option[value="' + resolvedCategoryId + '"]').length) {
                        $('#edit_category_id').val(resolvedCategoryId);
                    }

                    loadEditServices(resolvedCategoryId, serviceId, sizeId);
                    $('#editServiceModal').modal('show');
                });

                function loadEditServices(categoryId, selectedServiceId = '', selectedSizeId = '') {
                    const $serviceSelect = $('#edit_service_id');
                    const $sizeWrapper = $('#edit_size_wrapper');
                    const $sizeSelect = $('#edit_size_id');

                    $serviceSelect.empty().append('<option value="">Loading services...</option>').prop('disabled', true);
                    $sizeWrapper.hide();
                    $sizeSelect.empty().append('<option value="">-- Choose Size --</option>').prop('required', false);

                    if (!categoryId) {
                        $serviceSelect.empty().append('<option value="">-- Choose Category --</option>');
                        return;
                    }

                    $.ajax({
                        url: `{{ url('admin/categories') }}/${categoryId}/services`,
                        method: 'GET',
                        success: function (services) {
                            $serviceSelect.empty().append('<option value="">-- Choose Service --</option>');

                            if (!services.length) {
                                $serviceSelect.append('<option value="">No services found</option>');
                                return;
                            }

                            services.forEach(service => {
                                $serviceSelect.append(`<option value="${service.id}">${service.name}</option>`);
                            });

                            // Keep current service visible even if inactive / missing from list
                            if (selectedServiceId && !$serviceSelect.find(`option[value="${selectedServiceId}"]`).length) {
                                const currentName = $('#edit_service_name_title').text() || 'Current Service';
                                $serviceSelect.append(`<option value="${selectedServiceId}">${currentName}</option>`);
                            }

                            $serviceSelect.prop('disabled', false);

                            if (selectedServiceId) {
                                $serviceSelect.val(selectedServiceId);
                                loadEditSizes(selectedServiceId, selectedSizeId);
                            }
                        },
                        error: function () {
                            $serviceSelect.empty().append('<option value="">Failed to load services</option>');
                        }
                    });
                }

                function loadEditSizes(serviceId, selectedSizeId = '') {
                    const $sizeWrapper = $('#edit_size_wrapper');
                    const $sizeSelect = $('#edit_size_id');

                    $sizeWrapper.hide();
                    $sizeSelect.empty().append('<option value="">-- Choose Size --</option>').prop('required', false);

                    if (!serviceId) return;

                    $.ajax({
                        url: `{{ url('admin/services') }}/${serviceId}/details`,
                        method: 'GET',
                        success: function (service) {
                            if (service.pricing_type !== 'sizeable') {
                                return;
                            }

                            // Only sizes belonging to this service (already scoped by its category)
                            const sizeOptions = (service.sizes || []).filter(sz => sz.size);

                            $sizeSelect.empty().append('<option value="">-- Choose Size --</option>');
                            sizeOptions.forEach(sz => {
                                $sizeSelect.append(
                                    `<option value="${sz.size.id}" data-price="${sz.price}">${sz.size.name} ($${sz.price})</option>`
                                );
                            });

                            if (sizeOptions.length) {
                                $sizeWrapper.show();
                                $sizeSelect.prop('required', true);
                                if (selectedSizeId) {
                                    $sizeSelect.val(String(selectedSizeId));
                                }
                            }
                        }
                    });
                }

                // Booking Date/Time AJAX update
                $('#btnUpdateDateTime').on('click', function () {
                    const date = $('#edit_booking_date').val();
                    const time = $('#edit_booking_time').val();

                    if (!date || !time) {
                        alert('Please provide both date and time.');
                        return;
                    }

                    const $btn = $(this);
                    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

                    $.ajax({
                        url: `{{ route('admin.bookings.updateDate', $booking->id) }}`,
                        method: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}',
                            booking_date: date,
                            start_time: time
                        },
                        success: function (response) {
                            if (response.success) {
                                $('#display_booking_date').text(date);

                                // Format time for display (using a simple JS trick)
                                const dummyDate = new Date(`2000-01-01T${time}`);
                                const formattedTime = dummyDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
                                $('#display_booking_time').text(formattedTime);

                                if (typeof toastr !== 'undefined') {
                                    toastr.success(response.message);
                                }
                                $('#editBookingDateTimeModal').modal('hide');
                            } else {
                                alert(response.message || 'Error updating schedule');
                            }
                        },
                        error: function (xhr) {
                            const error = xhr.responseJSON?.message || 'Failed to update schedule.';
                            alert(error);
                        },
                        complete: function () {
                            $btn.prop('disabled', false).html('Save Changes');
                        }
                    });
                });

                // Edit Modal Category -> Service Filtering
                $('#edit_category_id').on('change', function () {
                    loadEditServices($(this).val());
                });

                // Edit Modal Service -> Size/Variant Filtering
                $('#edit_service_id').on('change', function () {
                    loadEditSizes($(this).val());
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .-light {
                background-color: rgba(255, 255, 255, 0.05);
            }

            .bg-primary-light {
                background-color: rgba(var(--primary-rgb), 0.1);
            }

            .card {
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(10px);
                cursor: pointer;
            }

            .card-title {
                letter-spacing: 0.5px;
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.85rem;
            }

            .table thead th {
                background-color: rgba(0, 0, 0, 0.2);
                border-color: rgba(255, 255, 255, 0.1);
                color: #fff;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .table td {
                border-color: rgba(255, 255, 255, 0.05);
                vertical-align: middle;
            }

            .badge-lg {
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 1px;
                border-radius: 8px;
            }

            .btn-primary {
                /* background: linear-gradient(45deggit , var(--primary), #6f42c1); */
                border: none;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(var(--primary-rgb), 0.3);
            }

            .form-control:focus {
                background-color: rgba(255, 255, 255, 0.1);
                border-color: var(--primary);
                box-shadow: none;
                color: #000;
            }

            /* Payment Options Styling */
            .payment-options {
                display: flex;
                justify-content: center;
            }

            .payment-option {
                flex: 1;
                max-width: 100px;
            }

            .payment-option input[type="radio"] {
                display: none;
            }

            .payment-option label {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 15px 10px;
                border: 2px solid #f0f0f0;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.3s ease;
                width: 100%;
            }

            .payment-option label i {
                font-size: 24px;
                margin-bottom: 8px;
                color: #ccc;
            }

            .payment-option label span {
                font-size: 14px;
                font-weight: 600;
                color: #666;
            }

            .payment-option input[type="radio"]:checked+label {
                border-color: var(--primary);
                background-color: rgba(var(--primary-rgb), 0.05);
            }

            .payment-option input[type="radio"]:checked+label i {
                color: var(--primary);
            }

            .payment-option input[type="radio"]:checked+label span {
                color: var(--primary);
            }
        </style>
    @endpush
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Complete Booking & Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pb-4">
                    <div class="mb-4">
                        <i class="fa fa-check-circle text-success fa-4x mb-3"></i>
                        <h4 class="mb-1 text-dark">Total Amount Due</h4>
                        <h2 class="text-dark font-weight-bold">${{ number_format($booking->total_price, 2) }}</h2>
                    </div>
                    <p class="text-muted mb-4">Select the payment method used for this booking.</p>
                    <div class="payment-options d-flex justify-content-center gap-3">
                        <div class="payment-option">
                            <input type="radio" name="payment_method_modal" id="pm_card" value="card" checked>
                            <label for="pm_card">
                                <i class="fa fa-credit-card"></i>
                                <span>Card</span>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" name="payment_method_modal" id="pm_cheque" value="cheque">
                            <label for="pm_cheque">
                                <i class="fa fa-money-check"></i>
                                <span>Cheque</span>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" name="payment_method_modal" id="pm_cash" value="cash">
                            <label for="pm_cash">
                                <i class="fa fa-money-bill-wave"></i>
                                <span>Cash</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-danger light shadow-none me-2"
                        data-bs-dismiss="modal">Back</button>
                    <button type="button" class="btn btn-primary" id="confirmPaymentBtn">Confirm & Complete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancellation Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Cancel Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fa fa-exclamation-triangle text-danger fa-4x mb-3"></i>
                        <h4 class="text-dark">Cancellation Reason</h4>
                    </div>
                    <div class="form-group">
                        <textarea id="cancel_note_modal" class="form-control" rows="4"
                            placeholder="Please provide a reason for cancelling this booking..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-danger light shadow-none me-2"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmCancelBtn">Confirm Cancellation</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editServiceForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Service: <span id="edit_service_name_title"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select id="edit_category_id" class="form-control" required>
                                <option value="">-- Choose Category --</option>
                                @if (isset($booking->pet->category))
                                    <option value="{{ $booking->pet->category->id }}">
                                        {{ $booking->pet->category->name }}
                                    </option>
                                @else
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">Filtered by booking pet category</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service</label>
                            <select id="edit_service_id" name="service_id" class="form-control" required disabled>
                                <option value="">-- Choose Service --</option>
                            </select>
                        </div>
                        <div id="edit_size_wrapper" class="mb-3" style="display:none;">
                            <label class="form-label">Size/Variant</label>
                            <select id="edit_size_id" name="size_id" class="form-control">
                                <option value="">-- Choose Size --</option>
                            </select>
                        </div>
                        <div class="alert alert-info py-2 small">
                            Note: Price, Quantity, and Duration will be automatically updated based on the selected service.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ContactUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Contact Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center">
                        <p>Name: </p>
                        <p class="ms-2">{{ $booking->user->name }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <p>Email: </p>
                        <p class="ms-2">{{ $booking->user->email }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <p>Phone: </p>
                        <p class="ms-2">{{ $booking->user->phone }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ViewMapModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Booking Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center">
                        No address specified
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Date/Time Modal -->
    <div class="modal fade" id="editBookingDateTimeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Booking Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Booking Date</label>
                        <input type="date" id="edit_booking_date" class="form-control" value="{{ $booking->booking_date }}"
                            min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Booking Time</label>
                        <input type="time" id="edit_booking_time" class="form-control" value="{{ $booking->booking_time }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnUpdateDateTime">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection