@extends('admin.layout.body')
@section('title', $title)
@section('content')
    <link href="{{ asset('admin/assets/css/premium-dashboard.css') }}" rel="stylesheet">

    <!-- Start Page Content -->
    <main class="page-content bg-light premium-dashboard">

        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">

                @include('admin.component.breadcumb')

                <!-- Welcome Banner -->
                <div class="welcome-banner mt-3">
                    <div class="welcome-content">
                        <h2 class="welcome-title">Halo, {{ Auth::user()->name }}! 👋</h2>
                        <p class="welcome-subtitle">Selamat datang di panel admin {{ config('app.name') }}. Berikut adalah ringkasan statistik dan pendaftaran terbaru hari ini.</p>
                    </div>
                </div>

                <!-- Shortcuts / Stats Data -->
                <div class="shortcut-container">
                    <div class="shortcut-card-premium">
                        <div class="icon-box primary">
                            <i class="uil uil-users-alt"></i>
                        </div>
                        <h3 class="mb-1 fw-bold text-dark">{{ $userTotal }}</h3>
                        <p class="shortcut-title">Total Pengguna</p>
                    </div>

                    <div class="shortcut-card-premium">
                        <div class="icon-box success">
                            <i class="uil uil-file-bookmark-alt"></i>
                        </div>
                        <h3 class="mb-1 fw-bold text-dark">{{ $suratKuasaTotal }}</h3>
                        <p class="shortcut-title">Total Surat Kuasa</p>
                    </div>

                    <div class="shortcut-card-premium">
                        <div class="icon-box warning">
                            <i class="uil uil-comment-alt-heart"></i>
                        </div>
                        <h3 class="mb-1 fw-bold text-dark">{{ $testimoniTotal }}</h3>
                        <p class="shortcut-title">Total Testimoni</p>
                    </div>
                </div>

                <!-- Table Surat Kuasa -->
                <div class="premium-panel mt-4">
                    <div class="premium-panel-header">
                        <h4 class="premium-panel-title">
                            <i class="uil uil-clipboard-notes text-primary"></i>
                            Pendaftaran Surat Kuasa Terbaru
                        </h4>
                        <a href="{{ route('surat-kuasa.index') }}" class="btn-link-premium">
                            Lihat Semua <i class="uil uil-arrow-right"></i>
                        </a>
                    </div>
                    <div class="premium-panel-body p-0">
                        <div class="table-responsive scroll-container" style="max-height: 350px;">
                            <table class="table table-hover table-center bg-white mb-0" style="font-size: 14px;">
                                <thead class="bg-light sticky-top" style="z-index: 1;">
                                    <tr>
                                        <th class="border-bottom p-3">#</th>
                                        <th class="border-bottom p-3">ID Daftar</th>
                                        <th class="border-bottom p-3">Tanggal</th>
                                        <th class="border-bottom p-3" style="min-width: 220px;">Pemohon</th>
                                        <th class="border-bottom p-3">Jenis</th>
                                        <th class="border-bottom p-3">Tahapan</th>
                                        <th class="border-bottom p-3 text-center" style="min-width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($verifikasiSuratKuasa as $index => $suratKuasa)
                                        @php
                                            $tahapanLower = strtolower($suratKuasa->tahapan);
                                            if (str_contains($tahapanLower, 'selesai') || str_contains($tahapanLower, 'diterima') || str_contains($tahapanLower, 'disetujui')) {
                                                $statusClass = 'success';
                                            } elseif (str_contains($tahapanLower, 'tolak') || str_contains($tahapanLower, 'batal')) {
                                                $statusClass = 'danger';
                                            } else {
                                                $statusClass = 'warning';
                                            }
                                        @endphp
                                        <tr>
                                            <th class="p-3">{{ $index + 1 }}</th>
                                            <td class="p-3 fw-bold text-primary">{{ $suratKuasa->id_daftar }}</td>
                                            <td class="p-3 text-muted">
                                                {{ \Carbon\Carbon::parse($suratKuasa->tanggal_daftar)->isoFormat('dddd, D MMMM Y') }}
                                            </td>
                                            <td class="p-3 fw-semibold text-dark">
                                                {{ $suratKuasa->pemohon }}
                                            </td>
                                            <td class="p-3">
                                                <span class="badge bg-soft-info text-info rounded-pill px-3 py-2">{{ $suratKuasa->jenis_surat }}</span>
                                            </td>
                                            <td class="p-3">
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ $suratKuasa->tahapan }}
                                                </span>
                                            </td>
                                            <td class="p-3 text-center">
                                                <a href="{{ route('surat-kuasa.detail', ['id' => Crypt::encrypt($suratKuasa->id)]) }}" class="btn btn-sm btn-icon btn-pills btn-soft-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center p-5 text-muted">
                                                <div class="py-4">
                                                    <i class="uil uil-inbox fs-1 d-block mb-2 text-primary opacity-50"></i>
                                                    <h6 class="text-dark">Belum ada pendaftaran</h6>
                                                    <small>Data surat kuasa pendaftaran akan muncul di sini.</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mt-4">
                    <div class="col-lg-8 col-sm-12 mb-4">
                        <!-- Chart Bar -->
                        <div class="premium-panel mb-4 h-auto">
                            <div class="premium-panel-header">
                                <h4 class="premium-panel-title">
                                    <i class="uil uil-chart-bar text-primary"></i>
                                    Grafik Pendaftaran Surat Kuasa ({{ date('Y') }})
                                </h4>
                            </div>
                            <div class="premium-panel-body">
                                <div class="chart-wrapper" style="height: 320px;">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Audit Trail -->
                        <div class="premium-panel h-auto border-0" style="background: #ffffff; box-shadow: 0 4px 20px rgba(23, 32, 51, 0.04);">
                            <div class="premium-panel-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center">
                                        <i class="uil uil-history text-primary me-2 fs-5"></i>
                                        Aktivitas Terbaru
                                    </h6>
                                    <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2">Log Sistem</span>
                                </div>

                                @if ($lastAuditTrail)
                                    <div class="d-flex align-items-start p-3 rounded-4 bg-white" style="border: 1px solid rgba(19, 108, 52, 0.12); box-shadow: 0 2px 10px rgba(23, 32, 51, 0.03);">
                                        <div class="icon-box primary mb-0 me-3" style="width: 48px; height: 48px; flex-shrink: 0; background-color: rgba(19, 108, 52, 0.08);">
                                            <i class="uil uil-file-info-alt fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1 align-self-center">
                                            <p class="text-secondary m-0 lh-base" style="font-size: 0.95rem; text-align: left;">
                                                {{ $lastAuditTrail->payload }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center p-4 bg-white rounded-4" style="border: 1px dashed rgba(0,0,0,0.1);">
                                        <p class="text-muted m-0">
                                            <i class="uil uil-info-circle me-1"></i> Belum ada data audit trail.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-12 mb-4">
                        <!-- Donut Chart -->
                        <div class="premium-panel h-100">
                            <div class="premium-panel-header">
                                <h4 class="premium-panel-title">
                                    <i class="uil uil-chart-pie text-success"></i>
                                    Statistik {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}
                                </h4>
                            </div>
                            <div class="premium-panel-body d-flex flex-column align-items-center justify-content-center">
                                <div id="statistik-surat-kuasa" class="w-100"></div>
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

    <script src="{{ asset('admin/assets/plugins/chartjs/dist/chart.umd.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initiate Tooltips if bootstrap is available
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            if (typeof bootstrap !== 'undefined') {
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Bar Chart
            const ctx = document.getElementById('myChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                                label: 'Disetujui',
                                data: @json($chartData),
                                backgroundColor: 'rgba(19, 108, 52, 0.82)',
                                hoverBackgroundColor: 'rgba(19, 108, 52, 1)',
                                borderRadius: 4,
                                borderSkipped: false
                            },
                            {
                                label: 'Ditolak',
                                data: @json($chartDataDitolak),
                                backgroundColor: 'rgba(228, 63, 82, 0.82)',
                                hoverBackgroundColor: 'rgba(228, 63, 82, 1)',
                                borderRadius: 4,
                                borderSkipped: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 13
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(23, 32, 51, 0.92)',
                                padding: 12,
                                titleFont: {
                                    family: "'Inter', sans-serif",
                                    size: 14
                                },
                                bodyFont: {
                                    family: "'Inter', sans-serif",
                                    size: 13
                                },
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false,
                                },
                                ticks: {
                                    font: {
                                        family: "'Inter', sans-serif"
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    font: {
                                        family: "'Inter', sans-serif"
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Donut Chart - Statistik Surat Kuasa
            try {
                var options = {
                    chart: {
                        height: 360,
                        type: 'donut',
                        fontFamily: "'Inter', sans-serif",
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800,
                        }
                    },
                    series: @json($statistikDonutChart['series']),
                    labels: @json($statistikDonutChart['labels']),
                    colors: ['#136C34', '#20C997', '#F1B53D', '#E43F52', '#14B8A6', '#64748B'],
                    legend: {
                        show: true,
                        position: 'bottom',
                        offsetY: 0,
                        itemMargin: {
                            horizontal: 10,
                            vertical: 5
                        },
                        markers: {
                            width: 10,
                            height: 10,
                            offsetX: -3,
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        dropShadow: {
                            enabled: false,
                        },
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold',
                        }
                    },
                    stroke: {
                        show: true,
                        colors: ['#ffffff'],
                        width: 3
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '22px',
                                        fontWeight: 700,
                                    },
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        label: 'Total',
                                        fontSize: '16px',
                                        fontWeight: 600,
                                        color: '#7d879c'
                                    }
                                }
                            }
                        }
                    },
                    responsive: [{
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 380,
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                }
                var el = document.querySelector("#statistik-surat-kuasa");
                if (el) {
                    var chart = new ApexCharts(el, options);
                    chart.render();
                }
            } catch (error) {
                console.error("Error rendering ApexChart: ", error);
            }
        });
    </script>
@endsection
