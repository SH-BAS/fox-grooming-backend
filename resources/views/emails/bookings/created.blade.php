@component('emails.layouts.admin')
# Booking Created Successfully!

Hello {{ $booking->user->name }},

We are happy to inform you that your booking has been created successfully. Our team will review your request and you
will receive another email once the booking is confirmed.

### Booking Details:
@component('mail::panel')
**Pet:** {{ $booking->pet->name }} ({{ $booking->pet->category->name }} - {{ $booking->pet->type->name ?? 'N/A' }})
**Date:** {{ $booking->booking_date }}
**Time:** {{ $booking->start_time }} - {{ $booking->end_time }}
**Branch:** {{ $booking->franchise->name }}
**Location:** {{ $booking->franchise->address }}
@endcomponent

### Services:
@foreach($booking->bookedServices as $bookedService)
    - {{ $bookedService->service->name }} ({{ $bookedService->quantity }}x)
@endforeach

**Total Price:** {{ config('app.currency', '$') }}{{ number_format($booking->total_price, 2) }}

If you have any questions or need to make changes, please feel free to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent