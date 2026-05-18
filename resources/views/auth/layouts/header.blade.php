<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? '' }}</title>

    @include('miscellaneous.meta')

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" class="theme-opt" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/libs/@mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/css/style-green.min.css') }}" id="color-opt" class="theme-opt" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}">
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

        .text-success,
        .text-primary {
            color: var(--esatu-primary) !important;
        }

        .auth-body {
            background: #f6fbf8;
            min-height: 100vh;
        }

        .auth-shell {
            min-height: 100vh;
            padding: 32px 0;
            background: linear-gradient(180deg, #dff7ea 0%, #f8fbfa 48%, #ffffff 100%);
        }

        .auth-card {
            overflow: hidden;
            border: 1px solid rgba(19, 108, 52, 0.14);
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 24px 70px rgba(33, 47, 64, 0.12);
        }

        .auth-hero {
            min-height: 720px;
            background: linear-gradient(180deg, rgba(19, 108, 52, 0.13) 0%, rgba(19, 108, 52, 0.04) 100%);
        }

        .auth-hero-inner,
        .auth-form-panel {
            padding: 48px;
        }

        .auth-logo {
            max-height: 58px;
            cursor: pointer;
        }

        .auth-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(19, 108, 52, 0.18);
            border-radius: 999px;
            background: rgba(19, 108, 52, 0.08);
            color: #136c34;
            font-weight: 700;
            letter-spacing: 0.02em;
            padding: 8px 14px;
        }

        .auth-copy {
            color: #6d7c90;
            line-height: 1.8;
        }

        .auth-visual {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 18px 45px rgba(19, 108, 52, 0.16);
        }

        .auth-info-card {
            border: 1px solid rgba(19, 108, 52, 0.12);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.82);
            padding: 18px;
        }

        .auth-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(19, 108, 52, 0.1);
            color: #136c34;
            flex: 0 0 44px;
        }

        .auth-form-panel {
            min-height: 720px;
        }

        .auth-form-card {
            width: 100%;
            max-width: 480px;
        }

        .auth-form-card .form-control {
            min-height: 48px;
            border-color: #e5ece8;
            border-radius: 8px;
            background: #fbfefd;
        }

        .auth-form-card .form-control:focus {
            border-color: #136c34;
            box-shadow: 0 0 0 0.2rem rgba(19, 108, 52, 0.1);
        }

        .auth-form-card .form-check-input:checked {
            background-color: #136c34;
            border-color: #136c34;
        }

        .auth-form-card .form-icon .icons {
            top: 15px;
            color: #136c34;
        }

        .auth-muted-link {
            color: #136c34;
            font-weight: 700;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #8a98aa;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            height: 1px;
            flex: 1;
            background: #e7eee9;
        }

        .auth-captcha {
            border: 1px dashed rgba(19, 108, 52, 0.28);
            border-radius: 8px;
            background: #f7fcf9;
            padding: 12px;
        }

        @media (max-width: 991.98px) {
            .auth-shell {
                padding: 18px 0;
            }

            .auth-card {
                border-radius: 8px;
            }

            .auth-form-panel {
                min-height: auto;
                padding: 32px 22px;
            }
        }
    </style>
</head>
