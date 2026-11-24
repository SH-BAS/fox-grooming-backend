@extends('layouts.web')

@section('title', 'Services | Fox Grooming')

@section('styles')
    <style>
        /* Section Background Overlay */
        .services-section {
            position: relative;
        }

        .services-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
        }

        .services-section .container {
            position: relative;
            z-index: 2;
        }

        /* Service list grid */
        .services-grid {
            display: flex;
            flex-wrap: wrap;
        }

        .services-grid > [class*="col-"] {
            display: flex;
        }

        .service-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #eef1f4;
            box-shadow: 0 8px 28px rgba(15, 23, 42, 0.06);
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.1);
        }

        .service-card-image {
            height: 220px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .service-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .service-card:hover .service-card-image img {
            transform: scale(1.05);
        }

        .service-card-body {
            padding: 22px 20px 26px;
            flex: 1;
            display: flex;
            flex-direction: column;
            text-align: center;
            min-height: 0;
        }

        .service-card-title {
            margin: 0 0 12px;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.35;
            color: #f15b22;
        }

        .service-card-desc {
            margin: 0 0 16px;
            font-size: 14px;
            line-height: 1.65;
            color: #1c1c1d;
            flex: 1 1 auto;
            min-height: 0;
            max-height: calc(1.65em * 4);
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overflow-wrap: break-word;
            word-wrap: break-word;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
        }

        .service-card-desc::-webkit-scrollbar {
            width: 5px;
        }

        .service-card-desc::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 999px;
        }

        .service-card-desc::-webkit-scrollbar-track {
            background: transparent;
        }

        .service-card-price {
            margin-top: auto;
            padding-top: 4px;
        }

        .service-card-price .price-fixed {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .service-card-price .price-fixed span {
            color: #f15b22;
        }

        .service-card-price .price-tags {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
        }

        .service-card-price .price-tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
        }

        .services-empty {
            padding: 40px 20px;
            text-align: center;
            color: #6b7280;
        }
        /* Pet category selector (Dog / Cat cards) */
        .pet-category-tabs {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
            border-bottom: none;
            margin-top: 30px;
            padding: 0;
        }

        .pet-category-tabs > li {
            float: none;
            margin: 0;
        }

        .pet-category-tabs > li > a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 140px;
            height: 140px;
            border-radius: 20px;
            background: #f5f5f5 !important;
            color: #333 !important;
            border: none !important;
            padding: 20px 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: none;
        }

        .pet-category-tabs > li > a i {
            display: block !important;
            font-size: 44px !important;
            line-height: 1;
            margin: 0 0 14px !important;
            color: #555 !important;
        }

        .pet-category-tabs > li > a .pet-category-label {
            font-size: 15px;
            font-weight: 500;
            line-height: 1.2;
            color: inherit;
        }

        .pet-category-tabs > li.active > a,
        .pet-category-tabs > li.active > a:hover,
        .pet-category-tabs > li.active > a:focus {
            background: #f15b22 !important;
            color: #fff !important;
            box-shadow: 0 5px 15px rgba(241, 91, 34, 0.3);
        }

        .pet-category-tabs > li.active > a i {
            color: #fff !important;
        }

        .pet-category-tabs > li:not(.active) > a:hover,
        .pet-category-tabs > li:not(.active) > a:focus {
            background: #ececec !important;
            color: #333 !important;
            box-shadow: none;
        }

        .pet-category-tabs > li:not(.active) > a:hover i,
        .pet-category-tabs > li:not(.active) > a:focus i {
            color: #555 !important;
        }

        @media (max-width: 480px) {
            .pet-category-tabs > li > a {
                width: 120px;
                height: 120px;
                border-radius: 18px;
            }

            .pet-category-tabs > li > a i {
                font-size: 36px !important;
                margin-bottom: 10px !important;
            }
        }

        @media (max-width: 768px) {
            .service-card-image {
                height: 200px;
            }

            .service-card-title {
                font-size: 17px;
            }
        }
    </style>
@endsection
@section('content')
    <!-- Section: inner-header -->
    <section class="inner-header hero-banner divider parallax layer-overlay overlay-dark-5"
        data-bg-img="{{ asset('assets/images/service-header.webp') }}">
        <!-- <div class="container pt-100 pb-50">
            <div class="section-content pt-100">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="title text-white">services</h3>
                    </div>
                </div>
            </div>
        </div> -->
    </section>


    <!-- Section: Services -->
    <section class="divider parallax" data-parallax-ratio="0.7" style="padding: 80px 0px;">

        <!-- Top Images -->
        <div class="secondSectionLeftImg">
            <img src="{{ asset('images/homeScreen/secondSectionTopLeft.png') }}">
        </div>
        <div class="secondSectionRightImg">
            <img src="{{ asset('images/homeScreen/secondSectionTopRight.png') }}">
        </div>

        <div class="container">

            <!-- Heading -->
            <div class="text-center mb-40">
                <img src="{{ asset('images/logo-wide.png') }}" alt="">
                <!-- <h2 class="mt-20">Don't forget <span class="text-theme-colored">to leave a review</span></h2> -->
                <p>Your feedback helps us improve our services.</p>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs pet-category-tabs text-center">
                @foreach($categories as $index => $category)
                    <li class="{{ $index == 0 ? 'active' : '' }}">
                        <a data-toggle="tab" href="#service-cat-{{ $category->id }}">
                            <i class="fa fa-paw"></i>
                            <span class="pet-category-label">{{ $category->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-40">

                @foreach($categories as $index => $category)
                    <div id="service-cat-{{ $category->id }}" class="tab-pane fade {{ $index == 0 ? 'in active' : '' }}">

                        @php
                            $categoryServices = $category->services->where('is_active', 1);
                        @endphp

                        @if($categoryServices->count() > 0)
                            <div class="row services-grid">

                                @foreach($categoryServices as $service)
                                    <div class="col-lg-4 col-md-6 col-sm-6 mb-30">

                                        <div class="service-card">
                                            <div class="service-card-image">
                                                <img src="{{ $service->image }}" alt="{{ $service->name }}">
                                            </div>

                                            <div class="service-card-body">
                                                <h4 class="service-card-title">{{ $service->name }}</h4>

                                                @if($service->description)
                                                    <p class="service-card-desc">{{ $service->description }}</p>
                                                @endif

                                                <!-- <div class="service-card-price">
                                                    @if($service->pricing_type == 'fixable' && $service->sizes->count() > 0)
                                                        <p class="price-fixed mb-0">
                                                            <span>From</span>
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

                                    </div>
                                @endforeach

                            </div>
                        @else
                            <div class="services-empty">
                                <p class="mb-0">No services available for {{ $category->name }} at the moment.</p>
                            </div>
                        @endif

                    </div>
                @endforeach

            </div>

        </div>
    </section>

    <!-- Section: Why Choose Us  -->
    <section class="bg-lightest">
        <div class="container pb-0">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-7 content-readable">
                        <h2 class="mb-30 mt-0">Why Choose Us?</h2>
                        <p>Choosing Fox Grooming Mobile Pet Services means giving your pet a superior level of care, comfort, and convenience that traditional grooming providers often cannot match. Our professional groomer comes directly to your doorstep, eliminating the need for time-consuming travel and making it easier to fit grooming into your busy schedule.</p>
                        <p>For your pet, this personalised approach greatly reduces stress by avoiding car journeys and busy salon environments filled with unfamiliar animals and noise. Instead, your pet enjoys a calm, one-on-one grooming experience, where their individual needs and wellbeing are the sole focus.</p>
                        <p>Our mobile service also provides a cleaner and safer environment, minimising exposure to parasites and illnesses that can occur in high-traffic grooming salons. With no waiting times and a dedicated session for each pet, appointments are efficient without ever compromising on quality.</p>
                        <p>At Fox Grooming, we are committed to delivering a professional, personalised service that prioritises your pet’s comfort, reduces anxiety, and offers you complete convenience from start to finish.</p>
                        <!-- <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="icon-box border-1px p-15 mb-30">
                                    <a class="icon pull-left sm-pull-none flip" href="#">
                                        <i class="flaticon-pet-dog-1 text-theme-colored"></i>
                                    </a>
                                    <div class=" ml-70 ml-sm-0">
                                        <h5 class="icon-box-title mt-15 mb-5 text-uppercase">Best in industry</h5>
                                        <p>Lorem ipsum dolor sit amet, consectetur.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="icon-box border-1px p-15 mb-30">
                                    <a class="icon pull-left sm-pull-none flip" href="#">
                                        <i class="flaticon-pet-people text-theme-colored"></i>
                                    </a>
                                    <div class="ml-70 ml-sm-0">
                                        <h5 class="icon-box-title mt-15 mb-5 text-uppercase">Customer support</h5>
                                        <p>Lorem ipsum dolor sit amet, consectetur.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="icon-box border-1px p-15 mb-30">
                                    <a class="icon pull-left sm-pull-none flip" href="#">
                                        <i class="flaticon-pet-emergency-ambulance text-theme-colored"></i>
                                    </a>
                                    <div class="ml-70 ml-sm-0">
                                        <h5 class="icon-box-title mt-15 mb-5 text-uppercase">Emergency services</h5>
                                        <p>Lorem ipsum dolor sit amet, consectetur.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="icon-box border-1px p-15 mb-30">
                                    <a class="icon pull-left sm-pull-none flip" href="#">
                                        <i class="flaticon-pet-animal text-theme-colored"></i>
                                    </a>
                                    <div class="ml-70 ml-sm-0">
                                        <h5 class="icon-box-title mt-15 mb-5 text-uppercase">Veterinary help</h5>
                                        <p>Lorem ipsum dolor sit amet, consectetur.</p>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="col-md-4 col-md-offset-1">
                        <img alt="" src="{{ asset('images/about/4.jpg') }}" class="img-fullwidth service-choose-us-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Divider: Call To Action  -->
    <section class="bg-theme-colored">
        <div class="container pt-0 pb-30">
            <div class="row">
                <div class="call-to-action p-0 pt-30 pb-20">
                    <div class="col-md-9">
                        <h3 class="mt-5 text-white">Professional Solutions for Pet Services!</h3>
                    </div>
                    <!-- <div class="col-md-3 text-right flip sm-text-center">
                        <a href="#" class="btn btn-default btn-circled btn-lg mt-5">Purchase Now<i
                                class="fa fa-angle-double-right font-16 ml-10"></i></a>
                    </div> -->
                </div>
            </div>
        </div>
    </section>

    <!-- Section: team Our Doctors -->
        <!-- <section>
            <div class="container">
                <div class="section-title">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="line-height-1 mt-0 mb-0">Meet Our <span class="text-theme-colored">Veterinary
                                    Doctor</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="owl-carousel-4col" data-dots="true">
                                <div class="item">
                                    <div class="team-member maxwidth400">
                                        <div class="thumb"><img alt="" src="{{ asset('images/team/1.jpg') }}"
                                                class="img-fullwidth"></div>
                                        <div class="info">
                                            <h4>Jone doe <small>- Veterinary Doctor</small></h4>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisi cing elit. Consectetur
                                                adipisicing elit.
                                                Tempore provident accusamus est asperiores illum eius is.</p>
                                            <ul
                                                class="styled-icons icon-theme-colored icon-circled icon-dark icon-sm mt-15 mb-0">
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fa fa-skype"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="team-member maxwidth400">
                                        <div class="thumb"><img alt="" src="{{ asset('images/team/2.jpg') }}"
                                                class="img-fullwidth"></div>
                                        <div class="info">
                                            <h4>Jone doe <small>- Veterinary Doctor</small></h4>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisi cing elit. Consectetur
                                                adipisicing elit.
                                                Tempore provident accusamus est asperiores illum eius is.</p>
                                            <ul
                                                class="styled-icons icon-theme-colored icon-circled icon-dark icon-sm mt-15 mb-0">
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fa fa-skype"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="team-member maxwidth400">
                                        <div class="thumb"><img alt="" src="{{ asset('images/team/3.jpg') }}"
                                                class="img-fullwidth"></div>
                                        <div class="info">
                                            <h4>Jone doe <small>- Veterinary Doctor</small></h4>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisi cing elit. Consectetur
                                                adipisicing elit.
                                                Tempore provident accusamus est asperiores illum eius is.</p>
                                            <ul
                                                class="styled-icons icon-theme-colored icon-circled icon-dark icon-sm mt-15 mb-0">
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fa fa-skype"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="team-member maxwidth400">
                                        <div class="thumb"><img alt="" src="{{ asset('images/team/4.jpg') }}"
                                                class="img-fullwidth"></div>
                                        <div class="info">
                                            <h4>Jone doe <small>- Veterinary Doctor</small></h4>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisi cing elit. Consectetur
                                                adipisicing elit.
                                                Tempore provident accusamus est asperiores illum eius is.</p>
                                            <ul
                                                class="styled-icons icon-theme-colored icon-circled icon-dark icon-sm mt-15 mb-0">
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fa fa-skype"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="team-member maxwidth400">
                                        <div class="thumb"><img alt="" src="{{ asset('images/team/2.jpg') }}"
                                                class="img-fullwidth"></div>
                                        <div class="info">
                                            <h4>Jone doe <small>- Veterinary Doctor</small></h4>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisi cing elit. Consectetur
                                                adipisicing elit.
                                                Tempore provident accusamus est asperiores illum eius is.</p>
                                            <ul
                                                class="styled-icons icon-theme-colored icon-circled icon-dark icon-sm mt-15 mb-0">
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fa fa-skype"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
@endsection
