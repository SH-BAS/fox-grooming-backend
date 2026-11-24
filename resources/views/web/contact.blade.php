@extends('layouts.web')
@section('title', 'Contact Us | Fox Grooming')
@section('content')
    <!-- Section: inner-header -->
    <section class="inner-header hero-banner divider parallax layer-overlay overlay-white-8"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <!-- <div class="container pt-60 pb-60">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="title">Contact Us</h2>
                        <ol class="breadcrumb text-center text-black mt-10">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Pages</a></li>
                            <li class="active text-theme-colored">Page Title</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> -->
    </section>

    <!-- Section: Have Any Question -->
    <section class="divider">
        <div class="container pt-60 pb-60">
            <div class="section-title mb-60">
                <div class="row">
                    <div class="col-md-12">
                        <div class="esc-heading small-border text-center">
                            <h3>Have any Questions?</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="contact-info text-center">
                            <i class="fa fa-phone font-36 mb-10 text-theme-colored"></i>
                            <h4>Call Us</h4>
                            <h6 class="text-gray">Phone: +262 695 2601</h6>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="contact-info text-center">
                           <img src="{{ asset('images/homeScreen/car.png') }}" alt="" style="margin-top: -75px;">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="contact-info text-center">
                            <i class="fa fa-envelope font-36 mb-10 text-theme-colored"></i>
                            <h4>Email</h4>
                            <h6 class="text-gray">foxgroomingusa@gmail.com</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Contact -->
    <section data-bg-img="{{ asset('images/pattern/p4.png') }}">
        <div class="container">
            <div class="section-title text-center">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h2 class="text-uppercase font-28 mt-0"><span class="text-theme-colored">Contact</span> Us</h2>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">

                        <!-- Contact Form -->
                        <form class="contact-form-transparent" id="contact-form" action="{{ route('web.contact.submit') }}"
                            name="contact-form" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter First Name" id="contact-form-name"
                                            name="first_name" required="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter Last Name" id="contact-form-name"
                                            name="last_name" required="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="email" placeholder="Enter Email" id="contact-form-email" name="email"
                                            class="form-control required email">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter phone" id="contact-form-phone" name="phone"
                                            class="form-control required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea rows="5" placeholder="Enter Message" id="contact-form-message" name="message"
                                    class="form-control required"></textarea>
                            </div>
                            <div id="contact-form-result" class="alert alert-success" role="alert" style="display: none;">
                            </div>
                            <div class="form-group text-center mb-0">
                                <button data-loading-text="Please wait..."
                                    class="btn btn-colored btn-rounded btn-theme-colored pl-30 pr-30" type="submit">Send
                                    your
                                    message</button>
                            </div>
                        </form>
                        <!-- Contact Form Validation-->
                        <script type="text/javascript">
                            $("#contact-form").on('submit', function (e) {
                                e.preventDefault();
                                var form = $(this);
                                var form_result_div = '#contact-form-result';
                                var form_btn = form.find('button[type="submit"]');
                                var form_btn_old_msg = form_btn.html();
                                
                                form_btn.html(form_btn.data("loading-text")).prop('disabled', true);
                                $(form_result_div).hide();

                                $.ajax({
                                    url: form.attr('action'),
                                    type: 'POST',
                                    data: form.serialize(),
                                    success: function (data) {
                                        form_btn.html(form_btn_old_msg).prop('disabled', false);
                                        if (data.status == 'success') {
                                            form[0].reset();
                                            $(form_result_div).removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn('slow');
                                        } else {
                                            $(form_result_div).removeClass('alert-success').addClass('alert-danger').html(data.message).fadeIn('slow');
                                        }
                                        setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                                    },
                                    error: function() {
                                        form_btn.html(form_btn_old_msg).prop('disabled', false);
                                        $(form_result_div).removeClass('alert-success').addClass('alert-danger').html('An error occurred. Please try again.').fadeIn('slow');
                                        setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Divider: Google Map -->
    <section>
        <div class="container-fluid pt-0 pb-0">
            <div class="row">

                <!-- Google Map HTML Codes -->
                <div data-address="121 King Street, Melbourne Victoria 3000 Australia" data-popupstring-id="#popupstring1"
                    class="map-canvas autoload-map" data-mapstyle="style2" data-height="400"
                    data-latlng="-37.817314,144.955431" data-title="sample title" data-zoom="12"
                    data-marker="{{ asset('images/map-marker.png') }}">
                </div>
                <div class="map-popupstring hidden" id="popupstring1">
                    <div class="text-center">
                        <h3>ThemeMascot Office</h3>
                        <p>121 King Street, Melbourne Victoria 3000 Australia</p>
                    </div>
                </div>
                <!-- Google Map Javascript Codes -->
                <script src="http://maps.google.com/maps/api/js?key=AIzaSyAYWE4mHmR9GyPsHSOVZrSCOOljk8DU9B4"></script>
                <script src="js/google-map-init.js"></script>

            </div>
        </div>
    </section>
@endsection