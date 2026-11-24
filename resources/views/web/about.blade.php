@extends('layouts.web')
@section('title', 'About Us | Fox Grooming')

@section('styles')
    <style>
        .about-services-grid {
            display: flex;
            flex-wrap: wrap;
        }

        .about-services-grid > [class*="col-"] {
            display: flex;
        }

        .about-service-item {
            width: 100%;
            text-align: center;
            padding: 8px 6px 20px;
            margin-bottom: 6px;
        }

        .about-service-image {
            width: 120px;
            height: 120px;
            margin: 0 auto 14px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #f3f4f6;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            flex-shrink: 0;
        }

        .about-service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .about-service-title {
            margin: 0;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.45;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #374151;
            min-height: 52px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .about-service-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.25s ease;
        }

        .img-fullwidth{
            height: 500px;
            margin-top: 10px;
        }
        .about-service-item:hover .about-service-title a {
            color: #f15b22;
        }

        .about-service-item:hover .about-service-image {
            border-color: rgba(241, 91, 34, 0.25);
            box-shadow: 0 10px 28px rgba(241, 91, 34, 0.18);
        }

        @media (max-width: 767px) {
            .about-service-image {
                width: 130px;
                height: 130px;
            }

            .about-service-title {
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Section: inner-header -->
    <section class="inner-header hero-banner divider parallax layer-overlay overlay-dark-5"data-bg-img="{{ asset('images/bg/bg1.jpg') }}" >
        <!-- <div class="container pt-100 pb-50">
            <div class="section-content pt-100">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="title text-white">About</h3>
                    </div>
                </div>
            </div>
        </div> -->
    </section>


    <!-- Section: About -->
    <section id="about">
        <div class="container pt-60 pb-80">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-7 content-readable">
                        <h2 class="mt-0">Welcome to Pet Care</h2>
                        <p>At Fox Grooming Mobile Pet Services, we are a business devoted to delivering a refined, boutique grooming experience where quality, care, and attention to detail are at the heart of everything we do. We believe that every pet deserves not just grooming, but a truly personalised and elevated experience—one that reflects the love and value you place on them as part of your family.</p>
                        <p>Our team of professionally trained and highly experienced groomers brings extensive expertise across all breeds, coat types, and temperaments. From delicate puppies to senior companions and even the most nervous or high-maintenance dogs, we approach each pet with confidence, patience, and a deep understanding of their individual needs. Our experience allows us to provide not only exceptional grooming results but also a calm, reassuring environment where your pet feels safe, respected, and genuinely cared for.</p>
                        <p>Designed with a luxury, stress-free philosophy, our fully equipped mobile grooming service arrives at your doorstep, offering complete privacy and one-on-one attention. This exclusive setting eliminates the anxiety of busy salons, ensuring your pet enjoys a peaceful, uninterrupted experience tailored entirely to them. Every detail of our service—from the products we use to the way we handle and care for your pet—is carefully considered to achieve the highest standards of comfort, hygiene, and finish.</p>
                        <p>At Fox Grooming, we don’t simply groom—we curate a premium experience defined by trust, expertise, and genuine passion. Our goal is to create a seamless, indulgent service for you, while giving your pet the exceptional treatment they truly deserve.</p>

                        <!-- @if ($services->count() > 0)
                            <div class="row about-services-grid mt-30">
                                @foreach ($services as $service)
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="about-service-item">
                                            <div class="about-service-image">
                                                <img src="{{ $service->image }}" alt="{{ $service->name }}">
                                            </div>
                                            <h4 class="about-service-title">
                                                <a href="{{ route('web.services') }}">{{ $service->name }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mt-30 mb-0">No services found.</p>
                        @endif -->
                    </div>
                    <div class="col-md-5">
                        <img class="img-fullwidth" src="{{ asset('images/homeScreen/car.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Divider: Call To Action  -->
    <!-- <section class="bg-theme-colored">
        <div class="container pt-0 pb-30">
            <div class="row">
                <div class="call-to-action p-0 pt-30 pb-20">
                    <div class="col-md-9">
                        <h3 class="mt-5 text-white">Best Pet Care & Pet Services </h3>
                    </div>
                    <div class="col-md-3 text-right flip sm-text-center">
                        <a href="#" class="btn btn-default btn-circled btn-lg mt-5">Purchase Now<i
                                class="fa fa-angle-double-right font-16 ml-10"></i></a>
                    </div> 
                </div>
            </div>
        </div>
    </section> -->


    <!-- Section: Why Choose Us  -->
    <!-- <section>
        <div class="container pb-0">
            <div class="section-content">
                <div class="row">
                <div class="col-md-7">
                        <h2 class="mb-30 mt-0 line-height-1">Why Choose Us?</h2>
                        <p class="mb-20">Choosing Fox Grooming Mobile Pet Services means giving your pet a superior level of care, comfort, and convenience that traditional grooming providers often cannot match. Our professional groomer comes directly to your doorstep, eliminating the need for time-consuming travel and making it easier to fit grooming into your busy schedule.
                        For your pet, this personalised approach greatly reduces stress by avoiding car journeys and busy salon environments filled with unfamiliar animals and noise. Instead, your pet enjoys a calm, one-on-one grooming experience, where their individual needs and wellbeing are the sole focus.
                        Our mobile service also provides a cleaner and safer environment, minimising exposure to parasites and illnesses that can occur in high-traffic grooming salons. With no waiting times and a dedicated session for each pet, appointments are efficient without ever compromising on quality.
                        At Fox Grooming, we are committed to delivering a professional, personalised service that prioritises your pet’s comfort, reduces anxiety, and offers you complete convenience from start to finish.
                        </p>
                        <div class="row">
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
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-1">
                        <img alt="" src="{{ asset('images/about/4.jpg') }}" class="img-fullwidth service-choose-us-img">
                    </div>
                </div>
            </div>
        </div>
    </section> -->


    <!-- Section: team Our Doctors-->
    <!-- <section id="services" class="layer-overlay overlay-white-4" data-bg-img="{{ asset('images/pattern/p8.png') }}">
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