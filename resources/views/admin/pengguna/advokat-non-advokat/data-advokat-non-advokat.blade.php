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
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <h6 class="card-title mb-0 text-dark">Data Advokat/Non Advokat</h6>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <select class="form-select form-select-sm" id="statusFilter" aria-label="Filter Status" style="width: auto; min-width: 140px;">
                                        <option value="">Semua Status</option>
                                        <option value="0">Aktif</option>
                                        <option value="1">Terblokir</option>
                                    </select>
                                    <select class="form-select form-select-sm" id="profileStatusFilter" aria-label="Filter Profile" style="width: auto; min-width: 170px;">
                                        <option value="">Semua Profile</option>
                                        <option value="1">Terverifikasi</option>
                                        <option value="0">Belum Terverifikasi</option>
                                    </select>
                                    <a href="{{ route('advokat.form', ['param' => 'add']) }}" class="btn btn-primary btn-sm btn-pills">Tambah</a>
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
@endsection
@push('scripts')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const profileStatusFilter = document.getElementById('profileStatusFilter');

            // Menambahkan event listener ke filter status
            statusFilter.addEventListener('change', function() {
                window.LaravelDataTables['advokat-table'].ajax.reload();
            });

            // Menambahkan event listener ke filter profile status
            profileStatusFilter.addEventListener('change', function() {
                window.LaravelDataTables['advokat-table'].ajax.reload();
            });

            // Columns to hide and show in child row (column index + data key + label)
            const hiddenCols = [{
                    index: 6,
                    key: 'created_at',
                    label: 'Dibuat'
                },
                {
                    index: 7,
                    key: 'updated_at',
                    label: 'Diperbarui'
                }
            ];

            // Wait for DataTable to initialise then set up child rows
            const checkTable = setInterval(function() {
                if (window.LaravelDataTables && window.LaravelDataTables['advokat-table']) {
                    clearInterval(checkTable);
                    const dt = window.LaravelDataTables['advokat-table'];
                    const tableEl = document.getElementById('advokat-table');

                    // Hide the columns that will be shown in child row
                    hiddenCols.forEach(function(col) {
                        dt.column(col.index).visible(false);
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

                            let html = '<div class="dt-child-detail"><dl>';
                            hiddenCols.forEach(function(col) {
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
                        window.LaravelDataTables['advokat-table'].ajax.reload();
                    } else {
                        Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                }
            }
        }
    </script>
@endpush
