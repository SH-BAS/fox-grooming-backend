@extends('layouts.web')

@section('title', 'Home | Fox Grooming')
@section('styles')
    <style>
        .container {
            position: relative;
        }
        /* Swiper Custom styling */
        .reviews-swiper {
            width: 100%;
            padding: 50px 0 !important;
        }
        .ReviewsSectionContent {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            margin: 10px;
        }
        .ReviewsSectionContent:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        .starsReviews {
            color: #ffc107;
            margin-bottom: 15px;
        }
        .ReviewerInfo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f8f9fa;
        }
        .swiper-pagination-bullet-active {
            background-color: var(--theme-colored) !important;
        }

        /* Home services side-by-side slider */
        .home-services-row {
            align-items: stretch;
        }

        .home-services-image-col {
            display: flex;
            flex-direction: column;
        }

        .home-services-slider-panel {
            height: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 32px 28px;
            border-radius: 4px;
        }

        .home-services-carousel-shell {
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
            min-height: 280px;
            padding: 0 44px;
        }

        .home-services-swiper {
            width: 100%;
            height: 100%;
            padding: 8px 0 0 !important;
        }

        .home-services-swiper .swiper-slide {
            height: auto;
            display: flex;
        }

        .home-service-card {
            width: 100%;
            height: 100%;
            background: #fff;
            border: 1px solid #e8edf2;
            border-radius: 18px;
            padding: 22px 18px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.07);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 240px;
        }

        .home-service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.12);
        }

        .home-service-card-image {
        width: 150px;
        height: 150px;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 16px;
        flex-shrink: 0;
        border: 3px solid #f3f4f6;
}

        .home-service-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .home-service-card-title {
            margin: 0 0 12px;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.35;
            min-height: 44px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .home-service-card-price {
            margin-top: auto;
            width: 100%;
        }

        .home-service-card-price .price-fixed {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .home-service-card-price .price-fixed span {
            color: var(--theme-colored, #5cb85c);
        }

        .home-service-card-price .price-tags {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
        }

        .home-service-card-price .price-tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
        }

        .home-services-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            background: #fff;
            color: #111827;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: all 0.25s ease;
        }

        .home-services-nav:hover {
            background: var(--theme-colored, #5cb85c);
            color: #fff;
        }

        .home-services-nav.swiper-button-disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }

        .home-services-prev {
            left: 0;
        }

        .home-services-next {
            right: 0;
        }

        .home-services-pagination {
            position: relative !important;
            margin-top: 18px;
            bottom: auto !important;
        }

        .home-services-view-all {
            margin-top: 20px;
            text-align: center;
        }

        @media (max-width: 991px) {
            .home-services-slider-panel {
                margin-top: 24px;
                padding: 28px 20px 22px;
            }

            .home-services-carousel-shell {
                padding: 0 40px;
                min-height: 260px;
            }
        }

        @media (max-width: 767px) {
            .home-services-carousel-shell {
                padding: 0 36px;
            }

            .home-services-nav {
                width: 34px;
                height: 34px;
            }

            .home-service-card {
                min-height: 220px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endsection
@section('content')

    <!-- Section: home -->
    <section id="home">
        <div class="container-fluid p-0">
            <div class="Main-bannerSection">
                <div class="Main-bannerSection-content d-flex align-items-end justify-content-center">
                    <div class="Main-bannerSection-content-inner text-center">
                        <h2 class="text-uppercase font-weight-600 mb-0">REQUEST INFORMATION</h2>
                        <h4 class="font-weight-400 mt-0" style="padding:20px"><em>We will get in touch via phone, email or text to discuss our
                                Mobile Pet Grooming services.</em></h4>
                        <div class="Main-bannerSection-content-inner-form">
                            <form action="{{ route('web.checkZip') }}" method="POST">
                                @csrf
                                <input type="text" name="zip_code" placeholder="Enter Zip Code" class="form-control"
                                    required>
                                <button type="submit"
                                    class="btn btn-flat btn-theme-colored text-uppercase mt-20 border-left-theme-color-2-4px">Book
                                    Now – Pamper Your Pet Today!</button>
                            </form>
                            @if(session('zip_error'))
                                <span class="text-danger mt-10" style="color: red;">{{ session('zip_error') }}</span>
                            @endif
                        </div>
                        <img src="{{ asset('images/homeScreen/car.png') }}" alt="" style="width: 300px; height: 250px; margin-top: -50px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="divider parallax" data-parallax-ratio="0.7" style="padding: 80px 0px;">
        <div class="secondSectionLeftImg" data-aos="fade-down-right" data-aos-offset="300" data-aos-easing="ease-in-sine">
            <img alt="" src="{{ asset('images/homeScreen/secondSectionTopLeft.png') }}">
        </div>
        <div class="secondSectionRightImg" data-aos="fade-down-left" data-aos-offset="300" data-aos-easing="ease-in-sine">
            <img alt="" src="{{ asset('images/homeScreen/secondSectionTopRight.png') }}">
        </div>
        <div class="container pt-0 pb-0">
            <div class="secondSectionTopContent d-flex flex-column align-items-center justify-content-center">
                <img src="{{ asset('images/logo-wide.png') }}" alt="">
                <div class="w-100 mb-40 text-center">
                    <h2 class="mb-10 mt-0 line-height-1 text-uppercase">Mobile Pet <span class="text-theme-colored">
                            Grooming Services</span></h2>
                    <p>Fox Grooming is a Nationwide Company specialised in offering the best Mobile Dog Grooming Services.
                    </p>
                </div>
            </div>
            <div class="row home-services-row">
                <div class="col-lg-4 col-md-12 home-services-image-col">
                    <div class="row mb-10 pt-10">
                        <div class="col-md-12">
                            <div class="secondSectionImg" data-aos="fade-up" data-aos-offset="500"
                                data-aos-easing="ease-in-sine">
                                <img alt="" src="{{ asset('images/homeScreen/secondSectionLeft MainImg.png') }}" sizes="(max-width: 472px) 100vw, 472px" class="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12">
                    <div class="home-services-slider-panel" data-aos="fade-up" data-aos-offset="400" data-aos-easing="ease-in-sine">
                        @if ($services->count() > 0)
                            <div class="home-services-carousel-shell">
                                <button type="button" class="home-services-nav home-services-prev" aria-label="Previous services">
                                    <i class="fa fa-chevron-left"></i>
                                </button>

                                <div class="swiper home-services-swiper">
                                    <div class="swiper-wrapper">
                                        @foreach ($services as $service)
                                            <div class="swiper-slide">
                                                <div class="home-service-card">
                                                    <div class="home-service-card-image">
                                                        <img src="{{ $service->image }}" alt="{{ $service->name }}">
                                                    </div>
                                                    <h4 class="home-service-card-title text-theme-colored font-weight-600">{{ $service->name }}</h4>
                                                    <!-- <div class="home-service-card-price">
                                                        @if($service->pricing_type == 'fixable' && $service->sizes->count() > 0)
                                                            <p class="price-fixed mb-0">
                                                                <span class="text-theme-colored">Price:</span>
                                                                ${{ (int) $service->sizes->first()->price }}
                                                            </p>
                                                        @elseif($service->pricing_type == 'sizeable' && $service->sizes->count() > 0)
                                                            <div class="price-tags">
                                                                @foreach($service->sizes as $sizePrice)
                                                                    <span class="price-tag">
                                                                        {{ $sizePrice->size->name ?? 'Any' }}: ${{ (int) $sizePrice->price }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div> -->
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button type="button" class="home-services-nav home-services-next" aria-label="Next services">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>

                            <div class="home-services-pagination swiper-pagination"></div>

                            <div class="home-services-view-all">
                                <a href="{{ route('web.services') }}" class="btn btn-flat btn-theme-colored text-uppercase border-left-theme-color-2-4px">
                                    View All Services
                                </a>
                            </div>
                        @else
                            <div class="thumb text-center">
                                <h4 class="icon-box-title mt-20 mb-20 text-uppercase letter-sapce-3 font-weight-400">
                                    <a href="#">No services found</a>
                                </h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ThirdSectionServices">
        <div class="ThirdSectionTopImg">
            <img src="{{ asset('images/homeScreen/shape-white.png') }}" alt="">
        </div>
        <div class="container pr-100 pl-100">
            <!-- <div class="row">
                <div class="ThirdSectionTopContent">
                    <img src="{{ asset('images/homeScreen/thirdSectionTopImg.png') }}" alt="">
                    <h2 class="redColor">Our Grooming Services</h2>
                    <h4 class="text-white">From Bath Time to Bow-Wow!</h4>
                    <h4 class="text-white">We offer full-service grooming for dogs and cats of all sizes and breeds.</h4>
                </div>
            </div>
            <div class="row">
                <div class="ThirdSectionMeinContent">
                    <div class="row">
                        @if ($services->count() > 0)
                            @foreach ($services as $service)
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="ThirdSectionContentBox">
                                        <img src="{{ $service->image }}" alt="">
                                        <div>
                                            <h5 class="redColor">{{ $service->name }}</h5>
                                            <p>{{ $service->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-12">
                                <div class="thumb text-center">
                                    <h4 class="icon-box-title mt-20 mb-20 text-uppercase letter-sapce-3 font-weight-400">
                                        <a href="#">No services found</a>
                                    </h4>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div> -->
        </div>
        <div class="ThirdSectionBottomImg" data-aos="fade-right" data-aos-offset="300" data-aos-easing="ease-in-sine">
            <img src="{{ asset('images/homeScreen/car.png') }}" alt="">
        </div>
    </section>


    <section class="FourthSectionHow">
        <div class="container pr-100 pl-100">
            <img class="fourthSectionLeftImg" src="{{ asset('images/homeScreen/ForthSection2.png') }}" alt="">
            <div class="row"  style="margin-bottom: 50px;">
                <div class="FourthSectionHowSideLeft col-md-4 col-sm-12">

                </div>
                <div class="FourthSectionHowSideRight col-md-8 col-sm-12">
                    <div class="meet-doctors">
                        <h2 class="text-uppercase mt-20"><span class="text-theme-colored">How Mobile Grooming Works</span>
                        </h2>
                        <p>Once your booking is confirmed, our professional dog groomers will arrive at your home at the
                            schedule time with a fully equipped Van that has all the necessary tools and supplies to provide
                            an amazing.</p>
                        <p>grooming experience for your pet. Once the pet grooming is completed, the dog groomer will bring
                            the back back to your home.</p>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="FourthSectionSerize">
                                <div class="serizeBox">
                                    <h3 class="text-white m-0">01</h3>
                                </div>
                                <h4 class="text-dark">Book Online or Call</h4>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="FourthSectionSerize">
                                <div class="serizeBox">
                                    <h3 class="text-white m-0">02</h3>
                                </div>
                                <h4 class="text-dark">We Arrive at Your Home</h4>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="FourthSectionSerize">
                                <div class="serizeBox">
                                    <h3 class="text-white m-0">03</h3>
                                </div>
                                <h4 class="text-dark">Groom & Pamper</h4>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="FourthSectionSerize">
                                <div class="serizeBox">
                                    <h3 class="text-white m-0">04</h3>
                                </div>
                                <h4 class="text-dark">Happy Pet, Happy You</h4>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row mt-40">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="FourthSectionImgContent">
                                <div class="FourthSectionContent">
                                    <h2>30+</h2>
                                    <p class="ml-15">Our 30+ years of pet care expertise.</p>
                                </div>
                                <div class="FourthSectionImg1">
                                    <img src="{{ asset('images/homeScreen/ForthSection1.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="FourthSectionImgContent">
                                <div class="FourthSectionImg2">
                                    <img src="{{ asset('images/homeScreen/ForthSection4.png') }}" alt="">
                                </div>
                                <div class="FourthSectionContent">
                                    <h2>120K+</h2>
                                    <p class="ml-15">Successfully finished project with creativity</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="FourthSectionImgContent">
                                <div class="FourthSectionContent">
                                    <h2>99K+</h2>
                                    <p class="ml-15">Client easily conversions growth increased.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="FourthSectionImgContent">
                                <div class="FourthSectionImg3">
                                    <img src="{{ asset('images/homeScreen/ForthSection3.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>

    <section class="FifthSectionFrom">
        <!-- <div class="FormSection"> -->
        <div class="container FormSection" >
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top: 150px;">
                    <div class="FifthSectionFromContent">
                        <h2 class="text-uppercase mt-20">Spa in a Day in Just a <span class="text-theme-colored">Click
                                Away!</span></h2>
                        <h6 class="text-black">Ready to treat your pet? Click below to schedule your grooming session today
                        </h6>
                        <form id="home_contact_form" action="{{ route('web.contact.submit') }}" method="POST"
                            class="FifthSectionFromFields">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="">First name*</label>
                                    <input class="form-control" type="text" name="first_name" required>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="">Last name*</label>
                                    <input class="form-control" type="text" name="last_name" required>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="">Phone Number*</label>
                                    <input class="form-control" type="text" name="phone" required>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="">Email</label>
                                    <input class="form-control" type="email" name="email" required>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <label for="">Message</label>
                                    <textarea class="form-control" name="message" rows="4"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <div id="home-form-result" class="alert mt-20" role="alert" style="display: none;">
                                    </div>
                                </div>
                                <button type="submit" data-loading-text="Please wait..."
                                    class="btn btn-flat btn-theme-colored text-uppercase mt-20 mb-sm-30 border-left-theme-color-2-4px">Submit
                                    Now</button>
                            </div>
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                if (window.jQuery) {
                                    $('#home_contact_form').on('submit', function (e) {
                                        e.preventDefault();
                                        var form = $(this);
                                        var form_btn = form.find('button[type="submit"]');
                                        var form_result_div = '#home-form-result';
                                        var form_btn_old_msg = form_btn.html();

                                        form_btn.html(form_btn.data("loading-text")).prop('disabled', true);
                                        $(form_result_div).hide();

                                        $.ajax({
                                            url: form.attr('action'),
                                            type: 'POST',
                                            data: form.serialize(),
                                            success: function (response) {
                                                form_btn.html(form_btn_old_msg).prop('disabled', false);
                                                if (response.status == 'success') {
                                                    form[0].reset();
                                                    $(form_result_div).removeClass('alert-danger').addClass('alert-success').html(response.message).fadeIn('slow');
                                                } else {
                                                    $(form_result_div).removeClass('alert-success').addClass('alert-danger').html(response.message).fadeIn('slow');
                                                }
                                                setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                                            },
                                            error: function () {
                                                form_btn.html(form_btn_old_msg).prop('disabled', false);
                                                $(form_result_div).removeClass('alert-success').addClass('alert-danger').html('An error occurred. Please try again.').fadeIn('slow');
                                                setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                                            }
                                        });
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- </div> -->
    </section>

    <!-- <section class="divider parallax" data-parallax-ratio="0.7" style="padding: 80px 0px;">
        <div class="secondSectionLeftImg" data-aos="fade-down-right" data-aos-offset="300" data-aos-easing="ease-in-sine">
            <img alt="" src="{{ asset('images/homeScreen/secondSectionTopLeft.png') }}">
        </div>
        <div class="secondSectionRightImg" data-aos="fade-down-left" data-aos-offset="300" data-aos-easing="ease-in-sine">
            <img alt="" src="{{ asset('images/homeScreen/secondSectionTopRight.png') }}">
        </div>
        <div class="container pt-0 pb-0">
            <div class="secondSectionTopContent d-flex flex-column align-items-center justify-content-center">
                <img src="{{ asset('images/logo-wide.png') }}" alt="">
                <div class="w-100 mb-40 text-center">
                    <h2 class="mb-10 mt-0 line-height-1 text-uppercase">don't forget <span class="text-theme-colored"> to
                            leave a review</span></h2>
                    <p>Your feedback is very important to us and we are encouraging you to help us know what we are doing
                        good as well as what we can improve to your services.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="swiper reviews-swiper">
                        <div class="swiper-wrapper">
                            @forelse($reviews as $review)
                                <div class="swiper-slide">
                                    <div class="ReviewsSectionContent">
                                        <div class="starsReviews">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <h4 class="text-black mb-15" style="height: 50px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            {{ $review->user->name ?? 'Happy Client' }}
                                        </h4>
                                        <p class="text-black mb-20" style="height: 100px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                                            {{ $review->comment }}
                                        </p>
                                        <div class="ReviewerInfo d-flex align-items-center">
                                            <img src="{{ $review->user->image ?? asset('assets/images/user_placeholder.jpg') }}" alt="{{ $review->user->name ?? 'User' }}">
                                            <div class="ReviewerInfoContent ml-15">
                                                <h6 class="text-black font-weight-600 m-0">{{ $review->user->name ?? 'Guest' }}</h6>
                                                <p class="text-muted small m-0">{{ $review->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                @php
                                    $mockReviews = [
                                        ['name' => 'Linda Heyes', 'comment' => 'Our dog Buddy always comes back looking like a champion. The mobile service is incredibly convenient and the team is so gentle with him.', 'img' => 'reviews2.png'],
                                        ['name' => 'John Doe', 'comment' => 'Professional, on time, and very skilled. My cat is usually very nervous about grooming, but they handled her with such care and patience.', 'img' => 'reviews3.png'],
                                        ['name' => 'Heather D. Mao', 'comment' => 'Fantastic experience! The van is super clean and fully equipped. Highly recommend Fox Grooming for anyone who loves their pets.', 'img' => 'reviews1.png'],
                                    ];
                                @endphp
                                @foreach($mockReviews as $mock)
                                    <div class="swiper-slide">
                                        <div class="ReviewsSectionContent">
                                            <div class="starsReviews">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                            <h4 class="text-black mb-15">{{ $mock['name'] }}</h4>
                                            <p class="text-black mb-20">{{ $mock['comment'] }}</p>
                                            <div class="ReviewerInfo d-flex align-items-center">
                                                <img src="{{ asset('images/homeScreen/' . $mock['img']) }}" alt="">
                                                <div class="ReviewerInfoContent ml-15">
                                                    <h6 class="text-black font-weight-600 m-0">{{ $mock['name'] }}</h6>
                                                    <p class="text-muted small m-0">Verified Client</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforelse
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.home-services-swiper')) {
            new Swiper('.home-services-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.home-services-next',
                    prevEl: '.home-services-prev',
                },
                pagination: {
                    el: '.home-services-pagination',
                    clickable: true,
                },
                autoplay: {
                    delay: 4500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                },
            });
        }

        new Swiper('.reviews-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            pagination: {
                el: '.reviews-swiper .swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    });
</script>
@endsection