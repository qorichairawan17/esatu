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
                                <h1 class="fw-bold mb-3">
                                    {{ isset($token) ? 'Buat password baru dengan aman' : 'Pulihkan akses akun layanan' }}
                                </h1>
                                <p class="auth-copy mb-4">
                                    {{ isset($token)
                                        ? 'Gunakan password baru yang kuat agar akses layanan surat kuasa digital tetap terlindungi.'
                                        : 'Masukkan email terdaftar untuk menerima tautan reset password dan kembali mengakses layanan digital.' }}
                                </p>
                            </div>

                            <div>
                                <img src="{{ asset('assets/images/model-5.jpeg') }}" class="auth-visual mb-4" alt="Pemulihan akun {{ config('app.name') }}">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="auth-info-card h-100">
                                            <div class="d-flex align-items-center">
                                                <span class="auth-icon me-3">
                                                    <i class="uil uil-envelope-check fs-4"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">Email aktif</h6>
                                                    <p class="text-muted small mb-0">Tautan reset akun</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="auth-info-card h-100">
                                            <div class="d-flex align-items-center">
                                                <span class="auth-icon me-3">
                                                    <i class="uil uil-shield-check fs-4"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">Aman</h6>
                                                    <p class="text-muted small mb-0">Token terbatas</p>
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
                                @if (isset($token))
                                <div class="text-center mb-4">
                                    <span class="auth-badge mb-3">
                                        <i class="uil uil-padlock"></i>
                                        Reset password
                                    </span>
                                    <h2 class="fw-bold mb-2">Password Baru</h2>
                                    <p class="text-muted mb-0">Gunakan minimal 8 karakter untuk mengamankan akun Kamu.</p>
                                </div>

                                <form class="login-form" id="reset-password-form">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="email">
                                                    Email <span class="text-success">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input type="email" class="form-control ps-5" name="email" id="email"
                                                        value="{{ $email ?? old('email') }}" readonly autocomplete="email">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="password">
                                                    Password Baru <span class="text-success">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="lock" class="fea icon-sm icons"></i>
                                                    <input type="password" class="form-control ps-5" placeholder="Minimal 8 karakter" name="password"
                                                        id="password" required autocomplete="new-password">
                                                    <small class="text-danger mt-2 d-block" id="passwordError"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-4">
                                                <label class="form-label" for="password_confirmation">
                                                    Konfirmasi Password <span class="text-success">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="lock" class="fea icon-sm icons"></i>
                                                    <input type="password" class="form-control ps-5" placeholder="Ulangi password baru"
                                                        name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success rounded-pill">
                                                    Simpan Password <i class="uil uil-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>

                                        @if (!Auth::user())
                                        <div class="col-12 text-center">
                                            <p class="mb-0 mt-4">
                                                <small class="text-muted me-2">Ingat password Kamu?</small>
                                                <a href="{{ route('app.signin') }}" class="auth-muted-link">Masuk</a>
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </form>
                                @else
                                <div class="text-center mb-4">
                                    <span class="auth-badge mb-3">
                                        <i class="uil uil-envelope-upload"></i>
                                        Lupa password
                                    </span>
                                    <h2 class="fw-bold mb-2">Reset Akses Akun</h2>
                                    <p class="text-muted mb-0">Tautan reset akan dikirim ke email yang terdaftar pada akun Kamu.</p>
                                </div>

                                <form class="login-form" id="send-link-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="auth-info-card mb-4">
                                                <div class="d-flex align-items-start">
                                                    <span class="auth-icon me-3">
                                                        <i class="uil uil-info-circle fs-4"></i>
                                                    </span>
                                                    <div>
                                                        <h6 class="mb-1">Cek kotak masuk email</h6>
                                                        <p class="text-muted small mb-0">Jika email terdaftar, tautan reset akan dikirim untuk membuat password baru.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label" for="email">
                                                    Email <span class="text-success">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input type="email" class="form-control ps-5" placeholder="nama@email.com" name="email"
                                                        id="email" required autocomplete="email" value="{{ old('email') }}">
                                                    <small class="text-danger mt-2 d-block" id="emailError"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-success rounded-pill">
                                                    Kirim Tautan Reset <i class="uil uil-arrow-right"></i>
                                                </button>
                                                <a href="{{ route('app.signin') }}" class="btn btn-soft-success rounded-pill">
                                                    Kembali ke Login
                                                </a>
                                            </div>
                                        </div>

                                        @if (!Auth::user())
                                        <div class="col-12 text-center">
                                            <p class="mb-0 mt-4">
                                                <small class="text-muted me-2">Ingat password Kamu?</small>
                                                <a href="{{ route('app.signin') }}" class="auth-muted-link">Masuk</a>
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </form>
                                @endif
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
    @if (session()->has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: @json(session('error')),
            showConfirmButton: true,
        });
    </script>
    @endif
    @include('auth.scripts.handleResetPassword')
    @include('miscellaneous.pwa-sw')
</body>

</html>