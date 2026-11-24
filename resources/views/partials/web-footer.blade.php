<!-- Footer -->
<footer id="footer" class="bg-black-222">
    <div class="container pt-70 pb-30">
        <div class="row border-bottom-black">
            <div class="col-sm-12 col-md-12">
                <div class="widget dark">
                    <img class="mb-20" alt="" src="{{ asset('images/logo-wide-white.png') }}">
                    <p class="text-gray">Where professional dog grooming services are at  your doorstep.</p>
                    <ul class="list-inline mt-5">
                        <li class="m-0 pl-10 pr-10"> <i class="fa fa-phone text-theme-colored mr-5"></i> <a
                                class="text-gray" href="#">123-456-789</a> </li>
                        <li class="m-0 pl-10 pr-10"> <i class="fa fa-envelope-o text-theme-colored mr-5"></i> <a
                                class="text-gray" href="#">foxgroomingusa@gmail.com</a> </li>
                        <li class="m-0 pl-10 pr-10"> <i class="fa fa-globe text-theme-colored mr-5"></i> <a
                                class="text-gray" href="https://foxgrooming.com/">foxgrooming.com</a> </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-8 col-md-8">
            <div class="widget dark">
                    <h5 class="widget-title mb-10">Subscribe Us</h5>
                    <!-- Mailchimp Subscription Form Starts Here -->
                    <form id="newsletter-subscription-form-footer" class="newsletter-form"
                        action="{{ route('web.newsletter.subscribe') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" placeholder="Your Email"
                                class="form-control input-lg font-16" data-height="45px" required style="height: 45px;">
                            <span class="input-group-btn">
                                <button data-height="45px" class="btn btn-colored btn-theme-colored btn-xs m-0 font-14"
                                    type="submit" data-loading-text="Wait...">Subscribe</button>
                            </span>
                        </div>
                    </form>
                    <div id="newsletter-form-result" class="mt-10"></div>

                    <script type="text/javascript">
                        $('#newsletter-subscription-form-footer').on('submit', function (e) {
                            e.preventDefault();
                            var form = $(this);
                            var form_btn = form.find('button[type="submit"]');
                            var form_result_div = '#newsletter-form-result';
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
                                        $(form_result_div).html('<div class="alert alert-success">' + data.message + '</div>').fadeIn('slow');
                                    } else {
                                        $(form_result_div).html('<div class="alert alert-danger">' + data.message + '</div>').fadeIn('slow');
                                    }
                                    setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                                },
                                error: function () {
                                    form_btn.prop('disabled', false).html(form_btn_old_msg);
                                    $(form_result_div).html('<div class="alert alert-danger">An error occurred. Please try again.</div>').fadeIn('slow');
                                    setTimeout(function () { $(form_result_div).fadeOut('slow') }, 4000);
                                }
                            });
                        });
                    </script>
                    <!-- Mailchimp Subscription Form Ends Here -->
             </div>
            </div>
             <div class="col-sm-4 col-md-4">
             <div class="widget dark">
                    <ul class="styled-icons icon-dark icon-circled icon-theme-colored icon-sm pull-right flip sm-pull-none sm-text-center mt-sm-15" style="margin-top: 30px;">
                            <li ><a href="#" style="background-color: #3b5998;"><i class="fa fa-facebook"></i></a></li>
                        <!-- youtube fa-youtube-play -->
                        <li><a href="#" style="background-color: #c4302b;"><i class="fa fa-youtube-play"></i></a></li>
                        <li><a href="#" style="background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
               
            </div>
        </div>
        <div class="row mt-30">
            <div class="col-md-4">
           
            </div>
                <!-- <div class="col-md-3 col-md-offset-1">
                    <div class="widget dark">
                        <h5 class="widget-title mb-10">Call Us Now</h5>
                        <div class="text-gray">
                            +61 3 1234 5678 <br>
                            +12 3 1234 5678
                        </div>
                    </div>
                </div> -->
            <div class="col-md-3">
                
            </div>
        </div>
    </div>
    <div class="footer-bottom bg-black-333">
        <div class="container pt-20 pb-20">
            <div class="row">
                <div class="col-md-6">
                    <p class="font-11 text-black-777 m-0">Copyright &copy;2015 ThemeMascot. All Rights Reserved</p>
                </div>
                <div class="col-md-6 text-right">
                    <div class="widget no-border m-0">
                        <ul class="list-inline sm-text-center mt-5 font-12">
                            <li>
                                <a href="#">FAQ</a>
                            </li>
                            <li>|</li>
                            <li>
                                <a href="#">Help Desk</a>
                            </li>
                            <li>|</li>
                            <li>
                                <a href="#">Support</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
</div>
<!-- end wrapper -->

<!-- Footer Scripts -->
<!-- JS | Custom script for all pages -->
<script src="{{ asset('js/custom.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.actions.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.carousel.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.migration.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.navigation.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.parallax.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('js/revolution-slider/js/extensions/revolution.extension.video.min.js') }}"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>