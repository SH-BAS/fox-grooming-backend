@extends('layouts.admin')

@section('title', 'Booking Schedule')
@section('page-title', 'Schedule')

@section('content')
    <style>
        .fc-list-event:hover {
            background-color: #b0b0b0 !important;
            color: inherit !important;
            filter: none !important;
            box-shadow: none !important;
            transition: none !important;
        }

        .fc-event {
            border-radius: 6px;
            padding: 2px 4px;
            cursor: pointer;
            border: none !important;
        }

        /* Essential fix for drag offset / positioning */
        .fc-v-event {
            display: block;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .fc-event-main {
            padding: 2px;
            height: 100%;
        }

        /* Fix for the dragged shadow/mirror */
        .fc-event-dragging {
            opacity: 0.6 !important;
        }

        .fc-event-mirror {
            z-index: 9999 !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3) !important;
            opacity: 0.85 !important;
            pointer-events: none;
        }

        .fc-event-title {
            font-weight: 600;
        }

        .fc-event-time {
            font-weight: 700;
            font-size: 0.85em;
        }

        tbody tr {
            border-bottom: 1px solid var(--border-light);
            transition: background-color 0.2s ease;
            background: #fff !important;
            backdrop-filter: none !important;
        }

        body.fc-is-dragging {
            user-select: none;
            -webkit-user-select: none;
            cursor: grabbing !important;
        }

        /* Quick Create (+) button styling */
        .fc-daygrid-day {
            position: relative !important;
        }

        .fc-day-add-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            background-color: #3b82f6;
            color: #fff !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none !important;
            font-weight: bold;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.25s, transform 0.25s;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .fc-daygrid-day:hover .fc-day-add-btn {
            opacity: 1;
            transform: scale(1.1);
        }

        .fc-day-add-btn:hover {
            background-color: #2563eb;
            transform: scale(1.2) !important;
        }

        /* Event delete button styling */
        .bookingCard {
            position: relative;
        }

        .fc-event-delete-btn {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: #fff;
            color: #dc3545;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            opacity: 0;
            transition: opacity 0.2s, transform 0.2s;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            border: 1px solid #dc3545;
            cursor: pointer !important;
        }

        .fc-event:hover .fc-event-delete-btn {
            opacity: 1;
        }

        .fc-event-delete-btn:hover {
            transform: scale(1.2);
            background: #dc3545;
            color: #fff;
        }

        .fc-timegrid-event-harness {
            width: 204px !important;
        }

        /* Time Slot Modal Styles */
        .time-slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }

        .time-slot-item {
            padding: 10px;
            text-align: center;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 600;
        }

        .time-slot-item:hover {
            background-color: #f8f9fa;
            border-color: #ef4444;
            color: #ef4444;
        }

        .time-slot-item.selected {
            background-color: #ef4444;
            border-color: #ef4444;
            color: #fff;
        }
    </style>

    <div class="container-fluid page__container">
        <div class="row">
            <div class="col-md-2 mb-3">
                <label class="form-label font-weight-bold">Staff Bookings <span class="text-danger">*</span></label>
                <select name="staff_id" id="staff_id" class="form-control">
                    <option value="">-- All staff bookings --</option>
                    @foreach ($staffs as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control" required="">
                    <option value="">-- All Status --</option>
                    <option value="pending">Pending</option>
                    <option value="assigned">Assigned</option>
                    <option value="ontheway">On the Way</option>
                    <option value="arrived">Arrived</option>
                    <option value="started">Started</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Drag & Drop Booking Date/Time Confirmation Modal -->
    <div class="modal fade" id="dragDropTimeModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-backdrop="static" data-bs-keyboard="false" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Booking Date & Time</h5>
                    <button type="button" class="btn-close dragCancelBtn" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Selected Date:</label>
                        <input type="date" id="dragDateDisplay" class="form-control bg-light" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Start Time:</label>
                        <input type="time" id="dragTimeInput" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary dragCancelBtn" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="dragSaveBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css' rel='stylesheet' />
@endpush

@push('scripts')
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        $(document).ready(function () {
            const calendarEl = document.getElementById('calendar');

            // Store all bookings for filtering
            const allBookings = @json($bookings);

            function getStatusColor(status) {
                switch (status) {
                    case 'pending': return '#ffc107';   // Amber
                    case 'assigned': return '#17a2b8';  // Cyan
                    case 'confirmed': return '#17a2b8'; // Cyan
                    case 'started': return '#8b5cf6';   // Purple
                    case 'completed': return '#28a745'; // Green
                    case 'cancelled': return '#dc3545'; // Red
                    case 'ontheway': return '#06b6d4';  // Cyan
                    case 'arrived': return '#6366f1';   // Indigo
                    default: return '#6c757d';          // Gray
                }
            }

            function mapBookingToEvent(b) {
                // Start time from exact Laravel backend calculation
                const startMoment = moment(b.formatted_start, 'YYYY-MM-DD HH:mm:ss');
                const endMoment = moment(b.formatted_end, 'YYYY-MM-DD HH:mm:ss');

                let totalMinutes = endMoment.diff(startMoment, 'minutes');
                if (totalMinutes <= 0) totalMinutes = 60;

                const servicesList = b.booked_services ? b.booked_services.map(bs => bs.service?.name ?? '').filter(n => n).join(', ') : '-';

                return {
                    id: b.id,
                    title: `${b.user?.name ?? 'Customer'}`,
                    staff: `${b.staff?.name ?? 'No assigned'}`,
                    start: startMoment.toISOString(),
                    end: endMoment.toISOString(),
                    backgroundColor: getStatusColor(b.status),
                    borderColor: getStatusColor(b.status),
                    textColor: '#fff',
                    display: 'block',
                    allDay: false,
                    extendedProps: {
                        status: b.status,
                        pet: b.pet?.name ?? '-',
                        services: servicesList,
                        user: b.user?.name ?? '-',
                        startFormatted: startMoment.format('hh:mm A'),
                        endFormatted: endMoment.format('hh:mm A'),
                        duration: totalMinutes
                    }
                }
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                editable: true,
                droppable: true,
                fixedMirrorParent: document.body,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                initialView: 'dayGridMonth',
                selectable: false,
                editable: true,
                businessHours: true,
                dayMaxEvents: true,
                height: "auto",
                longPressDelay: 0,
                snapDuration: '00:15:00',
                events: allBookings.map(mapBookingToEvent),
                dayCellDidMount: function (info) {
                    const today = moment().startOf('day');
                    const cellDate = moment(info.date);

                    // Skip past dates
                    if (cellDate.isBefore(today)) return;

                    const addBtn = document.createElement('div');
                    addBtn.className = 'fc-day-add-btn';
                    addBtn.title = 'Quick Create Booking for ' + cellDate.format('MMM D');
                    addBtn.innerHTML = '+';

                    addBtn.onclick = function () {
                        const dateStr = cellDate.format('YYYY-MM-DD');
                        const url = `{{ route('admin.bookings.create') }}?date=${dateStr}`;
                        window.location.href = url;
                    };

                    info.el.appendChild(addBtn);

                    // Month View adjustment to avoid layout clashing with date numbers
                    if (calendar.view.type === 'dayGridMonth') {
                        const topEl = info.el.querySelector('.fc-daygrid-day-top');
                        if (topEl) {
                            topEl.style.paddingRight = '25px';
                        }
                    }
                },

                eventDragStart: function () {
                    document.body.classList.add('fc-is-dragging');
                },

                eventDragStop: function () {
                    document.body.classList.remove('fc-is-dragging');
                },

                eventDrop: function (info) {
                    const newStart = moment(info.event.start);
                    const today = moment().startOf('day');

                    // Determine old original values
                    const bIndex = allBookings.findIndex(b => b.id == info.event.id);
                    let oldDateStr = moment(info.oldEvent.start).format('YYYY-MM-DD');
                    let oldTimeStr = '00:00:00';
                    if (bIndex !== -1) {
                        const mStart = moment(allBookings[bIndex].formatted_start, 'YYYY-MM-DD HH:mm:ss');
                        oldDateStr = mStart.format('YYYY-MM-DD');
                        oldTimeStr = mStart.format('HH:mm:ss');
                    }

                    if (newStart.isBefore(today)) {
                        alert('You cannot move a booking to a past date.');
                        info.revert();
                        return;
                    }

                    const bookingId = info.event.id;
                    const bookingDate = newStart.format('YYYY-MM-DD');
                    
                    // Pre-fill dropped time, optionally preserving old time for month view drops
                    let droppedTimeStr = newStart.format('HH:mm');
                    if (info.view.type === 'dayGridMonth' || newStart.format('HH:mm:ss') === '00:00:00') {
                        droppedTimeStr = oldTimeStr.substring(0, 5);
                    }

                    // Populate and Show Modal
                    $('#dragDateDisplay').val(bookingDate);
                    $('#dragTimeInput').val(droppedTimeStr);

                    const $modal = $('#dragDropTimeModal');
                    $modal.modal('show');

                    // Unbind old events to prevent multiples
                    $('#dragSaveBtn').off('click');
                    $('.dragCancelBtn').off('click');
                    $modal.off('hidden.bs.modal hidden.modal');

                    let wasSaved = false;

                    // Bind cancel
                    $('.dragCancelBtn').on('click', function() {
                        $modal.modal('hide');
                    });

                    $modal.on('hidden.bs.modal hidden.modal', function() {
                        if (!wasSaved) {
                            info.revert();
                        }
                    });

                    // Bind save
                    $('#dragSaveBtn').on('click', function() {
                        let finalTime = $('#dragTimeInput').val();
                        if (!finalTime) {
                            alert("Start time is required.");
                            return;
                        }
                        if (finalTime.length === 5) finalTime += ':00'; // Append seconds if needed

                        // Validation rule: No changes detected
                        if (bookingDate === oldDateStr && finalTime === oldTimeStr) {
                            wasSaved = true; // prevent revert from firing during hide
                            info.revert(); // revert visuals since nothing actually changed
                            $modal.modal('hide');
                            
                            if (typeof toastr !== 'undefined') {
                                toastr.info('No changes detected');
                            } else {
                                alert('No changes detected');
                            }
                            return;
                        }

                        wasSaved = true;
                        const $btn = $(this);
                        $btn.prop('disabled', true).text('Saving...');

                        $.ajax({
                            url: `{{ route('admin.bookings.updateDate', ':id') }}`.replace(':id', bookingId),
                            method: 'PATCH',
                            data: {
                                _token: '{{ csrf_token() }}',
                                booking_date: bookingDate,
                                start_time: finalTime
                            },
                            success: function (response) {
                                $btn.prop('disabled', false).text('Save Changes');
                                $modal.modal('hide');

                                if (response.success) {
                                    // Update backend shadow structure
                                    if (bIndex !== -1) {
                                        allBookings[bIndex].formatted_start = `${bookingDate} ${finalTime}`;
                                    }

                                    // Refresh calendar UI parameters
                                    const newDateObj = moment(`${bookingDate} ${finalTime}`, 'YYYY-MM-DD HH:mm:ss');
                                    info.event.setStart(newDateObj.toDate());

                                    const duration = info.event.extendedProps.duration || 60;
                                    const newEnd = newDateObj.clone().add(duration, 'minutes');
                                    info.event.setEnd(newEnd.toDate());

                                    info.event.setExtendedProp('startFormatted', newDateObj.format('hh:mm A'));
                                    info.event.setExtendedProp('endFormatted', newEnd.format('hh:mm A'));

                                    if (typeof toastr !== 'undefined') {
                                        toastr.success(response.message);
                                    } else {
                                        console.log(response.message);
                                    }
                                } else {
                                    info.revert();
                                    if (typeof toastr !== 'undefined') {
                                        toastr.error(response.message || 'Error updating booking');
                                    } else {
                                        alert(response.message || 'Error updating booking');
                                    }
                                }
                            },
                            error: function (xhr) {
                                $btn.prop('disabled', false).text('Save Changes');
                                $modal.modal('hide');
                                info.revert();
                                
                                const error = xhr.responseJSON?.message || 'Failed to update booking.';
                                if (typeof toastr !== 'undefined') {
                                    toastr.error(error);
                                } else {
                                    alert(error);
                                }
                            }
                        });
                    });
                },

                eventContent: function (arg) {
                    const e = arg.event.extendedProps;
                    const isCompleted = e.status === 'completed';
                    const isPast = moment(arg.event.start).isBefore(moment(), 'day');
                    const showDelete = !isCompleted && !isPast;

                    return {
                        html: `
                            <div class="bookingCard" style="font-size: 11px; line-height: 1.2;">
                                <b>${e.startFormatted} - ${e.endFormatted}</b><br>
                                ${arg.event.title}<br>
                                <small style="background-color:#4d4a4a; color:white; padding:1px 3px; border-radius:3px; font-size: 10px;">Assigned To: (${e.staff})</small>
                                ${showDelete ? '<div class="fc-event-delete-btn" title="Delete Booking">🗑️</div>' : ''}
                            </div>
                        `
                    };
                },

                eventClick: function (info) {
                    // Check if delete button was clicked
                    if (info.jsEvent.target.classList.contains('fc-event-delete-btn')) {
                        info.jsEvent.preventDefault();
                        info.jsEvent.stopPropagation();

                        const isCompleted = info.event.extendedProps.status === 'completed';
                        const isPast = moment(info.event.start).isBefore(moment(), 'day');

                        if (isCompleted || isPast) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Not allowed', 'You cannot delete a ' + (isCompleted ? 'completed' : 'past') + ' booking.', 'warning');
                            } else {
                                alert('You cannot delete a ' + (isCompleted ? 'completed' : 'past') + ' booking.');
                            }
                            return false;
                        }

                        window.confirmDelete('Are you sure you want to delete this booking?').then(function (ok) {
                            if (!ok) {
                                return;
                            }

                            const bookingId = info.event.id;
                            $.ajax({
                                url: `{{ route('admin.bookings.destroy', ':id') }}`.replace(':id', bookingId),
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    if (response.success) {
                                        info.event.remove();
                                        // Update local data array
                                        const bIndex = allBookings.findIndex(b => b.id == bookingId);
                                        if (bIndex !== -1) {
                                            allBookings.splice(bIndex, 1);
                                        }

                                        if (typeof toastr !== 'undefined') {
                                            toastr.success(response.message);
                                        }
                                    } else {
                                        if (typeof Swal !== 'undefined') {
                                            Swal.fire('Error', response.message || 'Error deleting booking', 'error');
                                        } else {
                                            alert(response.message || 'Error deleting booking');
                                        }
                                    }
                                },
                                error: function () {
                                    if (typeof Swal !== 'undefined') {
                                        Swal.fire('Error', 'Failed to delete booking. Please try again.', 'error');
                                    } else {
                                        alert('Failed to delete booking. Please try again.');
                                    }
                                }
                            });
                        });
                        return false;
                    }

                    const bookingId = info.event.id;
                    const url = `{{ route('admin.bookings.show', ':id') }}`.replace(':id', bookingId);
                    window.location.href = url;
                },

                eventDidMount: function (info) {
                    $(info.el).css({
                        'border-radius': '6px',
                        'padding': '3px 5px',
                        'cursor': 'pointer'
                    });
                }
            });

            calendar.render();

            function filterEvents() {
                const staffId = $('#staff_id').val();
                const status = $('#status').val();

                const filtered = allBookings.filter(b => {
                    const staffMatch = !staffId || b.staff_id == staffId;
                    const statusMatch = !status || b.status == status;
                    return staffMatch && statusMatch;
                });

                calendar.removeAllEvents();
                calendar.addEventSource(filtered.map(mapBookingToEvent));
            }

            // Listen for changes
            $('#staff_id, #status').on('change', filterEvents);

        });

    </script>
@endpush