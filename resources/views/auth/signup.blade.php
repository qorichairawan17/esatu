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
                                <img onclick="window.location='{{ route('app.home') }}'" src="{{ asset('icons/navbar.png') }}" class="auth-logo mb-5" alt="{{ config('app.name') }}">
                                <span class="auth-badge mb-4">
                                    <i class="uil uil-user-plus"></i>
                                    Registrasi akun layanan
                                </span>
                                <h1 class="fw-bold mb-3">
                                    Daftar untuk mulai mengajukan surat kuasa
                                </h1>
                                <p class="auth-copy mb-4">
                                    Buat akun pengguna, lengkapi data diri, lalu ajukan surat kuasa secara Digital melalui layanan {{ config('app.name') }}.
                                </p>
                            </div>

                            <div>
                                <img src="{{ asset('assets/images/model-1.jpeg') }}" class="auth-visual mb-4" alt="Pendaftaran {{ config('app.name') }}">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="auth-info-card h-100">
                                            <div class="d-flex align-items-center">
                                                <span class="auth-icon me-3">
                                                    <i class="uil uil-edit-alt fs-4"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">Data akun</h6>
                                                    <p class="text-muted small mb-0">Identitas pengguna</p>
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
                                                    <p class="text-muted small mb-0">Verifikasi email</p>
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
                                        <i class="uil uil-user-plus"></i>
                                        Daftar pengguna
                                    </span>
                                    <h2 class="fw-bold mb-2">Buat Akun Baru</h2>
                                    <p class="text-muted mb-0">Gunakan email aktif untuk menerima aktivasi akun.</p>
                                </div>

                                <form class="login-form" id="register-form">
                                    @method('POST')
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="namaDepan">
                                                    Nama Depan <span class="text-danger">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="user" class="fea icon-sm icons"></i>
                                                    <input type="text" class="form-control @error('namaDepan') is-invalid @enderror ps-5" placeholder="Nama depan" id="namaDepan" name="namaDepan"
                                                        required value="{{ old('namaDepan') }}" autocomplete="given-name">
                                                    <small class="text-danger mt-2" id="namaDepanError"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="namaBelakang">
                                                    Nama Belakang <span class="text-danger">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="user" class="fea icon-sm icons"></i>
                                                    <input type="text" class="form-control @error('namaBelakang') is-invalid @enderror ps-5" placeholder="Nama belakang" id="namaBelakang"
                                                        name="namaBelakang" required autocomplete="family-name" value="{{ old('namaBelakang') }}">
                                                    <small class="text-danger mt-2" id="namaBelakangError"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="email">
                                                    Email <span class="text-danger">*</span>
                                                </label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror ps-5" placeholder="nama@email.com" id="email" name="email" required
                                                        value="{{ old('email') }}" autocomplete="email">
                                                    <small class="text-danger mt-2" id="emailError"></small>
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
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror ps-5 pe-5" placeholder="Minimal 8 karakter" id="password"
                                                        name="password" required autocomplete="new-password">
                                                    <span class="position-absolute" style="right: 16px; top: 12px; cursor: pointer; z-index: 10;" onclick="togglePassword()">
                                                        <i class="mdi mdi-eye-outline" id="togglePasswordIcon" style="font-size: 1.2rem; color: #8a98aa;"></i>
                                                    </span>
                                                    <small class="text-danger mt-2" id="passwordError"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="auth-info-card mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="privacy_policy" id="privacy_policy">
                                                    <label class="form-check-label" for="privacy_policy">
                                                        Saya telah membaca dan menyetujui
                                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal" class="auth-muted-link">
                                                            Kebijakan Privasi & Persyaratan Penggunaan
                                                        </a>
                                                    </label>
                                                    <small class="text-danger mt-2 d-block" id="privacy_policyError"></small>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="text-muted small">Butuh akses lama?</span>
                                                <a href="{{ route('auth.forgot-password') }}" class="auth-muted-link">Lupa password?</a>
                                            </div>

                                            <div class="d-grid">
                                                <button class="btn btn-success rounded-pill" type="submit" id="register-button">
                                                    Daftar <i class="uil uil-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-4">
                                            <div class="auth-divider">atau daftar dengan</div>
                                            <div class="d-grid mt-3">
                                                <a href="{{ route('google.redirect', ['action' => 'register']) }}" class="btn btn-outline-danger rounded-pill">
                                                    <i class="mdi mdi-google text-danger"></i> Google
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-12 text-center">
                                            <p class="mb-0 mt-4">
                                                <small class="text-muted me-2">Sudah punya akun?</small>
                                                <a href="{{ route('app.signin') }}" class="auth-muted-link">
                                                    Login di sini
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

    <div class="modal fade" id="privacyPolicyModal" tabindex="-1" aria-labelledby="privacyPolicyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyPolicyModalLabel">Kebijakan Privasi dan Persyaratan Penggunaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>1. Pendahuluan</h5>
                    <p class="text-muted" style="text-align: justify;">
                        Kebijakan Privasi ini menjelaskan bagaimana {{ config('app.author') }} melalui aplikasi Digital Surat Kuasa mengelola Data Pribadi pengguna. Kami berkomitmen untuk menjaga
                        kerahasiaan dan keamanan Data Pribadi sesuai UU No. 27 Tahun 2022 tentang Perlindungan Data Pribadi. Dengan menggunakan aplikasi ini, pengguna dianggap telah membaca, memahami,
                        dan menyetujui Kebijakan Privasi ini.
                    </p>

                    <h5>2. Pengumpulan Informasi</h5>
                    <p class="text-muted mb-2">Aplikasi dapat mengumpulkan data berikut:</p>
                    <ul class="text-muted">
                        <li>Identitas diri: nama lengkap, NIK, alamat, nomor telepon, email.</li>
                        <li>Data hukum: nomor perkara, pihak berperkara, informasi terkait surat kuasa.</li>
                        <li>Dokumen pendukung: KTP, surat kuasa, dan dokumen hukum lainnya.</li>
                        <li>Data teknis: username, password, log aktivitas, alamat IP.</li>
                    </ul>

                    <h5>3. Penggunaan Informasi</h5>
                    <p class="text-muted mb-2">Informasi Kamu digunakan untuk:</p>
                    <ul class="text-muted">
                        <li>Memproses pendaftaran surat kuasa Kamu.</li>
                        <li>Mengirim notifikasi terkait status pendaftaran Kamu.</li>
                        <li>Memverifikasi identitas pemberi dan penerima kuasa.</li>
                        <li>Pemenuhan kewajiban hukum dan administrasi peradilan.</li>
                        <li>Menyediakan dukungan layanan pengguna.</li>
                    </ul>

                    <h5>4. Keamanan Data</h5>
                    <p class="text-muted" style="text-align: justify;">
                        Kami menerapkan langkah keamanan yang wajar untuk melindungi informasi pribadi Kamu dari akses, penggunaan, atau pengungkapan yang tidak sah. Namun, tidak ada metode transmisi
                        melalui internet atau metode penyimpanan Digital yang 100% aman.
                    </p>

                    <h5>5. Hak Pengguna</h5>
                    <p class="text-muted mb-2">Pengguna berhak untuk:</p>
                    <ul class="text-muted">
                        <li>Mendapatkan informasi pemrosesan Data Pribadi.</li>
                        <li>Memperbaiki, memperbarui, atau menghapus Data Pribadi sesuai hukum.</li>
                        <li>Menarik persetujuan pemrosesan Data Pribadi.</li>
                        <li>Mengajukan keberatan atas pemrosesan tertentu.</li>
                    </ul>

                    <h5>6. Pengungkapan Data</h5>
                    <p class="text-muted mb-2">Data Pribadi tidak akan diperjualbelikan kepada pihak mana pun. Data hanya dapat dibuka apabila:</p>
                    <ul class="text-muted">
                        <li>Diwajibkan oleh undang-undang.</li>
                        <li>Diminta oleh otoritas resmi berdasarkan prosedur hukum.</li>
                        <li>Dibutuhkan dalam penyelenggaraan tugas peradilan.</li>
                    </ul>

                    <h5>7. Perubahan Kebijakan Privasi</h5>
                    <p class="text-muted">
                        Kami dapat mengubah Kebijakan Privasi ini sewaktu-waktu. Setiap perubahan akan diumumkan melalui aplikasi atau situs resmi pengadilan.
                    </p>

                    <h5>8. Persetujuan Pengguna</h5>
                    <p class="text-muted mb-0" style="text-align:justify;">
                        Dengan mencentang kotak persetujuan saat pendaftaran, Kamu setuju dengan pengumpulan dan penggunaan informasi sesuai kebijakan ini. Kamu juga setuju untuk mematuhi semua
                        persyaratan dan ketentuan yang berlaku.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft-success rounded-pill" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-sm btn-success rounded-pill" id="agree-button">Saya Setuju</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @if (session()->has('success'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Notifikasi',
                text: @json(session()->get('success')),
            })
        </script>
    @elseif (session()->has('error'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Oops',
                text: @json(session()->get('error')),
            })
        </script>
    @endif
    @include('auth.scripts.handleRegister')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const agreeButton = document.getElementById('agree-button');
            if (agreeButton) {
                agreeButton.addEventListener('click', function() {
                    document.getElementById('privacy_policy').checked = true;
                    bootstrap.Modal.getInstance(document.getElementById('privacyPolicyModal')).hide();
                });
            }
        });

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('mdi-eye-outline');
                toggleIcon.classList.add('mdi-eye-off-outline');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('mdi-eye-off-outline');
                toggleIcon.classList.add('mdi-eye-outline');
            }
        }
    </script>
    @include('miscellaneous.pwa-sw')
</body>

</html>
