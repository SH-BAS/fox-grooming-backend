<header class="header">
    <div class="header-top sm-text-center">
        <div class="container m-0 pl-40 pr-40 w-100" style="width: 100%;">
            <div class="row">
                <div class="col-md-8">
                    <div class="widget no-border m-0">
                        <ul class="list-inline sm-text-center mt-5">
                            <li>
                                <a href="#" class="text-white"><i class="fa fa-clock-o text-theme-colored"></i> Mon -
                                    Fri: 8:00 -18:00</a>
                            </li>
                            <li><a href="#" class="text-white">|</a></li>
                            <!-- <li><a href="#" class="text-white"><i class="fa fa-map-marker text-theme-colored"></i> 28
                                    Street, New York, USA</a></li>
                            <li><a href="#" class="text-white">|</a></li> -->
                            <li><a href="#" class="text-white"><i class="fa fa-phone text-theme-colored"></i> Book
                                    Online or Call: 123-456-7890</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="widget no-border m-0">
                        <ul
                            class="styled-icons icon-dark icon-circled icon-theme-colored icon-sm pull-right flip sm-pull-none sm-text-center mt-sm-15">
                            <li ><a href="#" style="background-color: #3b5998;"><i class="fa fa-facebook"></i></a></li>
                        <!-- youtube fa-youtube-play -->
                        <li><a href="#" style="background-color: #c4302b;"><i class="fa fa-youtube-play"></i></a></li>
                        <li><a href="#" style="background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-nav">
        <div class="header-nav-wrapper navbar-scrolltofixed bg-white">
            <div class="container m-0 pl-40 pr-40 w-100" style="width: 100%;">
                <nav id="menuzord-right" class="menuzord green no-bg">
                    <a class="menuzord-brand pull-left flip" href="{{ route('web.index') }}"><img
                            src="{{ asset('images/logo-wide.png') }}" alt=""></a>
                    <ul class="menuzord-menu">
                        <div class="navigationSectionDiv"
                            style="display: flex;flex-direction: column;align-items: flex-end;">
                            <div class="header-top-buttons-section"
                                style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px; margin-top: 15px;">
                                <a href="{{ route('web.book.franchise') }}"
                                    class="btn btn-flat btn-theme-colored text-uppercase border-left-theme-color-2-4px"
                                    style="padding: 10px 20px;">Book Now</a>
                            </div>

                            <ul class="menuzord-menu">
                                <li class="{{ request()->routeIs('web.index') ? 'active' : '' }}"><a
                                        href="{{ route('web.index') }}">Home</a></li>
                                <li class="{{ request()->routeIs('web.services') ? 'active' : '' }}"><a
                                        href="{{ route('web.services') }}">Services</a></li>
                                {{-- <li class="{{ request()->routeIs('web.courses') ? 'active' : '' }}"><a
                                        href="{{ route('web.courses') }}">Dog Grooming Courses</a></li> --}}
                                <li class="{{ request()->routeIs('web.about') ? 'active' : '' }}"><a
                                        href="{{ route('web.about') }}">About</a></li>
                                <li class="{{ request()->routeIs('web.franchise') ? 'active' : '' }}"><a
                                        href="{{ route('web.franchise') }}">Franchise Opportunities</a></li>
                                <!-- <li class="{{ request()->routeIs('web.pricing') ? 'active' : '' }}"><a
                                        href="{{ route('web.pricing') }}">Pricing</a></li> -->
                                <li class="{{ request()->routeIs('web.contact') ? 'active' : '' }}"><a
                                        href="{{ route('web.contact') }}">Contact Us</a></li>

                                @auth
                                    <li>
                                        <a href="javascript:void(0)" class="d-flex align-items-center"
                                            style="display: flex; align-items: center; gap: 8px; margin-top: -15px;">
                                            <img src="{{ auth()->user()->image }}" alt="{{ auth()->user()->name }}"
                                                class="img-circle"
                                                style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #5cb85c;">
                                            {{ auth()->user()->name }}
                                        </a>
                                        <ul class="dropdown">
                                            <li><a href="{{ route('web.profile.index') }}"><i class="fa fa-user"></i>
                                                    Profile</a></li>
                                            <li>
                                                <a href="#"
                                                    onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();">
                                                    <i class="fa fa-sign-out"></i> Logout
                                                </a>
                                                <form id="header-logout-form" action="{{ route('web.logout') }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @endauth

                                @guest
                                    <li><a href="{{ route('web.login') }}">Login</a></li>
                                    <li><a href="{{ route('web.register') }}">Register</a></li>
                                @endguest
                            </ul>
                        </div>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
