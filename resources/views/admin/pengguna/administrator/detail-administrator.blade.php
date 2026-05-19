@extends('admin.layout.body')
@section('title', $title)
@section('content')
    <!-- Start Page Content -->
    <main class="page-content bg-light">

        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">

                @include('admin.component.breadcumb')

                <div class="row mt-4">
                    <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-bold">{{ $detailTitle }}</h5>
                        <a href="{{ route('administrator.index') }}" class="btn btn-sm text-white btn-danger px-3 shadow-sm rounded-pill">
                            <i class="uil uil-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <!-- Profile Overview -->
                    <div class="col-xl-4 col-lg-5 col-md-12 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 text-center h-100 overflow-hidden">
                            <div class="bg-soft-primary" style="height: 120px;"></div>
                            <div class="card-body p-4 pt-0">
                                <div class="position-relative mt-n5 mb-3">
                                    @if (isset($user) && $user->profile->foto)
                                        <img src="{{ asset('storage/' . $user->profile->foto) }}" class="rounded-circle shadow-sm bg-white"
                                            style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #fff;" alt="">
                                    @else
                                        <img src="{{ asset('assets/images/user/user-none.png') }}" class="rounded-circle shadow-sm bg-white"
                                            style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #fff;" alt="">
                                    @endif
                                </div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $user->name }}</h5>
                                <p class="text-muted small mb-3">
                                    <i class="uil uil-envelope me-1"></i> {{ $user->email }}
                                </p>
                                <div>
                                    <span
                                        class="badge rounded-pill bg-{{ $user->block ? 'soft-danger' : 'soft-success' }} text-{{ $user->block ? 'danger' : 'success' }} px-3 py-2 fw-medium border border-{{ $user->block ? 'danger' : 'success' }}-subtle">
                                        <i class="uil {{ $user->block ? 'uil-ban' : 'uil-check-circle' }} me-1"></i> {{ $user->block ? 'Akun Diblokir' : 'Akun Aktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="col-xl-8 col-lg-7 col-md-12 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-transparent border-bottom p-4">
                                <h6 class="mb-0 fw-bold"><i class="uil uil-user me-2 text-primary"></i> Detail Informasi</h6>
                            </div>
                            <div class="card-body p-4 pt-3">
                                <div class="row py-2 border-bottom border-light align-items-center">
                                    <div class="col-sm-4 text-muted small fw-medium text-uppercase mb-2 mb-sm-0">Nama Lengkap</div>
                                    <div class="col-sm-8 text-dark fw-semibold">{{ $user->name }}</div>
                                </div>
                                <div class="row py-2 border-bottom border-light align-items-center mt-2">
                                    <div class="col-sm-4 text-muted small fw-medium text-uppercase mb-2 mb-sm-0">Tanggal Lahir</div>
                                    <div class="col-sm-8 text-dark">{{ $user->profile->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</div>
                                </div>
                                <div class="row py-2 border-bottom border-light align-items-center mt-2">
                                    <div class="col-sm-4 text-muted small fw-medium text-uppercase mb-2 mb-sm-0">Jenis Kelamin</div>
                                    <div class="col-sm-8 text-dark">{{ $user->profile->jenis_kelamin ?? '-' }}</div>
                                </div>
                                <div class="row py-2 border-bottom border-light align-items-center mt-2">
                                    <div class="col-sm-4 text-muted small fw-medium text-uppercase mb-2 mb-sm-0">Kontak / Telepon</div>
                                    <div class="col-sm-8 text-dark">{{ $user->profile->kontak ?? '-' }}</div>
                                </div>
                                <div class="row py-2 border-bottom border-light align-items-center mt-2">
                                    <div class="col-sm-4 text-muted small fw-medium text-uppercase mb-2 mb-sm-0">Alamat</div>
                                    <div class="col-sm-8 text-dark lh-base">{{ $user->profile->alamat ?? '-' }}</div>
                                </div>
                                <div class="row py-2 border-bottom border-light align-items-center mt-2">
                                    <div class="col-sm-4 text-muted small fw-medium text-uppercase mb-2 mb-sm-0">Role</div>
                                    <div class="col-sm-8 text-dark lh-base">{{ $user->role ?? '-' }}</div>
                                </div>
                                <div class="row mt-4 pt-2 bg-light rounded-3 p-3 mx-0">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded px-2 py-1 me-3 shadow-sm">
                                                <i class="uil uil-calendar-alt"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted mb-1">Dibuat Pada</div>
                                                <div class="small fw-semibold text-dark">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y H:i') : '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success text-white rounded px-2 py-1 me-3 shadow-sm">
                                                <i class="uil uil-clock"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted mb-1">Terakhir Diperbarui</div>
                                                <div class="small fw-semibold text-dark">{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->translatedFormat('d M Y H:i') : '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
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
    <!--End page-content" -->
@endsection
