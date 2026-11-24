@extends('layouts.admin')

@section('title', 'Create Booking')
@section('page-title', 'Create New Booking')

@section('content')
    <div class="row">
        <div class="col-xl-12">
         
                <form action="{{ route('admin.bookings.store') }}" method="POST" id="bookingForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Basic Info Card -->
                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">1. Customer & Main Service</h4>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-xs sharp shadow mb-3 "><i
                                    class="fas fa-arrow-left"></i></a>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-xs sharp shadow mb-3 "><i
                                    class="fas fa-plus"></i>
                                create new customer</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Select Customer <span
                                            class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control" required>
                                        <option value="">-- Choose Customer --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Select Pet <span
                                            class="text-danger">*</span></label>
                                    <select name="pet_id" id="pet_id" class="form-control" required disabled>
                                        <option value="">-- Choose Pet --</option>
                                    </select>
                                    <small class="text-primary d-block mt-1 fw-bold" id="pet_info"></small>
                                    @error('pet_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Select Franchise <span
                                            class="text-danger">*</span></label>
                                    <select name="franchise_id" id="franchise_id" class="form-control" required>
                                        <option value="">-- Choose Franchise --</option>
                                        @foreach($franchises as $franchise)
                                            <option value="{{ $franchise->id }}" {{ old('franchise_id') == $franchise->id ? 'selected' : '' }}>{{ $franchise->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('franchise_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Base Grooming Package <span
                                            class="text-danger">*</span></label>
                                    <select name="base_category_id" id="base_category_id" class="form-control" required
                                        disabled>
                                        <option value="">-- Select Pet First --</option>
                                    </select>
                                    <small class="text-muted" id="base_price_label">Filtered by selected pet (Dog/Cat).</small>
                                    @error('base_category_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Assign Staff (Optional)</label>
                                    <select name="staff_id" id="staff_id" class="form-control">
                                        <option value="">-- Unassigned (First Available) --</option>
                                        @foreach($staff as $s)
                                            <option value="{{ $s->id }}" {{ old('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('staff_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Card -->
                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">2. Additional Services (Add-ons)</h4>
                        </div>
                        <div class="card-body">
                            <!-- Service Selector Row -->
                            <div class="row p-3 mb-4  rounded border border-secondary">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small fw-bold ">Category</label>
                                    <select id="temp_category_id" name="category_id" class="form-control " disabled>
                                        <option value="">-- Select Base Package First --</option>
                                    </select>
                                    <small class="text-muted d-block mt-1">Filtered by Base Grooming Package</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small fw-bold ">Service</label>
                                    <select id="temp_service_id" class="form-control " disabled>
                                        <option value="">-- Choose Category --</option>
                                    </select>
                                </div>
                                <div id="temp_size_wrapper" class="col-md-3 mb-3" style="display:none;">
                                    <label class="form-label small fw-bold ">Size</label>
                                    <select id="temp_size_id" class="form-control ">
                                        <option value="">-- Size --</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label small fw-bold ">Price ($)</label>
                                    <input type="number" id="temp_price" class="form-control " step="0.01" placeholder="0.00">
                                </div>
                                <div class="col-md-1 mb-3 d-flex align-items-end">
                                    <button type="button" id="btnAddService" class="btn btn-primary w-100"><i
                                            class="fas fa-plus"></i></button>
                                </div>
                            </div>

                            <!-- Selected Services Table -->
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="selectedServicesTable">
                                    <thead class="bg-primary ">
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Size/Variant</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-center" width="100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="basePackageRow" style="display:none;">
                                            <td><strong id="basePackageName">Base Package</strong></td>
                                            <td><span id="basePackageSize">N/A</span></td>
                                            <td class="text-end"><strong id="basePackagePrice">$0.00</strong></td>
                                            <td class="text-center text-muted small">Required</td>
                                        </tr>
                                        <tr id="noServicesRow">
                                            <td colspan="4" class="text-center text-muted py-4">No additional services added
                                                yet.</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-dark">
                                            <td colspan="2" class="text-end fw-bold">Subtotal:</td>
                                            <td class="text-end fw-bold" id="subtotalDisplay">$0.00</td>
                                            <td></td>
                                        </tr>
                                        <tr class="table-dark">
                                            <td colspan="2" class="text-end fw-bold">Total Duration:</td>
                                            <td class="text-end fw-bold" id="durationDisplay">0 mins</td>
                                            <td></td>
                                        </tr>
                                        <tr class="table-dark">
                                            <td colspan="2" class="text-end fw-bold">Tax ({{ $tax->tax ?? 0 }}%):</td>
                                            <td class="text-end fw-bold" id="taxDisplay">$0.00</td>
                                            <td></td>
                                        </tr>
                                        <tr class="table-warning border-top-2">
                                            <td colspan="2" class="text-end">
                                                <h4 class="mb-0">GRAND TOTAL:</h4>
                                            </td>
                                            <td class="text-end">
                                                <h4 class="mb-0" id="grandTotalDisplay">$0.00</h4>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Date & Time Card -->
                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">3. Date & Time</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">Booking Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="booking_date" class="form-control" required
                                        min="{{ date('Y-m-d') }}" value="{{ old('booking_date', $selectedDate) }}">
                                    @error('booking_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">Booking Time <span
                                            class="text-danger">*</span></label>
                                    <select name="start_time" id="booking_time" class="form-control" required {{ empty($slots) ? 'disabled' : '' }}>
                                        @if(empty($slots))
                                            <option value="">-- Select Date, Franchise & Services First --</option>
                                        @else
                                            <option value="">-- Choose Time --</option>
                                            @foreach($slots as $slot)
                                                <option value="{{ $slot }}" {{ (old('start_time', $selectedTime) == $slot) ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::parse($slot)->format('h:i A') }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="time_slot_msg" class="text-danger fw-bold" style="display:none;"></small>
                                    <div id="end_time_display" class="mt-2 text-success fw-bold" style="display:none;">
                                        Estimated End Time: <span id="calculated_end_time">--:-- --</span>
                                    </div>
                                    @error('start_time')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info Card -->
                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">4. Additional Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label font-weight-bold">User Notes / Special Instructions</label>
                                    <textarea name="user_notes" class="form-control" rows="3"
                                        placeholder="Enter any special requests or notes from the customer..."></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Attachment (Optional)</label>
                                    <input type="file" name="document" class="form-control mb-1">
                                    <small class="text-muted">Allowed: JPG, PNG, PDF, DOC (Max 2MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Inputs for Pricing & Duration -->
                    <input type="hidden" name="base_price" id="hidden_base_price" value="0">
                    <input type="hidden" name="total_price" id="hidden_total_price" value="0">
                    <input type="hidden" name="total_duration" id="hidden_total_duration" value="0">
                    <input type="hidden" name="end_time" id="hidden_end_time" value="">

                    <div class="text-end mb-5">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 btn-lg shadow">Complete Booking</button>
                    </div>
                </form>
                   <!-- @if ($availableBooking > 0)
            @else
                <div class="alert alert-danger">No available booking slots today.</div>
            @endif -->
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        const petsData = @json($pets);
        const categoriesData = @json($categories->map(function ($c) {
            $label = (isset($c->standards[0]) && $c->standards[0]->title)
                ? ($c->standards[0]->title . ' - ' . $c->name)
                : $c->name;

            return ['id' => $c->id, 'name' => $c->name, 'label' => $label];
        })->values());
        const oldBaseCategoryId = @json(old('base_category_id'));
        const taxRate = {{ $tax->tax ?? 0 }};
        let currentPetSizeId = null;
        let selectedBaseCategoryData = null;
        let currentServiceData = null;
        let serviceCounter = 0;

        $(document).ready(function () {
            // Fetch Time Slots logic
            function updateTimeSlots(selectedDate, defaultTime = '') {
                const $timeSelect = $('#booking_time');
                const $msg = $('#time_slot_msg');

                $timeSelect.prop('disabled', true).empty().append('<option value="">Loading slots...</option>');
                $msg.hide();

                const franchiseId = $('#franchise_id').val();
                const totalDuration = $('#hidden_total_duration').val();
                const staffId = $('#staff_id').val();

                if (!selectedDate || !franchiseId || totalDuration <= 0) {
                    $timeSelect.empty().append('<option value="">-- Select Franchise, Date & Services --</option>');
                    return;
                }

                $.ajax({
                    url: `{{ route('admin.bookings.getTimeSlots') }}`,
                    data: {
                        date: selectedDate,
                        franchise_id: franchiseId,
                        total_duration: totalDuration,
                        staff_id: staffId
                    },
                    success: function (response) {
                        $timeSelect.empty();
                        if (response.success && response.slots.length > 0) {
                            $timeSelect.append('<option value="">-- Choose Time --</option>');
                            response.slots.forEach(slot => {
                                const startTimeDisplay = moment(slot.start_time, 'HH:mm').format('hh:mm A');
                                const endTimeDisplay = moment(slot.end_time, 'HH:mm').format('hh:mm A');
                                const display = `${startTimeDisplay} - ${endTimeDisplay}`;
                                const isSelected = (slot.start_time === defaultTime || slot.start_time + ':00' === defaultTime) ? 'selected' : '';
                                $timeSelect.append(`<option value="${slot.start_time}" ${isSelected}>${display}</option>`);
                            });
                            $timeSelect.prop('disabled', false);
                        } else {
                            $timeSelect.append('<option value="">No slots</option>');
                            $msg.text(response.message || 'No available slots for this day.').show();
                        }
                    },
                    error: function () {
                        $timeSelect.empty().append('<option value="">Error</option>');
                        $msg.text('Failed to fetch slots.').show();
                    }
                });
            }

            // Initial load check
            const initialDate = $('input[name="booking_date"]').val();
            const initialTime = @json($selectedTime);
            
            // IF url has date and time, logic skips the initial AJAX slot loader
            // because they are now pre-rendered by Blade for better UX.
            // Only use updateTimeSlots if we have a date but NO time (e.g. fresh navigation)
            if (initialDate && !initialTime) {
                updateTimeSlots(initialDate);
            }

            // Date, Franchise & Staff change events
            $('input[name="booking_date"], #franchise_id, #staff_id').on('change', function () {
                updateTimeSlots($('input[name="booking_date"]').val());
            });

            // Original JS logic starts here
            // Filter Base Grooming Package by selected pet category (Dog/Cat)
            function filterBasePackagesByPet(petCategoryId, preferredCategoryId = null) {
                const $baseSelect = $('#base_category_id');

                $baseSelect.empty();
                selectedBaseCategoryData = null;

                if (!petCategoryId) {
                    $baseSelect.append('<option value="">-- Select Pet First --</option>');
                    $baseSelect.prop('disabled', true);
                    filterAddonCategories(null);
                    calculateBasePrice();
                    return;
                }

                const matched = categoriesData.filter(c => String(c.id) === String(petCategoryId));

                if (matched.length === 0) {
                    $baseSelect.append('<option value="">-- No package for this pet --</option>');
                    $baseSelect.prop('disabled', true);
                    filterAddonCategories(null);
                    calculateBasePrice();
                    return;
                }

                $baseSelect.append('<option value="">-- Choose Category --</option>');
                matched.forEach(category => {
                    $baseSelect.append(`<option value="${category.id}">${category.label}</option>`);
                });
                $baseSelect.prop('disabled', false);

                const selectId = preferredCategoryId && matched.some(c => String(c.id) === String(preferredCategoryId))
                    ? preferredCategoryId
                    : matched[0].id;

                $baseSelect.val(String(selectId)).trigger('change');
            }

            // Customer -> Pet Filtering
            $('#user_id').on('change', function () {
                const userId = $(this).val();
                const $petSelect = $('#pet_id');
                const $petInfo = $('#pet_info');

                $petSelect.empty().append('<option value="">-- Choose Pet --</option>');
                $petInfo.text('');
                currentPetSizeId = null;
                filterBasePackagesByPet(null);

                if (!userId) {
                    $petSelect.prop('disabled', true);
                    return;
                }

                const userPets = petsData.filter(p => p.user_id == userId);

                if (userPets.length > 0) {
                    userPets.forEach(pet => {
                        $petSelect.append(
                            `<option value="${pet.id}" data-size="${pet.size_id}" data-category="${pet.category_id}">${pet.name}</option>`
                        );
                    });
                    $petSelect.prop('disabled', false);
                } else {
                    $petSelect.append('<option value="">No pets found</option>');
                    $petSelect.prop('disabled', true);
                }
                calculateBasePrice();
            });

            // Pet Selection -> Size ID + Base Package filter
            $('#pet_id').on('change', function () {
                const $option = $(this).find('option:selected');
                currentPetSizeId = $option.data('size');
                const petId = $(this).val();
                const $petInfo = $('#pet_info');

                if (petId) {
                    const pet = petsData.find(p => p.id == petId);
                    const sizeName = pet && pet.size ? pet.size.name : 'Standard';
                    const categoryName = pet && pet.category ? pet.category.name : '';
                    $petInfo.text(categoryName ? `Pet: ${categoryName} | Size: ${sizeName}` : `Pet Size: ${sizeName}`);
                    filterBasePackagesByPet(pet.category_id);
                } else {
                    $petInfo.text('');
                    filterBasePackagesByPet(null);
                }
            });

            // Filter add-on Category options by selected Base Grooming Package (Dog/Cat)
            function filterAddonCategories(baseCategoryId) {
                const $addonCategory = $('#temp_category_id');
                const $serviceSelect = $('#temp_service_id');
                const $sizeWrapper = $('#temp_size_wrapper');
                const $priceInput = $('#temp_price');

                $serviceSelect.empty().append('<option value="">-- Choose Category --</option>').prop('disabled', true);
                $sizeWrapper.hide();
                $priceInput.val('');
                currentServiceData = null;

                $addonCategory.empty();

                if (!baseCategoryId) {
                    $addonCategory.append('<option value="">-- Select Base Package First --</option>');
                    $addonCategory.prop('disabled', true);
                    return;
                }

                const matched = categoriesData.filter(c => String(c.id) === String(baseCategoryId));

                if (matched.length === 0) {
                    $addonCategory.append('<option value="">-- No matching category --</option>');
                    $addonCategory.prop('disabled', true);
                    return;
                }

                $addonCategory.append('<option value="">-- Category --</option>');
                matched.forEach(category => {
                    $addonCategory.append(`<option value="${category.id}">${category.name}</option>`);
                });
                $addonCategory.prop('disabled', false);
                $addonCategory.val(baseCategoryId).trigger('change');
            }

            // Base Category Selection
            $('#base_category_id').on('change', function () {
                const categoryId = $(this).val();
                filterAddonCategories(categoryId);

                if (!categoryId) {
                    selectedBaseCategoryData = null;
                    calculateBasePrice();
                    return;
                }

                $.ajax({
                    url: `{{ url('admin/categories') }}/${categoryId}/details`,
                    method: 'GET',
                    success: function (category) {
                        selectedBaseCategoryData = category;
                        calculateBasePrice();
                        updateTimeSlots($('input[name="booking_date"]').val()); // Services changed via base category
                    }
                });
            });

            // Restore pet/base package after validation errors
            const oldUserId = @json(old('user_id'));
            const oldPetId = @json(old('pet_id'));
            if (oldUserId) {
                $('#user_id').val(oldUserId).trigger('change');
                if (oldPetId) {
                    $('#pet_id').val(oldPetId).trigger('change');
                    if (oldBaseCategoryId) {
                        filterBasePackagesByPet(
                            (petsData.find(p => String(p.id) === String(oldPetId)) || {}).category_id,
                            oldBaseCategoryId
                        );
                    }
                }
            }

            function calculateBasePrice() {
                const $label = $('#base_price_label');
                const $row = $('#basePackageRow');
                const $nameCell = $('#basePackageName');
                const $sizeCell = $('#basePackageSize');
                const $priceCell = $('#basePackagePrice');
                const $hiddenBase = $('#hidden_base_price');

                if (!selectedBaseCategoryData || !currentPetSizeId) {
                    $label.text('Please select both pet and category.');
                    $row.hide();
                    $hiddenBase.val(0);
                    calculateGrandTotal();
                    return;
                }

                let price = 0;
                let sizeName = "Standard";

                if (selectedBaseCategoryData.pricing_type === 'fixable') {
                    const standard = selectedBaseCategoryData.standards.find(s => s.size_id === null);
                    if (standard) price = parseFloat(standard.price);
                } else {
                    const standard = selectedBaseCategoryData.standards.find(s => s.size_id == currentPetSizeId);
                    if (standard) {
                        price = parseFloat(standard.price);
                        sizeName = standard.size ? standard.size.name : "N/A";
                    }
                }

                if (price > 0) {
                    $label.text(`Base Rate: $${price.toFixed(2)}`);
                    $nameCell.text(selectedBaseCategoryData.name + " (Base)");
                    $sizeCell.text(sizeName);
                    $priceCell.text(`$${price.toFixed(2)}`);
                    $row.show();
                    $hiddenBase.val(price);
                } else {
                    $label.text('No standard price found for this size.');
                    $row.hide();
                    $hiddenBase.val(0);
                }
                calculateGrandTotal();
            }

            // Add-on Category -> Service Filtering
            $('#temp_category_id').on('change', function () {
                const categoryId = $(this).val();
                const $serviceSelect = $('#temp_service_id');
                const $sizeWrapper = $('#temp_size_wrapper');
                const $priceInput = $('#temp_price');

                $serviceSelect.empty().append('<option value="">-- Choose Service --</option>').prop('disabled', true);
                $sizeWrapper.hide();
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

            // Service Details Fetch
            $('#temp_service_id').on('change', function () {
                const serviceId = $(this).val();
                const $sizeWrapper = $('#temp_size_wrapper');
                const $sizeSelect = $('#temp_size_id');
                const $priceInput = $('#temp_price');

                $sizeWrapper.hide();
                $priceInput.val('');
                currentServiceData = null;

                if (!serviceId) return;

                $.ajax({
                    url: `{{ url('admin/services') }}/${serviceId}/details`,
                    method: 'GET',
                    success: function (service) {
                        currentServiceData = service;
                        if (service.pricing_type === 'fixable') {
                            const fixedPrice = service.sizes.find(s => s.size_id === null);
                            if (fixedPrice) $priceInput.val(fixedPrice.price);
                        } else if (service.pricing_type === 'sizeable') {
                            $sizeWrapper.show();
                            $sizeSelect.empty().append('<option value="">-- Size --</option>');
                            service.sizes.forEach(sz => {
                                if (sz.size) {
                                    $sizeSelect.append(`<option value="${sz.size.id}">${sz.size.name} ($${sz.price})</option>`);
                                }
                            });
                            // Auto-select match size if found
                            if (currentPetSizeId) {
                                $sizeSelect.val(currentPetSizeId).trigger('change');
                            }
                        }
                    }
                });
            });

            // Size -> Price Update
            $('#temp_size_id').on('change', function () {
                const sizeId = $(this).val();
                if (!sizeId || !currentServiceData) return;
                const sizeEntry = currentServiceData.sizes.find(s => s.size_id == sizeId);
                if (sizeEntry) $('#temp_price').val(sizeEntry.price);
            });

            // Add Service to List
            $('#btnAddService').on('click', function () {
                const serviceId = $('#temp_service_id').val();
                const serviceName = $('#temp_service_id option:selected').text();
                const sizeId = $('#temp_size_id').val();
                const sizeName = sizeId ? $('#temp_size_id option:selected').text().split(' (')[0] : 'Standard';
                const price = $('#temp_price').val();

                if (!serviceId || !price) {
                    alert('Please select a service and ensure price is set.');
                    return;
                }

                $('#noServicesRow').hide();

                const row = `
                            <tr class="service-row" data-index="${serviceCounter}">
                                <td>
                                    <input type="hidden" name="services[${serviceCounter}][service_id]" value="${serviceId}">
                                    ${serviceName}
                                </td>
                                <td>
                                    <input type="hidden" name="services[${serviceCounter}][size_id]" value="${sizeId || ''}">
                                    ${sizeName}
                                </td>
                                <td class="text-end">
                                    <input type="hidden" name="services[${serviceCounter}][price]" class="row-price" value="${price}">
                                    <input type="hidden" class="row-duration" value="${currentServiceData.minutes || 0}">
                                    $${parseFloat(price).toFixed(2)}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-xs btnRemoveService"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `;

                $('#selectedServicesTable tbody').append(row);
                serviceCounter++;
                calculateGrandTotal();

                // Reset selector row
                $('#temp_service_id').val('').trigger('change');
            });

            // Remove Service
            $(document).on('click', '.btnRemoveService', function () {
                $(this).closest('tr').remove();
                if ($('.service-row').length === 0) $('#noServicesRow').show();
                calculateGrandTotal();
            });

            function calculateGrandTotal() {
                let addonsTotal = 0;
                let totalDuration = 0;

                // Add Base Category Minutes
                if (selectedBaseCategoryData) {
                    totalDuration += parseInt(selectedBaseCategoryData.minutes) || 0;
                }

                // Add Addon Services Minutes
                $('.row-price').each(function () {
                    addonsTotal += parseFloat($(this).val()) || 0;
                });
                $('.row-duration').each(function () {
                    totalDuration += parseInt($(this).val()) || 0;
                });

                const basePrice = parseFloat($('#hidden_base_price').val()) || 0;
                const subtotal = basePrice + addonsTotal;
                const taxAmount = (subtotal * taxRate) / 100;
                const grandTotal = subtotal + taxAmount;

                $('#subtotalDisplay').text('$' + subtotal.toFixed(2));
                $('#taxDisplay').text('$' + taxAmount.toFixed(2));
                $('#durationDisplay').text(totalDuration + ' mins');
                $('#grandTotalDisplay').text('$' + grandTotal.toFixed(2));
                $('#hidden_total_price').val(grandTotal.toFixed(2));
                $('#hidden_total_duration').val(totalDuration);
                
                updateTimeSlots($('input[name="booking_date"]').val());
                updateEndTime();
            }

            // Booking Time change -> recalculate end time
            $('#booking_time').on('change', function() {
                updateEndTime();
            });

            function updateEndTime() {
                const startTime = $('#booking_time').val();
                const duration = parseInt($('#hidden_total_duration').val()) || 0;
                const $display = $('#end_time_display');
                const $val = $('#calculated_end_time');
                const $hidden = $('#hidden_end_time');

                if (startTime && duration > 0) {
                    const end = moment(startTime, 'HH:mm').add(duration, 'minutes');
                    $val.text(end.format('hh:mm A'));
                    $hidden.val(end.format('HH:mm:ss'));
                    $display.show();
                } else {
                    $display.hide();
                    $hidden.val('');
                }
            }

            // Form Validation Pre-submit
            $('#bookingForm').on('submit', function (e) {
                if (parseFloat($('#hidden_base_price').val()) <= 0) {
                    e.preventDefault();
                    alert('Please select a valid Base Grooming Category and Pet to calculate the base fee.');
                }
            });
        });
    </script>
@endpush