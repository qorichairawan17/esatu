@include('auth.layouts.header')

<body class="auth-body">
    <div id="preloader">
        <div id="status">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
        </div>
    </div>

    <section class="auth-shell d-flex align-items-center">
        <div class="container">
            <div class="auth-card">
                <div class="row g-0">
                    <div class="col-lg-6 d-none d-lg-flex auth-hero">
                        <div class="auth-hero-inner d-flex flex-column justify-content-between w-100">
                            <div>
                                <img onclick="window.location='{{ route('app.home') }}'" src="{{ asset('icons/navbar.png') }}" class="auth-logo mb-5"
                                    alt="{{ config('app.name') }}">
                                <span class="auth-badge mb-4">
                                    <i class="uil uil-shield-check"></i>
                                    Akses layanan digital
                                </span>
                                <h1 class="fw-bold mb-3">
                                    Masuk ke {{ config('app.name') }}
                                </h1>
                                <p class="auth-copy mb-4">
                                    Kelola pendaftaran surat kuasa, pantau verifikasi, dan cetak bukti barcode melalui satu pintu layanan yang rapi
                                    dan aman.
                                </p>
                            </div>

                            <div>
                                <img src="{{ asset('assets/images/model-5.jpeg') }}" class="auth-visual mb-4" alt="Layanan {{ config('app.name') }}">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="auth-info-card h-100">
                                            <div class="d-flex align-items-center">
                                                <span class="auth-icon me-3">
                                                    <i class="uil uil-file-check-alt fs-4"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">Surat Kuasa</h6>
                                                    <p class="text-muted small mb-0">Pendaftaran digital</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="auth-info-card h-100">
                                            <div class="d-flex align-items-center">
                                                <span class="auth-icon me-3">
                                                    <i class="uil uil-qrcode-scan fs-4"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">Barcode</h6>
                                                    <p class="text-muted small mb-0">Bukti terverifikasi</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="auth-form-panel d-flex align-items-center justify-content-center">
                            <div class="auth-form-card">
                                <div class="text-center mb-4">
                                    <span class="auth-badge mb-3">
                                        <i class="uil uil-signin"></i>
                                        Login pengguna
                                    </span>
                                    <h2 class="fw-bold mb-2">Selamat Datang</h2>
                                    <p class="text-muted mb-0">Masuk untuk melanjutkan pendaftaran surat kuasa digital.</p>
                                </div>

                                <form class="login-form" id="login-form">
                                    @method('POST')
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="email">
                                                    Email <span class="text-danger">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror ps-5"
                                                        placeholder="nama@email.com" id="email" name="email" required
                                                        value="{{ old('email') }}" autocomplete="email">
                                                    <div class="invalid-feedback mt-2" id="emailError"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="password">
                                                    Password <span class="text-danger">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="lock" class="fea icon-sm icons"></i>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror ps-5 pe-5"
                                                        placeholder="Masukkan password" id="password" name="password" required
                                                        value="{{ old('password') }}" autocomplete="current-password">
                                                    <span class="position-absolute" style="right: 16px; top: 12px; cursor: pointer; z-index: 10;"
                                                        onclick="togglePassword()">
                                                        <i class="mdi mdi-eye-outline" id="togglePasswordIcon"
                                                            style="font-size: 1.2rem; color: #8a98aa;"></i>
                                                    </span>
                                                    <div class="invalid-feedback mt-2" id="passwordError"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="auth-captcha mb-3">
                                                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                                    <div>
                                                        <label class="form-label mb-1" for="captcha">Kode Captcha <span
                                                                class="text-danger">*</span></label>
                                                        <p class="text-muted small mb-0">Klik gambar untuk memuat ulang.</p>
                                                    </div>
                                                    <img title="Klik Untuk Refresh" class="img-fluid" src="{{ captcha_src('flat') }}" alt="captcha"
                                                        id="captcha-img" style="cursor: pointer;">
                                                </div>
                                                <input class="form-control @error('captcha') is-invalid @enderror mt-3" type="text"
                                                    name="captcha" id="captcha" placeholder="Masukkan kode captcha" required>
                                                <div class="invalid-feedback mt-2" id="captchaError"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="text-muted small">Akun lama? login untuk aktivasi ulang.</span>
                                                <a href="{{ route('auth.forgot-password') }}" class="auth-muted-link">Lupa password?</a>
                                            </div>
                                            <div class="d-grid">
                                                <button class="btn btn-success rounded-pill" type="submit" id="login-button">
                                                    Masuk <i class="uil uil-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-4">
                                            <div class="auth-divider">atau masuk dengan</div>
                                            <div class="d-grid mt-3">
                                                <a href="{{ route('google.redirect', ['action' => 'login']) }}"
                                                    class="btn btn-outline-danger rounded-pill">
                                                    <i class="mdi mdi-google text-danger"></i> Google
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-12 text-center">
                                            <p class="mb-0 mt-4">
                                                <small class="text-muted me-2">Belum punya akun?</small>
                                                <a href="{{ route('app.signup') }}" class="auth-muted-link">
                                                    Daftar di sini
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @if (session()->has('success') || session()->has('error'))
        <script>
            Swal.fire({
                position: 'center',
                icon: @json(session()->has('success') ? 'success' : 'error'),
                title: @json(session()->has('success') ? 'Notifikasi' : 'Oops...'),
                text: @json(session()->get('success') ?? session()->get('error')),
            })
        </script>
    @endif
    @include('auth.scripts.handleAuth')
    @include('miscellaneous.pwa-sw')
</body>

</html>
