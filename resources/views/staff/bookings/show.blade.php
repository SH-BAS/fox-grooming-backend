@extends('layouts.staff')

@section('page-title', 'Booking Details')

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
            <a href="{{ route('staff.bookings.schedule') }}">
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
            <!-- Customer & Pet Row -->
            <div class="row">
                <!-- Customer Card -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header  border-0 pb-0">
                            <h4 class="card-title text-primary"><i class="fa fa-user me-2"></i>Customer Info</h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="user-avatar-lg mb-3 mx-auto">
                                {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <h5 class="mb-1">{{ $booking->user->name ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">{{ $booking->user->email ?? '-' }}</p>
                            <p class="text-muted mb-0">{{ $booking->user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pet Card -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header  border-0 pb-0">
                            <h4 class="card-title text-warning"><i class="fa fa-paw me-2"></i>Pet Info</h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="pet-avatar-lg mb-3 mx-auto">
                                {{ strtoupper(substr($booking->pet->name ?? 'P', 0, 1)) }}
                            </div>
                            <h5 class="mb-1">{{ $booking->pet->name ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">{{ $booking->pet->category->name ?? 'Pet' }}</p>
                            <p class="text-muted mb-0">{{ $booking->pet->age ?? '-' }} yrs •
                                {{ $booking->pet->gender ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Services Card -->
            <div class="card mt-4">
                <div class="card-header ">
                    <h4 class="card-title ">Booking Details & Services</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary-light me-3">
                                    <i class="fa fa-calendar text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted small text-uppercase font-weight-bold">Date & Day</p>
                                    <h5 class="mb-0">{{ $booking->booking_date }} -
                                        {{date('l', strtotime($booking->booking_date))}}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info-light me-3">
                                    <i class="fa fa-clock text-info"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted small text-uppercase font-weight-bold">Time</p>
                                    <h5 class="mb-0">{{ Carbon\Carbon::parse($booking->booking_time)->format('h:i A')}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Current Tasks</h5>
                        <a href="{{ route('staff.bookings.invoice', $booking->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-file-invoice me-2"></i>Invoice
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
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
                                                    data-service-name="{{ $item->service->name ?? 'Service' }}"
                                                    @if($booking->status == 'completed')
                                                        onclick="alert('This booking is completed and cannot be modified.'); return false;"
                                                    @endif>
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @if($booking->status != 'completed')
                                                    <form action="{{ route('staff.bookings.deleteService', $item->id) }}"
                                                        method="POST"
                                                        data-confirm="Are you sure you want to delete this service?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-xs sharp"><i
                                                                class="fa fa-trash"></i></button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-danger btn-xs sharp"
                                                        onclick="alert('This booking is completed and cannot be modified.');"><i
                                                            class="fa fa-trash"></i></button>
                                                @endif
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

                    <div class="border-top pt-3 mt-3">
                        <h5 class="mb-3">Add Additional Service</h5>
                        <form action="{{ route('staff.bookings.addService', $booking->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <select id="temp_category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        <option value="{{ $booking->pet->category->id }}" selected>
                                            {{ $booking->pet->category->name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select id="temp_service_id" name="service_id" class="form-control" required disabled>
                                        <option value="">-- Choose Service --</option>
                                    </select>
                                </div>
                                <div id="temp_size_wrapper" class="col-md-4 mb-2" style="display:none;">
                                    <select id="temp_size_id" name="size_id" class="form-control">
                                        <option value="">-- Size --</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <button type="submit" class="btn btn-success w-100"><i class="fa fa-plus me-1"></i>
                                        Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Booking Timeline Card -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Booking Timeline</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                    <th>Updated By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hasHistory = false;
                                    $statusKeys = [
                                        'assigned_at' => 'Assigned',
                                        'ontheway_at' => 'On The Way',
                                        'arrived_at' => 'Arrived',
                                        'started_at' => 'Started',
                                        'completed_at' => 'Completed',
                                        'cancelled_at' => 'Cancelled',
                                    ];
                                @endphp

                                @foreach($booking->logs->sortByDesc('id') as $history)
                                    @foreach($statusKeys as $key => $label)
                                        @if($history->{$key})
                                            @php $hasHistory = true; @endphp
                                            <tr>
                                                <td><span class="badge badge-xs light badge-secondary">{{ $label }}</span></td>
                                                <td>{{ \Carbon\Carbon::parse($history->{$key})->format('M d, Y h:i A') }}</td>
                                                <td>{{ ucfirst($history->updated_by_type ?? 'Staff') }}
                                                    ({{ $history->updatedBy()->name ?? '-' }})</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach

                                @if(!$hasHistory)
                                    <tr>
                                        <td colspan="3" class="text-muted text-center">No timeline events recorded yet.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="card">
                <div class="card-header ">
                    <h4 class="card-title  mb-0">Booking Action #{{ $booking->id }}</h4>
                    <a href="{{ route('staff.bookings.index') }}" class="ms-auto btn btn-primary btn-xs sharp shadow"><i
                            class="fas fa-arrow-left"></i></a>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted mb-1">Current Status</label>
                        @php
                            $badgeClass = 'bg-warning';
                            if ($booking->status == 'completed')
                                $badgeClass = 'bg-success';
                            elseif ($booking->status == 'cancelled')
                                $badgeClass = 'bg-danger';
                            elseif ($booking->status == 'assigned')
                                $badgeClass = 'bg-info';
                            elseif ($booking->status == 'ontheway')
                                $badgeClass = 'bg-secondary';
                            elseif ($booking->status == 'arrived')
                                $badgeClass = 'bg-primary';
                            elseif ($booking->status == 'started')
                                $badgeClass = '';
                        @endphp
                        <div>
                            <span
                                class="badge badge-lg {{ $badgeClass }} w-100 p-3">{{ strtoupper($booking->status) }}</span>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <form id="statusUpdateForm" action="{{ route('staff.bookings.updateStatus', $booking->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="payment_method" id="payment_method_hidden">
                        <input type="hidden" name="cancel_note" id="cancel_note_hidden">
                        <label class="form-label"><strong>Update Status</strong></label>
                        <div class="form-group">
                            <select id="statusSelect" name="status" class="form-control mb-3">
                                @foreach(['assigned', 'ontheway', 'arrived', 'started', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $booking->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary w-100" type="submit">Update Status</button>
                        </div>
                    </form>

                    <hr class="my-4">

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
                        <form action="{{ route('staff.bookings.uploadDocument', $booking->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" name="document" class="form-control" required>
                                <button type="submit" class="btn btn-secondary">Upload</button>
                            </div>
                            <small class="text-muted">Allowed: JPG, PNG, PDF, DOC (Max 2MB)</small>
                        </form>
                    </div>
                </div>
                <div class="card-footer text-center border-top">
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-light w-100">Back to All Bookings</a>
                </div>
            </div>
        </div>
    </div>

    <!-- user bookings history -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card mt-4">
                <div class="card-header border-bottom">
                    <h4 class="card-title text-info"><i class="fa fa-history me-2"></i>User Bookings History</h4>
                </div>
                <div class="card-body p-4">
                    @if($booking->user->bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Services</th>
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
                                                @foreach($userBooking->bookedServices as $bs)
                                                    <span
                                                        class="badge badge-xs light badge-primary mb-1">{{ $bs->service->name ?? 'Service' }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($userBooking->staff)
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://placehold.co/30x30?text={{ strtoupper(substr($userBooking->staff->name ?? '?', 0, 1)) }}"
                                                            class="rounded-circle me-2" width="24" alt="Staff">
                                                        <span>{{ $userBooking->staff->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $hBadgeClass = 'badge-warning';
                                                    if ($userBooking->status == 'completed')
                                                        $hBadgeClass = 'badge-success';
                                                    elseif ($userBooking->status == 'cancelled')
                                                        $hBadgeClass = 'badge-danger';
                                                @endphp
                                                <span class="badge badge-xs light {{ $hBadgeClass }}">
                                                    {{ strtoupper($userBooking->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($userBooking->staff_id == auth()->guard('staff')->id())
                                                    <a href="{{ route('staff.bookings.show', $userBooking->id) }}"
                                                        class="btn btn-primary btn-xs sharp"><i class="fa fa-eye"></i></a>
                                                @else
                                                    <span class="text-muted small">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-0 text-muted text-center">No previous bookings found for this user.</p>
                    @endif
                </div>
            </div>
        </div>
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
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
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

                $serviceSelect.empty().append('<option value="">-- Choose Service --</option>').prop('disabled', true);
                $sizeWrapper.hide();
                $priceInput.val('0');
                $('#temp_size_id').val('');

                if (!categoryId) return;

                $.ajax({
                    url: `{{ url('staff/categories') }}/${categoryId}/services`,
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

            // Service Details Fetch
            $('#temp_service_id').on('change', function () {
                const serviceId = $(this).val();
                const $sizeWrapper = $('#temp_size_wrapper');
                const $sizeSelect = $('#temp_size_id');
                const $priceInput = $('#temp_price');

                $sizeWrapper.hide();
                $priceInput.val('0');
                $sizeSelect.val('');

                if (!serviceId) return;

                $.ajax({
                    url: `{{ url('staff/services') }}/${serviceId}/details`,
                    method: 'GET',
                    success: function (service) {
                        const standards = service.category.standards;

                        // Check if category is fixable (standard with null size_id)
                        const fixedStandard = standards.find(s => s.size_id === null);

                        if (fixedStandard && service.category.pricing_type === 'fixable') {
                            // It's fixable, no size needed, but let's keep it safe
                            $sizeWrapper.hide();
                        } else {
                            $sizeWrapper.show();
                            $sizeSelect.empty().append('<option value="">-- Size --</option>');
                            standards.forEach(std => {
                                if (std.size) {
                                    $sizeSelect.append(`<option value="${std.size_id}" data-price="${std.price}">${std.size.name} ($${std.price})</option>`);
                                }
                            });
                            $sizeSelect.prop('required', true);
                        }
                    }
                });
            });

            // Size -> Price Update (Optional now as backend handles it, but good for UI if we had a price display)
            $('#temp_size_id').on('change', function () {
                // No action needed for now as we don't display the price in the form anymore per requirements
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

            // Edit Service
            $('.edit-service-btn').on('click', function () {
                @if($booking->status == 'completed')
                    return; // Handled by alert
                @endif

                                            const id = $(this).data('id');
                const categoryId = $(this).data('category-id');
                const serviceId = $(this).data('service-id');
                const sizeId = $(this).data('size-id');
                const serviceName = $(this).data('service-name');

                $('#edit_service_name_title').text(serviceName);

                // Set category and wait for services to load, then set service and wait for sizes
                $('#edit_category_id').val(categoryId).trigger('change');

                // We use an interval to check if services have loaded
                let serviceLoadCheck = setInterval(() => {
                    if (!$('#edit_service_id').prop('disabled') && $('#edit_service_id option').length > 1) {
                        clearInterval(serviceLoadCheck);
                        $('#edit_service_id').val(serviceId).trigger('change');

                        // Check for sizes if needed
                        let sizeLoadCheck = setInterval(() => {
                            if (!$('#edit_size_wrapper').is(':visible') || ($('#edit_size_id option').length > 1)) {
                                clearInterval(sizeLoadCheck);
                                if (sizeId) $('#edit_size_id').val(sizeId);
                            }
                        }, 100);
                    }
                }, 100);

                $('#editServiceForm').attr('action', `{{ url('staff/bookings/services') }}/${id}`);
                $('#editServiceModal').modal('show');
            });

            // Edit Modal Category -> Service Filtering
            $('#edit_category_id').on('change', function () {
                const categoryId = $(this).val();
                const $serviceSelect = $('#edit_service_id');
                const $sizeWrapper = $('#edit_size_wrapper');

                $serviceSelect.empty().append('<option value="">-- Choose Service --</option>').prop('disabled', true);
                $sizeWrapper.hide();

                if (!categoryId) return;

                $.ajax({
                    url: `{{ url('staff/categories') }}/${categoryId}/services`,
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

            // Edit Modal Service Details Fetch
            $('#edit_service_id').on('change', function () {
                const serviceId = $(this).val();
                const $sizeWrapper = $('#edit_size_wrapper');
                const $sizeSelect = $('#edit_size_id');

                $sizeWrapper.hide();
                $sizeSelect.val('');

                if (!serviceId) return;

                $.ajax({
                    url: `{{ url('staff/services') }}/${serviceId}/details`,
                    method: 'GET',
                    success: function (service) {
                        const standards = service.category.standards;
                        const fixedStandard = standards.find(s => s.size_id === null);

                        if (fixedStandard && service.category.pricing_type === 'fixable') {
                            $sizeWrapper.hide();
                            $sizeSelect.prop('required', false);
                        } else {
                            $sizeWrapper.show();
                            $sizeSelect.empty().append('<option value="">-- Size --</option>');
                            standards.forEach(std => {
                                if (std.size) {
                                    $sizeSelect.append(`<option value="${std.size_id}" data-price="${std.price}">${std.size.name} ($${std.price})</option>`);
                                }
                            });
                            $sizeSelect.prop('required', true);
                        }
                    }
                });
            });
        });
    </script>
@endpush
@push('styles')
    <style>
        .user-avatar-lg,
        .pet-avatar-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            font-weight: bold;
        }

        .user-avatar-lg {
            background-color: #a8a6a7ff;
        }

        .pet-avatar-lg {
            background-color: #a8a6a7ff;
        }

        .bg-primary-light {
            background-color: rgba(72, 133, 237, 0.1);
        }

        .bg-info-light {
            background-color: rgba(5, 190, 255, 0.1);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
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