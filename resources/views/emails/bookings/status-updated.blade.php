@component('emails.layouts.admin')
# Booking Status Update

Hello {{ $booking->user->name }},

{{ $statusMessage }}

### Current Booking Status: **{{ ucfirst(str_replace('_', ' ', $booking->status)) }}**

### Booking Details:
@component('mail::panel')
**Pet:** {{ $booking->pet->name }}
**Date:** {{ $booking->booking_date }}
**Time:** {{ $booking->start_time }} - {{ $booking->end_time }}
**Branch:** {{ $booking->franchise->name }}
@endcomponent

@if($booking->staff)
    ### Staff Member:
    **{{ $booking->staff->name }}**
@endif

If you have any questions regarding this update, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent