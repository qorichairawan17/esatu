@extends('admin.layout.body')
@section('title', $title)

@section('content')
    @php
        $profile = $user->profile;
        $isProfileComplete = (int) $user->profile_status === 1;
        $isUserRole = Auth::user()->role == \App\Enum\RoleEnum::User->value;
        $isGoogleLinked = filled($user->google_id);
        $profilePhoto = $profile?->foto ? asset('storage/' . $profile->foto) : asset('assets/images/user/user-none.png');
        $registeredAt = \Carbon\Carbon::parse($user->created_at)->isoFormat('DD MMMM YYYY');
        $displayName = trim(($profile?->nama_depan ?? '') . ' ' . ($profile?->nama_belakang ?? '')) ?: $user->name;
    @endphp

    <main class="page-content bg-light profile-page">
        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">
                @include('admin.component.breadcumb')

                @if (session('warning'))
                    <div class="alert alert-warning border-0 mt-3 mb-0">
                        {{ session('warning') }}
                    </div>
                @endif

                <div class="profile-notice d-flex gap-3 p-3 mt-3">
                    <span class="profile-notice-icon">
                        <i class="uil uil-shield-check"></i>
                    </span>
                    <div>
                        <h6 class="mb-1">Gunakan identitas yang valid dan formal.</h6>
                        <p class="text-muted mb-0">Data profil ini dipakai sebagai identitas pribadi Advokat atau Badan Hukum yang diwakili saat pendaftaran surat kuasa.</p>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-xl-4 col-lg-5">
                        <div class="profile-card p-4 text-center">
                            <img class="profile-avatar rounded-circle" src="{{ $profilePhoto }}" alt="Foto profil {{ $displayName }}">

                            <h5 class="mt-3 mb-1">{{ $displayName }}</h5>
                            <p class="text-muted mb-2">{{ $user->email }}</p>

                            <span class="badge {{ $isProfileComplete ? 'bg-soft-primary text-primary' : 'bg-soft-warning text-warning' }} px-3 py-2">
                                {{ $isProfileComplete ? 'Profil Lengkap' : 'Profil Belum Lengkap' }}
                            </span>

                            <div class="profile-meta-list text-start mt-4">
                                <div class="profile-meta-item">
                                    <span class="profile-card-icon"><i class="uil uil-user-square"></i></span>
                                    <div>
                                        <small class="text-muted d-block">Peran</small>
                                        <strong>{{ $user->role }}</strong>
                                    </div>
                                </div>
                                <div class="profile-meta-item">
                                    <span class="profile-card-icon"><i class="uil uil-calendar-alt"></i></span>
                                    <div>
                                        <small class="text-muted d-block">Terdaftar</small>
                                        <strong>{{ $registeredAt }}</strong>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary w-100 mt-4" data-bs-toggle="modal" data-bs-target="#uploadFoto">
                                <i class="uil uil-camera me-1"></i> Ubah Foto
                            </button>

                            @if ($isUserRole)
                                <div class="profile-danger-zone mt-4 pt-4">
                                    <p class="small text-muted mb-2">Hapus akun beserta seluruh data yang tersimpan.</p>
                                    <button class="btn btn-outline-danger w-100" id="btn-delete-account">
                                        <i class="uil uil-trash-alt me-1"></i> Hapus Akun
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="profile-side-card p-4 mt-3">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="profile-card-icon"><i class="uil uil-google"></i></span>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Akun Google</h6>
                                    <p class="text-muted small mb-3">{{ $isGoogleLinked ? 'Akun sudah tertaut.' : 'Tautkan akun untuk opsi masuk tambahan.' }}</p>

                                    @if ($isGoogleLinked)
                                        <form id="unlink-google-form" action="{{ route('google.unlink') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary w-100">
                                                Putuskan Tautan
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('google.redirect', ['action' => 'link']) }}" class="btn btn-outline-primary w-100">
                                            Tautkan Google
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="profile-side-card p-4 mt-3">
                            <h6 class="mb-3">Bantuan</h6>
                            <ul class="list-unstyled profile-contact-list mb-0">
                                <li>
                                    <i class="uil uil-phone text-primary me-2"></i>
                                    {{ $infoApp->kontak }}
                                </li>
                                <li>
                                    <i class="uil uil-envelope text-primary me-2"></i>
                                    {{ $infoApp->email }}
                                </li>
                                <li>
                                    <i class="uil uil-book-open text-primary me-2"></i>
                                    <a href="{{ route('panduan.show') }}" target="_blank">Panduan Penggunaan</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-7">
                        <div class="profile-status-banner {{ $isProfileComplete ? 'is-complete' : 'is-incomplete' }} d-flex gap-3 p-3 mb-3">
                            <i class="uil {{ $isProfileComplete ? 'uil-check-circle' : 'uil-exclamation-triangle' }} fs-4"></i>
                            <div>
                                <h6 class="mb-1">{{ $isProfileComplete ? 'Profil siap digunakan' : 'Lengkapi profil terlebih dahulu' }}</h6>
                                <p class="mb-0">{{ $isProfileComplete ? 'Anda dapat melanjutkan pengajuan surat kuasa.' : 'Data pribadi, alamat, kontak, dan foto wajib lengkap sebelum pengajuan surat kuasa.' }}</p>
                            </div>
                        </div>

                        <div class="profile-panel">
                            <div class="profile-panel-header p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                    <div>
                                        <h5 class="mb-1">Pengaturan Profil</h5>
                                        <p class="text-muted mb-0">Perbarui identitas akun dan keamanan akses.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                <ul class="nav nav-pills profile-tabs gap-2 mb-4" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-profil-tab" data-bs-toggle="pill" data-bs-target="#pills-profil" type="button" role="tab"
                                            aria-controls="pills-profil" aria-selected="true">
                                            <i class="uil uil-user me-1"></i> Profil Saya
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button" role="tab"
                                            aria-controls="pills-password" aria-selected="false">
                                            <i class="uil uil-lock-alt me-1"></i> Password
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-profil" role="tabpanel" aria-labelledby="pills-profil-tab" tabindex="0">
                                        <form id="updateProfileForm" method="post" action="{{ route('profile.update') }}">
                                            @csrf

                                            <div class="profile-form-section mb-3">
                                                <h6 class="profile-form-section-title">Identitas</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="namaDepan" class="form-label">Nama Depan <span class="text-danger">*</span></label>
                                                        <input type="text" name="namaDepan" class="form-control" id="namaDepan" required value="{{ old('namaDepan', $profile?->nama_depan ?? '') }}"
                                                            placeholder="Nama depan">
                                                        <div class="invalid-feedback" id="namaDepan-error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="namaBelakang" class="form-label">Nama Belakang</label>
                                                        <input type="text" name="namaBelakang" class="form-control" id="namaBelakang" value="{{ old('namaBelakang', $profile?->nama_belakang ?? '') }}"
                                                            placeholder="Nama belakang">
                                                        <div class="invalid-feedback" id="namaBelakang-error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" name="email" class="form-control" id="email" required value="{{ old('email', $user->email ?? '') }}" placeholder="nama@example.com">
                                                        <div class="invalid-feedback" id="email-error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="kontak" class="form-label">Kontak HP/Telepon <span class="text-danger">*</span></label>
                                                        <input type="tel" name="kontak" class="form-control" id="kontak" required value="{{ old('kontak', $profile?->kontak ?? '') }}" placeholder="081234567890">
                                                        <div class="invalid-feedback" id="kontak-error"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="profile-form-section">
                                                <h6 class="profile-form-section-title">Data Personal</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="tanggalLahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                                        <input type="text" id="tanggalLahir" name="tanggalLahir" class="form-control" required
                                                            value="{{ old('tanggalLahir', $profile?->tanggal_lahir ? \Carbon\Carbon::parse($profile->tanggal_lahir)->format('d-m-Y') : '') }}"
                                                            placeholder="dd-mm-yyyy">
                                                        <div class="invalid-feedback" id="tanggalLahir-error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                                        <div class="d-flex flex-wrap gap-3 pt-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="jenisKelamin" id="lakiLaki" value="Laki-Laki"
                                                                    {{ old('jenisKelamin', $profile?->jenis_kelamin) === 'Laki-Laki' ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="lakiLaki">Laki-laki</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="jenisKelamin" id="perempuan" value="Perempuan"
                                                                    {{ old('jenisKelamin', $profile?->jenis_kelamin) === 'Perempuan' ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="perempuan">Perempuan</label>
                                                            </div>
                                                        </div>
                                                        <div class="invalid-feedback d-block" id="jenisKelamin-error"></div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                                        <textarea name="alamat" id="alamat" class="form-control" required placeholder="Alamat lengkap">{{ old('alamat', $profile?->alamat ?? '') }}</textarea>
                                                        <div class="invalid-feedback" id="alamat-error"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="profile-action-row mt-4">
                                                <button class="btn btn-primary px-4" type="submit" id="btn-save-profile">
                                                    Simpan Profil
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab" tabindex="0">
                                        <div class="profile-form-section mb-3">
                                            <div class="d-flex gap-3">
                                                <span class="profile-notice-icon">
                                                    <i class="uil uil-lock-alt"></i>
                                                </span>
                                                <div>
                                                    <h6 class="mb-1">Jaga keamanan akun</h6>
                                                    <p class="text-muted mb-0">Gunakan password yang kuat dan jangan membagikan akses akun kepada siapa pun.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <form id="updatePasswordForm" method="post" action="{{ route('profile.updatePassword') }}">
                                            @csrf
                                            <div class="profile-form-section">
                                                <h6 class="profile-form-section-title">Ubah Password</h6>
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="passwordLama" class="form-label">Password Lama <span class="text-danger">*</span></label>
                                                        <input type="password" name="passwordLama" class="form-control" id="passwordLama" required placeholder="Masukkan password lama">
                                                        <div class="invalid-feedback" id="passwordLama-error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="passwordBaru" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                                        <input type="password" name="passwordBaru" class="form-control" id="passwordBaru" required placeholder="Masukkan password baru">
                                                        <div class="invalid-feedback" id="passwordBaru-error"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="passwordBaru_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                                        <input type="password" name="passwordBaru_confirmation" class="form-control" id="passwordBaru_confirmation" required placeholder="Ulangi password baru">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="profile-action-row mt-4">
                                                <a href="{{ route('auth.forgot-password') }}" class="me-auto">Lupa password?</a>
                                                <button class="btn btn-primary px-4" type="submit" id="btn-save-password">
                                                    Simpan Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="uploadFoto" tabindex="-1" aria-labelledby="uploadFoto-title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header border-bottom">
                                <h5 class="modal-title" id="uploadFoto-title">Unggah Foto Profil</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" id="close-modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <label for="foto" class="form-label">Pilih Foto <span class="text-danger">*</span></label>
                                <input type="file" name="foto" class="form-control" id="foto" required accept="image/png, image/jpeg, image/jpg">
                                <div class="invalid-feedback" id="foto-error"></div>
                                <small class="text-muted d-block mt-2">Format jpg, jpeg, atau png. Maksimal 5 MB.</small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="btn-save-photo">Simpan Foto</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.layout.content-footer')
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/jquery-ui-1.14.1/jquery-ui.js') }}"></script>
    <script>
        $("#tanggalLahir").datepicker({
            dateFormat: 'dd-mm-yy',
            autoClose: true
        });

        $('#uploadFoto').on('hidden.bs.modal', function() {
            const fotoInput = $('#foto');
            fotoInput.val('');
            fotoInput.removeClass('is-invalid');
            $('#foto-error').text('');
        });

        $(document).ready(function() {
            $('#btn-save-photo').on('click', function() {
                const button = $(this);
                const originalButtonText = button.html();
                const fotoInput = $('#foto');
                const foto = $('#foto')[0].files[0];
                const errorDiv = $('#foto-error');
                let isSuccess = false;

                fotoInput.removeClass('is-invalid');
                errorDiv.text('');

                if (!foto) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silahkan pilih foto terlebih dahulu!',
                    });
                    return;
                }

                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...').prop('disabled', true);

                const formData = new FormData();
                formData.append('foto', foto);

                fetch("{{ route('profile.updatePhoto') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(({
                        status,
                        body
                    }) => {
                        if (status === 422) {
                            if (body.errors && body.errors.foto) {
                                fotoInput.addClass('is-invalid');
                                errorDiv.text(body.errors.foto[0]);
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Unggah',
                                text: body.message || 'Periksa kembali file Anda.',
                            });
                        } else if (status >= 200 && status < 300) {
                            isSuccess = true;
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: body.message,
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: body.message || 'Tidak dapat memproses permintaan Anda.',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                        });
                    })
                    .finally(() => {
                        if (!isSuccess) {
                            button.html(originalButtonText).prop('disabled', false);
                        }
                    });
            });

            $('#updatePasswordForm').on('submit', function(e) {
                e.preventDefault();

                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                const form = $(this);
                const formData = new FormData(this);
                const button = $('#btn-save-password');
                const originalButtonText = button.html();

                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...').prop('disabled', true);

                fetch(form.attr('action'), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(({
                        status,
                        body
                    }) => {
                        if (status === 422) {
                            $.each(body.errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '-error').text(value[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Silakan periksa kembali isian Anda.',
                            });
                        } else if (status >= 200 && status < 300) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: body.message,
                            });
                            form[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: body.message || 'Tidak dapat memproses permintaan Anda.',
                            });
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                        });
                    }).finally(() => {
                        button.html(originalButtonText).prop('disabled', false);
                    });
            });

            $('#updateProfileForm').on('submit', function(e) {
                e.preventDefault();

                $('#updateProfileForm .form-control').removeClass('is-invalid');
                $('#updateProfileForm .invalid-feedback').text('');

                const form = $(this);
                const formData = new FormData(this);
                const button = $('#btn-save-profile');
                const originalButtonText = button.html();

                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...').prop('disabled', true);

                fetch(form.attr('action'), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(({
                        status,
                        body
                    }) => {
                        if (status === 422) {
                            $.each(body.errors, function(key, value) {
                                const el = $('#' + key);
                                el.addClass('is-invalid');
                                $('#' + key + '-error').text(value[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Silakan periksa kembali isian Anda.',
                            });
                        } else if (status >= 200 && status < 300) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: body.message,
                                timer: 2000,
                                showConfirmButton: false,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: body.message || 'Tidak dapat memproses permintaan Anda.',
                            });
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                        });
                    }).finally(() => {
                        button.html(originalButtonText).prop('disabled', false);
                    });
            });

            $('#btn-delete-account').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    html: `
                        <p class="text-danger fw-bold">Tindakan ini tidak dapat dibatalkan.</p>
                        <p>Seluruh data Anda, termasuk riwayat pendaftaran surat kuasa dan file terkait, akan dihapus secara permanen.</p>
                        <p class="mt-3">Untuk melanjutkan, ketik "<b class="text-danger">hapus akun saya</b>" di bawah ini:</p>
                        <input type="text" id="confirmation-text" class="form-control" placeholder="hapus akun saya">
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e43f52',
                    cancelButtonColor: '#667085',
                    confirmButtonText: 'Ya, Hapus Akun Saya',
                    cancelButtonText: 'Batal',
                    preConfirm: () => {
                        const confirmationText = Swal.getPopup().querySelector('#confirmation-text').value;
                        if (confirmationText.toLowerCase() !== 'hapus akun saya') {
                            Swal.showValidationMessage(`Silakan ketik "hapus akun saya" untuk konfirmasi.`);
                            return false;
                        }
                        return true;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus Akun...',
                            text: 'Mohon tunggu sebentar, proses ini akan menghapus semua data Anda.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch("{{ route('profile.destroy') }}", {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => response.json().then(data => ({
                                status: response.status,
                                body: data
                            })))
                            .then(({
                                status,
                                body
                            }) => {
                                if (status === 200) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: body.message,
                                    }).then(() => {
                                        window.location.href = "{{ route('app.signin') }}";
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: body.message || 'Terjadi kesalahan saat menghapus akun.',
                                    });
                                }
                            })
                    }
                });
            });

            $('#unlink-google-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Putuskan tautan akun Google?',
                    text: 'Anda masih bisa login menggunakan email dan password.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#29AA59',
                    cancelButtonColor: '#667085',
                    confirmButtonText: 'Ya, putuskan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        form.submit();
                    }
                })
            });

            @if (session('success'))
                Swal.fire('Berhasil!', @json(session('success')), 'success');
            @endif

            @if (session('error'))
                Swal.fire('Gagal!', @json(session('error')), 'error');
            @endif
        });
    </script>
@endpush
