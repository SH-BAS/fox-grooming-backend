@if(config('services.google_maps.key'))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places"></script>
@else
    <script>console.error('Google Maps API Key is missing in services config.');</script>
@endif
