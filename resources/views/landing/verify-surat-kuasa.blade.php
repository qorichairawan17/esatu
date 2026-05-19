@extends('landing.index')
@section('title', $title)
@section('content')
    @php
        $pendaftaran = $suratKuasa->pendaftaran;
        $pihak = collect($pendaftaran?->pihak ?? []);
        $pemberiKuasa = $pihak->where('jenis', 'Pemberi')->pluck('nama')->filter()->join(', ') ?: 'Tidak tersedia';
        $penerimaKuasa = $pihak->where('jenis', 'Penerima')->pluck('nama')->filter()->join(', ') ?: 'Tidak tersedia';
        $formatDate = fn($date): string => filled($date) ? \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') : 'Tidak tersedia';
    @endphp

    @push('styles')
        <link href="{{ asset('assets/css/verify-surat-kuasa.css') }}" rel="stylesheet" type="text/css">
    @endpush

    <main class="verify-page pt-5">
        <section class="verify-hero">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="badge rounded-pill verify-badge px-3 py-2 mb-3">
                            <i class="uil uil-shield-check me-1"></i> Laman Verifikasi Resmi
                        </span>
                        <h1 class="fw-bold mb-3">Surat Kuasa Terverifikasi</h1>
                        <p class="text-muted para-desc mb-0">
                            Data berikut menunjukkan surat kuasa telah tercatat pada sistem
                            {{ config('app.name') }} dan dapat digunakan untuk memastikan keabsahan bukti pendaftaran.
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <div class="verify-card bg-white p-4">
                            <div class="d-flex align-items-start">
                                <span class="verify-status-icon me-3">
                                    <i class="uil uil-check-circle fs-2"></i>
                                </span>
                                <div>
                                    <span class="badge rounded-pill bg-soft-success text-success mb-2">Sah dan terdaftar</span>
                                    <h5 class="mb-1">{{ $suratKuasa->nomor_surat_kuasa ?: 'Nomor belum tersedia' }}</h5>
                                    <p class="text-muted mb-0 small">
                                        ID Pendaftaran: <span class="fw-semibold text-dark">{{ $pendaftaran->id_daftar ?? 'Tidak tersedia' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="verify-card bg-white p-4 p-lg-5">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                                <div>
                                    <span class="text-success fw-semibold small">Ringkasan Dokumen</span>
                                    <h4 class="mb-1">Informasi Surat Kuasa</h4>
                                    <p class="text-muted mb-0">
                                        Ringkasan data yang tersimpan pada pendaftaran surat kuasa.
                                    </p>
                                </div>
                                <i class="uil uil-file-check-alt text-success fs-1"></i>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="verify-detail">
                                        <small>ID Pendaftaran</small>
                                        <strong>{{ $pendaftaran->id_daftar ?? 'Tidak tersedia' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="verify-detail">
                                        <small>Nomor Surat Kuasa</small>
                                        <strong>{{ $suratKuasa->nomor_surat_kuasa ?: 'Tidak tersedia' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="verify-detail">
                                        <small>Tanggal Register</small>
                                        <strong>{{ $formatDate($suratKuasa->tanggal_register) }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="verify-detail">
                                        <small>Tanggal Didaftarkan</small>
                                        <strong>{{ $formatDate($pendaftaran->tanggal_daftar ?? null) }}</strong>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="verify-detail">
                                        <small>Perihal</small>
                                        <strong>{{ $pendaftaran->perihal ?? 'Tidak tersedia' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="verify-detail">
                                        <small>Jenis Surat Kuasa</small>
                                        <strong>{{ $pendaftaran->jenis_surat ?? 'Tidak tersedia' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="verify-detail">
                                        <small>Klasifikasi</small>
                                        <strong>Surat Kuasa {{ $pendaftaran->klasifikasi ?? 'Tidak tersedia' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="verify-side-panel p-4 h-100">
                            <span class="text-success fw-semibold small">Validasi Petugas</span>
                            <h5 class="mb-3">Pengesahan</h5>

                            <div class="mb-4">
                                <small class="text-muted d-block mb-1">Disahkan oleh Panitera</small>
                                <strong class="text-dark">{{ $suratKuasa->panitera->nama ?? 'Tidak tersedia' }}</strong>
                            </div>
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1">Diverifikasi oleh Petugas</small>
                                <strong class="text-dark">{{ $suratKuasa->approval->name ?? 'Tidak tersedia' }}</strong>
                            </div>
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1">Didaftarkan oleh</small>
                                <strong class="text-dark">{{ $pendaftaran->user->name ?? 'Tidak tersedia' }}</strong>
                            </div>

                            <div class="alert alert-light border mb-0">
                                <div class="d-flex">
                                    <i class="uil uil-info-circle text-success fs-5 me-2"></i>
                                    <p class="text-muted small mb-0">
                                        Informasi ini berasal dari barcode atau tautan verifikasi resmi
                                        {{ config('app.name') }}.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="verify-card bg-white p-4 p-lg-5">
                            <div class="row align-items-center g-4">
                                <div class="col-lg-4">
                                    <span class="text-success fw-semibold small">Para Pihak</span>
                                    <h4 class="mb-2">Pihak dalam Surat Kuasa</h4>
                                    <p class="text-muted mb-0">
                                        Nama pihak yang tercatat dalam pendaftaran surat kuasa ini.
                                    </p>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="verify-party-box">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="uil uil-user-square text-success fs-4 me-2"></i>
                                                    <h6 class="mb-0">Pemberi Kuasa</h6>
                                                </div>
                                                <p class="text-muted mb-0">{{ $pemberiKuasa }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="verify-party-box">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="uil uil-user-check text-success fs-4 me-2"></i>
                                                    <h6 class="mb-0">Penerima Kuasa</h6>
                                                </div>
                                                <p class="text-muted mb-0">{{ $penerimaKuasa }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('app.home') }}" class="btn btn-pills btn-soft-success">
                                <i class="uil uil-estate me-1"></i> Beranda
                            </a>
                            <a href="{{ route('app.signin') }}" class="btn btn-pills btn-success">
                                Masuk Aplikasi <i class="uil uil-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
