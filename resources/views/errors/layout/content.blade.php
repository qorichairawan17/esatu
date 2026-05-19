<div class="error-page-wrapper">
    <div class="error-card">
        {{-- Subtle Decorative Elements --}}
        <div class="error-decorations">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
        </div>

        <div class="text-center">
            {{-- Logo --}}
            <img src="{{ asset('icons/navbar.png') }}" class="error-logo" alt="{{ config('app.name') }} Logo">

            {{-- Error Code --}}
            <h1 class="error-code">Error {{ $code }}</h1>

            {{-- Error Message --}}
            <p class="error-message">
                {{ $message }}
            </p>

            {{-- Action Buttons --}}
            <div class="error-actions">
                <a href="{{ url()->previous() == url()->current() ? route('app.home') : url()->previous() }}" class="btn-error btn-primary-gradient">
                    <i class="uil uil-arrow-left"></i>
                    Kembali
                </a>
                <a href="{{ route('app.home') }}" class="btn-error btn-outline-custom">
                    <i class="uil uil-home-alt"></i>
                    Beranda
                </a>
            </div>
        </div>
    </div>
</div>
