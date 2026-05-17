@extends('admin.layout.body')
@section('title', $title)
@push('styles')
    <link href="{{ asset('admin/assets/css/detail-advokat.css') }}?v=3" rel="stylesheet">
@endpush

@section('content')
    <main class="page-content bg-light adv-detail-page">
        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">
                @include('admin.component.breadcumb')

                @php
                    $profile = $user->profile;
                    $encryptedUserId = Crypt::encrypt($user->id);
                    $hasInvalidProfileData = false;

                    if ($user->role === \App\Enum\RoleEnum::User->value) {
                        $fieldsToCheck = [$user->name, $profile?->jenis_kelamin, $profile?->kontak, $profile?->alamat];

                        foreach ($fieldsToCheck as $field) {
                            if (empty($field) || trim((string) $field, " \t\n\r\0\x0B-") === '') {
                                $hasInvalidProfileData = true;
                                break;
                            }
                        }

                        if (empty($profile?->tanggal_lahir)) {
                            $hasInvalidProfileData = true;
                        }
                    }

                    $profileFields = [
                        ['label' => 'Nama Lengkap', 'value' => $user->name],
                        [
                            'label' => 'Tanggal Lahir',
                            'value' => $profile?->tanggal_lahir ? \Carbon\Carbon::parse($profile->tanggal_lahir)->translatedFormat('d F Y') : '-',
                        ],
                        ['label' => 'Jenis Kelamin', 'value' => $profile?->jenis_kelamin ?: '-'],
                        ['label' => 'Kontak / Telepon', 'value' => $profile?->kontak ?: '-'],
                        ['label' => 'Alamat', 'value' => $profile?->alamat ?: '-'],
                    ];
                @endphp

                <section class="adv-command mt-4">
                    <div>
                        <span class="adv-kicker">Detail Pengguna</span>
                        <h4 class="adv-title">{{ $detailTitle }}</h4>
                        <p class="adv-subtitle mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="adv-actions">
                        @if ($hasInvalidProfileData)
                            <button type="button" class="btn btn-warning btn-sm adv-action-btn" id="btn-send-warning">
                                <i class="uil uil-envelope-alt me-1"></i> Kirim Peringatan
                            </button>
                        @endif
                        <a href="{{ route('advokat.index') }}" class="btn btn-danger btn-sm adv-action-btn">
                            <i class="uil uil-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </section>

                @if ($hasInvalidProfileData)
                    <div class="adv-alert" role="alert">
                        <i class="uil uil-exclamation-triangle"></i>
                        <div>
                            <strong>Peringatan Profil Tidak Valid</strong>
                            <p>Beberapa informasi profil belum diisi atau masih menggunakan karakter tidak valid.</p>
                        </div>
                    </div>
                @endif

                <div class="row g-4">
                    <div class="col-xl-4 col-lg-5">
                        <aside class="adv-profile-card">
                            <div class="adv-profile-banner"></div>
                            <div class="adv-profile-body">
                                <div class="adv-avatar-wrap">
                                    @if ($profile?->foto)
                                        <img src="{{ asset('storage/' . $profile->foto) }}" class="adv-avatar" alt="{{ $user->name }}">
                                    @else
                                        <img src="{{ asset('assets/images/user/user-none.png') }}" class="adv-avatar" alt="{{ $user->name }}">
                                    @endif
                                </div>
                                <h5>{{ $user->name }}</h5>
                                <p>{{ $user->email }}</p>
                                <div class="adv-badge-row">
                                    <span class="adv-status-badge {{ $user->block ? 'is-blocked' : 'is-active' }}">
                                        <i class="uil {{ $user->block ? 'uil-ban' : 'uil-check-circle' }}"></i>
                                        {{ $user->block ? 'Akun Diblokir' : 'Akun Aktif' }}
                                    </span>
                                    <span class="adv-status-badge {{ $user->profile_status ? 'is-active' : 'is-muted' }}">
                                        <i class="uil {{ $user->profile_status ? 'uil-shield-check' : 'uil-clock' }}"></i>
                                        {{ $user->profile_status ? 'Profil Terverifikasi' : 'Profil Belum Verifikasi' }}
                                    </span>
                                </div>
                            </div>
                            <div class="adv-profile-metrics">
                                <div>
                                    <span>Surat Kuasa</span>
                                    <strong>{{ number_format($user->surat_kuasa_count) }}</strong>
                                </div>
                                <div>
                                    <span>Bergabung</span>
                                    <strong>{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</strong>
                                </div>
                            </div>
                        </aside>
                    </div>

                    <div class="col-xl-8 col-lg-7">
                        <section class="adv-detail-card">
                            <div class="adv-tabs-wrap">
                                <ul class="nav adv-tabs" id="advDetailTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-pane" type="button"
                                            role="tab" aria-controls="info-pane" aria-selected="true">
                                            <i class="uil uil-user"></i>
                                            Detail Informasi
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="surat-kuasa-tab" data-bs-toggle="tab" data-bs-target="#surat-kuasa-pane"
                                            type="button" role="tab" aria-controls="surat-kuasa-pane" aria-selected="false">
                                            <i class="uil uil-file-alt"></i>
                                            Daftar Surat Kuasa
                                            <span>{{ number_format($user->surat_kuasa_count) }}</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="info-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                                    <div class="adv-info-grid">
                                        @foreach ($profileFields as $field)
                                            <div class="adv-info-item {{ $field['label'] === 'Alamat' ? 'is-wide' : '' }}">
                                                <span>{{ $field['label'] }}</span>
                                                <strong>{{ $field['value'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="adv-timeline-box">
                                        <div>
                                            <i class="uil uil-shield-check"></i>
                                            <span>Persetujuan Privasi</span>
                                            @if ($user->privacy_policy_agreed_at)
                                                <strong>Disetujui pada
                                                    {{ \Carbon\Carbon::parse($user->privacy_policy_agreed_at)->translatedFormat('d M Y H:i') }}</strong>
                                            @else
                                                <strong>Belum Disetujui</strong>
                                            @endif
                                        </div>
                                        <div>
                                            <i class="uil uil-calendar-alt"></i>
                                            <span>Dibuat Pada</span>
                                            <strong>{{ $user->created_at ? $user->created_at->translatedFormat('d M Y H:i') : '-' }}</strong>
                                        </div>
                                        <div>
                                            <i class="uil uil-clock"></i>
                                            <span>Terakhir Diperbarui</span>
                                            <strong>{{ $user->updated_at ? $user->updated_at->translatedFormat('d M Y H:i') : '-' }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="surat-kuasa-pane" role="tabpanel" aria-labelledby="surat-kuasa-tab" tabindex="0">
                                    <div class="adv-table-head">
                                        <div>
                                            <span class="adv-kicker">Riwayat Pendaftaran</span>
                                            <h6>Surat kuasa yang pernah didaftarkan pengguna</h6>
                                        </div>
                                        <span class="adv-soft-pill">{{ number_format($user->surat_kuasa_count) }} Data</span>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="user-surat-kuasa-table" class="table adv-table w-100">
                                            <thead>
                                                <tr>
                                                    <th>ID Daftar</th>
                                                    <th>Tanggal Daftar</th>
                                                    <th>Tahapan</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.layout.content-footer')
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const suratKuasaTable = $('#user-surat-kuasa-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: @json(route('advokat.surat-kuasa-data', ['id' => $encryptedUserId])),
                order: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'id_daftar',
                        name: 'id_daftar'
                    },
                    {
                        data: 'tanggal_daftar',
                        name: 'tanggal_daftar'
                    },
                    {
                        data: 'tahapan',
                        name: 'tahapan',
                        className: 'text-md-center'
                    }
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Cari surat kuasa...',
                    lengthMenu: '_MENU_ data',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Belum ada data',
                    zeroRecords: 'Belum ada surat kuasa yang didaftarkan',
                    emptyTable: 'Belum ada surat kuasa yang didaftarkan',
                    processing: 'Memuat data...',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });

            document.getElementById('surat-kuasa-tab').addEventListener('shown.bs.tab', function() {
                suratKuasaTable.columns.adjust().responsive.recalc();
            });

            const btnSendWarning = document.getElementById('btn-send-warning');

            if (btnSendWarning) {
                btnSendWarning.addEventListener('click', function() {
                    const url = @json(route('advokat.send-warning', ['id' => $encryptedUserId]));

                    Swal.fire({
                        title: 'Kirim Peringatan?',
                        text: 'Email peringatan akan dikirim ke pengguna ini untuk memperbarui profil.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f1b53d',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Kirim!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            btnSendWarning.disabled = true;
                            const originalHtml = btnSendWarning.innerHTML;
                            btnSendWarning.innerHTML =
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';

                            try {
                                const response = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': @json(csrf_token())
                                    }
                                });

                                const data = await response.json();

                                if (!response.ok) {
                                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan pada server.', 'error');
                                } else {
                                    Swal.fire('Berhasil!', data.message, 'success');
                                }
                            } catch (error) {
                                console.error('Fetch Error:', error);
                                Swal.fire('Error!', 'Tidak dapat terhubung ke server.', 'error');
                            } finally {
                                btnSendWarning.disabled = false;
                                btnSendWarning.innerHTML = originalHtml;
                            }
                        }
                    });
                });
            }
        });
    </script>
@endpush
