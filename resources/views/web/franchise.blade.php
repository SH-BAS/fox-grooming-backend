@extends('layouts.web')
@section('title', 'Franchise | Fox Grooming')
@section('styles')
    <style>
        /* Franchise package cards */
        .package-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            padding: 30px 25px;
            margin-bottom: 30px;
            position: relative;
            transition: box-shadow .3s, transform .3s;
            text-align: center
        }

        .package-card:hover {
            box-shadow: 0 10px 35px rgba(0, 0, 0, .12);
            transform: translateY(-5px)
        }

        .package-card.featured {
            border: 2px solid #8bc34a
        }

        .package-card .popular-ribbon {
            position: absolute;
            top: -1px;
            right: 20px;
            background: #8bc34a;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 14px;
            border-radius: 0 0 4px 4px
        }

        .package-card .package-price {
            font-size: 42px;
            font-weight: 700;
            line-height: 1;
            color: #333;
            margin: 15px 0 5px
        }

        .package-card .package-price sup {
            font-size: 20px;
            vertical-align: top;
            margin-top: 10px
        }

        .package-card .package-price span {
            font-size: 14px;
            color: #777;
            font-weight: 400
        }

        .package-card ul.package-features {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            text-align: left
        }

        .package-card ul.package-features li {
            padding: 7px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #374151
        }

        .package-card ul.package-features li i {
            margin-right: 8px;
            color: #8bc34a
        }

        /* Process steps */
        .step-box {
            text-align: center;
            padding: 20px
        }

        .step-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #8bc34a;
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            line-height: 60px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .step-connector {
            border-top: 2px dashed #8bc34a;
            margin-top: 30px;
            display: none
        }

        @media(min-width:992px) {
            .step-connector {
                display: block
            }
        }

        /* Testimonial */
        .franchise-testimonial {
            background: #f9f9f9;
            border-left: 4px solid #8bc34a;
            padding: 25px 30px;
            border-radius: 0 4px 4px 0;
            margin-bottom: 25px
        }

        .franchise-testimonial p {
            font-style: italic;
            color: #374151;
            margin-bottom: 10px;
            font-size: 15px;
            line-height: 1.75
        }

        /* Map/Location placeholder */
        .territory-map {
            background: #e8f5e9;
            border: 2px dashed #8bc34a;
            border-radius: 4px;
            padding: 60px 20px;
            text-align: center;
            color: #4a7c59
        }

        .territory-map i {
            font-size: 48px;
            margin-bottom: 10px;
            display: block
        }

        /* CTA section */
        .franchise-cta {
            background: linear-gradient(135deg, #5a8a1a 0%, #8bc34a 100%);
            padding: 60px 0
        }

        /* Enquiry form */
        .enquiry-section {
            background: #f4f9ee;
            padding: 60px 0
        }

        .enquiry-section .form-control {
            border-radius: 3px;
            height: 44px
        }

        .enquiry-section textarea.form-control {
            height: auto
        }

        /* Stat */
        .fran-stat {
            text-align: center;
            padding: 20px 10px
        }

        .fran-stat .num {
            font-size: 42px;
            font-weight: 700;
            line-height: 1
        }

        .fran-stat .lbl {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px
        }

        /* Accordion FAQ */
        .faq-item {
            border-bottom: 1px solid #e8e8e8
        }

        .faq-question {
            padding: 14px 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #111827
        }

        .faq-question:hover {
            color: #8bc34a
        }

        .faq-answer {
            display: none;
            padding-bottom: 14px;
            color: #374151;
            line-height: 1.8;
            font-size: 15px
        }

        .faq-answer.open {
            display: block
        }

        .faq-toggle {
            font-size: 20px;
            transition: transform .3s
        }

        .faq-toggle.rotated {
            transform: rotate(45deg)
        }

        .img-fullwidth{
            height: 500px;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <!-- Hero Banner -->
    <section class="inner-header hero-banner divider parallax layer-overlay overlay-dark-5"
        data-bg-img="{{ asset('images/bg/bg1.jpg') }}">
        <!-- <div class="container pt-100 pb-50">
            <div class="section-content pt-100">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="title text-white">Franchise Opportunities</h3>
                        <ol class="breadcrumb text-white mt-10">
                            <li><a href="index-mp-layout2.html" class="text-white">Home</a></li>
                            <li class="active text-white">Franchise Opportunities</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> -->
    </section>

    <!-- Intro -->
    <section>
        <div class="container pt-60 pb-20">
            <div class="row">
                <div class="col-md-7 content-readable">
                    <h2 class="mt-0">Join a growing industry. Build a business you love. Deliver care that matters.</h2>
                    <p class="mb-20">At Fox Grooming Mobile Pet Services, we are proud to be part of a thriving and ever-expanding pet care industry, where today’s pet owners are seeking not only convenience, but exceptional quality and personalised service. As demand for mobile grooming continues to grow, we are delighted to offer exclusive franchise opportunities for individuals who are passionate about animal care and driven to build a successful, premium service business.</p>
                    <p>Joining the Fox Grooming franchise family means becoming part of a trusted, family-led brand that has been carefully built on a foundation of professionalism, expertise, and genuine care. Our approach is centred around delivering a calm, high-end grooming experience that prioritises the wellbeing of each pet, and this standard is reflected in every aspect of our brand. As a franchise partner, you will represent these values within your own territory, bringing our signature service to your local community.</p>
                    <p>We provide a comprehensive and supportive framework designed to set you up for long-term success. This includes professional training, guidance in operating a fully equipped mobile grooming unit, and access to our proven systems and processes. From day-to-day operations to customer service excellence and brand positioning, we ensure that you are fully prepared to confidently run your own Fox Grooming business to the highest standard.</p>
                    <p>In addition to initial training, you will benefit from ongoing support and mentorship, helping you grow, adapt, and thrive in a competitive market. We also offer guidance on local marketing, customer retention strategies, and maintaining the premium level of service that defines our reputation. Our goal is to empower you with both the skills and the confidence needed to build a business that is not only profitable, but also personally rewarding.</p>
                    <p>This is more than just a franchise—it is an opportunity to invest in a fast-growing industry, operate under a respected and recognisable brand, and create a service that genuinely enhances the lives of pets and their owners. With increasing demand for mobile grooming and a strong shift towards convenience-led services, there has never been a better time to become part of the Fox Grooming journey.</p>
                    <!-- <a href="{{ route('web.book.franchise') }}"
                        class="btn btn-theme-colored btn-lg text-white text-uppercase mt-20 mr-10">Get
                        Started
                        Today <i class="fa fa-arrow-right ml-10"></i></a> -->
                    <!-- <a href="{{ route('web.pricing') }}"
                        class="btn btn-border btn-theme-colored btn-lg text-uppercase mt-20">View
                        Prices</a> -->
                </div>
                <div class="col-md-5">
                <img class="img-fullwidth" src="{{ asset('images/homeScreen/car.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <!-- <section class="bg-theme-colored">
        <div class="container pt-40 pb-40">
            <div class="row">
                <div class="col-sm-3 col-xs-6">
                    <div class="fran-stat text-white">
                        <div class="num">50+</div>
                        <div class="lbl">Active Locations</div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="fran-stat text-white">
                        <div class="num">12 Yrs</div>
                        <div class="lbl">Years in Business</div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="fran-stat text-white">
                        <div class="num">$120K</div>
                        <div class="lbl">Avg. Annual Revenue</div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="fran-stat text-white">
                        <div class="num">95%</div>
                        <div class="lbl">Franchisee Renewal Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Why Franchise With Us -->
    <section class="bg-lightest">
        <div class="container pt-60 pb-40 content-readable">
            <div class="section-title text-center mb-40">
                <!-- <h2 class="mt-0">Why Choose a <span class="text-theme-colored">Fox Grooming</span> Franchise?
                </h2> -->
                <p>Everything you need to build a thriving grooming business — under one trusted brand.</p>
            </div>
            <div class="row" id="franchise-list">
                @if($franchises->count() == 0)
                    <div class="col-md-12 text-center">
                        <h3 class="text-theme-colored">No Franchises Found</h3>
                        <p>We're sorry, but there are no franchises available in your area.</p>
                    </div>
                @else
                    @foreach($franchises as $franchise)
                        <div class="col-md-4 mb-30">
                            <div class="franchise-card border-1px p-15 text-center">
                                <img src="{{ $franchise->image }}" alt="{{ $franchise->name }}" class="img-thumbnail mb-15"
                                    style="width: 100%; height: 200px; object-fit: cover;">
                                <h3 class="mt-0 text-theme-colored">{{ $franchise->name }}</h3>
                                <p class="mb-10"><i class="fa fa-map-marker text-theme-colored"></i> {{ $franchise->address }},
                                    {{ $franchise->city }}
                                </p>
                                <a href="{{ route('web.franchise.details', $franchise->id) }}"
                                    class="btn btn-theme-colored btn-block mt-10">Read More</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section>
        <div class="container pt-60 pb-40 content-readable">
            <h2 class="text-center mt-0 mb-40">How It <span class="text-theme-colored">Works</span></h2>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="step-box">
                        <div class="step-number">1</div>
                        <h4>Submit Enquiry</h4>
                        <p>Fill in our enquiry form below. A franchise development manager will contac tyou to discuss your goal.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="step-box">
                        <div class="step-number">2</div>
                        <h4>Discovery Meeting</h4>
                        <p>Meet our team in person or online. Review the Franchise Disclosure Document and ask questions.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="step-box">
                        <div class="step-number">3</div>
                        <h4>Sign &amp; Train</h4>
                        <p>Sign your franchise agreement, secure your territory and complete our comprehensive training programme.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="step-box">
                        <div class="step-number">4</div>
                        <h4>Open &amp; Grow</h4>
                        <p>Launch your Fox Grooming - mobile pet grooming franchise with our support, marketing blitz and ongoing mentorship from experienced franchises.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

      <!-- Enquiry Form -->
      <section id="enquire" class="enquiry-section">
        <div class="container content-readable">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="text-center mt-0 mb-5">Request Franchise <span class="text-theme-colored">Information</span>
                    </h2>
                    <p class="text-center mb-30">Complete the form below and a franchise development manager
                        will be in touch within 48 hours.</p>
                    <form id="franchise-enquiry-form" action="{{ route('web.contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>First Name *</label><input type="text" class="form-control"
                                        placeholder="Your first name" required name="first_name"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label>Last Name *</label><input type="text" class="form-control"
                                        placeholder="Your last name" required name="last_name"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>Email Address *</label><input type="email"
                                        class="form-control" placeholder="your@email.com" required name="email"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label>Phone Number *</label><input type="tel" class="form-control"
                                        placeholder="+1 (xxx) xxx-xxxx" required name="phone"></div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Select a Service *</label>
                                    <select class="form-control" style="height:44px;" name="subject">
                                        @if($services->count() > 0)
                                            <option value="">-- Select a Service --</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->name }}">{{ $service->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No services available</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label >Why would you like to own a Fox Grooming mobile franchisee?</label>
                            <textarea class="form-control" rows="4" style="height:auto;"
                                placeholder="Your background, business experience, and why you're interested in a Fox Grooming franchise..."
                                name="message"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-theme-colored btn-lg text-white text-uppercase mt-10"
                                style="min-width:220px;">Send Enquiry <i class="fa fa-paper-plane ml-10"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Franchise Packages -->
    <!-- <section id="packages" class="bg-lightest">
    <div class="container pt-60 pb-40">
        <h2 class="text-center mt-0 mb-10">Franchise <span class="text-theme-colored">Packages</span></h2>
        <p class="text-center mb-40">Choose the investment level that's right for you. All packages include
            full training and ongoing support.</p>
        <div class="row">


            <div class="col-md-4">
                <div class="package-card">
                    <h3 class="mt-5 mb-5">Starter</h3>
                    <p class="text-muted" style="font-size:13px;">Mobile grooming van — low overheads,
                        maximum flexibility</p>
                    <div class="package-price"><sup>$</sup>25,000<span>/total investment</span></div>
                    <ul class="package-features">
                        <li><i class="fa fa-check"></i> Branded grooming van &amp; equipment</li>
                        <li><i class="fa fa-check"></i> 4-week training programme</li>
                        <li><i class="fa fa-check"></i> Exclusive service territory</li>
                        <li><i class="fa fa-check"></i> Marketing starter pack</li>
                        <li><i class="fa fa-check"></i> Dedicated support manager</li>
                        <li><i class="fa fa-times" style="color:#ccc;"></i> Salon fit-out</li>
                        <li><i class="fa fa-times" style="color:#ccc;"></i> Walk-in salon location</li>
                    </ul>
                    <a href="#enquire" class="btn btn-border btn-theme-colored text-uppercase btn-block"
                        style="padding:10px;">Enquire Now</a>
                </div>
            </div>


            <div class="col-md-4">
                <div class="package-card featured">
                    <div class="popular-ribbon">Most Popular</div>
                    <h3 class="mt-5 mb-5 text-theme-colored">Professional</h3>
                    <p class="text-muted" style="font-size:13px;">Full salon — the complete Fox Grooming
                        experience</p>
                    <div class="package-price"><sup>$</sup>65,000<span>/total investment</span></div>
                    <ul class="package-features">
                        <li><i class="fa fa-check"></i> Full salon fit-out &amp; equipment</li>
                        <li><i class="fa fa-check"></i> 4-week training programme</li>
                        <li><i class="fa fa-check"></i> Exclusive territory (5km radius)</li>
                        <li><i class="fa fa-check"></i> Grand opening marketing campaign</li>
                        <li><i class="fa fa-check"></i> Dedicated support manager</li>
                        <li><i class="fa fa-check"></i> Booking &amp; POS software</li>
                        <li><i class="fa fa-check"></i> Staff recruitment assistance</li>
                    </ul>
                    <a href="#enquire" class="btn btn-theme-colored text-white text-uppercase btn-block"
                        style="padding:10px;">Enquire Now</a>
                </div>
            </div>


            <div class="col-md-4">
                <div class="package-card">
                    <h3 class="mt-5 mb-5">Enterprise</h3>
                    <p class="text-muted" style="font-size:13px;">Multi-unit ownership — scale your business
                        empire</p>
                    <div class="package-price"><sup>$</sup>150,000<span>/total investment</span></div>
                    <ul class="package-features">
                        <li><i class="fa fa-check"></i> 3 salon locations included</li>
                        <li><i class="fa fa-check"></i> Full training for all staff</li>
                        <li><i class="fa fa-check"></i> Exclusive regional territory</li>
                        <li><i class="fa fa-check"></i> Dedicated account manager</li>
                        <li><i class="fa fa-check"></i> Priority supplier pricing</li>
                        <li><i class="fa fa-check"></i> Reduced royalty rates</li>
                        <li><i class="fa fa-check"></i> Sub-franchising rights available</li>
                    </ul>
                    <a href="#enquire" class="btn btn-border btn-theme-colored text-uppercase btn-block"
                        style="padding:10px;">Enquire Now</a>
                </div>
            </div>

        </div>
        <p class="text-center text-muted mt-10" style="font-size:13px;">* Investment totals include
            franchise fee, fit-out, equipment, and working capital. Financing options available. Speak to
            our team for a full breakdown.</p>
    </div>
    </section> -->

    <!-- Franchisee Testimonials -->
    <!-- <section class="layer-overlay overlay-white-9" data-bg-img="{{ asset('images/pattern/p8.png') }}">
        <div class="container pt-60 pb-40">
            <h2 class="text-center mt-0 mb-40">Hear from Our <span class="text-theme-colored">Franchisees</span>
            </h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="franchise-testimonial">
                        <p>"I opened my Fox Grooming salon 2 years ago with no prior business experience. The
                            training and ongoing support gave me the confidence to grow to a team of 4 in just
                            18 months."</p>
                        <strong><i class="fa fa-user text-theme-colored mr-5"></i> David R. — Franchisee,
                            Chicago</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="franchise-testimonial">
                        <p>"The recognised Fox Grooming brand brought clients through the door from day one. The
                            marketing support alone was worth the investment. My salon was profitable within 8
                            months."</p>
                        <strong><i class="fa fa-user text-theme-colored mr-5"></i> Maria L. — Franchisee,
                            Miami</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="franchise-testimonial">
                        <p>"I started with the Starter mobile package and expanded to a full salon within 18
                            months. The team at Fox Grooming HQ supported me every step of the way. Best
                            decision I ever made."</p>
                        <strong><i class="fa fa-user text-theme-colored mr-5"></i> Tom S. — Franchisee,
                            Dallas</strong>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- FAQ -->
    <!-- <section class="bg-lightest">
        <div class="container pt-60 pb-40">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="text-center mt-0 mb-30">Franchise <span class="text-theme-colored">FAQ</span>
                    </h2>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">Do I need grooming experience to
                            become a franchisee? <span class="faq-toggle">+</span></div>
                        <div class="faq-answer">No grooming experience is required. Our 4-week training
                            programme covers everything from grooming techniques to business management.
                            However, a passion for dogs and a customer-focused mindset are essential.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">What are the ongoing fees? <span
                                class="faq-toggle">+</span></div>
                        <div class="faq-answer">Franchisees pay a royalty fee of 7% of monthly gross revenue and
                            a 2% contribution to the national marketing fund. Enterprise package holders benefit
                            from reduced royalty rates.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">Is financing available? <span
                                class="faq-toggle">+</span></div>
                        <div class="faq-answer">Yes. We work with preferred lenders who specialise in franchise
                            financing. We can also assist you in preparing a business plan to support your
                            application. Many franchisees fund 50–70% through loans.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">How long does it take to open my franchise?  <span class="faq-toggle">+</span></div>
                        <div class="faq-answer">From signing your franchise agreement to opening day typically
                            takes 3–5 months. This covers training, site selection, fit-out, equipment
                            installation, and your Grand Opening marketing campaign.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">What is the term of the franchise
                            agreement? <span class="faq-toggle">+</span></div>
                        <div class="faq-answer">The standard franchise agreement is for a 5-year term, renewable
                            on mutually agreed terms. Our 95% renewal rate reflects the satisfaction of our
                            franchisees with the ongoing relationship.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">Can I see an existing location
                            before I commit? <span class="faq-toggle">+</span></div>
                        <div class="faq-answer">Absolutely. We encourage all prospective franchisees to visit
                            one or more existing salons as part of the Discovery Meeting process. You can also
                            speak directly with current franchisees to get their honest perspective.</div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

  

    <!-- Final CTA -->
    <section class="bg-theme-colored">
        <div class="container pt-0 pb-30">
            <div class="row">
                <div class="call-to-action p-0 pt-30 pb-20">
                    <div class="col-md-9">
                        <h3 class="mt-5 text-white">Join the Fox Grooming family and build the business you've
                            always dreamed of!</h3>
                    </div>
                    <div class="col-md-3 text-right flip sm-text-center">
                        <a href="{{ route('web.contact') }}" class="btn btn-default btn-circled btn-lg mt-5">Contact Us
                            <i class="fa fa-angle-double-right font-16 ml-10"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        function toggleFaq(el) {
            var answer = el.nextElementSibling;
            var toggle = el.querySelector('.faq-toggle');
            answer.classList.toggle('open');
            toggle.classList.toggle('rotated');
        }
        $('#franchise-enquiry-form').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var form_btn = form.find('button[type="submit"]');
            var form_result_div = '#franchise-form-result';

            if ($(form_result_div).length == 0) {
                form_btn.before('<div id="franchise-form-result" class="alert mt-20" role="alert" style="display: none;"></div>');
            }

            var form_btn_old_msg = form_btn.html();
            form_btn.html(form_btn.data("loading-text")).prop('disabled', true);
            $(form_result_div).hide();

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    form_btn.prop('disabled', false).html(form_btn_old_msg);
                    if (response.status == 'success') {
                        form[0].reset();
                        $(form_result_div).removeClass('alert-danger').addClass('alert-success').html(response.message).fadeIn('slow');
                    } else {
                        $(form_result_div).removeClass('alert-success').addClass('alert-danger').html(response.message).fadeIn('slow');
                    }
                    setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                },
                error: function (xhr) {
                    form_btn.prop('disabled', false).html(form_btn_old_msg);
                    $(form_result_div).removeClass('alert-success').addClass('alert-danger').html('An error occurred. Please try again.').fadeIn('slow');
                    setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                }
            });
        });
    </script>
@endsection