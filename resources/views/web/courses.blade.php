@extends('layouts.web')

@section('title', 'Courses | Fox Grooming')

@section('styles')
    <style>
        .Cardicon {
            display: inline-block;
            height: 70px;
            text-align: center;
            width: 100px;
            background: #ff0000;
            border-radius: 50%;
            color: #fff;
        }

        .course-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            margin-bottom: 30px;
            overflow: hidden;
            transition: box-shadow .3s, transform .3s;
            height: 500px;
        }

        .course-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12);
            transform: translateY(-4px)
        }

        .course-img-wrap {
            height: 200px;
            overflow: hidden
        }

        .course-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s
        }

        .course-card:hover .course-img-wrap img {
            transform: scale(1.06)
        }

        .course-body {
            padding: 20px;
            height: 298px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .course-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px
        }

        .badge-beginner {
            background: #d4edda;
            color: #155724
        }

        .badge-intermediate {
            background: #fff3cd;
            color: #856404
        }

        .badge-advanced {
            background: #f8d7da;
            color: #721c24
        }

        .badge-all {
            background: #d1ecf1;
            color: #0c5460
        }

        .course-meta span {
            display: inline-block;
            margin-right: 12px;
            font-size: 13px;
            color: #777
        }

        .stat-item {
            text-align: center
        }

        .stat-number {
            font-size: 40px;
            font-weight: 700;
            line-height: 1
        }

        .stat-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px
        }

        .testimonial-box {
            background: #f9f9f9;
            border-left: 4px solid #8bc34a;
            padding: 20px 25px;
            border-radius: 0 4px 4px 0;
            margin-bottom: 25px
        }

        .testimonial-box p {
            font-style: italic;
            color: #555;
            margin-bottom: 10px
        }

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
            color: #333
        }

        .faq-question:hover {
            color: #8bc34a
        }

        .faq-answer {
            display: none;
            padding-bottom: 14px;
            color: #666;
            line-height: 1.7
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

        .enroll-section {
            background: #f4f9ee;
            padding: 60px 0
        }

        .enroll-section .form-control {
            border-radius: 3px;
            height: 44px
        }

        .enroll-section textarea.form-control {
            height: auto
        }
    </style>
@endsection

@section('content')
    <!-- Hero Banner -->
    <section class="inner-header hero-banner divider parallax layer-overlay overlay-dark-5"
        data-bg-img="{{ asset('images/bg/bg1.jpg') }}">
        <div class="container pt-100 pb-50">
            <div class="section-content pt-100">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="title text-white">Dog Grooming Courses</h3>
                        <ol class="breadcrumb text-white mt-10">
                            <li><a href="index-mp-layout2.html" class="text-white">Home</a></li>
                            <li class="active text-white">Dog Grooming Courses</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Intro -->
    <section>
        <div class="container pt-60 pb-20">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <h2 class="mt-0">Launch Your <span class="text-theme-colored">Dog Grooming</span> Career
                    </h2>
                    <p class="font-16">Whether you're a complete beginner or a seasoned groomer upgrading your
                        skills, our professionally designed courses give you everything you need to succeed.
                        Learn from certified experts, practise on real dogs, and graduate with
                        industry-recognised credentials.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="bg-theme-colored">
        <div class="container pt-40 pb-40">
            <div class="row">
                <div class="col-sm-3 col-xs-6">
                    <div class="stat-item text-white">
                        <div class="stat-number">1,200+</div>
                        <div class="stat-label">Graduates</div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="stat-item text-white">
                        <div class="stat-number">8</div>
                        <div class="stat-label">Courses Offered</div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="stat-item text-white">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Expert Instructors</div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="stat-item text-white">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses -->
    <section id="courses" class="bg-lightest">
        <div class="container pt-60 pb-40">
            <div class="section-title text-center mb-40">
                <h2 class="mt-0">Our <span class="text-theme-colored">Courses</span></h2>
                <p>Choose the course that best fits your experience level and goals.</p>
            </div>
            <div class="row">

                <div class="col-lg-4 col-md-4 col-sm-6 h-100">
                    <div class="course-card">
                        <div class="course-img-wrap"><img src="{{ asset('images/photos/bg2.jpg') }}" alt="Beginner Course">
                        </div>
                        <div class="course-body">
                            <div>
                                <span class="course-badge badge-beginner">Beginner</span>
                                <h4 class="mt-5 mb-5">Introduction to Dog Grooming</h4>
                                <div class="course-meta mb-10">
                                    <span><i class="fa fa-clock-o text-theme-colored"></i> 4 Weeks</span>
                                    <span><i class="fa fa-users text-theme-colored"></i> 12 Students</span>
                                </div>
                                <p>Learn coat types, basic brushing, bathing techniques, nail clipping and ear
                                    care
                                    with hands-on practice sessions at our fully equipped salon.</p>
                            </div>
                            <a href="#enroll" class="btn btn-theme-colored text-white text-uppercase"
                                style="margin-top:10px;padding:8px 20px;">Enroll Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 h-100">
                    <div class="course-card">
                        <div class="course-img-wrap"><img src="{{ asset('images/photos/bg3.jpg') }}"
                                alt="Intermediate Course">
                        </div>
                        <div class="course-body">
                            <div>
                                <span class="course-badge badge-intermediate">Intermediate</span>
                                <h4 class="mt-5 mb-5">Breed-Specific Styling</h4>
                                <div class="course-meta mb-10">
                                    <span><i class="fa fa-clock-o text-theme-colored"></i> 6 Weeks</span>
                                    <span><i class="fa fa-users text-theme-colored"></i> 10 Students</span>
                                </div>
                                <p>Dive into breed standards and specialist cuts for Poodles, Spaniels, and
                                    Terriers. Includes scissor techniques and professional finishing sprays.</p>
                            </div>
                            <a href="#enroll" class="btn btn-theme-colored text-white text-uppercase"
                                style="margin-top:10px;padding:8px 20px;">Enroll Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 h-100">
                    <div class="course-card">
                        <div class="course-img-wrap"><img src="{{ asset('images/photos/bg4.jpg') }}" alt="Advanced Course">
                        </div>
                        <div class="course-body">
                            <div>
                                <span class="course-badge badge-advanced">Advanced</span>
                                <h4 class="mt-5 mb-5">Advanced Competition Grooming</h4>
                                <div class="course-meta mb-10">
                                    <span><i class="fa fa-clock-o text-theme-colored"></i> 8 Weeks</span>
                                    <span><i class="fa fa-users text-theme-colored"></i> 8 Students</span>
                                </div>
                                <p>Master competition-level presentation, creative colouring, and advanced clipper
                                    work. Build a portfolio ready to win regional championships.</p>
                            </div>
                            <a href="#enroll" class="btn btn-theme-colored text-white text-uppercase"
                                style="margin-top:10px;padding:8px 20px;">Enroll Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 h-100">
                    <div class="course-card">
                        <div class="course-img-wrap"><img src="{{ asset('images/about/4.jpg') }}" alt="Business Course">
                        </div>
                        <div class="course-body">
                            <div>
                                <span class="course-badge badge-all">All Levels</span>
                                <h4 class="mt-5 mb-5">Run Your Own Grooming Business</h4>
                                <div class="course-meta mb-10">
                                    <span><i class="fa fa-clock-o text-theme-colored"></i> 3 Weeks</span>
                                    <span><i class="fa fa-users text-theme-colored"></i> 15 Students</span>
                                </div>
                                <p>From pricing to marketing, booking systems to client retention — learn everything
                                    about running a profitable pet grooming salon successfully.</p>
                            </div>
                            <a href="#enroll" class="btn btn-theme-colored text-white text-uppercase"
                                style="margin-top:10px;padding:8px 20px;">Enroll Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 h-100">
                    <div class="course-card">
                        <div class="course-img-wrap"><img src="{{ asset('images/about/3.jpg') }}"
                                alt="Health & Safety Course">
                        </div>
                        <div class="course-body">
                            <div>
                                <span class="course-badge badge-intermediate">Intermediate</span>
                                <h4 class="mt-5 mb-5">Pet Health &amp; Safety Essentials</h4>
                                <div class="course-meta mb-10">
                                    <span><i class="fa fa-clock-o text-theme-colored"></i> 2 Weeks</span>
                                    <span><i class="fa fa-users text-theme-colored"></i> 14 Students</span>
                                </div>
                                <p>Learn animal first aid, skin condition identification, parasite checks, and safe
                                    handling techniques to ensure the wellbeing of every pet.</p>
                            </div>
                            <a href="#enroll" class="btn btn-theme-colored text-white text-uppercase"
                                style="margin-top:10px;padding:8px 20px;">Enroll Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 h-100">
                    <div class="course-card">
                        <div class="course-img-wrap"><img src="{{ asset('images/about/2.jpg') }}" alt="Online Course"></div>
                        <div class="course-body">
                            <div>
                                <span class="course-badge badge-beginner">Online</span>
                                <h4 class="mt-5 mb-5">Online Fundamentals Course</h4>
                                <div class="course-meta mb-10">
                                    <span><i class="fa fa-clock-o text-theme-colored"></i> Self-Paced</span>
                                    <span><i class="fa fa-users text-theme-colored"></i> Unlimited</span>
                                </div>
                                <p>Study at your own pace with our full video library, quizzes, and tutor Q&amp;A
                                    forum. Ideal for those who cannot attend in-person training sessions.</p>
                            </div>
                            <a href="#enroll" class="btn btn-theme-colored text-white text-uppercase"
                                style="margin-top:10px;padding:8px 20px;">Enroll Now</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section>
        <div class="container pt-60 pb-40">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="mt-0 mb-20">Why Learn With <span class="text-theme-colored">Fox Grooming?</span>
                    </h2>
                    <p class="mb-30">Our instructors are award-winning groomers with decades of real-world
                        experience. Every course combines theory with hands-on practice so you graduate truly
                        job-ready.</p>
                    <div class="icon-box border-1px p-15 mb-20 position-relative"
                        style="display: flex;align-items: center;justify-content: center;">
                        <a class="Cardicon" href="#"><i class="flaticon-pet-scissors"></i></a>
                        <div class="ml-10 ml-sm-0">
                            <h5 class="icon-box-title mt-10 mb-5 text-uppercase">Hands-On Training</h5>
                            <p>Supervised practice on real dogs in our fully equipped grooming salon included in
                                every module.</p>
                        </div>
                    </div>
                    <div class="icon-box border-1px p-15 mb-20 position-relative"
                        style="display: flex;align-items: center;justify-content: center;">
                        <a class="Cardicon" href="#"><i class="flaticon-pet-dog-1"></i></a>
                        <div class="ml-10 ml-sm-0">
                            <h5 class="icon-box-title mt-10 mb-5 text-uppercase">Certified Credentials</h5>
                            <p>Graduate with an industry-recognised certificate accepted by employers and
                                clients nationwide.</p>
                        </div>
                    </div>
                    <div class="icon-box border-1px p-15 mb-20 position-relative"
                        style="display: flex;align-items: center;justify-content: center;">
                        <a class="Cardicon" href="#"><i class="flaticon-pet-people"></i></a>
                        <div class="ml-10 ml-sm-0">
                            <h5 class="icon-box-title mt-10 mb-5 text-uppercase">Small Class Sizes</h5>
                            <p>Groups of 8–15 students ensure personalised attention and faster skill
                                development for every learner.</p>
                        </div>
                    </div>
                    <div class="icon-box border-1px p-15 mb-20 position-relative"
                        style="display: flex;align-items: center;justify-content: center;">
                        <a class="Cardicon" href="#"><i class="flaticon-pet-animal"></i></a>
                        <div class="ml-10 ml-sm-0">
                            <h5 class="icon-box-title mt-10 mb-5 text-uppercase">Career Support</h5>
                            <p>We help place graduates with top salons and provide mentorship for those starting
                                their own business.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-md-offset-1">
                    <img src="{{ asset('images/about/2.png') }}" alt="Grooming Training" class="img-fullwidth">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="layer-overlay overlay-white-9" data-bg-img="{{ asset('images/pattern/p8.png') }}">
        <div class="container pt-60 pb-40">
            <h2 class="text-center mt-0 mb-40">What Our <span class="text-theme-colored">Students Say</span>
            </h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-box">
                        <p>"The beginner's course completely changed my career. Within 3 months of graduating I
                            had a full client list. The instructors are patient and incredibly knowledgeable."
                        </p>
                        <strong><i class="fa fa-user text-theme-colored mr-5"></i> Sarah M. — Salon Owner, New
                            York</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-box">
                        <p>"The breed-specific styling course took my skills to a whole new level. I went from
                            basic clips to winning my first regional competition just four months later!"</p>
                        <strong><i class="fa fa-user text-theme-colored mr-5"></i> James T. — Competitive
                            Groomer</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-box">
                        <p>"The online fundamentals course was ideal for my busy schedule. The video content is
                            high quality and tutor responses in the forum were faster than I expected."</p>
                        <strong><i class="fa fa-user text-theme-colored mr-5"></i> Lisa K. — Home
                            Groomer</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="bg-lightest">
        <div class="container pt-60 pb-40">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="text-center mt-0 mb-30">Frequently Asked <span class="text-theme-colored">Questions</span>
                    </h2>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">Do I need any prior experience to
                            enroll? <span class="faq-toggle">+</span></div>
                        <div class="faq-answer">No experience is required for our Beginner and Online courses.
                            For Intermediate and Advanced levels we recommend completing a foundational course
                            first or demonstrating equivalent experience.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">What equipment do I need to bring?
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">All professional grooming equipment is provided for in-person
                            courses. You are welcome to bring your own tools. A full kit list is emailed upon
                            enrollment.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">Are the courses accredited? <span
                                class="faq-toggle">+</span></div>
                        <div class="faq-answer">Yes. All Fox Grooming courses are accredited by the National Pet
                            Grooming Association (NPGA) and certificates are widely recognised by employers
                            across the country.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">How many dogs will I groom during
                            the course? <span class="faq-toggle">+</span></div>
                        <div class="faq-answer">Students groom a minimum of 20–40 dogs depending on the course
                            level. We partner with local shelters and dog owners who volunteer their pets for
                            training.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">Is there a payment plan available?
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">Yes, we offer flexible 3-month and 6-month interest-free payment
                            plans on all in-person courses. Contact us after submitting your enrollment form to
                            arrange a plan.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enroll Form -->
    <section id="enroll" class="enroll-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="text-center mt-0 mb-5">Enroll <span class="text-theme-colored">Today</span></h2>
                    <p class="text-center mb-30">Fill in the form below and a course advisor will contact you
                        within 24 hours.</p>
                    <form id="course-enroll-form" action="{{ route('web.contact.submit') }}" method="POST">
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
                                <div class="form-group"><label>Phone Number</label><input type="tel" class="form-control"
                                        placeholder="+1 (xxx) xxx-xxxx" name="phone"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Course Interested In *</label>
                            <select class="form-control" style="height:44px;" required name="subject">
                                <option value="">-- Select a Course --</option>
                                <option>Introduction to Dog Grooming (Beginner)</option>
                                <option>Breed-Specific Styling (Intermediate)</option>
                                <option>Advanced Competition Grooming</option>
                                <option>Run Your Own Grooming Business</option>
                                <option>Pet Health &amp; Safety Essentials</option>
                                <option>Online Fundamentals Course</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Message / Questions</label><textarea class="form-control" rows="4"
                                style="height:auto;"
                                placeholder="Tell us about your experience level or any questions you have..."
                                name="message"></textarea>
                            <div id="enroll-form-result" class="alert mt-20" role="alert" style="display: none;"></div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-theme-colored btn-lg text-white text-uppercase mt-10"
                                    data-loading-text="Please wait..." style="min-width:200px;">Submit Enquiry <i
                                        class="fa fa-paper-plane ml-10"></i></button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-theme-colored">
        <div class="container pt-0 pb-30">
            <div class="row">
                <div class="call-to-action p-0 pt-30 pb-20">
                    <div class="col-md-9">
                        <h3 class="mt-5 text-white">Ready to start your dog grooming journey? Contact us today!
                        </h3>
                    </div>
                    <div class="col-md-3 text-right flip sm-text-center">
                        <a href="{{ route('web.contact') }}" class="btn btn-default btn-circled btn-lg mt-5">Get In
                            Touch <i class="fa fa-angle-double-right font-16 ml-10"></i></a>
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
        $('#course-enroll-form').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var form_btn = form.find('button[type="submit"]');
            var form_result_div = '#enroll-form-result';
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
    </script>
@endsection