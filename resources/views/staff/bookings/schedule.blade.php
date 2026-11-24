@extends('layouts.staff')

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

        .fc-event-main {
            padding: 2px;
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

        a.fc-event.fc-event-start.fc-event-end.fc-event-today.fc-event-future.fc-timegrid-event.fc-v-event {
            width: 100%;
        }

        .fc-timeGridDay-view.fc-view.fc-timegrid a.fc-event.fc-event-start.fc-event-end.fc-event-today.fc-event-future.fc-timegrid-event.fc-v-event {
            width: 276px;
        }

        a.fc-event.fc-event-start.fc-event-end.fc-event-today.fc-event-future.fc-timegrid-event.fc-v-event {
            height: auto !important;
            overflow: hidden;
        }


        .fc-timegrid-event-harness-inset .fc-timegrid-event,
        .fc-timegrid-event.fc-event-mirror,
        .fc-timegrid-more-link {
            box-shadow: 0 0 0 1px var(--fc-page-bg-color);
            width: 100%;
        }

        .fc-timegrid-event-harness {
            width: 204px !important;
        }
    </style>

    <div class="container-fluid page__container">
        <div class="row">
            <div class="col-md-2 mb-3">
                <label class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control">
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
                // Start time
                const startMoment = moment(`${b.booking_date} ${b.booking_time}`, 'YYYY-MM-DD HH:mm:ss');

                // Calculate total duration from services
                let totalMinutes = 0;
                if (b.booked_services && b.booked_services.length > 0) {
                    b.booked_services.forEach(bs => {
                        if (bs.service && bs.service.minutes) {
                            totalMinutes += parseInt(bs.service.minutes);
                        }
                    });
                }

                if (totalMinutes === 0) totalMinutes = 60;

                const endMoment = startMoment.clone().add(totalMinutes, 'minutes');
                const servicesList = b.booked_services ? b.booked_services.map(bs => bs.service?.name ?? '').filter(n => n).join(', ') : '-';

                return {
                    id: b.id,
                    title: `${b.pet?.name ?? 'Pet'} - ${b.user?.name ?? 'Customer'}`,
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
                        staff: b.staff?.name ?? 'No assigned'
                    }
                }
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                initialView: 'dayGridMonth',
                selectable: false,
                editable: false,
                businessHours: true,
                dayMaxEvents: true,
                height: "auto",
                events: allBookings.map(mapBookingToEvent),

                eventContent: function (arg) {
                    const e = arg.event.extendedProps;
                    return {
                        html: `
                                                                <div class="bookingCard" style="font-size: 11px; line-height: 1.2;">
                                                                    <b>${e.startFormatted} - ${e.endFormatted}</b><br>
                                                                    ${arg.event.title}<br>
                                                                    <small>${e.services}</small><br>
                                                                    <small style="background-color:#4d4a4a; color:white; padding:1px 3px; border-radius:3px; font-size: 10px;">Assigned To: (${e.staff})</small>
                                                                </div>
                                                            `
                    };
                },

                eventClick: function (info) {
                    const bookingId = info.event.id;
                    const url = `{{ route('staff.bookings.show', ':id') }}`.replace(':id', bookingId);
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
                const status = $('#status').val();

                const filtered = allBookings.filter(b => {
                    const statusMatch = !status || b.status == status;
                    return statusMatch;
                });

                calendar.removeAllEvents();
                calendar.addEventSource(filtered.map(mapBookingToEvent));
            }

            // Listen for changes
            $('#status').on('change', filterEvents);
        });

    </script>
@endpush