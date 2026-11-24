@extends('layouts.admin')

@section('title', 'Edit Booking')
@section('page-title', 'Edit Booking #' . $booking->id)

@section('content')
    @php
        $totalMinutes = 0;
        if ($booking->pet && $booking->pet->category) {
            $totalMinutes += (int) $booking->pet->category->minutes;
        }
        if ($booking->bookedServices) {
            foreach ($booking->bookedServices as $bs) {
                $totalMinutes += (int) ($bs->minutes * $bs->quantity);
            }
        }
    @endphp
    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="hidden_total_duration" value="{{ $totalMinutes }}">
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Booking Details</h4>
                        <a href="{{ route('admin.bookings.index') }}"
                            class="ms-auto btn btn-primary btn-xs sharp shadow mb-3 "><i class="fas fa-arrow-left"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Customer <span
                                        class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="">-- Choose Customer --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Pet <span class="text-danger">*</span></label>
                                <select name="pet_id" id="pet_id" class="form-control" required>
                                    <option value="">-- Choose Pet --</option>
                                    @foreach($pets as $pet)
                                        <option value="{{ $pet->id }}" {{ old('pet_id', $booking->pet_id) == $pet->id ? 'selected' : '' }}>
                                            {{ $pet->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pet_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Franchise <span class="text-danger">*</span></label>
                                <select name="franchise_id" id="franchise_id" class="form-control" required>
                                    <option value="">-- Choose Franchise --</option>
                                    @foreach($franchises as $franchise)
                                        <option value="{{ $franchise->id }}" {{ old('franchise_id', $booking->franchise_id) == $franchise->id ? 'selected' : '' }}>
                                            {{ $franchise->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('franchise_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    @foreach(['pending', 'assigned', 'ontheway', 'arrived', 'started', 'completed', 'cancelled'] as $st)
                                        <option value="{{ $st }}" {{ old('status', $booking->status) == $st ? 'selected' : '' }}>
                                            {{ ucfirst($st) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Booking Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="booking_date" id="booking_date" class="form-control" required
                                    value="{{ $booking->booking_date }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Booking Time <span
                                        class="text-danger">*</span></label>
                                <select name="start_time" id="booking_time" class="form-control" required>
                                    <option value="">-- Choose Time --</option>
                                    <option value="{{ date('H:i', strtotime($booking->start_time)) }}" selected>
                                        {{ date('h:i A', strtotime($booking->start_time)) }}
                                    </option>
                                </select>
                                <small id="time_slot_msg" class="text-danger fw-bold" style="display:none;"></small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Assign Staff</label>
                                <select name="staff_id" id="staff_id" class="form-control">
                                    <option value="">-- Unassigned --</option>
                                    @foreach($staff as $s)
                                        <option value="{{ $s->id }}" {{ $booking->staff_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="form-label font-weight-bold">User Notes / Special Instructions</label>
                                <textarea name="user_notes" class="form-control"
                                    rows="3">{{ $booking->user_notes }}</textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Attachment</label>
                                <input type="file" name="document" class="form-control mb-1">
                                @if($booking->document)
                                    <a href="{{ asset('storage/' . $booking->document) }}" target="_blank"
                                        class="text-primary small">View Current Document</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 shadow">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        const allPets = @json($pets);
        const bookingTimeUrl = `{{ route('admin.bookings.getTimeSlots') }}`;

        $(document).ready(function () {
            $('#user_id').on('change', function () {
                const userId = $(this).val();
                const $petSelect = $('#pet_id');
                const currentPetId = "{{ $booking->pet_id }}";

                $petSelect.empty().append('<option value="">-- Choose Pet --</option>');

                if (!userId) return;

                const userPets = allPets.filter(p => p.user_id == userId);
                userPets.forEach(pet => {
                    $petSelect.append(`<option value="${pet.id}" ${pet.id == currentPetId ? 'selected' : ''}>${pet.name}</option>`);
                });
            });

            function updateTimeSlots() {
                const selectedDate = $('#booking_date').val();
                const franchiseId = $('#franchise_id').val();
                const staffId = $('#staff_id').val();
                const totalDuration = parseInt($('#hidden_total_duration').val()) || 0;
                const defaultTime = "{{ date('H:i', strtotime($booking->start_time)) }}";
                const isOriginalDate = (selectedDate === "{{ $booking->booking_date }}");

                const $timeSelect = $('#booking_time');
                const $msg = $('#time_slot_msg');

                $timeSelect.prop('disabled', true).empty().append('<option value="">Loading slots...</option>');
                $msg.hide();

                if (!selectedDate || !franchiseId || totalDuration <= 0) {
                    $timeSelect.empty().append('<option value="">-- Select Franchise and Date --</option>');
                    return;
                }

                $.ajax({
                    url: bookingTimeUrl,
                    data: {
                        date: selectedDate,
                        franchise_id: franchiseId,
                        total_duration: totalDuration,
                        staff_id: staffId
                    },
                    success: function (response) {
                        $timeSelect.empty();
                        if (response.success && response.slots && response.slots.length > 0) {
                            $timeSelect.append('<option value="">-- Choose Time --</option>');
                            
                            let foundCurrent = false;

                            response.slots.forEach(slot => {
                                const startTimeDisplay = moment(slot.start_time, 'HH:mm').format('hh:mm A');
                                const endTimeDisplay = moment(slot.end_time, 'HH:mm').format('hh:mm A');
                                const display = `${startTimeDisplay} - ${endTimeDisplay}`;
                                
                                const isSelected = (slot.start_time === defaultTime || slot.start_time + ':00' === defaultTime) ? 'selected' : '';
                                if(isSelected) foundCurrent = true;
                                
                                $timeSelect.append(`<option value="${slot.start_time}" ${isSelected}>${display}</option>`);
                            });
                            
                            if(!foundCurrent && isOriginalDate) {
                                const oldTimeDisplay = moment(defaultTime, 'HH:mm').format('hh:mm A');
                                $timeSelect.append(`<option value="${defaultTime}" selected>${oldTimeDisplay} (Current)</option>`);
                            }

                            $timeSelect.prop('disabled', false);
                        } else {
                            if(isOriginalDate) {
                                const oldTimeDisplay = moment(defaultTime, 'HH:mm').format('hh:mm A');
                                $timeSelect.append(`<option value="${defaultTime}" selected>${oldTimeDisplay} (Current)</option>`);
                                $timeSelect.prop('disabled', false);
                            } else {
                                $timeSelect.append('<option value="">No slots</option>');
                                $msg.text(response.message || 'No available slots for this day.').show();
                            }
                        }
                    },
                    error: function () {
                        $timeSelect.empty().append('<option value="">Error</option>');
                        $msg.text('Failed to fetch slots.').show();
                    }
                });
            }

            $('#booking_date, #franchise_id, #staff_id').on('change', function () {
                updateTimeSlots();
            });

            updateTimeSlots();
        });
    </script>
@endpush