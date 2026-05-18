@extends('admin.layout.body')
@section('title', $title)
@section('content')
    <link href="{{ asset('admin/assets/css/monitoring-dashboard.css') }}?v=2" rel="stylesheet">

    <main class="page-content bg-light monev-dashboard">
        @include('admin.component.top-header')

        <div class="container-fluid">
            <div class="layout-specing">
                @include('admin.component.breadcumb')

                @php
                    $totalRegistrations = (int) $overview['total_pendaftaran'];
                    $statusBase = max(1, $totalRegistrations);
                    $pctApproved = $totalRegistrations > 0 ? round(($overview['total_disetujui'] / $statusBase) * 100) : 0;
                    $pctRejected = $totalRegistrations > 0 ? round(($overview['total_ditolak'] / $statusBase) * 100) : 0;
                    $pctProcess = $totalRegistrations > 0 ? round(($overview['total_proses'] / $statusBase) * 100) : 0;
                    $paymentTotal = (int) ($paymentTypes['total'] ?? 0);
                    $paymentCoverage = $totalRegistrations > 0 ? min(100, round(($paymentTotal / $totalRegistrations) * 100)) : 0;
                    $paymentUnpaid = max(0, $totalRegistrations - $paymentTotal);
                    $paymentTop = $paymentTypes['top'] ?? null;
                    $statusTotal = array_sum($statusDistribution['series']);
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    $maxMonthly = max(1, max(array_merge($growth['monthly_current'], $growth['monthly_previous'])));
                    $statCards = [
                        [
                            'label' => 'Total Pendaftaran',
                            'value' => $overview['total_pendaftaran'],
                            'note' => 'Semua permohonan masuk',
                            'percentage' => 100,
                            'tone' => 'primary',
                            'icon' => 'uil-files-landscapes',
                        ],
                        [
                            'label' => 'Disetujui',
                            'value' => $overview['total_disetujui'],
                            'note' => $overview['total_disetujui'] . ' dari ' . $overview['total_pendaftaran'] . ' permohonan',
                            'percentage' => $pctApproved,
                            'tone' => 'success',
                            'icon' => 'uil-check-circle',
                        ],
                        [
                            'label' => 'Ditolak',
                            'value' => $overview['total_ditolak'],
                            'note' => 'Perlu perhatian lanjutan',
                            'percentage' => $pctRejected,
                            'tone' => 'danger',
                            'icon' => 'uil-times-circle',
                        ],
                        [
                            'label' => 'Dalam Proses',
                            'value' => $overview['total_proses'],
                            'note' => 'Menunggu verifikasi',
                            'percentage' => $pctProcess,
                            'tone' => 'warning',
                            'icon' => 'uil-clock',
                        ],
                    ];
                @endphp

                <section class="monev-command mt-3">
                    <div>
                        <span class="monev-eyebrow">Monitoring & Evaluasi</span>
                        <h2 class="monev-command-title">Ringkasan Surat Kuasa</h2>
                        <p class="monev-command-copy">Data operasional pendaftaran, verifikasi, testimoni, dan pembayaran PNBP.</p>
                    </div>
                    <div class="monev-command-metrics">
                        <div class="monev-command-metric">
                            <span>Total</span>
                            <strong>{{ number_format($totalRegistrations) }}</strong>
                        </div>
                        <div class="monev-command-metric">
                            <span>Pembayaran</span>
                            <strong id="payment-total-head">{{ number_format($paymentTotal) }}</strong>
                        </div>
                        <div class="monev-command-metric">
                            <span>Approval</span>
                            <strong>{{ $pctApproved }}%</strong>
                        </div>
                    </div>
                </section>

                <div class="monev-stats-grid">
                    @foreach ($statCards as $card)
                        <div class="monev-stat-card is-{{ $card['tone'] }}">
                            <div class="monev-stat-top">
                                <span class="monev-stat-icon"><i class="uil {{ $card['icon'] }}"></i></span>
                                <span class="monev-stat-percent">{{ $card['percentage'] }}%</span>
                            </div>
                            <div class="monev-stat-value">{{ number_format($card['value']) }}</div>
                            <div class="monev-stat-label">{{ $card['label'] }}</div>
                            <p class="monev-stat-note">{{ $card['note'] }}</p>
                            <div class="monev-meter">
                                <span style="width: {{ $card['percentage'] }}%;"></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row g-4">
                    <div class="col-xl-4">
                        <div class="monev-panel h-100">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Rata-rata</span>
                                    <h4 class="monev-panel-title">Pendaftaran Bulanan</h4>
                                </div>
                                <span class="monev-trend is-{{ $growth['trend'] }}">
                                    @if ($growth['trend'] === 'up')
                                        <i class="uil uil-arrow-up"></i>
                                    @elseif ($growth['trend'] === 'down')
                                        <i class="uil uil-arrow-down"></i>
                                    @else
                                        <i class="uil uil-minus"></i>
                                    @endif
                                    {{ abs($growth['growth_percentage']) }}%
                                </span>
                            </div>
                            <div class="monev-panel-body">
                                <div class="monev-large-number">{{ number_format($growth['average'], 1) }}</div>
                                <p class="monev-muted mb-4">Dibandingkan total tahun {{ $growth['previous_year'] }}</p>
                                <div class="monev-split-metrics">
                                    <div>
                                        <span>Tahun {{ $growth['current_year'] }}</span>
                                        <strong>{{ number_format($growth['current_total']) }}</strong>
                                    </div>
                                    <div>
                                        <span>Tahun {{ $growth['previous_year'] }}</span>
                                        <strong>{{ number_format($growth['previous_total']) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="monev-panel h-100">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Komparasi</span>
                                    <h4 class="monev-panel-title">Perbandingan Bulanan</h4>
                                </div>
                                <div class="monev-legend">
                                    <span><i class="is-current"></i>{{ $growth['current_year'] }}</span>
                                    <span><i class="is-previous"></i>{{ $growth['previous_year'] }}</span>
                                </div>
                            </div>
                            <div class="monev-panel-body monev-scroll is-compact">
                                @foreach ($months as $index => $month)
                                    <div class="monev-comparison-row">
                                        <span class="monev-comparison-label">{{ $month }}</span>
                                        <div class="monev-comparison-track" title="{{ $growth['current_year'] }}">
                                            <span class="monev-comparison-bar is-current" style="width: {{ ($growth['monthly_current'][$index] / $maxMonthly) * 100 }}%;">
                                                @if ($growth['monthly_current'][$index] > 0)
                                                    {{ $growth['monthly_current'][$index] }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="monev-comparison-track" title="{{ $growth['previous_year'] }}">
                                            <span class="monev-comparison-bar is-previous" style="width: {{ ($growth['monthly_previous'][$index] / $maxMonthly) * 100 }}%;">
                                                @if ($growth['monthly_previous'][$index] > 0)
                                                    {{ $growth['monthly_previous'][$index] }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-0">
                    <div class="col-xl-8">
                        <div class="monev-panel">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Tren</span>
                                    <h4 class="monev-panel-title">Statistik Pendaftaran</h4>
                                </div>
                            </div>
                            <div class="monev-filter-bar">
                                <label class="monev-field">
                                    <span>Periode</span>
                                    <select id="filter-period" class="monev-input">
                                        <option value="daily">Harian</option>
                                        <option value="monthly" selected>Bulanan</option>
                                        <option value="quarterly">Triwulan</option>
                                        <option value="semester">Semester</option>
                                        <option value="yearly">Tahunan</option>
                                    </select>
                                </label>
                                <label class="monev-field">
                                    <span>Dari</span>
                                    <input type="date" id="filter-start" class="monev-input">
                                </label>
                                <label class="monev-field">
                                    <span>Sampai</span>
                                    <input type="date" id="filter-end" class="monev-input">
                                </label>
                                <div class="monev-filter-actions">
                                    <button type="button" onclick="applyFilter()" class="monev-btn is-primary">
                                        <i class="uil uil-filter"></i>
                                        Terapkan
                                    </button>
                                    <button type="button" onclick="resetFilter()" class="monev-btn">
                                        <i class="uil uil-redo"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                            <div class="monev-panel-body">
                                <div class="monev-chart-shell">
                                    <canvas id="registrationChart"></canvas>
                                    <div class="monev-loading d-none" id="chart-loading">
                                        <div class="monev-spinner"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="monev-panel h-100">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Status</span>
                                    <h4 class="monev-panel-title">Distribusi Permohonan</h4>
                                </div>
                                <span class="monev-soft-badge" id="status-total-count">{{ number_format($statusTotal) }}</span>
                            </div>
                            <div class="monev-panel-body">
                                <div id="statusDonutChart" class="monev-donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-0">
                    <div class="col-xl-5">
                        <div class="monev-panel h-100">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Pembayaran</span>
                                    <h4 class="monev-panel-title">Jenis Pembayaran</h4>
                                </div>
                                <span class="monev-soft-badge" id="payment-type-count">{{ number_format($paymentTypes['total_types'] ?? 0) }} Jenis</span>
                            </div>
                            <div class="monev-panel-body">
                                <div id="paymentTypeChart" class="monev-donut"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="monev-panel h-100">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">PNBP</span>
                                    <h4 class="monev-panel-title">Ringkasan Pembayaran</h4>
                                </div>
                                <span class="monev-soft-badge" id="payment-coverage">{{ $paymentCoverage }}% Terbayar</span>
                            </div>
                            <div class="monev-panel-body">
                                <div class="monev-payment-summary">
                                    <div>
                                        <span>Pembayaran Masuk</span>
                                        <strong id="payment-total-count">{{ number_format($paymentTotal) }}</strong>
                                    </div>
                                    <div>
                                        <span>Belum Bayar</span>
                                        <strong id="payment-unpaid-count">{{ number_format($paymentUnpaid) }}</strong>
                                    </div>
                                    <div>
                                        <span>Metode Dominan</span>
                                        <strong id="payment-top-label">{{ $paymentTop['label'] ?? '-' }}</strong>
                                        <small id="payment-top-total">{{ $paymentTop ? number_format($paymentTop['total']) . ' transaksi' : 'Belum ada data' }}</small>
                                    </div>
                                </div>

                                <div class="monev-payment-list" id="payment-type-list">
                                    @forelse (($paymentTypes['items'] ?? []) as $paymentType)
                                        <div class="monev-payment-row">
                                            <span class="monev-rank-badge">{{ $paymentType['rank'] }}</span>
                                            <div class="monev-payment-main">
                                                <div class="monev-payment-title">
                                                    <strong>{{ $paymentType['label'] }}</strong>
                                                    <span>{{ number_format($paymentType['total']) }} transaksi</span>
                                                </div>
                                                <div class="monev-payment-track">
                                                    <span style="width: {{ $paymentType['percentage'] }}%;"></span>
                                                </div>
                                            </div>
                                            <span class="monev-payment-percent">{{ $paymentType['percentage'] }}%</span>
                                        </div>
                                    @empty
                                        <div class="monev-empty is-small">
                                            <i class="uil uil-wallet"></i>
                                            <p>Belum ada data pembayaran.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-0">
                    <div class="col-xl-4">
                        <div class="monev-panel h-100">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Peringkat</span>
                                    <h4 class="monev-panel-title">Top Advokat/Non Advokat</h4>
                                </div>
                            </div>
                            <div class="monev-panel-body p-0">
                                @if (count($topAdvokats) > 0)
                                    <div class="monev-scroll">
                                        <table class="monev-rank-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($topAdvokats as $advokat)
                                                    <tr>
                                                        <td><span class="monev-rank-badge">{{ $advokat['rank'] }}</span></td>
                                                        <td><strong>{{ $advokat['nama'] }}</strong></td>
                                                        <td class="text-end"><span class="monev-table-count">{{ number_format($advokat['total_pendaftaran']) }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="monev-empty">
                                        <i class="uil uil-user-times"></i>
                                        <p>Belum ada data advokat.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="monev-panel h-100">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Kinerja</span>
                                    <h4 class="monev-panel-title">Panitera</h4>
                                </div>
                            </div>
                            <div class="monev-panel-body p-0">
                                @if (count($paniteraPerformance) > 0)
                                    <div class="monev-scroll">
                                        <table class="monev-rank-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th class="text-end">Approval</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($paniteraPerformance as $panitera)
                                                    <tr>
                                                        <td><span class="monev-rank-badge">{{ $panitera['rank'] }}</span></td>
                                                        <td>
                                                            <strong>{{ $panitera['nama'] }}</strong>
                                                            <span>{{ $panitera['jabatan'] }}</span>
                                                        </td>
                                                        <td class="text-end"><span class="monev-table-count">{{ number_format($panitera['total_approval']) }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="monev-empty">
                                        <i class="uil uil-user-times"></i>
                                        <p>Belum ada data panitera.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="monev-panel h-100">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Kinerja</span>
                                    <h4 class="monev-panel-title">Petugas Verifikasi</h4>
                                </div>
                            </div>
                            <div class="monev-panel-body p-0">
                                @if (count($verifierPerformance) > 0)
                                    <div class="monev-scroll">
                                        <table class="monev-rank-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th class="text-end">Verifikasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($verifierPerformance as $verifier)
                                                    <tr>
                                                        <td><span class="monev-rank-badge">{{ $verifier['rank'] }}</span></td>
                                                        <td><strong>{{ $verifier['nama'] }}</strong></td>
                                                        <td class="text-end"><span class="monev-table-count">{{ number_format($verifier['total_verifikasi']) }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="monev-empty">
                                        <i class="uil uil-user-times"></i>
                                        <p>Belum ada data petugas.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-0">
                    <div class="col-12">
                        <div class="monev-panel">
                            <div class="monev-panel-header">
                                <div>
                                    <span class="monev-panel-kicker">Pengguna</span>
                                    <h4 class="monev-panel-title">Testimoni Terbaru</h4>
                                </div>
                                <span class="monev-soft-badge">{{ count($testimonials) }} Testimoni</span>
                            </div>
                            <div class="monev-panel-body p-0">
                                @forelse ($testimonials as $testi)
                                    <div class="monev-testimonial">
                                        @if ($testi['avatar'])
                                            <img src="{{ $testi['avatar'] }}" alt="{{ $testi['nama'] }}" class="monev-testimonial-avatar">
                                        @else
                                            <span class="monev-testimonial-avatar">{{ mb_strtoupper(mb_substr($testi['nama'], 0, 1)) }}</span>
                                        @endif
                                        <div class="monev-testimonial-body">
                                            <div class="monev-testimonial-head">
                                                <strong>{{ $testi['nama'] }}</strong>
                                                <span>{{ $testi['tanggal'] }}</span>
                                            </div>
                                            <div class="monev-stars" aria-label="{{ $testi['rating'] }} dari 5">
                                                @for ($star = 1; $star <= 5; $star++)
                                                    <span class="{{ $star <= $testi['rating'] ? 'is-filled' : '' }}">&#9733;</span>
                                                @endfor
                                            </div>
                                            <p>{{ Str::limit($testi['testimoni'], 220) }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="monev-empty">
                                        <i class="uil uil-comment-alt-slash"></i>
                                        <p>Belum ada testimoni pengguna.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.layout.content-footer')
    </main>

    <script src="{{ asset('admin/assets/plugins/chartjs/dist/chart.umd.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const monthLabels = @json($months);
            const registrationCanvas = document.getElementById('registrationChart');
            const numberFormatter = new Intl.NumberFormat('id-ID');
            const statusColors = ['#29AA59', '#E43F52', '#F1B53D'];
            const paymentColors = ['#29AA59', '#14B8A6', '#F1B53D', '#64748B', '#20C997', '#168643'];
            let registrationChart = null;
            let statusDonutChart = null;
            let paymentTypeChart = null;

            function formatNumber(value) {
                return numberFormatter.format(Number(value || 0));
            }

            function normalizeDonut(data, colors) {
                const labels = data?.labels || [];
                const series = (data?.series || []).map((value) => Number(value || 0));
                const total = series.reduce((sum, value) => sum + value, 0);

                if (total <= 0) {
                    return {
                        labels: ['Belum ada data'],
                        series: [0],
                        colors: ['#cbd5e1'],
                    };
                }

                return {
                    labels,
                    series,
                    colors,
                };
            }

            function donutOptions(data, colors) {
                const normalized = normalizeDonut(data, colors);

                return {
                    chart: {
                        height: 286,
                        type: 'donut',
                        toolbar: {
                            show: false
                        }
                    },
                    series: normalized.series,
                    labels: normalized.labels,
                    colors: normalized.colors,
                    legend: {
                        position: 'bottom',
                        fontWeight: 600,
                        itemMargin: {
                            horizontal: 10,
                            vertical: 4
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        colors: ['#ffffff'],
                        width: 3
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '72%',
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: '24px',
                                        fontWeight: 800,
                                        color: '#111827',
                                        formatter: (value) => formatNumber(value)
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '13px',
                                        color: '#64748b',
                                        formatter: (w) => formatNumber(w.globals.seriesTotals.reduce((a, b) => a + b, 0))
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: (value) => `${formatNumber(value)} data`
                        }
                    }
                };
            }

            function updateDonut(chart, data, colors) {
                if (!chart) {
                    return;
                }

                const normalized = normalizeDonut(data, colors);
                chart.updateOptions({
                    labels: normalized.labels,
                    colors: normalized.colors
                });
                chart.updateSeries(normalized.series);
            }

            function renderPaymentTypes(data, registrationTotal) {
                const items = data?.items || [];
                const list = document.getElementById('payment-type-list');
                const top = data?.top || null;
                const total = Number(data?.total || 0);
                const unpaid = Math.max(0, Number(registrationTotal || 0) - total);
                const coverage = Number(registrationTotal || 0) > 0 ? Math.min(100, Math.round((total / Number(registrationTotal)) * 100)) : 0;

                document.getElementById('payment-total-head').textContent = formatNumber(total);
                document.getElementById('payment-total-count').textContent = formatNumber(total);
                document.getElementById('payment-unpaid-count').textContent = formatNumber(unpaid);
                document.getElementById('payment-type-count').textContent = `${formatNumber(data?.total_types || 0)} Jenis`;
                document.getElementById('payment-coverage').textContent = `${coverage}% Terbayar`;
                document.getElementById('payment-top-label').textContent = top ? top.label : '-';
                document.getElementById('payment-top-total').textContent = top ? `${formatNumber(top.total)} transaksi` : 'Belum ada data';

                if (!list) {
                    return;
                }

                list.innerHTML = '';

                if (!items.length) {
                    const empty = document.createElement('div');
                    empty.className = 'monev-empty is-small';

                    const icon = document.createElement('i');
                    icon.className = 'uil uil-wallet';

                    const text = document.createElement('p');
                    text.textContent = 'Belum ada data pembayaran.';

                    empty.append(icon, text);
                    list.appendChild(empty);
                    return;
                }

                items.forEach((item) => {
                    const row = document.createElement('div');
                    row.className = 'monev-payment-row';

                    const rank = document.createElement('span');
                    rank.className = 'monev-rank-badge';
                    rank.textContent = item.rank;

                    const main = document.createElement('div');
                    main.className = 'monev-payment-main';

                    const title = document.createElement('div');
                    title.className = 'monev-payment-title';

                    const label = document.createElement('strong');
                    label.textContent = item.label;

                    const totalLabel = document.createElement('span');
                    totalLabel.textContent = `${formatNumber(item.total)} transaksi`;

                    const track = document.createElement('div');
                    track.className = 'monev-payment-track';

                    const bar = document.createElement('span');
                    bar.style.width = `${Math.min(100, Number(item.percentage || 0))}%`;

                    const percentage = document.createElement('span');
                    percentage.className = 'monev-payment-percent';
                    percentage.textContent = `${item.percentage}%`;

                    title.append(label, totalLabel);
                    track.appendChild(bar);
                    main.append(title, track);
                    row.append(rank, main, percentage);
                    list.appendChild(row);
                });
            }

            if (registrationCanvas) {
                registrationChart = new Chart(registrationCanvas, {
                    type: 'bar',
                    data: {
                        labels: monthLabels,
                        datasets: [{
                            label: @json('Pendaftaran ' . $growth['current_year']),
                            data: @json($growth['monthly_current']),
                            backgroundColor: 'rgba(41, 170, 89, 0.82)',
                            hoverBackgroundColor: 'rgba(41, 170, 89, 1)',
                            borderRadius: 5,
                            borderSkipped: false,
                            maxBarThickness: 34,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#172033',
                                padding: 12,
                                cornerRadius: 6,
                                callbacks: {
                                    label: (context) => `${context.dataset.label}: ${formatNumber(context.parsed.y)}`
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.18)',
                                    drawBorder: false
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            const statusEl = document.querySelector('#statusDonutChart');
            if (statusEl) {
                statusDonutChart = new ApexCharts(statusEl, donutOptions(@json($statusDistribution), statusColors));
                statusDonutChart.render();
            }

            const paymentEl = document.querySelector('#paymentTypeChart');
            if (paymentEl) {
                paymentTypeChart = new ApexCharts(paymentEl, donutOptions(@json($paymentTypes), paymentColors));
                paymentTypeChart.render();
            }

            window.applyFilter = function() {
                const period = document.getElementById('filter-period').value;
                const startDate = document.getElementById('filter-start').value;
                const endDate = document.getElementById('filter-end').value;
                const loading = document.getElementById('chart-loading');

                loading.classList.remove('d-none');
                registrationCanvas.style.opacity = '0.35';

                const params = new URLSearchParams({
                    period
                });

                if (startDate) {
                    params.append('start_date', startDate);
                }

                if (endDate) {
                    params.append('end_date', endDate);
                }

                fetch(`{{ route('monitoring.chart-data') }}?${params.toString()}`)
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data.success || !registrationChart) {
                            return;
                        }

                        registrationChart.data.labels = data.registration.labels;
                        registrationChart.data.datasets[0].data = data.registration.values;
                        registrationChart.data.datasets[0].label = `Pendaftaran (${data.registration.period_type})`;
                        registrationChart.update('active');

                        document.getElementById('status-total-count').textContent = formatNumber(
                            (data.distribution?.series || []).reduce((sum, value) => sum + Number(value || 0), 0)
                        );

                        updateDonut(statusDonutChart, data.distribution, statusColors);
                        updateDonut(paymentTypeChart, data.payment_types, paymentColors);
                        renderPaymentTypes(data.payment_types, data.registration.total);
                    })
                    .catch((error) => console.error('Filter error:', error))
                    .finally(() => {
                        loading.classList.add('d-none');
                        registrationCanvas.style.opacity = '1';
                    });
            };

            window.resetFilter = function() {
                document.getElementById('filter-period').value = 'monthly';
                document.getElementById('filter-start').value = '';
                document.getElementById('filter-end').value = '';
                window.applyFilter();
            };
        });
    </script>
@endsection
