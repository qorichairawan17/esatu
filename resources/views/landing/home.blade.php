@extends('landing.index')
@section('title', $title)
@section('content')
@php
$courtName = $infoApp->pengadilan_negeri ?? config('app.author');
$courtWebsite = $infoApp->website ?? '#';
$courtEmail = $infoApp->email ?? null;
$feedbackEmails = array_values(array_unique(array_filter([$courtEmail])));
$leaders = [
[
'name' => $pejabatStruktural->ketua ?? null,
'role' => 'Ketua',
'photo' => $pejabatStruktural->foto_ketua ?? null,
'alt' => 'Foto Ketua',
],
[
'name' => $pejabatStruktural->wakil_ketua ?? null,
'role' => 'Wakil Ketua',
'photo' => $pejabatStruktural->foto_wakil_ketua ?? null,
'alt' => 'Foto Wakil Ketua',
],
[
'name' => $pejabatStruktural->panitera ?? null,
'role' => 'Panitera',
'photo' => $pejabatStruktural->foto_panitera ?? null,
'alt' => 'Foto Panitera',
],
[
'name' => $pejabatStruktural->sekretaris ?? null,
'role' => 'Sekretaris',
'photo' => $pejabatStruktural->foto_sekretaris ?? null,
'alt' => 'Foto Sekretaris',
],
];
$serviceSteps = [
['title' => 'Daftar akun', 'description' => 'Pengguna masuk ke aplikasi dan melengkapi profil pendaftaran.'],
['title' => 'Ajukan surat kuasa', 'description' => 'Data pendaftaran dan dokumen pendukung diunggah secara digital.'],
['title' => 'Pembayaran', 'description' => 'Biaya PNBP dapat dibayarkan melalui QRIS atau transfer bank.'],
['title' => 'Verifikasi & barcode', 'description' => 'Petugas memverifikasi data, lalu bukti barcode dapat dicetak.'],
];
$serviceFeatures = [
[
'icon' => 'uil uil-file-plus-alt',
'title' => 'Pendaftaran Online',
'description' => 'Pengajuan surat kuasa dapat dilakukan tanpa harus datang dan mengantre di PTSP.',
],
[
'icon' => 'uil uil-qrcode-scan',
'title' => 'Bukti Barcode',
'description' => 'Setiap pendaftaran yang disetujui memiliki bukti barcode untuk memudahkan pengecekan.',
],
[
'icon' => 'uil uil-shield-check',
'title' => 'Verifikasi Petugas',
'description' => 'Data dan pembayaran diperiksa oleh petugas sebelum layanan dinyatakan selesai.',
],
];
@endphp

<section class="bg-linear-gradient-primary position-relative overflow-hidden pt-5 pb-5">
    <div class="container mt-5 pt-5 pb-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="title-heading wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <span class="badge rounded-pill bg-soft-success text-success mb-3 px-3 py-2">Layanan Digital Pengadilan</span>
                    <h1 class="heading fw-bold mb-3">
                        {{ config('app.name') }}<br>
                        <span class="text-success">Pendaftaran Surat Kuasa Digital</span>
                    </h1>
                    <p class="text-muted para-desc mb-4">
                        Daftarkan surat kuasa secara online dengan proses yang lebih ringkas, biaya resmi
                        <span class="fw-semibold text-dark">Rp10.000</span>, dan bukti pendaftaran yang mudah diverifikasi.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('app.signin') }}" class="btn btn-pills btn-success">
                            Akses Sekarang <i data-feather="arrow-right" class="fea icon-sm"></i>
                        </a>
                        <a href="{{ route('panduan.show') }}" class="btn btn-pills btn-soft-success">
                            Lihat Panduan <i class="uil uil-book-open"></i>
                        </a>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="features feature-success p-3 bg-white rounded shadow-sm h-100">
                                <div class="d-flex align-items-center">
                                    <div class="icon text-center rounded-pill">
                                        <i class="uil uil-file-check-alt fs-4 mb-0 text-success"></i>
                                    </div>
                                    <div class="flex-1 ms-3">
                                        <span class="text-muted d-block small">Surat Kuasa</span>
                                        <strong class="fs-5 text-dark">
                                            <span class="counter-value" data-target="{{ $totalSuratKuasa }}">{{ $totalSuratKuasa }}</span>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="features feature-success p-3 bg-white rounded shadow-sm h-100">
                                <div class="d-flex align-items-center">
                                    <div class="icon text-center rounded-pill">
                                        <i class="uil uil-users-alt fs-4 mb-0 text-success"></i>
                                    </div>
                                    <div class="flex-1 ms-3">
                                        <span class="text-muted d-block small">Pengguna</span>
                                        <strong class="fs-5 text-dark">
                                            <span class="counter-value" data-target="{{ $totalUser }}">{{ $totalUser }}</span>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted small mb-0 mt-3">
                        Dikembangkan oleh
                        <a href="{{ $courtWebsite }}" target="_blank" class="text-success fw-semibold">{{ $courtName }}</a>
                    </p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="position-relative wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                    <img src="{{ asset('assets/images/dashboard.png') }}" class="img-fluid rounded shadow" alt="Dashboard {{ config('app.name') }}">
                    <div class="bg-white rounded shadow-sm p-3 position-relative mt-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md-sm bg-soft-success text-success rounded-circle text-center">
                                <i class="uil uil-clock fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Jam layanan</h6>
                                <p class="text-muted mb-0 small">8 jam kerja, 5 hari kerja.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-4 border-bottom">
    <div class="container">
        <div class="row justify-content-center g-3 text-center">
            <div class="col-lg-4 col-md-6">
                <span class="fw-semibold text-muted">Mahkamah Agung</span>
            </div>
            <div class="col-lg-4 col-md-6">
                <span class="fw-semibold text-muted">Direktorat Jenderal Badan Peradilan Umum</span>
            </div>
            <div class="col-lg-4 col-md-6">
                <span class="fw-semibold text-muted">{{ $infoApp->pengadilan_tinggi ?? 'Pengadilan Tinggi' }}</span>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="section-title mb-4 pb-2 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <h4 class="title mb-3">Layanan yang Lebih Ringkas</h4>
                    <p class="text-muted para-desc mx-auto mb-0">
                        {{ config('app.name') }} membantu proses pendaftaran, pembayaran, verifikasi, dan pencetakan bukti surat kuasa dalam satu
                        layanan digital.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($serviceFeatures as $feature)
            <div class="col-lg-4 col-md-6 wow animate__animated animate__fadeInUp" data-wow-delay=".{{ $loop->iteration + 1 }}s">
                <div class="features feature-success p-4 bg-white rounded shadow-sm h-100">
                    <div class="icon text-center rounded-pill mb-3">
                        <i class="{{ $feature['icon'] }} fs-4 mb-0 text-success"></i>
                    </div>
                    <h5 class="mb-2">{{ $feature['title'] }}</h5>
                    <p class="text-muted mb-0">{{ $feature['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="section-title wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <span class="badge rounded-pill bg-soft-success text-success mb-3 px-3 py-2">Alur Layanan</span>
                    <h4 class="title mb-3">Satu Pintu, Satu Klik, Urusan Kuasa Jadi Praktis.</h4>
                    <p class="text-muted mb-4">
                        Pendaftaran surat kuasa tidak lagi bergantung pada antrean fisik. Pengguna dapat menyiapkan data, mengunggah dokumen, membayar
                        PNBP, dan memantau verifikasi dari aplikasi.
                    </p>
                    <div class="alert alert-light border mb-0">
                        <div class="d-flex">
                            <i class="uil uil-info-circle text-success fs-4 me-2"></i>
                            <p class="text-muted mb-0">
                                Biaya pendaftaran per surat kuasa sebesar
                                <span class="text-success fw-bold">Rp10.000</span>
                                sesuai PP Nomor 5 Tahun 2019.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="row g-3">
                    @foreach ($serviceSteps as $step)
                    <div class="col-md-6 wow animate__animated animate__fadeInUp" data-wow-delay=".{{ $loop->iteration + 1 }}s">
                        <div class="card border-0 shadow-sm rounded h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span
                                        class="badge rounded-pill bg-success me-2">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h6 class="mb-0">{{ $step['title'] }}</h6>
                                </div>
                                <p class="text-muted mb-0">{{ $step['description'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                <img src="{{ asset('assets/images/model.jpg') }}" class="img-fluid rounded shadow" alt="Pelayanan {{ config('app.name') }}">
            </div>
            <div class="col-lg-5 wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                <div class="section-title">
                    <span class="badge rounded-pill bg-soft-success text-success mb-3 px-3 py-2">Informasi Layanan</span>
                    <h4 class="title mb-3">Terhubung dengan PTSP {{ config('app.author') }}</h4>
                    <p class="text-muted">
                        Layanan ini dirancang untuk memudahkan advokat maupun non-advokat dalam mendaftarkan surat kuasa. Bukti pendaftaran dapat
                        dicetak setelah data dan pembayaran diverifikasi.
                    </p>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="uil uil-check-circle text-success h5 me-2 align-middle"></i>
                            Pendaftaran surat kuasa secara elektronik
                        </li>
                        <li class="mb-2">
                            <i class="uil uil-check-circle text-success h5 me-2 align-middle"></i>
                            Pembayaran melalui QRIS atau transfer bank
                        </li>
                        <li>
                            <i class="uil uil-check-circle text-success h5 me-2 align-middle"></i>
                            Cetak bukti barcode pendaftaran
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="section-title mb-4 pb-2 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <h4 class="title mb-3">Pejabat Pimpinan</h4>
                    <p class="text-muted para-desc mx-auto mb-0">
                        Profil pejabat pimpinan pada
                        <span class="fw-bold text-success">{{ config('app.author') }}</span>.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($leaders as $leader)
            @php
            $leaderPhoto = filled($leader['photo']) ? asset('storage/' . $leader['photo']) : asset('assets/images/user/user-none.png');
            @endphp
            <div class="col-lg-3 col-md-6 wow animate__animated animate__fadeInUp" data-wow-delay=".{{ $loop->iteration + 1 }}s">
                <div class="card team team-success text-center border-0 shadow-sm rounded h-100">
                    <div class="card-body p-4">
                        <img src="{{ $leaderPhoto }}" class="avatar avatar-ex-large rounded-circle shadow mb-3" alt="{{ $leader['alt'] }}">
                        <h5 class="mb-1">
                            <span class="name text-dark">{{ $leader['name'] ?: 'Belum tersedia' }}</span>
                        </h5>
                        <small class="designation text-muted">{{ $leader['role'] }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@if (!empty($testimoni) && count($testimoni) > 0)
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="section-title mb-4 pb-2 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <h4 class="title mb-3">Testimoni Pengguna</h4>
                    <p class="text-muted para-desc mx-auto mb-0">
                        Pengalaman pengguna yang telah mendaftarkan surat kuasa melalui
                        <span class="text-success fw-bold">{{ config('app.name') }}</span>.
                    </p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12 mt-4">
                <div class="tiny-three-item">
                    @foreach ($testimoni as $item)
                    @php
                    $photoPath = data_get($item, 'user.profile.foto');
                    $googleAvatar = data_get($item, 'user.avatar');
                    $imageSrc = $photoPath ? asset('storage/' . $photoPath) : ($googleAvatar ?: asset('assets/images/client/01.jpg'));
                    $userName = data_get($item, 'user.name', 'Pengguna');
                    @endphp
                    <div class="tiny-slide wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                        <div class="d-flex client-testi m-1">
                            <img src="{{ $imageSrc }}" class="avatar avatar-small client-image rounded shadow"
                                alt="Foto testimoni dari {{ $userName }}">
                            <div class="card flex-1 content p-3 shadow-sm rounded position-relative border-0">
                                <ul class="list-unstyled mb-0">
                                    @for ($i = 0; $i < min((int) $item->rating, 5); $i++)
                                        <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i></li>
                                        @endfor
                                </ul>
                                <p class="text-muted mt-2 mb-2">"{{ $item->testimoni }}"</p>
                                <h6 class="text-success mb-0">- {{ $userName }}</h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="section-title wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <span class="badge rounded-pill bg-success mb-3 px-3 py-2">Layanan 8 Jam Kerja / 5 Hari Kerja</span>
                    <h4 class="title mb-3">Kritik, Saran, dan Pengaduan</h4>
                    <p class="text-muted para-desc mx-auto">
                        Sampaikan masukan atau pengaduan melalui kanal email resmi berikut.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                        @foreach ($feedbackEmails as $email)
                        <a href="mailto:{{ $email }}" class="btn btn-pills btn-soft-success">
                            {{ $email }} <i class="uil uil-envelope"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection