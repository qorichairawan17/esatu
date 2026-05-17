@extends('admin.layout.body')
@section('title', $title)

@section('content')
    <!-- Start Page Content -->
    <main class="page-content bg-light">

        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">

                @include('admin.component.breadcumb')

                <div class="mt-4">
                    <div class="card shadow">
                        <div class="card-header bg-soft-primary">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    @if (Auth::user()->role == \App\Enum\RoleEnum::User->value)
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('surat-kuasa.form', ['param' => 'add', 'klasifikasi' => App\Enum\SuratKuasaEnum::Advokat->value]) }}"
                                                class="btn btn-sm btn-primary rounded-pill">
                                                Daftar Advokat
                                            </a>
                                            <a href="{{ route('surat-kuasa.form', ['param' => 'add', 'klasifikasi' => App\Enum\SuratKuasaEnum::NonAdvokat->value]) }}"
                                                class="btn btn-sm btn-warning rounded-pill">
                                                Daftar Non Advokat
                                            </a>
                                        </div>
                                    @else
                                        <button class="btn btn-sm btn-danger rounded-pill" id="hapus-surat-kuasa-ditolak">
                                            Hapus Surat Kuasa Ditolak
                                        </button>
                                    @endif
                                </div>

                                <div class="d-flex flex-column flex-sm-row gap-2 ms-md-auto">
                                    <div class="widget-filter">
                                        <select class="form-select form-select-sm" id="klasifikasiFilter" aria-label="Filter Klasifikasi">
                                            <option value="">Semua Klasifikasi</option>
                                            @foreach (\App\Enum\SuratKuasaEnum::cases() as $enum)
                                                <option value="{{ $enum->value }}">{{ $enum->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="widget-filter">
                                        <select class="form-select form-select-sm" id="tahapanFilter" aria-label="Filter Tahapan">
                                            <option value="">Semua Tahapan</option>
                                            @foreach (\App\Enum\TahapanSuratKuasaEnum::cases() as $enum)
                                                <option value="{{ $enum->value }}">{{ $enum->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="widget-filter">
                                        <select class="form-select form-select-sm" id="statusFilter" aria-label="Filter Status">
                                            <option value="">Semua Status</option>
                                            @foreach (\App\Enum\StatusSuratKuasaEnum::cases() as $enum)
                                                <option value="{{ $enum->value }}">{{ $enum->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {!! $dataTable->table(['class' => 'table table-hover', 'style' => 'width:100%;']) !!}
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

    {{-- Modal Testimoni - Tampil otomatis jika pengguna sudah lebih dari 3 kali mendaftar di minggu ini dan belum memberikan testimoni --}}
    @if (Auth::user()->role == \App\Enum\RoleEnum::User->value)
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
                                <textarea class="form-control" id="pesan" name="pesan" rows="4" placeholder="Ceritakan kepuasan dan pengalaman kamu menggunakan e-SuKa..." required style="resize: none;">{{ optional($testimoniUser)->testimoni }}</textarea>
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
    @endif
@endsection
@push('scripts')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {

            const klasifikasiFilter = document.getElementById('klasifikasiFilter');
            const tahapanFilter = document.getElementById('tahapanFilter');
            const statusFilter = document.getElementById('statusFilter');

            if (klasifikasiFilter) {
                klasifikasiFilter.addEventListener('change', function() {
                    window.LaravelDataTables['pendaftaransuratkuasa-table'].ajax.reload();
                });
            }

            if (tahapanFilter) {
                tahapanFilter.addEventListener('change', function() {
                    window.LaravelDataTables['pendaftaransuratkuasa-table'].ajax.reload();
                });
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    window.LaravelDataTables['pendaftaransuratkuasa-table'].ajax.reload();
                });
            }

            // Base columns always hidden (shown in child row)
            const baseCols = [{
                    index: 5,
                    key: 'perihal',
                    label: 'Perihal'
                },
                {
                    index: 6,
                    key: 'jenis_surat',
                    label: 'Jenis Surat'
                }
            ];

            // Extra columns hidden on mobile (shown in child row)
            const mobileCols = [{
                    index: 7,
                    key: 'tahapan',
                    label: 'Tahapan'
                },
                {
                    index: 8,
                    key: 'status',
                    label: 'Status',
                    isHtml: true
                },
                {
                    index: 9,
                    key: 'action',
                    label: 'Aksi',
                    isHtml: true
                }
            ];

            function isMobile() {
                return window.innerWidth <= 768;
            }

            let currentHiddenCols = [];
            let lastIsMobile = null;

            function getHiddenCols() {
                if (isMobile()) {
                    return baseCols.concat(mobileCols);
                }
                return baseCols.slice();
            }

            function applyColumnVisibility(dt) {
                const nowMobile = isMobile();
                if (lastIsMobile === nowMobile) return;
                lastIsMobile = nowMobile;

                currentHiddenCols = getHiddenCols();
                const hiddenIndexes = currentHiddenCols.map(c => c.index);

                // Set visibility for all manageable columns
                const allIndexes = baseCols.concat(mobileCols).map(c => c.index);
                allIndexes.forEach(function(idx) {
                    dt.column(idx).visible(!hiddenIndexes.includes(idx));
                });

                // Close all open child rows when switching mode
                dt.rows().every(function() {
                    if (this.child.isShown()) {
                        this.child.hide();
                        const trNode = this.node();
                        if (trNode) trNode.classList.remove('shown');
                    }
                });
            }

            // Wait for DataTable to initialise then set up child rows
            const checkTable = setInterval(function() {
                if (window.LaravelDataTables && window.LaravelDataTables['pendaftaransuratkuasa-table']) {
                    clearInterval(checkTable);
                    const dt = window.LaravelDataTables['pendaftaransuratkuasa-table'];
                    const tableEl = document.getElementById('pendaftaransuratkuasa-table');

                    // Initial column visibility
                    applyColumnVisibility(dt);

                    // Re-apply on resize / orientation change
                    let resizeTimer;
                    window.addEventListener('resize', function() {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(function() {
                            applyColumnVisibility(dt);
                        }, 250);
                    });

                    // Handle expand/collapse click via event delegation
                    tableEl.addEventListener('click', function(e) {
                        const btn = e.target.closest('.dt-expand-btn');
                        if (!btn) return;

                        const tr = btn.closest('tr');
                        const row = dt.row(tr);

                        if (row.child.isShown()) {
                            row.child.hide();
                            tr.classList.remove('shown');
                        } else {
                            const rowData = row.data();
                            const activeCols = getHiddenCols();

                            let html = '<div class="dt-child-detail"><dl>';
                            activeCols.forEach(function(col) {
                                const val = rowData[col.key] || '-';
                                html += '<dt>' + col.label + '</dt>';
                                html += '<dd>' + val + '</dd>';
                            });
                            html += '</dl></div>';

                            row.child(html, 'child-row').show();
                            tr.classList.add('shown');
                        }
                    });
                }
            }, 200);
        });
    </script>
    <script type="text/javascript">
        window.deleteData = async function(url) {
            const result = await Swal.fire({
                title: 'Apakah Kamu yakin?',
                text: "Data yang akan dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        await Swal.fire('Berhasil!', data.message, 'success');
                        window.LaravelDataTables['pendaftaransuratkuasa-table'].ajax.reload();
                    } else {
                        Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Oops', 'Terjadi kesalahan.', 'error');
                }
            }
        };
    </script>
    @if (Auth::user()->role != \App\Enum\RoleEnum::User->value)
        <script type="text/javascript">
            document.getElementById('hapus-surat-kuasa-ditolak').addEventListener('click', async function() {
                const result = await Swal.fire({
                    title: 'Apakah Kamu yakin?',
                    text: "Semua surat kuasa yang ditolak akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus semua!',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    try {
                        const response = await fetch('{{ route('surat-kuasa.destroy-rejected') }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            await Swal.fire('Berhasil!', data.message, 'success');
                            window.LaravelDataTables['pendaftaransuratkuasa-table'].ajax.reload();
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat menghapus data.', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Oops', 'Terjadi kesalahan.', 'error');
                    }
                }
            });
        </script>
    @endif

    {{-- Script Testimoni untuk User --}}
    @if (Auth::user()->role == \App\Enum\RoleEnum::User->value)
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                // Tampilkan modal testimoni otomatis jika memenuhi kriteria
                @if ($shouldShowTestimoniPrompt)
                    // Delay sedikit agar halaman sudah siap
                    setTimeout(function() {
                        const testimoniModal = new bootstrap.Modal(document.getElementById('testimoniModal'));
                        testimoniModal.show();
                    }, 1000);
                @endif

                // Update tampilan nilai rating di bawah bintang
                const ratingInputs = document.querySelectorAll('input[name="rating"]');
                const ratingValueEl = document.getElementById('ratingValue');
                const ratingInvalid = document.getElementById('ratingInvalid');

                if (ratingInputs.length > 0) {
                    ratingInputs.forEach(input => {
                        input.addEventListener('change', () => {
                            ratingValueEl.textContent = input.value;
                            ratingInvalid.style.display = 'none';
                        });
                    });
                }

                // Logika submit form testimoni
                const form = document.getElementById('formTestimoni');
                if (form) {
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
                                    Swal.fire('Berhasil!', data.message, 'success');
                                    const modal = bootstrap.Modal.getInstance(document.getElementById('testimoniModal'));
                                    modal.hide();
                                } else {
                                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Tidak dapat terhubung ke server.', 'error');
                            }).finally(() => {
                                submitButton.disabled = false;
                                submitButton.innerHTML = '<i class="ti ti-send me-1"></i>Kirim Testimoni';
                            });
                    });
                }
            });
        </script>
    @endif
@endpush
