@extends('layouts.web')

@section('title', 'Select Franchise | Fox Grooming')

@section('content')
    <section class="inner-header divider parallax layer-overlay overlay-dark-5"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <div class="container pt-70 pb-20">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title text-white text-center">Spa in a Day in Just a Click Away</h2>
                        <p class="text-white text-center">Select your nearest branch to start booking.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row mb-30" data-aos="fade-up">
                <div class="col-md-8 col-md-offset-2">
                    <div class="search-box border-1px p-30 bg-silver-light">
                        <h4 class="text-theme-colored mt-0 text-center mb-20">Find Services Near You</h4>
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker text-theme-colored"></i></span>
                                <input type="text" id="address-input" class="form-control input-lg"
                                    placeholder="Enter your address (e.g. 123 Pet St, New York)">
                            </div>
                            <p class="text-muted mt-5 small text-center">We'll show you the branches that service your area.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="franchise-list">
                @include('booking.partials.franchise_list', ['franchises' => $franchises])
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places"></script>
<script>
    $(document).ready(function () {
        var input = document.getElementById('address-input');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();

            filterFranchises(lat, lng);
        });

        function filterFranchises(lat, lng) {
            $('#franchise-list').fadeOut(200, function () {
                $(this).html('<div class="col-md-12 text-center"><i class="fa fa-spinner fa-spin fa-3x text-theme-colored"></i><p class="mt-10">Searching for nearby branches...</p></div>').fadeIn(200);
            });

            $.ajax({
                url: "{{ route('web.search.franchises') }}",
                type: "GET",
                data: {
                    latitude: lat,
                    longitude: lng
                },
                success: function (response) {
                    if (response.success) {
                        $('#franchise-list').fadeOut(200, function () {
                            $(this).html(response.html).fadeIn(200);
                        });
                    }
                },
                error: function () {
                    $('#franchise-list').html('<div class="col-md-12 text-center text-danger"><i class="fa fa-exclamation-circle fa-2x"></i><p>Something went wrong. Please try again.</p></div>');
                }
            });
        }
    });
</script>