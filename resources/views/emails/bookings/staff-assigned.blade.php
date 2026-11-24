@component('emails.layouts.admin')
# Staff Has Been Assigned!

Hello {{ $booking->user->name }},

We wanted to let you know that a staff member has been assigned to your upcoming grooming session.

### Assigned Staff:
@component('mail::panel')
**Name:** {{ $booking->staff->name }}
**Contact:** {{ $booking->staff->phone ?? 'N/A' }}
@endcomponent

### Booking Details:
@component('mail::panel')
**Pet:** {{ $booking->pet->name }}
**Date:** {{ $booking->booking_date }}
**Time:** {{ $booking->start_time }} - {{ $booking->end_time }}
**Branch:** {{ $booking->franchise->name }}
@endcomponent

Our team is dedicated to providing the best care for your pet. We look forward to seeing you soon!

Thanks,<br>
{{ config('app.name') }}
@endcomponent