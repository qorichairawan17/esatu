@extends('admin.layout.body')
@section('title', $title)

@section('content')
    <!-- CSS Dependencies -->
    <link href="{{ asset('admin/assets/css/premium-dashboard.css') }}" rel="stylesheet" />

    <!-- Start Page Content -->
    <main class="page-content bg-light premium-dashboard">

        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">

                @include('admin.component.breadcumb')

                <!-- Welcome Banner & Shortcuts -->
                <div class="welcome-banner mt-3">
                    <div class="welcome-content">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge rounded-pill bg-primary align-middle me-2 py-1 px-3" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px;">{{ config('app.name') }}
                                DASHBOARD
                            </span>
                        </div>
                        <h4 class="welcome-title">Selamat datang kembali, {{ Auth::user()->name }} 👋</h4>
                        <p class="welcome-subtitle">Akses semua layanan dan informasi pendaftaran Surat Kuasa dengan mudah melalui pintasan yang tersedia di bawah ini.</p>

                        <div class="shortcut-container">
                            <a href="{{ route('surat-kuasa.index') }}" class="shortcut-card-premium">
                                <div class="icon-box primary">
                                    <i class="ti ti-file-text"></i>
                                </div>
                                <h6 class="shortcut-title">Pendaftaran</h6>
                            </a>

                            <a href="#" data-bs-toggle="modal" data-bs-target="#suratKuasaModel" class="shortcut-card-premium">
                                <div class="icon-box success">
                                    <i class="ti ti-credit-card"></i>
                                </div>
                                <h6 class="shortcut-title">Pembayaran</h6>
                            </a>

                            <a href="#" data-bs-toggle="modal" data-bs-target="#testimoniModal" class="shortcut-card-premium">
                                <div class="icon-box warning">
                                    <i class="ti ti-star"></i>
                                </div>
                                <h6 class="shortcut-title">Testimoni</h6>
                            </a>

                            <a href="{{ route('panduan.show') }}" class="shortcut-card-premium">
                                <div class="icon-box info">
                                    <i class="ti ti-help"></i>
                                </div>
                                <h6 class="shortcut-title">Panduan</h6>
                            </a>

                            <a href="{{ route('profile.index') }}" class="shortcut-card-premium">
                                <div class="icon-box danger">
                                    <i class="ti ti-user"></i>
                                </div>
                                <h6 class="shortcut-title">Profil Akun</h6>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <!-- Chart Section -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="premium-panel">
                            <div class="premium-panel-header">
                                <h6 class="premium-panel-title">
                                    <i class="ti ti-chart-bar text-primary fs-4"></i> Grafik Pendaftaran Surat Kuasa
                                </h6>
                            </div>
                            <div class="premium-panel-body">
                                <div class="chart-wrapper">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Registrations Section -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="premium-panel">
                            <div class="premium-panel-header">
                                <h6 class="premium-panel-title">
                                    <i class="ti ti-history text-primary fs-4"></i> Pendaftaran Terbaru
                                </h6>
                                <a href="{{ route('surat-kuasa.index') }}" class="btn-link-premium">
                                    Lihat <i class="uil uil-arrow-right"></i>
                                </a>
                            </div>

                            <div class="premium-panel-body px-2 py-0">
                                <div class="scroll-container px-2 py-3">
                                    @forelse ($pendaftaranSuratKuasa as $suratKuasa)
                                        <a href="{{ route('surat-kuasa.detail', ['id' => Crypt::encrypt($suratKuasa->id)]) }}" class="list-hover-item">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-md rounded-circle d-flex align-items-center justify-content-center border"
                                                        style="width: 48px; height: 48px; background: rgba(19, 108, 52, 0.08); border-color: rgba(19, 108, 52, 0.16) !important;">
                                                        <i class="ti ti-file-description fs-4 text-primary"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="item-title m-0">ID : {{ $suratKuasa->id_daftar }}</h6>
                                                    <span class="item-subtitle">{{ \Carbon\Carbon::parse($suratKuasa->tanggal_register)->isoFormat('dddd, D MMM Y') }}</span>
                                                </div>
                                            </div>
                                            <div class="ms-3 text-end">
                                                @php
                                                    $statusValue = $suratKuasa->status;
                                                    if ($statusValue instanceof \App\Enum\StatusSuratKuasaEnum) {
                                                        $statusValue = $statusValue->value;
                                                    }

                                                    $isDitolak =
                                                        (isset(\App\Enum\StatusSuratKuasaEnum::Ditolak->value) && $statusValue == \App\Enum\StatusSuratKuasaEnum::Ditolak->value) ||
                                                        strtolower($statusValue) == 'ditolak';
                                                    $isPending = strtolower($statusValue) == 'menunggu' || strtolower($statusValue) == 'pending';

                                                    $badgeClass = $isDitolak ? 'danger' : ($isPending ? 'warning' : 'success');
                                                @endphp
                                                <span class="status-badge {{ $badgeClass }}">{{ $statusValue }}</span>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="ti ti-inbox text-muted" style="font-size: 3.5rem; opacity: 0.3;"></i>
                                            </div>
                                            <h6 class="text-muted fw-semibold">Belum ada pendaftaran surat kuasa</h6>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end container-->
        @include('admin.layout.content-footer')
        <!-- End -->
    </main>

    <!-- Modal Testimoni -->
    <div class="modal fade modal-premium" id="testimoniModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testimoniModalLabel">Bagikan Pengalaman Kamu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="close-modal" aria-label="Close"></button>
                </div>

                <form id="formTestimoni" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4 text-center">
                            <label class="form-label d-block fw-bold text-dark fs-5 mb-2">Penilaian <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-3">Pilih bintang untuk memberikan rating pada layanan kami</p>

                            <fieldset class="rating" aria-label="Penilaian bintang" style="display:inline-block; font-size:1.5rem;">
                                <input type="radio" id="star5" name="rating" value="5" required @if (optional($testimoniUser)->rating == 5) checked @endif>
                                <label for="star5" title="5 - Sangat Puas"></label>

                                <input type="radio" id="star4" name="rating" value="4" @if (optional($testimoniUser)->rating == 4) checked @endif>
                                <label for="star4" title="4 - Puas"></label>

                                <input type="radio" id="star3" name="rating" value="3" @if (optional($testimoniUser)->rating == 3) checked @endif>
                                <label for="star3" title="3 - Cukup"></label>

                                <input type="radio" id="star2" name="rating" value="2" @if (optional($testimoniUser)->rating == 2) checked @endif>
                                <label for="star2" title="2 - Tidak Puas"></label>

                                <input type="radio" id="star1" name="rating" value="1" @if (optional($testimoniUser)->rating == 1) checked @endif>
                                <label for="star1" title="1 - Sangat Tidak Puas"></label>
                            </fieldset>

                            <div class="form-text mt-2">
                                Nilai saat ini: <strong id="ratingValue" class="text-primary fs-6">{{ optional($testimoniUser)->rating ?? 0 }}</strong> / 5
                            </div>
                            <div class="invalid-feedback d-block" id="ratingInvalid" style="display:none; font-weight:600;">Silakan pilih jumlah bintang terlebih dahulu.</div>
                        </div>

                        <div class="mb-2">
                            <label for="pesan" class="form-label fw-bold">Pesan Testimoni <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="pesan" name="pesan" rows="4" placeholder="Ceritakan kepuasan dan pengalaman kamu menggunakan {{ config('app.name') }} ..."></textarea>
                            <div class="invalid-feedback">Isi pesan testimoni wajib diisi.</div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light fw-bold px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-5">Kirim Testimoni</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div class="modal fade modal-premium" id="suratKuasaModel" tabindex="-1" aria-labelledby="suratKuasaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="suratKuasaModalLabel">Pilih Tagihan Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="close-modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label for="selectSuratKuasa" class="form-label fw-bold">
                            Data Pendaftaran <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg fs-6" id="selectSuratKuasa" name="suratKuasa" required>
                            <option value="" selected disabled>--- Pilih tiket pendaftaran Anda ---</option>
                            @forelse ($pembayaranSuratKuasa as $pembayaran)
                                <option value="{{ Crypt::encrypt($pembayaran->id) }}" data-tanggal="{{ \Carbon\Carbon::parse($pembayaran->tanggal_daftar)->isoFormat('D MMMM Y') }}"
                                    data-perihal="{{ $pembayaran->perihal }}">
                                    {{ $pembayaran->id_daftar }} - {{ Str::limit($pembayaran->perihal, 30) }}
                                </option>
                            @empty
                                <option value="" disabled>Yeay! Tidak ada tagihan yang belum terbayar.</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="p-4 bg-light rounded-4 border" id="info-surat-kuasa" style="display: none;">
                        <h6 class="fw-bold mb-3 text-primary"><i class="ti ti-info-circle me-1"></i> Rincian Informasi</h6>
                        <div class="d-flex mb-2">
                            <span class="text-muted me-auto" style="width: 80px;">Tanggal</span>
                            <span id="info-tanggal" class="fw-bold text-dark text-end"></span>
                        </div>
                        <div class="d-flex">
                            <span class="text-muted me-auto" style="width: 80px;">Perihal</span>
                            <span id="info-perihal" class="fw-bold text-dark text-end" style="word-break: break-word;"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light fw-bold px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="btnLanjutPembayaran" class="btn btn-primary px-5" disabled>Lanjutkan Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
    <!--End page-content" -->



    @push('scripts')
        <script src="{{ asset('admin/assets/plugins/chartjs/dist/chart.umd.js') }}"></script>
        <script>
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Disetujui',
                        data: @json($chartData),
                        backgroundColor: 'rgba(19, 108, 52, 0.85)',
                        borderColor: '#136C34',
                        borderWidth: 0,
                        borderRadius: 6,
                        barPercentage: 0.55
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.04)',
                                borderDash: [5, 5]
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#172033',
                            padding: 12,
                            titleFont: {
                                size: 13,
                                family: 'Inter'
                            },
                            bodyFont: {
                                size: 14,
                                weight: 'bold',
                                family: 'Inter'
                            },
                            displayColors: false,
                            cornerRadius: 8
                        }
                    }
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Update tampilan nilai rating di bawah bintang
                const ratingInputs = document.querySelectorAll('input[name="rating"]');
                const ratingValueEl = document.getElementById('ratingValue');
                const ratingInvalid = document.getElementById('ratingInvalid');

                ratingInputs.forEach(input => {
                    input.addEventListener('change', () => {
                        ratingValueEl.textContent = input.value;
                        ratingInvalid.style.display = 'none';
                    });
                });

                // Logika submit form testimoni
                const form = document.getElementById('formTestimoni');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!form.checkValidity()) {
                        e.stopPropagation();
                        form.classList.add('was-validated');
                        return;
                    }

                    const ratingChecked = document.querySelector('input[name="rating"]:checked');
                    if (!ratingChecked) {
                        ratingInvalid.style.display = 'block';
                        return;
                    }

                    form.classList.add('was-validated');

                    const formData = new FormData(form);
                    const submitButton = form.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';

                    fetch("{{ route('testimoni.store') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const modalEl = document.getElementById('testimoniModal');
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) modal.hide();
                                else document.querySelector('#testimoniModal .btn-close').click();

                                document.getElementById('formTestimoni').reset();
                                document.getElementById('formTestimoni').classList.remove('was-validated');

                                // Paksa hapus backdrop yang menyangkut
                                setTimeout(() => {
                                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                                    document.body.classList.remove('modal-open');
                                    document.body.style.overflow = '';
                                    document.body.style.paddingRight = '';

                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: data.message,
                                        icon: 'success',
                                        customClass: {
                                            confirmButton: 'btn btn-primary px-4'
                                        },
                                        buttonsStyling: false
                                    }).then(() => {
                                        // Pastikan lagi bersih
                                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                                        document.body.classList.remove('modal-open');
                                        document.body.style.overflow = '';
                                        document.body.style.paddingRight = '';
                                    });
                                }, 400);
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message || 'Terjadi kesalahan.',
                                    icon: 'error',
                                    customClass: {
                                        confirmButton: 'btn btn-danger px-4'
                                    },
                                    buttonsStyling: false
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Tidak dapat terhubung ke server.',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-danger px-4'
                                },
                                buttonsStyling: false
                            });
                        }).finally(() => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = 'Kirim Testimoni';
                        });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectSuratKuasa = document.getElementById('selectSuratKuasa');
                const infoContainer = document.getElementById('info-surat-kuasa');
                const infoTanggal = document.getElementById('info-tanggal');
                const infoPerihal = document.getElementById('info-perihal');
                const btnLanjut = document.getElementById('btnLanjutPembayaran');

                const paymentUrlTemplate = "{{ route('surat-kuasa.pembayaran', ['id' => ':id']) }}";

                if (selectSuratKuasa) {
                    selectSuratKuasa.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const selectedValue = selectedOption.value;

                        if (selectedValue) {
                            infoTanggal.textContent = selectedOption.getAttribute('data-tanggal');
                            infoPerihal.textContent = selectedOption.getAttribute('data-perihal');

                            // Animasi fade in
                            infoContainer.style.opacity = '0';
                            infoContainer.style.display = 'block';
                            setTimeout(() => {
                                infoContainer.style.transition = 'opacity 0.3s ease';
                                infoContainer.style.opacity = '1';
                            }, 50);

                            btnLanjut.disabled = false;
                        } else {
                            infoContainer.style.display = 'none';
                            btnLanjut.disabled = true;
                        }
                    });
                }

                if (btnLanjut) {
                    btnLanjut.addEventListener('click', function() {
                        const selectedId = selectSuratKuasa.value;
                        if (selectedId) {
                            // Show loading state
                            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengalihkan...';
                            this.disabled = true;

                            window.location.href = paymentUrlTemplate.replace(':id', selectedId);
                        }
                    });
                }
            });
        </script>
    @endpush

@endsection
