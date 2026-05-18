<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? '' }}</title>

    @include('miscellaneous.meta')

    <meta name="google-site-verification" content="3otjX6MrJ6ibgCr4QjYjVabahpCiXyBlo_nhvGXH-rQ" />
    <link href="{{ asset('assets/libs/tiny-slider/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/tobii/css/tobii.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" class="theme-opt" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/libs/@mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/css/style-green.min.css') }}" id="color-opt" class="theme-opt" rel="stylesheet" type="text/css">
    <style>
        .landing-footer {
            background: linear-gradient(180deg, #f2fbf6 0%, #ffffff 100%);
            border-top: 1px solid rgba(22, 163, 74, 0.16);
            color: #637083;
        }

        .landing-footer .footer-head {
            color: #16a34a;
        }

        .landing-footer .text-foot,
        .landing-footer p,
        .landing-footer .footer-bar {
            color: #637083;
        }

        .landing-footer .text-foot:hover,
        .landing-footer .footer-bar a {
            color: #16a34a !important;
        }

        .landing-footer .footer-bar {
            border-top-color: rgba(22, 163, 74, 0.14);
            background: rgba(22, 163, 74, 0.04);
        }

        .landing-footer .foot-social-icon li a {
            color: #16a34a;
            border-color: rgba(22, 163, 74, 0.24);
            background-color: #ffffff;
        }

        .landing-footer .foot-social-icon li a:hover {
            background-color: #16a34a;
            border-color: #16a34a !important;
            color: #ffffff !important;
        }

        #topnav .navigation-menu>li>span.disabled-menu {
            color: #a3adba;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
        </div>
    </div>
    <!-- Loader -->

    @include('landing.navbar')

    @yield('content')

    @include('landing.footer')

    <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i data-feather="arrow-up" class="fea icon-sm icons align-middle"></i></a>
    <!-- Back to top -->

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/tiny-slider/min/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/libs/jarallax/jarallax.min.js') }}"></script>
    <script src="{{ asset('assets/libs/wow.js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')

    @include('miscellaneous.pwa-sw')
</body>

</html>
