@extends('admin.layout.body')
@section('title', $title)
@section('content')
    <!-- Start Page Content -->
    <main class="page-content bg-light">

        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">

                @include('admin.component.breadcumb')

                <div class="card shadow-sm border-0 mt-4 rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h5 class="card-title mb-4 text-dark fw-bold">
                            Profil Pejabat Struktural
                        </h5>

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-pills gap-2 border-bottom pb-3" id="pejabatTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill px-4 fw-medium" id="ketua-tab" data-bs-toggle="tab" data-bs-target="#ketua-pane" type="button" role="tab"
                                    aria-controls="ketua-pane" aria-selected="true">Ketua</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4 fw-medium" id="wakil-ketua-tab" data-bs-toggle="tab" data-bs-target="#wakil-ketua-pane" type="button" role="tab"
                                    aria-controls="wakil-ketua-pane" aria-selected="false">Wakil Ketua</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4 fw-medium" id="panitera-tab" data-bs-toggle="tab" data-bs-target="#panitera-pane" type="button" role="tab"
                                    aria-controls="panitera-pane" aria-selected="false">Panitera</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4 fw-medium" id="sekretaris-tab" data-bs-toggle="tab" data-bs-target="#sekretaris-pane" type="button" role="tab"
                                    aria-controls="sekretaris-pane" aria-selected="false">Sekretaris</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4 border-0">
                        <form id="pejabatForm" action="{{ route('pejabat-struktural.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="tab-content pt-3" id="pejabatTabContent">

                                {{-- Tab Ketua --}}
                                <div class="tab-pane fade show active" id="ketua-pane" role="tabpanel" aria-labelledby="ketua-tab" tabindex="0">
                                    <div class="row align-items-center">
                                        <div class="col-md-7 mb-4">
                                            <label for="ketua" class="form-label text-muted fw-semibold mb-2">Nama Lengkap Ketua <span class="text-danger">*</span></label>
                                            <input type="text" name="ketua" class="form-control form-control-lg bg-light border-0 @error('ketua') is-invalid @enderror" id="ketua"
                                                placeholder="Masukkan Nama Ketua" value="{{ old('ketua', $pejabat->ketua ?? '') }}">
                                            <div class="invalid-feedback" id="ketua-error"></div>
                                        </div>
                                        <div class="col-md-5 mb-4">
                                            <label for="foto_ketua" class="form-label text-muted fw-semibold mb-2">Foto Ketua
                                                @if (empty($pejabat->foto_ketua))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="file" name="foto_ketua" class="form-control form-control-lg bg-light border-0 @error('foto_ketua') is-invalid @enderror" id="foto_ketua"
                                                accept="image/png, image/jpeg, image/gif">
                                            <div class="invalid-feedback" id="foto_ketua-error"></div>
                                            @if (!empty($pejabat->foto_ketua))
                                                <div class="mt-3 d-flex align-items-center gap-3">
                                                    <img src="{{ asset('storage/' . $pejabat->foto_ketua) }}" class="rounded-3 shadow-sm" alt="Foto Ketua"
                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                    <span class="text-success small fw-medium"><i class="fas fa-check-circle me-1"></i> Foto Tersimpan</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Tab Wakil Ketua --}}
                                <div class="tab-pane fade" id="wakil-ketua-pane" role="tabpanel" aria-labelledby="wakil-ketua-tab" tabindex="0">
                                    <div class="row align-items-center">
                                        <div class="col-md-7 mb-4">
                                            <label for="wakil_ketua" class="form-label text-muted fw-semibold mb-2">Nama Lengkap Wakil Ketua <span class="text-danger">*</span></label>
                                            <input type="text" name="wakil_ketua" class="form-control form-control-lg bg-light border-0 @error('wakil_ketua') is-invalid @enderror" id="wakil_ketua"
                                                placeholder="Masukkan Nama Wakil Ketua" value="{{ old('wakil_ketua', $pejabat->wakil_ketua ?? '') }}">
                                            <div class="invalid-feedback" id="wakil_ketua-error"></div>
                                        </div>
                                        <div class="col-md-5 mb-4">
                                            <label for="foto_wakil_ketua" class="form-label text-muted fw-semibold mb-2">Foto Wakil Ketua
                                                @if (empty($pejabat->foto_wakil_ketua))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="file" name="foto_wakil_ketua" class="form-control form-control-lg bg-light border-0 @error('foto_wakil_ketua') is-invalid @enderror"
                                                id="foto_wakil_ketua" accept="image/png, image/jpeg, image/gif">
                                            <div class="invalid-feedback" id="foto_wakil_ketua-error"></div>
                                            @if (!empty($pejabat->foto_wakil_ketua))
                                                <div class="mt-3 d-flex align-items-center gap-3">
                                                    <img src="{{ asset('storage/' . $pejabat->foto_wakil_ketua) }}" class="rounded-3 shadow-sm" alt="Foto Wakil Ketua"
                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                    <span class="text-success small fw-medium"><i class="fas fa-check-circle me-1"></i> Foto Tersimpan</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Tab Panitera --}}
                                <div class="tab-pane fade" id="panitera-pane" role="tabpanel" aria-labelledby="panitera-tab" tabindex="0">
                                    <div class="row align-items-center">
                                        <div class="col-md-7 mb-4">
                                            <label for="panitera" class="form-label text-muted fw-semibold mb-2">Nama Lengkap Panitera <span class="text-danger">*</span></label>
                                            <input type="text" name="panitera" class="form-control form-control-lg bg-light border-0 @error('panitera') is-invalid @enderror" id="panitera"
                                                placeholder="Masukkan Nama Panitera" value="{{ old('panitera', $pejabat->panitera ?? '') }}">
                                            <div class="invalid-feedback" id="panitera-error"></div>
                                        </div>
                                        <div class="col-md-5 mb-4">
                                            <label for="foto_panitera" class="form-label text-muted fw-semibold mb-2">Foto Panitera
                                                @if (empty($pejabat->foto_panitera))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="file" name="foto_panitera" class="form-control form-control-lg bg-light border-0 @error('foto_panitera') is-invalid @enderror"
                                                id="foto_panitera" accept="image/png, image/jpeg, image/gif">
                                            <div class="invalid-feedback" id="foto_panitera-error"></div>
                                            @if (!empty($pejabat->foto_panitera))
                                                <div class="mt-3 d-flex align-items-center gap-3">
                                                    <img src="{{ asset('storage/' . $pejabat->foto_panitera) }}" class="rounded-3 shadow-sm" alt="Foto Panitera"
                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                    <span class="text-success small fw-medium"><i class="fas fa-check-circle me-1"></i> Foto Tersimpan</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Tab Sekretaris --}}
                                <div class="tab-pane fade" id="sekretaris-pane" role="tabpanel" aria-labelledby="sekretaris-tab" tabindex="0">
                                    <div class="row align-items-center">
                                        <div class="col-md-7 mb-4">
                                            <label for="sekretaris" class="form-label text-muted fw-semibold mb-2">Nama Lengkap Sekretaris <span class="text-danger">*</span></label>
                                            <input type="text" name="sekretaris" class="form-control form-control-lg bg-light border-0 @error('sekretaris') is-invalid @enderror" id="sekretaris"
                                                placeholder="Masukkan Nama Sekretaris" value="{{ old('sekretaris', $pejabat->sekretaris ?? '') }}">
                                            <div class="invalid-feedback" id="sekretaris-error"></div>
                                        </div>
                                        <div class="col-md-5 mb-4">
                                            <label for="foto_sekretaris" class="form-label text-muted fw-semibold mb-2">Foto Sekretaris
                                                @if (empty($pejabat->foto_sekretaris))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="file" name="foto_sekretaris" class="form-control form-control-lg bg-light border-0 @error('foto_sekretaris') is-invalid @enderror"
                                                id="foto_sekretaris" accept="image/png, image/jpeg, image/gif">
                                            <div class="invalid-feedback" id="foto_sekretaris-error"></div>
                                            @if (!empty($pejabat->foto_sekretaris))
                                                <div class="mt-3 d-flex align-items-center gap-3">
                                                    <img src="{{ asset('storage/' . $pejabat->foto_sekretaris) }}" class="rounded-3 shadow-sm" alt="Foto Sekretaris"
                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                    <span class="text-success small fw-medium"><i class="fas fa-check-circle me-1"></i> Foto Tersimpan</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-light mt-4 mb-4">
                            <div class="d-flex justify-content-end">
                                <button type="submit" id="submitButton" class="btn btn-primary btn-sm fw-semibold rounded-pill shadow-sm">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div><!--end container-->

        @include('admin.layout.content-footer')
        <!-- End -->
    </main>

@endsection

@push('scripts')
    <script>
        document.getElementById('pejabatForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitButton = document.getElementById('submitButton');
            const spinner = submitButton.querySelector('.spinner-border');

            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            // Show spinner and disable button
            spinner.style.display = 'inline-block';
            submitButton.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        // Handle validation errors
                        Object.keys(data.errors).forEach(key => {
                            const input = document.getElementById(key);
                            const errorDiv = document.getElementById(`${key}-error`);
                            if (input) {
                                input.classList.add('is-invalid');
                            }
                            if (errorDiv) {
                                errorDiv.textContent = data.errors[key][0];
                            }
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Harap periksa kembali isian form Anda.',
                        });
                    } else {
                        // Handle other server errors
                        throw new Error(data.message || 'Terjadi kesalahan pada server.');
                    }
                } else {
                    // Handle success
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            timerProgressBar: true,
                        }).then(() => {
                            window.location.reload(); // Reload page to show new data
                        });
                    }
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Tidak dapat menyimpan data. Silakan coba lagi.',
                });
            } finally {
                // Hide spinner and re-enable button
                spinner.style.display = 'none';
                submitButton.disabled = false;
            }
        });
    </script>
@endpush
