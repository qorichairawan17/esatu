@extends('admin.layout.body')
@section('title', $title)

@section('content')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/pembayaran-surat-kuasa.css') }}">

    <!-- Start Page Content -->
    <main class="page-content bg-light">
        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">
                @include('admin.component.breadcumb')

                <div class="mt-4">
                    <div class="card clean-card">
                        <div class="card-body p-4 p-md-5">

                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                                <div>
                                    <h4 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.01em;">Pembayaran PNBP</h4>
                                    <p class="text-muted mb-0">ID: <span class="fw-semibold text-dark">{{ $suratKuasa->id_daftar }}</span> &bull; A.n <span
                                            class="fw-semibold text-dark">{{ $suratKuasa->pemohon }}</span></p>
                                </div>
                                <a href="{{ route('surat-kuasa.detail', ['id' => Crypt::encrypt($suratKuasa->id)]) }}" class="btn btn-danger rounded-pill px-4 fw-medium shadow-sm">
                                    <i class="uil uil-arrow-left me-1"></i> Kembali
                                </a>
                            </div>

                            <div class="info-alert p-4 mb-4 d-flex align-items-start gap-3">
                                <div class="text-primary mt-1">
                                    <i class="uil uil-info-circle" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Panduan Pembayaran</h6>
                                    <p class="mb-0 text-muted" style="line-height: 1.6;">Pilih metode pembayaran yang Kamu inginkan, selesaikan transaksi sesuai dengan nominal tagihan, dan unggah
                                        bukti transfer untuk mempercepat proses verifikasi oleh petugas.</p>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="{{ $config->logo_bank ? 'col-sm-6' : 'col-12' }}">
                                    <div id="pay-transfer" class="method-card" data-payment-method="Transfer Bank">
                                        <div class="method-icon-wrapper">
                                            <i class="uil uil-building fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1">Transfer Bank</h6>
                                        <p class="text-muted small mb-3">{{ $config->nama_bank }}</p>
                                        @if ($config->logo_bank)
                                            <img src="{{ asset('storage/' . $config->logo_bank) }}" alt="Logo Bank" class="img-fluid mt-auto" style="max-height: 28px; width: auto; object-fit: contain;">
                                        @endif
                                    </div>
                                </div>

                                @if ($config->qris)
                                    <div class="col-sm-6">
                                        <div id="pay-qris" class="method-card" data-payment-method="QRIS">
                                            <div class="method-icon-wrapper">
                                                <i class="uil uil-qrcode-scan fs-3"></i>
                                            </div>
                                            <h6 class="fw-bold mb-1">QRIS</h6>
                                            <p class="text-muted small mb-3">Ovo, Dana, Gopay, dll</p>
                                            <img src="{{ asset('images/quick-response-code-indonesia-standard-qris-seeklogo.svg') }}" alt="QRIS" class="img-fluid mt-auto"
                                                style="max-height: 28px; width: auto; object-fit: contain;">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div id="payment-details" class="fade-in" style="display: none;">
                                <div class="payment-box text-center mb-4">
                                    <p class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.8rem; letter-spacing: 0.1em;">Total Tagihan PNBP</p>
                                    <div class="amount-display mb-4">Rp 10.000</div>

                                    <div id="transfer-instructions" style="display: none;">
                                        <div class="account-box mx-auto text-start d-inline-block">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                @if ($config->logo_bank)
                                                    <img src="{{ asset('storage/' . $config->logo_bank) }}" alt="" style="max-height: 24px;">
                                                @endif
                                                <span class="fw-semibold text-muted">{{ $config->nama_bank }}</span>
                                            </div>
                                            <h3 class="fw-bold text-primary tracking-wide mb-1 user-select-all">{{ $config->nomor_rekening }}</h3>
                                            <p class="mb-0 text-muted small">a/n <span class="fw-bold text-dark" style="text-transform: uppercase;">RPL {{ $infoApp->pengadilan_negeri }}</span></p>
                                        </div>
                                        <p class="text-muted small mt-4 mb-0 mx-auto" style="max-width: 400px; line-height: 1.6;">
                                            Silakan buka aplikasi M-Banking Kamu, pilih menu transfer antar bank, dan masukkan nomor rekening tujuan di atas.
                                        </p>
                                    </div>

                                    <div id="qris-instructions" style="display: none;">
                                        <div class="account-box mx-auto d-inline-block p-2 bg-white">
                                            <img src="{{ asset('storage/' . $config->qris) }}" alt="QR Code" class="img-fluid rounded" style="max-width: 200px;">
                                        </div>
                                        <p class="text-muted small mt-4 mb-0 mx-auto" style="max-width: 400px; line-height: 1.6;">
                                            Silakan buka dompet digital pilihan Kamu, pilih menu <strong>Scan QR</strong>, lalu arahkan kamera ke QR Code di atas.
                                        </p>
                                    </div>
                                </div>

                                <form id="payment-form" enctype="multipart/form-data" action="{{ route('surat-kuasa.pembayaran-store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ Crypt::encrypt($suratKuasa->id) }}">
                                    <input type="hidden" name="jenis_pembayaran" id="jenis_pembayaran">

                                    <div class="mb-2">
                                        <label class="form-label fw-bold text-dark">Unggah Bukti Tagihan <span class="text-danger">*</span></label>
                                        <input type="file" name="bukti_pembayaran" class="form-control custom-file-input" accept=".pdf,image/png,image/jpeg,image/jpg" required>
                                        <div class="form-text text-muted mt-2 d-flex align-items-center gap-1">
                                            <i class="uil uil-info-circle"></i> Format dokumen: PDF, JPG, PNG (Maks 2MB)
                                        </div>
                                        <div id="bukti_pembayaran_error" class="invalid-feedback d-block fw-medium mt-1"></div>
                                    </div>

                                    <hr class="my-4" style="border-color: #e2e8f0;">

                                    <div class="text-end">
                                        <button type="submit" id="submit-button" class="btn btn-premium btn-primary-premium w-100 w-sm-auto px-5">
                                            <i class="uil uil-cloud-upload d-inline-block me-1"></i> Konfirmasi & Unggah
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div><!--end container-->

        @include('admin.layout.content-footer')
    </main>
    <!--End page-content" -->
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payTransfer = document.getElementById('pay-transfer');
            const payQris = document.getElementById('pay-qris');
            const paymentDetails = document.getElementById('payment-details');
            const transferInstructions = document.getElementById('transfer-instructions');
            const qrisInstructions = document.getElementById('qris-instructions');
            const jenisPembayaranInput = document.getElementById('jenis_pembayaran');
            const paymentForm = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');
            const buktiPembayaranError = document.getElementById('bukti_pembayaran_error');

            function selectPaymentMethod(method) {
                jenisPembayaranInput.value = method;

                if (paymentDetails.style.display === 'none') {
                    paymentDetails.style.display = 'block';
                    // Optional smooth scroll to details if on mobile
                    if (window.innerWidth < 768) {
                        setTimeout(() => {
                            paymentDetails.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }, 50);
                    }
                }

                if (payTransfer) {
                    method === 'Transfer Bank' ? payTransfer.classList.add('selected') : payTransfer.classList.remove('selected');
                }
                if (payQris) {
                    method === 'QRIS' ? payQris.classList.add('selected') : payQris.classList.remove('selected');
                }

                if (method === 'Transfer Bank') {
                    transferInstructions.style.display = 'block';
                    qrisInstructions.style.display = 'none';
                } else if (method === 'QRIS') {
                    qrisInstructions.style.display = 'block';
                    transferInstructions.style.display = 'none';
                }
            }

            if (payTransfer) {
                payTransfer.addEventListener('click', () => selectPaymentMethod('Transfer Bank'));
            }

            if (payQris) {
                payQris.addEventListener('click', () => selectPaymentMethod('QRIS'));
            }

            paymentForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                buktiPembayaranError.textContent = '';

                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengunggah...';

                const formData = new FormData(paymentForm);
                const action = paymentForm.getAttribute('action');

                try {
                    const response = await fetch(action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && result.errors) {
                            if (result.errors.bukti_pembayaran) {
                                buktiPembayaranError.textContent = result.errors.bukti_pembayaran[0];
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: result.message || 'Silakan periksa kembali file yang Kamu unggah.',
                                confirmButtonColor: '#3b82f6',
                                borderRadius: '1rem'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: result.message || 'Terjadi kesalahan pada server saat mengunggah file.',
                                confirmButtonColor: '#3b82f6',
                                borderRadius: '1rem'
                            });
                        }
                        throw new Error('Server error');
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Terkirim!',
                        text: result.message || 'Bukti pembayaran berhasil diunggah dan sedang diproses.',
                        timer: 2500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = "{{ route('surat-kuasa.detail', ['id' => Crypt::encrypt($suratKuasa->id)]) }}";
                        }
                    });

                } catch (error) {
                    console.error('Submission error:', error);
                } finally {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            });
        });
    </script>
@endpush
