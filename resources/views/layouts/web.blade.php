<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="description" content="ePetCare - Pet Care, Pets Club, Veterinary & Shop HTML5 Template" />
    <meta name="keywords" content="Pet,Pet Care,Pets Club,Dog,Cat,Veterinary,Veterinary & Shop" />
    <meta name="author" content="ThemeMascot" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page Title -->

    <title>@yield('title', 'Fox Grooming')</title>

    <!-- Favicon and Touch Icons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('images/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link href="{{ asset('images/apple-touch-icon-72x72.png') }}" rel="apple-touch-icon" sizes="72x72">
    <link href="{{ asset('images/apple-touch-icon-114x114.png') }}" rel="apple-touch-icon" sizes="114x114">
    <link href="{{ asset('images/apple-touch-icon-144x144.png') }}" rel="apple-touch-icon" sizes="144x144">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/css-plugin-collections.css') }}" rel="stylesheet" />
    <link id="menuzord-menu-skins" href="{{ asset('css/menuzord-skins/menuzord-strip.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style-main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/content-readable.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/colors/theme-skin-lightgreen.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/preloader.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/custom-bootstrap-margin-padding.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('js/revolution-slider/css/settings.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('js/revolution-slider/css/layers.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('js/revolution-slider/css/navigation.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="{{ asset('js/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-plugin-collection.js') }}"></script>
    <script src="{{ asset('js/revolution-slider/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ asset('js/revolution-slider/js/jquery.themepunch.revolution.min.js') }}"></script>

    @yield('styles')
    <script>
        (function () {
            const browserTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            const expiryDate = new Date();
            expiryDate.setTime(expiryDate.getTime() + (7 * 24 * 60 * 60 * 1000));
            document.cookie = `timezone=${browserTimezone}; path=/; expires=${expiryDate.toUTCString()}; SameSite=Lax`;
        })();
    </script>
</head>

<body class="">
    <div id="wrapper">

        <!-- preloader -->
        <div id="preloader">
            <div id="spinner" class="spinner large-icon">
                <img alt="" src="{{ asset('images/preloaders/13.gif') }}">
                <h5 class="line-height-1 font-18">Loading...</h5>
            </div>
            <div id="disable-preloader" class="btn btn-default btn-sm">Disable Preloader</div>
        </div>

        @include('partials.web-header')

        <div class="main-content">
            <div class="container mt-10">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info">
                        {{ session('info') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @yield('content')
        </div>

        @include('partials.web-footer')

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('assets/js/sweetalert-delete.js') }}"></script>

        @yield('scripts')

    </div>
</body>

</html>