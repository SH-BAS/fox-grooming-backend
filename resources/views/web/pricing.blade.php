@extends('layouts.web')

@section('title', 'Pricing | Fox Grooming')

@section('styles')
    <style>
        /* Tabs */
        .nav-tabs-custom {
            display: flex;
            justify-content: center;
            gap: 10px;
            border-bottom: none;
            margin-bottom: 40px;
        }

        .nav-tabs-custom li a {
            padding: 12px 25px;
            border-radius: 30px;
            background: #f5f5f5;
            color: #333;
            font-weight: 600;
            transition: 0.3s;
        }

        .nav-tabs-custom li.active a,
        .nav-tabs-custom li a:hover {
            background: #f15b22;
            color: #fff !important;
            box-shadow: 0 5px 15px rgba(241, 91, 34, 0.3);
        }

        /* Pricing Cards */
        .pricing-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: 0.3s;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .pricing-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid #f15b22;
            margin-bottom: 10px;
        }

        .price {
            font-size: 32px;
            color: #f15b22;
            margin: 10px 0;
        }

        /* Services Section */
        .services-box {
            background: #fafafa;
            padding: 30px;
            border-radius: 12px;
        }

        .service-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            transition: 0.3s;
            text-align: center;
            border: 1px solid #eee;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .service-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .service-card h5 {
            font-weight: 600;
        }

        .service-card .price {
            color: #f15b22;
            font-weight: bold;
        }

        .service-card .sizes span {
            display: block;
            font-size: 13px;
            color: #555;
        }

        .service-card .desc {
            font-size: 13px;
            color: #888;
        }
    </style>
@endsection

@section('content')
    <!-- Section: inner-header -->
    <section class="inner-header hero-banner divider parallax layer-overlay overlay-dark-5"
        data-bg-img="{{ asset('images/homeScreen/mainBanner.png') }}">
        <div class="container pt-90 pb-50">
            <!-- Section Content -->
            <div class="section-content pt-100">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="title text-white">Pricing</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Section: Pricing Table -->
    <!-- Section: Pricing Table -->
    <section id="pricing" data-bg-img="{{ asset('images/pattern/p8.png') }}">
        <div class="container">

            <div class="section-title text-center">
                <h2>Our <span class="text-theme-colored">Pricing</span></h2>
                <p>Transparent and competitive pricing for all your pet grooming needs.</p>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom">
                        @foreach($categories as $index => $category)
                            <li class="{{ $index == 0 ? 'active' : '' }}">
                                <a data-toggle="tab" href="#category-{{ $category->id }}">
                                    <i class="fa fa-paw"></i> {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">

                        @foreach($categories as $index => $category)
                            <div id="category-{{ $category->id }}" class="tab-pane fade {{ $index == 0 ? 'in active' : '' }}">

                                @php
                                    $groupedStandards = $category->standards->groupBy('title');
                                @endphp

                                @foreach($groupedStandards as $title => $standards)

                                    <h3 class="text-center mt-30 mb-20">{{ $title }}</h3>

                                    <div class="row">
                                        @foreach($standards as $standard)
                                            <div class="col-md-3 col-sm-6 mb-30">

                                                <div class="pricing-card">

                                                    @if($category->image)
                                                        <img src="{{ asset('assets/images/logo_black.png') }}" class="pricing-img">
                                                    @endif

                                                    <h4>
                                                        {{ $standard->size->name ?? 'Standard' }}
                                                    </h4>

                                                    <p class="text-muted">
                                                        {{ $standard->size ? $standard->size->weight_from . ' - ' . $standard->size->weight_to . ' lbs' : 'Fixed Price' }}
                                                    </p>

                                                    <h2 class="price">
                                                        ${{ (int) $standard->price }}
                                                    </h2>

                                                    <a href="{{ route('web.book.franchise') }}" class="btn btn-theme-colored btn-sm">
                                                        Book Now
                                                    </a>

                                                </div>

                                            </div>
                                        @endforeach
                                    </div>

                                @endforeach

                                <!-- Additional Services -->
                                @if($category->services->count() > 0)
                                    <div class="services-box mt-40">

                                        <h3 class="text-center mb-30">
                                            <i class="fa fa-plus-circle text-theme-colored"></i>
                                            Additional Services
                                        </h3>

                                        <div class="row">
                                            @foreach($category->services as $service)
                                                <div class="col-md-4 col-sm-6 mb-20">

                                                    <div class="service-card">

                                                        @if($service->image)
                                                            <img src="{{ $service->image }}" class="service-img">
                                                        @endif

                                                        <h5>{{ $service->name }}</h5>

                                                        @if($service->pricing_type == 'fixable')
                                                            <p class="price">
                                                                ${{ (int) $service->sizes->first()->price ?? 0 }}
                                                            </p>
                                                        @elseif($service->pricing_type == 'sizeable')
                                                            <div class="sizes">
                                                                @foreach($service->sizes as $sizePrice)
                                                                    <span>
                                                                        {{ $sizePrice->size->name ?? 'Any' }}:
                                                                        ${{ (int) $sizePrice->price }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        <p class="desc">
                                                            {{ Str::limit($service->description, 60) }}
                                                        </p>

                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                @endif

                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Section:Get SErvices -->
    <section>
        <div class="container-fluid p-0">
            <div class="row equal-height">
                <div class="col-sm-6 col-md-6 p-0 bg-img-cover" data-bg-img="{{ asset('images/bg/bg5.jpg') }}">
                </div>
                <div class="col-md-6 bg-light">
                    <div class="p-70">
                        <div class="row">
                            <div class="col-md-10">
                                <h2 class="mt-0 line-height-1"><span>Get A <span class="text-theme-colored">Service
                                            Now!</span></span></h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim labore iure repellendus
                                    quasi
                                    nobis.</p>
                                <form id="getaquote_form" name="getaquote_form" class="form-transparent mt-20" method="post"
                                    action="{{ route('web.contact.submit') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group mb-10">
                                                <input name="first_name" class="form-control" type="text" required=""
                                                    placeholder="Enter First Name" aria-required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-10">
                                                <input name="last_name" class="form-control" type="text"
                                                    placeholder="Enter Last Name" aria-required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-10">
                                                <input name="phone" class="form-control" type="tel"
                                                    placeholder="Enter Phone" aria-required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-10">
                                                <input name="email" class="form-control required email" type="email"
                                                    placeholder="Enter Email" aria-required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group mb-10">
                                                <div class="styled-select">
                                                    <select id="booking_service" name="subject" class="form-control">
                                                        <option value="">- Select Your Vehicle Model -</option>
                                                        <option value="Toyota">Toyota</option>
                                                        <option value="Jeep">Jeep</option>
                                                        <option value="Audi">Audi</option>
                                                        <option value="Truck">Truck</option>
                                                        <option value="Land Rover">Land Rover</option>
                                                        <option value="Lexus">Lexus</option>
                                                        <option value="Mazda">Mazda</option>
                                                        <option value="Mercedes - Benz">Mercedes - Benz</option>
                                                        <option value="Nissan">Nissan</option>
                                                        <option value="Mitsubishi">Mitsubishi</option>
                                                        <option value="Saab">Saab</option>
                                                        <option value="Renault">Renault</option>
                                                        <option value="Mercury">Mercury</option>
                                                        <option value="Pontiac Porsche">Pontiac Porsche</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-10">
                                        <textarea id="" name="message" class="form-control " placeholder="Enter Message"
                                            rows="5" aria-required="true"></textarea>
                                    </div>
                                    <div class="form-group mb-0 mt-20">
                                        <button type="submit" class="btn btn-dark btn-theme-colored text-uppercase"
                                            data-loading-text="Please wait...">Send Message</button>
                                    </div>
                                </form>
                                <!-- Get Your Project Started Form Validation-->
                                <script type="text/javascript">
                                    $("#getaquote_form").on('submit', function (e) {
                                        e.preventDefault();
                                        var form = $(this);
                                        var form_btn = form.find('button[type="submit"]');
                                        var form_result_div = '#form-result';

                                        if ($(form_result_div).length == 0) {
                                            form_btn.before('<div id="form-result" class="alert mt-20" role="alert" style="display: none;"></div>');
                                        }

                                        var form_btn_old_msg = form_btn.html();
                                        form_btn.html(form_btn.data("loading-text")).prop('disabled', true);
                                        $(form_result_div).hide();

                                        $.ajax({
                                            url: form.attr('action'),
                                            type: 'POST',
                                            data: form.serialize(),
                                            success: function (data) {
                                                form_btn.prop('disabled', false).html(form_btn_old_msg);
                                                if (data.status == 'success') {
                                                    form[0].reset();
                                                    $(form_result_div).removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn('slow');
                                                } else {
                                                    $(form_result_div).removeClass('alert-success').addClass('alert-danger').html(data.message).fadeIn('slow');
                                                }
                                                setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                                            },
                                            error: function () {
                                                form_btn.prop('disabled', false).html(form_btn_old_msg);
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
            </div>
        </div>
    </section>

    <!-- Divider: Clients -->
    <section class="clients bg-theme-colored">
        <div class="container pt-10 pb-0">
            <div class="row">
                <div class="col-md-12">
                    <!-- Section: Clients -->
                    <div class="owl-carousel-6col transparent text-center owl-nav-top">
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w1.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w2.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w3.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w4.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w5.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w6.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w3.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w4.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w5.png') }}" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="{{ asset('images/clients/w6.png') }}" alt=""></a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection