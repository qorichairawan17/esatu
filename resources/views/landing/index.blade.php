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
        :root {
            --esatu-primary: #136c34;
            --esatu-primary-dark: #0f5528;
            --esatu-primary-rgb: 19, 108, 52;
        }

        ::selection {
            background: rgba(var(--esatu-primary-rgb), 0.9);
            color: #ffffff;
        }

        .text-success,
        .text-primary,
        .auth-muted-link,
        #topnav .navigation-menu>li:hover>a,
        #topnav .navigation-menu>li.active>a {
            color: var(--esatu-primary) !important;
        }

        .btn-success,
        .btn-primary {
            background-color: var(--esatu-primary) !important;
            border-color: var(--esatu-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 8px 18px rgba(var(--esatu-primary-rgb), 0.18) !important;
        }

        .btn-success:hover,
        .btn-success:focus,
        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--esatu-primary-dark) !important;
            border-color: var(--esatu-primary-dark) !important;
            color: #ffffff !important;
        }

        .btn-soft-success,
        .bg-soft-success {
            background-color: rgba(var(--esatu-primary-rgb), 0.1) !important;
            border-color: rgba(var(--esatu-primary-rgb), 0.1) !important;
            color: var(--esatu-primary) !important;
        }

        .btn-soft-success:hover,
        .btn-soft-success:focus {
            background-color: var(--esatu-primary) !important;
            border-color: var(--esatu-primary) !important;
            color: #ffffff !important;
        }

        .landing-footer {
            background: linear-gradient(180deg, #f2fbf6 0%, #ffffff 100%);
            border-top: 1px solid rgba(19, 108, 52, 0.16);
            color: #637083;
        }

        .landing-footer .footer-head {
            color: #136c34;
        }

        .landing-footer .text-foot,
        .landing-footer p,
        .landing-footer .footer-bar {
            color: #637083;
        }

        .landing-footer .text-foot:hover,
        .landing-footer .footer-bar a {
            color: #136c34 !important;
        }

        .landing-footer .footer-bar {
            border-top-color: rgba(19, 108, 52, 0.14);
            background: rgba(19, 108, 52, 0.04);
        }

        .landing-footer .foot-social-icon li a {
            color: #136c34;
            border-color: rgba(19, 108, 52, 0.24);
            background-color: #ffffff;
        }

        .landing-footer .foot-social-icon li a:hover {
            background-color: #136c34;
            border-color: #136c34 !important;
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
