@extends('admin.layout.body')
@section('title', $title)
@section('content')
    <link href="{{ asset('admin/assets/css/detail-surat-kuasa.css') }}" rel="stylesheet" type="text/css" />

    <!-- Start Page Content -->
    <main class="page-content bg-light">

        @include('admin.component.top-header')

        <div class="container-fluid mb-4">
            <div class="layout-specing">

                @include('admin.component.breadcumb')

                <div class="mt-4">
                    <div class="card card-premium overflow-hidden">
                        <!-- Header Area -->
                        <div class="bg-white px-4 py-4 px-md-5 py-md-4 border-bottom">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <span class="badge bg-soft-primary text-primary mb-2 rounded-pill px-3 py-1 fw-semibold ls-1">ID: {{ $suratKuasa->id_daftar }}</span>
                                    <h4 class="mb-1 fw-bold text-dark">Surat Kuasa: {{ $suratKuasa->pemohon }}</h4>
                                    <p class="text-muted mb-0 fs-6">
                                        <i class="uil uil-clock me-1"></i> Terdaftar pada {{ \Carbon\Carbon::parse($suratKuasa->tanggal_daftar)->format('d M Y') }}
                                        {{ $suratKuasa->migrated_from_id ? '' : '(' . \Carbon\Carbon::parse($suratKuasa->created_at)->diffForHumans() . ')' }}
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center border" href="{{ route('surat-kuasa.index') }}">
                                        <i class="uil uil-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 p-md-5 bg-white">
                            <!-- Minimalist Pills -->
                            <div class="d-flex justify-content-center justify-content-md-start mb-5">
                                <ul class="nav nav-pills nav-custom-pills" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $suratKuasa->pembayaran ? 'active' : '' }}" id="pills-pendaftaran-tab" data-bs-toggle="pill" data-bs-target="#pills-pendaftaran"
                                            type="button" role="tab" aria-controls="pills-pendaftaran" aria-selected="true">
                                            <i class="uil uil-file-alt me-1"></i> Pendaftaran
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $suratKuasa->pembayaran ? '' : 'active' }}" id="pills-pembayaran-tab" data-bs-toggle="pill" data-bs-target="#pills-pembayaran"
                                            type="button" role="tab" aria-controls="pills-pembayaran" aria-selected="false">
                                            <i class="uil uil-bill me-1"></i> Pembayaran
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content" id="pills-tabContent">
                                <!-- Tab Pendaftaran -->
                                <div class="tab-pane fade {{ $suratKuasa->pembayaran ? 'show active' : '' }}" id="pills-pendaftaran" role="tabpanel" aria-labelledby="pills-pendaftaran-tab" tabindex="0">

                                    @if ($suratKuasa->status != \App\Enum\StatusSuratKuasaEnum::Disetujui->value)
                                        <div class="mb-4">
                                            @include('admin.surat-kuasa.component.alert-info-surat-kuasa')
                                        </div>
                                    @endif

                                    <div class="row g-5">
                                        <!-- Kolom Informasi Detail -->
                                        <div class="col-lg-7">

                                            <!-- Block: Info Pendaftar -->
                                            <h5 class="section-title"><i class="uil uil-user-check"></i> Informasi Pemohon</h5>
                                            <div class="row g-3 mb-5">
                                                <div class="col-sm-6">
                                                    <span class="info-label">Nama Pemohon</span>
                                                    <span class="info-value">{{ $suratKuasa->pemohon }}</span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span class="info-label">Kategori</span>
                                                    <span class="info-value">{{ $suratKuasa->klasifikasi }}</span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span class="info-label">Email</span>
                                                    <span class="info-value d-flex align-items-center">
                                                        {{ $suratKuasa->user->email }}
                                                        <a href="mailto:{{ $suratKuasa->user->email }}" class="btn btn-sm btn-soft-primary ms-2 py-0 px-2 rounded text-decoration-none"
                                                            data-bs-toggle="tooltip" title="Kirim Email">
                                                            <i class="uil uil-envelope-alt fs-6"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span class="info-label">No. Whatsapp</span>
                                                    <span class="info-value d-flex align-items-center">
                                                        @php
                                                            $kontak = $suratKuasa->user->profile->kontak ?? '-';
                                                            $waNumber = $kontak !== '-' ? $kontak : null;
                                                            if ($waNumber && substr($waNumber, 0, 1) === '0') {
                                                                $waNumber = '62' . substr($waNumber, 1);
                                                            }
                                                        @endphp
                                                        {{ $kontak }}
                                                        @if ($waNumber)
                                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}" target="_blank"
                                                                class="btn btn-sm btn-soft-success ms-2 py-0 px-2 rounded text-decoration-none" data-bs-toggle="tooltip" title="Chat via WhatsApp">
                                                                <i class="uil uil-whatsapp fs-6"></i>
                                                            </a>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Block: Info Surat -->
                                            <h5 class="section-title"><i class="uil uil-envelope-alt"></i> Detail Surat</h5>
                                            <div class="p-4 bg-soft-light rounded-3 border mb-4">
                                                @if ($suratKuasa->register)
                                                    <div class="mb-3">
                                                        <span class="info-label">Nomor Surat Kuasa</span>
                                                        <span class="info-value text-primary fs-5">{{ $suratKuasa->register->nomor_surat_kuasa }}</span>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <span class="info-label">Perihal</span>
                                                    <span class="info-value">{{ $suratKuasa->perihal }}</span>
                                                </div>
                                                <div>
                                                    <span class="info-label">Jenis Surat</span>
                                                    <span class="info-value">{{ $suratKuasa->jenis_surat }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Kolom Status & Dokumen -->
                                        <div class="col-lg-5">
                                            <!-- Block: Status Verifikasi -->
                                            <h5 class="section-title"><i class="uil uil-shield-check"></i> Status Verifikasi</h5>
                                            <div class="card bg-soft-light border-0 shadow-none mb-4">
                                                <div class="card-body p-4">
                                                    @php
                                                        $badgeClass = '';
                                                        switch ($suratKuasa->tahapan) {
                                                            case \App\Enum\TahapanSuratKuasaEnum::Pendaftaran->value:
                                                                $badgeClass = 'bg-soft-primary text-primary';
                                                                break;
                                                            case \App\Enum\TahapanSuratKuasaEnum::Verifikasi->value:
                                                                $badgeClass = 'bg-soft-success text-success';
                                                                break;
                                                            case \App\Enum\TahapanSuratKuasaEnum::PerbaikanData->value:
                                                                $badgeClass = 'bg-soft-warning text-warning';
                                                                break;
                                                            case \App\Enum\TahapanSuratKuasaEnum::PengajuanPerbaikanData->value:
                                                                $badgeClass = 'bg-soft-info text-info';
                                                                break;
                                                            case \App\Enum\TahapanSuratKuasaEnum::PerbaikanPembayaran->value:
                                                                $badgeClass = 'bg-soft-danger text-danger';
                                                                break;
                                                            case \App\Enum\TahapanSuratKuasaEnum::PengajuanPerbaikanPembayaran->value:
                                                                $badgeClass = 'bg-soft-info text-info';
                                                                break;
                                                            default:
                                                                $badgeClass = 'bg-soft-secondary text-secondary';
                                                        }
                                                    @endphp

                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="icon-box {{ str_replace('text', 'bg-soft', $badgeClass) }} me-3">
                                                            <i class="uil uil-layer-group fs-4 {{ explode(' ', $badgeClass)[1] }}"></i>
                                                        </div>
                                                        <div>
                                                            <span class="info-label mb-1">Tahapan Saat Ini</span>
                                                            <span class="badge {{ $badgeClass }} fs-6 rounded-pill px-3">{{ $suratKuasa->tahapan }}</span>
                                                        </div>
                                                    </div>

                                                    @if ($suratKuasa->status == \App\Enum\StatusSuratKuasaEnum::Ditolak->value)
                                                        <div class="mb-3 d-flex align-items-center">
                                                            <div class="icon-box bg-soft-danger me-3">
                                                                <i class="uil uil-times-circle fs-4 text-danger"></i>
                                                            </div>
                                                            <div>
                                                                <span class="info-label mb-1">Status Final</span>
                                                                <span class="badge bg-danger rounded-pill px-3">Ditolak</span>
                                                            </div>
                                                        </div>
                                                    @elseif ($suratKuasa->status == \App\Enum\StatusSuratKuasaEnum::Disetujui->value)
                                                        <div class="mb-3 d-flex align-items-center">
                                                            <div class="icon-box bg-soft-success me-3">
                                                                <i class="uil uil-check-circle fs-4 text-success"></i>
                                                            </div>
                                                            <div>
                                                                <span class="info-label mb-1">Status Final</span>
                                                                <span class="badge bg-success rounded-pill px-3">Disetujui</span>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($suratKuasa->register && $suratKuasa->register->approval)
                                                        <hr class="border-dashed my-3">
                                                        <div class="mb-2">
                                                            <span class="info-label">Diperiksa Oleh</span>
                                                            <span class="info-value fs-6 d-flex align-items-center"><i class="uil uil-user-circle me-1 text-primary"></i>
                                                                {{ $suratKuasa->register->approval->name }}</span>
                                                        </div>
                                                    @endif

                                                    @if ($suratKuasa->register && $suratKuasa->register->panitera)
                                                        <div class="mt-3">
                                                            <span class="info-label">Disetujui Oleh</span>
                                                            <span class="info-value fs-6 text-primary">{{ $suratKuasa->register->panitera->nama }}</span>
                                                            <small class="text-muted">Panitera {{ $infoApp->pengadilan_negeri }}</small>
                                                        </div>
                                                    @endif

                                                    @if (
                                                        $suratKuasa->status == \App\Enum\StatusSuratKuasaEnum::Ditolak->value ||
                                                            in_array($suratKuasa->tahapan, [
                                                                \App\Enum\TahapanSuratKuasaEnum::PerbaikanData->value,
                                                                \App\Enum\TahapanSuratKuasaEnum::PerbaikanPembayaran->value,
                                                                \App\Enum\TahapanSuratKuasaEnum::PengajuanPerbaikanData->value,
                                                                \App\Enum\TahapanSuratKuasaEnum::PengajuanPerbaikanPembayaran->value,
                                                            ]))
                                                        <hr class="border-dashed my-3">
                                                        <div class="p-3 bg-soft-danger rounded">
                                                            <span class="info-label text-danger">Catatan / Alasan</span>
                                                            <span class="info-value text-danger fs-6">{{ $suratKuasa->keterangan }}</span>
                                                        </div>
                                                    @elseif ($suratKuasa->tahapan == \App\Enum\TahapanSuratKuasaEnum::Pembayaran->value)
                                                        <hr class="border-dashed my-3">
                                                        <div class="p-3 bg-soft-info rounded">
                                                            <span class="info-label text-info">Keterangan</span>
                                                            <span class="info-value text-info fs-6">
                                                                Pendaftaran sedang divalidasi petugas <b>(Senin-Jum'at 08:00 - 16:30 WIB)</b>.
                                                                Cek notifikasi aplikasi / email berkala.
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Block: Widget Barcode -->
                                            @if ($suratKuasa->status == \App\Enum\StatusSuratKuasaEnum::Disetujui->value && $suratKuasa->register->uuid != null)
                                                <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden position-relative"
                                                    style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                                                    <div class="position-absolute end-0 bottom-0 opacity-10" style="transform: translate(10%, 20%);">
                                                        <i class="uil uil-qrcode-scan" style="font-size: 8rem;"></i>
                                                    </div>
                                                    <div class="card-body p-4 position-relative z-index-1">
                                                        <div class="d-flex align-items-start mb-4">
                                                            <div class="bg-white text-success rounded-3 p-2 me-3 shadow-sm flex-shrink-0 d-flex align-items-center justify-content-center"
                                                                style="width: 48px; height: 48px;">
                                                                <i class="uil uil-qrcode-scan fs-3"></i>
                                                            </div>
                                                            <div>
                                                                <h5 class="fw-bold mb-1 text-white">Dokumen Tervalidasi</h5>
                                                                <p class="mb-0 text-white opacity-75 fs-6" style="line-height: 1.4;">
                                                                    Surat kuasa ini sah dan dilengkapi e-barcode sebagai bukti legalitas.
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('surat-kuasa.barcode', ['id' => Crypt::encrypt($suratKuasa->id)]) }}" target="_blank"
                                                            class="btn btn-light text-success fw-bold w-100 rounded-pill shadow-sm d-flex justify-content-center align-items-center mb-1 transition-all hover-scale">
                                                            <i class="uil uil-import me-2 fs-5"></i> Unduh Barcode
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <!-- Dokumen Pendukung -->
                                    <div class="mt-4 pt-4 border-top">
                                        <h5 class="section-title"><i class="uil uil-folder-open"></i> Dokumen Pendukung</h5>
                                        <div class="row g-3 flex-nowrap flex-md-wrap overflow-x-auto pb-3" style="scrollbar-width: thin;-webkit-overflow-scrolling: touch;">
                                            <!-- KTP -->
                                            <div class="col-10 col-sm-6 col-md-3">
                                                <a target="_blank"
                                                    href="{{ route('surat-kuasa.preview-file', ['id' => Crypt::encrypt($suratKuasa->id), 'jenis_dokumen' => \App\Enum\JenisDokumenEnum::KTP->name]) }}"
                                                    class="text-decoration-none">
                                                    <div class="doc-card d-flex flex-column align-items-center justify-content-center text-center">
                                                        <div class="icon-box bg-soft-primary mb-3">
                                                            <i class="uil uil-file-bookmark-alt fs-3 text-primary"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark">KTP</h6>
                                                        <span class="badge bg-light text-muted border px-2 py-1">Lihat Dokumen</span>
                                                    </div>
                                                </a>
                                            </div>

                                            <!-- KTA/KTPP -->
                                            @php
                                                $isAdvokat = $suratKuasa->klasifikasi == \App\Enum\SuratKuasaEnum::Advokat->value;
                                                $docLabel = $isAdvokat ? 'KTA' : 'KTPP';
                                                $docEnumName = $isAdvokat ? \App\Enum\JenisDokumenEnum::KTA->name : \App\Enum\JenisDokumenEnum::KTTP->name;
                                            @endphp
                                            <div class="col-10 col-sm-6 col-md-3">
                                                <a target="_blank" href="{{ route('surat-kuasa.preview-file', ['id' => Crypt::encrypt($suratKuasa->id), 'jenis_dokumen' => $docEnumName]) }}"
                                                    class="text-decoration-none">
                                                    <div class="doc-card d-flex flex-column align-items-center justify-content-center text-center">
                                                        <div class="icon-box bg-soft-primary mb-3">
                                                            <i class="uil uil-postcard fs-3 text-primary"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark">{{ $docLabel }}</h6>
                                                        <span class="badge bg-light text-muted border px-2 py-1">Lihat Dokumen</span>
                                                    </div>
                                                </a>
                                            </div>

                                            <!-- BAS / ST -->
                                            @if ($isAdvokat)
                                                <div class="col-10 col-sm-6 col-md-3">
                                                    <a target="_blank"
                                                        href="{{ route('surat-kuasa.preview-file', ['id' => Crypt::encrypt($suratKuasa->id), 'jenis_dokumen' => \App\Enum\JenisDokumenEnum::BAS->name]) }}"
                                                        class="text-decoration-none">
                                                        <div class="doc-card d-flex flex-column align-items-center justify-content-center text-center">
                                                            <div class="icon-box bg-soft-primary mb-3">
                                                                <i class="uil uil-file-contract fs-3 text-primary"></i>
                                                            </div>
                                                            <h6 class="mb-1 text-dark">Berita Acara Sumpah</h6>
                                                            <span class="badge bg-light text-muted border px-2 py-1">Lihat Dokumen</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="col-10 col-sm-6 col-md-3">
                                                    <a target="_blank"
                                                        href="{{ route('surat-kuasa.preview-file', ['id' => Crypt::encrypt($suratKuasa->id), 'jenis_dokumen' => \App\Enum\JenisDokumenEnum::ST->name]) }}"
                                                        class="text-decoration-none">
                                                        <div class="doc-card d-flex flex-column align-items-center justify-content-center text-center">
                                                            <div class="icon-box bg-soft-primary mb-3">
                                                                <i class="uil uil-file-contract fs-3 text-primary"></i>
                                                            </div>
                                                            <h6 class="mb-1 text-dark">Surat Tugas</h6>
                                                            <span class="badge bg-light text-muted border px-2 py-1">Lihat Dokumen</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endif

                                            <!-- Surat Kuasa -->
                                            <div class="col-10 col-sm-6 col-md-3">
                                                <a target="_blank"
                                                    href="{{ route('surat-kuasa.preview-file', ['id' => Crypt::encrypt($suratKuasa->id), 'jenis_dokumen' => \App\Enum\JenisDokumenEnum::SK->name]) }}"
                                                    class="text-decoration-none">
                                                    <div class="doc-card d-flex flex-column align-items-center justify-content-center text-center">
                                                        <div class="icon-box bg-soft-primary mb-3">
                                                            <i class="uil uil-file-shield-alt fs-3 text-primary"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark">Surat Kuasa</h6>
                                                        <span class="badge bg-light text-muted border px-2 py-1">Lihat Dokumen</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pihak Terlibat -->
                                    <div class="mt-5 pt-4 border-top">
                                        <h5 class="section-title"><i class="uil uil-users-alt"></i> Pihak Surat Kuasa</h5>
                                        <div class="table-responsive rounded-3 border">
                                            <table class="table table-custom mb-0">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th width="15%">Role</th>
                                                        <th width="25%">Nama</th>
                                                        <th width="20%">NIK</th>
                                                        <th width="15%">Pekerjaan</th>
                                                        <th width="20%">Alamat</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($suratKuasa->pihak && count($suratKuasa->pihak) > 0)
                                                        @foreach ($suratKuasa->pihak as $key => $pihak)
                                                            <tr>
                                                                <td class="text-muted">{{ $key + 1 }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge rounded-pill {{ $pihak->jenis == 'Pemberi' ? 'bg-soft-primary text-primary border-primary' : 'bg-soft-warning text-warning border-warning' }} border px-2 py-1">
                                                                        {{ $pihak->jenis }} Kuasa
                                                                    </span>
                                                                </td>
                                                                <td class="fw-bold text-dark">{{ $pihak->nama }}</td>
                                                                <td><code class="text-secondary bg-light px-2 py-1 rounded">{{ $pihak->nik }}</code></td>
                                                                <td>{{ $pihak->pekerjaan }}</td>
                                                                <td><span class="text-truncate d-block" style="max-width: 200px;" data-bs-toggle="tooltip"
                                                                        title="{{ $pihak->alamat }}">{{ Str::limit($pihak->alamat, 25) }}</span></td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="6" class="text-center py-4 text-muted">Belum ada pihak yang ditambahkan.</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- Tab Pembayaran -->
                                <div class="tab-pane fade {{ $suratKuasa->pembayaran ? '' : 'show active' }}" id="pills-pembayaran" role="tabpanel" aria-labelledby="pills-pembayaran-tab"
                                    tabindex="0">
                                    @if ($suratKuasa->pembayaran)

                                        @if ($suratKuasa->status != \App\Enum\StatusSuratKuasaEnum::Disetujui->value)
                                            <div class="mb-4">
                                                @include('admin.surat-kuasa.component.alert-info-pembayaran')
                                            </div>
                                        @endif

                                        <div class="row justify-content-center">
                                            <div class="col-lg-8">
                                                <div class="card bg-soft-success border-0 shadow-none mb-4">
                                                    <div class="card-body p-4 d-flex align-items-center">
                                                        <div class="icon-box bg-white shadow-sm me-4" style="width:64px; height:64px;">
                                                            <i class="uil uil-check-circle fs-2 text-success"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-success mb-1 fw-bold">Pembayaran Dikonfirmasi</h4>
                                                            <p class="text-success mb-0 opacity-75">Pembayaran telah berhasil dilakukan dan dicatat dalam sistem.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card border border-light shadow-sm mb-4">
                                                    <div class="card-body p-4">
                                                        <h5 class="section-title mb-4"><i class="uil uil-receipt"></i> Detail Transaksi</h5>
                                                        <div class="row g-4">
                                                            <div class="col-sm-6">
                                                                <span class="info-label">Jenis Pembayaran</span>
                                                                <span class="info-value fs-5">{{ $suratKuasa->pembayaran->jenis_pembayaran }}</span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <span class="info-label">Tanggal Terbayar</span>
                                                                <span class="info-value">{{ Carbon\Carbon::parse($suratKuasa->pembayaran->created_at)->isoFormat('dddd, D MMMM Y') }}</span>
                                                            </div>
                                                            <div class="col-12">
                                                                <span class="info-label">Timestamp Riwayat</span>
                                                                <span class="info-value fs-6 text-muted">{{ $suratKuasa->pembayaran->created_at->format('d/m/Y H:i:s') }} &bull; Diperbarui
                                                                    {{ $suratKuasa->pembayaran->updated_at->format('d/m/Y H:i:s') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-grid">
                                                    <a target="_blank" href="{{ route('surat-kuasa.pembayaran-preview', ['id' => Crypt::encrypt($suratKuasa->id)]) }}"
                                                        class="btn btn-primary d-flex align-items-center justify-content-center py-2 shadow-sm rounded-pill">
                                                        <i class="uil uil-file-search-alt me-2"></i> Lihat Bukti Pembayaran
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="icon-box bg-soft-warning mx-auto mb-4 border border-warning" style="width: 80px; height: 80px; border-radius: 50%;">
                                                <i class="uil uil-wallet fs-1 text-warning"></i>
                                            </div>
                                            <h4 class="fw-bold text-dark mb-3">Menunggu Pembayaran</h4>
                                            <p class="text-muted mx-auto mb-5 fs-6" style="max-width: 450px;">
                                                Pendaftaran Surat Kuasa kamu belum dibayar, silahkan bayar sekarang dengan mengklik tombol pembayaran dibawah ini untuk melanjutkan proses verifikasi.
                                            </p>
                                            <a href="{{ route('surat-kuasa.pembayaran', ['id' => Crypt::encrypt($suratKuasa->id)]) }}"
                                                class="btn btn-warning text-dark fw-bold rounded-pill px-5 py-2 shadow-sm d-inline-flex align-items-center transition-all hover-scale">
                                                Lakukan Pembayaran <i class="uil uil-arrow-right ms-2 fs-5"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div> <!-- end card-body -->

                        <!-- Footer Actions Block -->
                        <div class="bg-light px-4 py-3 px-md-5 py-md-4 border-top">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <a href="{{ route('surat-kuasa.index') }}" class="btn btn-danger btn-sm rounded-pill shadow-sm">
                                        <i class="uil uil-arrow-left me-1"></i> Kembali ke Daftar
                                    </a>
                                </div>

                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    {{-- Tombol Aksi untuk User --}}
                                    @if (Auth::user()->role == \App\Enum\RoleEnum::User->value)
                                        @if ($suratKuasa->tahapan == \App\Enum\TahapanSuratKuasaEnum::PerbaikanData->value)
                                            <a href="{{ route('surat-kuasa.form', ['param' => 'edit', 'klasifikasi' => $suratKuasa->klasifikasi, 'id' => Crypt::encrypt($suratKuasa->id)]) }}"
                                                class="btn btn-warning btn-sm rounded-pill px-4 shadow-sm">
                                                <i class="uil uil-edit me-1"></i> Perbaiki Data
                                            </a>
                                        @endif
                                        @if ($suratKuasa->tahapan == \App\Enum\TahapanSuratKuasaEnum::PerbaikanPembayaran->value)
                                            <a href="{{ route('surat-kuasa.pembayaran', ['id' => Crypt::encrypt($suratKuasa->id)]) }}" class="btn btn-warning btn-sm rounded-pill px-4 shadow-sm">
                                                <i class="uil uil-file-upload-alt me-1"></i> Perbaiki Pembayaran
                                            </a>
                                        @endif
                                    @endif

                                    {{-- Tombol Aksi untuk Admin/Superadmin --}}
                                    @if (Auth::user()->role != \App\Enum\RoleEnum::User->value &&
                                            in_array($suratKuasa->tahapan, [
                                                \App\Enum\TahapanSuratKuasaEnum::Pembayaran->value,
                                                \App\Enum\TahapanSuratKuasaEnum::PerbaikanData->value,
                                                \App\Enum\TahapanSuratKuasaEnum::PengajuanPerbaikanData->value,
                                                \App\Enum\TahapanSuratKuasaEnum::PerbaikanPembayaran->value,
                                                \App\Enum\TahapanSuratKuasaEnum::PengajuanPerbaikanPembayaran->value,
                                            ]))
                                        <div class="d-flex align-items-center gap-2 bg-white p-2 rounded-pill shadow-sm border">
                                            <span class="ps-2 pe-3 text-muted fw-semibold fs-6 border-end d-none d-sm-inline-block"><i class="uil uil-check-shield me-1"></i> Verifikasi:</span>
                                            <button class="btn btn-soft-danger btn-sm rounded-pill px-4 fw-medium" data-bs-toggle="modal" data-bs-target="#tolak-surat-kuasa">
                                                <i class="uil uil-times-circle me-1"></i> Tolak
                                            </button>
                                            <button class="btn btn-success btn-sm rounded-pill px-4 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#setujui-surat-kuasa">
                                                <i class="uil uil-check-circle me-1"></i> Setujui
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end container-->

        @if (Auth::user()->role != \App\Enum\RoleEnum::User->value &&
                in_array($suratKuasa->tahapan, [
                    \App\Enum\TahapanSuratKuasaEnum::Pembayaran->value,
                    \App\Enum\TahapanSuratKuasaEnum::PerbaikanData->value,
                    \App\Enum\TahapanSuratKuasaEnum::PengajuanPerbaikanData->value,
                    \App\Enum\TahapanSuratKuasaEnum::PerbaikanPembayaran->value,
                    \App\Enum\TahapanSuratKuasaEnum::PengajuanPerbaikanPembayaran->value,
                ]))
            @include('admin.surat-kuasa.component.modal-verifikasi')
        @endif

        @include('admin.layout.content-footer')
        <!-- End -->
    </main>
    <!--End page-content" -->
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Approve Modal Logic ---
            const manualSwitch = document.getElementById('manualNomorSwitch');
            const nomorInput = document.getElementById('nomor_surat_kuasa');
            const formApprove = document.getElementById('form-approve');

            if (manualSwitch) {
                manualSwitch.addEventListener('change', function() {
                    if (this.checked) {
                        nomorInput.readOnly = false;
                    } else {
                        nomorInput.readOnly = true;
                        nomorInput.value = "{{ $nomorSuratKuasaBaru }}"; // Reset to auto value
                    }
                });
            }

            if (formApprove) {
                formApprove.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleFormSubmission(this, "{{ route('surat-kuasa.verifikasi.approve') }}",
                        'btn-approve');
                });
            }

            // --- Reject Modal Logic ---
            const formReject = document.getElementById('form-reject');
            if (formReject) {
                formReject.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleFormSubmission(this, "{{ route('surat-kuasa.verifikasi.reject') }}",
                        'btn-reject');
                });
            }

            // --- General Form Submission Handler ---
            async function handleFormSubmission(form, url, buttonId) {
                const button = document.getElementById(buttonId);
                const originalButtonHtml = button.innerHTML;
                button.disabled = true;
                button.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;

                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                const formData = new FormData(form);

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            // Validation errors
                            Object.keys(result.errors).forEach(key => {
                                const errorEl = document.getElementById(`${key}-error`);
                                const inputEl = document.getElementById(key);
                                if (errorEl) errorEl.textContent = result.errors[key][0];
                                if (inputEl) inputEl.classList.add('is-invalid');
                            });
                            Swal.fire('Validasi Gagal', result.message, 'error');
                        } else {
                            // Other server errors (409, 500, etc)
                            Swal.fire('Terjadi Kesalahan', result.message, 'error');
                        }
                        throw new Error('Server error');
                    }

                    // Success
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message,
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    }).then(() => {
                        window.location.reload();
                    });

                } catch (error) {
                    console.error('Form submission error:', error);
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalButtonHtml;
                }
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endpush
