<?php

namespace App\Services;

use App\Repositories\MonitoringRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MonitoringService
{
    public function __construct(private MonitoringRepository $repository) {}

    /**
     * Get registration statistics by period type with optional date filters.
     *
     * @param  string  $periodType  daily|monthly|quarterly|semester|yearly
     * @return array{chart_data: array, period_type: string, total: int}
     */
    public function getRegistrationStats(string $periodType = 'monthly', ?string $startDate = null, ?string $endDate = null): array
    {
        $format = match ($periodType) {
            'daily' => '%Y-%m-%d',
            'monthly' => '%Y-%m',
            'quarterly' => '%Y-Q',
            'semester' => '%Y-S',
            'yearly' => '%Y',
            default => '%Y-%m',
        };

        if ($periodType === 'quarterly' || $periodType === 'semester') {
            return $this->getCustomPeriodStats($periodType, $startDate, $endDate);
        }

        $data = $this->repository->getRegistrationCountsByPeriod($format, $startDate, $endDate);
        $total = $data->sum('total');

        $labels = $data->pluck('period')->toArray();
        $values = $data->pluck('total')->toArray();

        if ($periodType === 'monthly') {
            $labels = array_map(function ($label) {
                return Carbon::createFromFormat('Y-m', $label)->translatedFormat('M Y');
            }, $labels);
        } elseif ($periodType === 'daily') {
            $labels = array_map(function ($label) {
                return Carbon::createFromFormat('Y-m-d', $label)->translatedFormat('d M');
            }, $labels);
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'period_type' => $periodType,
            'total' => $total,
        ];
    }

    /**
     * Build quarterly / semester stats from monthly raw data.
     *
     * @return array{labels: array, values: array, period_type: string, total: int}
     */
    private function getCustomPeriodStats(string $periodType, ?string $startDate, ?string $endDate): array
    {
        $data = $this->repository->getRegistrationCountsByPeriod('%Y-%m', $startDate, $endDate);

        $grouped = [];
        foreach ($data as $row) {
            $date = Carbon::createFromFormat('Y-m', $row->period);
            $year = $date->year;
            $month = $date->month;

            if ($periodType === 'quarterly') {
                $quarter = ceil($month / 3);
                $key = "{$year}-Q{$quarter}";
            } else {
                $semester = $month <= 6 ? 1 : 2;
                $key = "{$year}-S{$semester}";
            }

            $grouped[$key] = ($grouped[$key] ?? 0) + $row->total;
        }

        return [
            'labels' => array_keys($grouped),
            'values' => array_values($grouped),
            'period_type' => $periodType,
            'total' => array_sum($grouped),
        ];
    }

    /**
     * Calculate average registrations and growth compared to previous period.
     *
     * @return array{average: float, growth_percentage: float, current_total: int, previous_total: int, trend: string}
     */
    public function getAverageAndGrowth(): array
    {
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;

        $cacheKey = "monev_growth_{$currentYear}";

        return Cache::remember($cacheKey, 1800, function () use ($currentYear, $previousYear) {
            $currentData = $this->repository->getMonthlyRegistrations($currentYear);
            $previousData = $this->repository->getMonthlyRegistrations($previousYear);

            $currentTotal = array_sum($currentData);
            $previousTotal = array_sum($previousData);

            $currentMonth = Carbon::now()->month;
            $currentAverage = $currentMonth > 0 ? round($currentTotal / $currentMonth, 1) : 0;

            $growthPercentage = 0;
            if ($previousTotal > 0) {
                $growthPercentage = round((($currentTotal - $previousTotal) / $previousTotal) * 100, 1);
            }

            $trend = $growthPercentage > 0 ? 'up' : ($growthPercentage < 0 ? 'down' : 'stable');

            return [
                'average' => $currentAverage,
                'growth_percentage' => $growthPercentage,
                'current_total' => $currentTotal,
                'previous_total' => $previousTotal,
                'current_year' => $currentYear,
                'previous_year' => $previousYear,
                'trend' => $trend,
                'monthly_current' => $currentData,
                'monthly_previous' => $previousData,
            ];
        });
    }

    /**
     * Get top advocates ranked by registration count.
     *
     * @return array<int, array{rank: int, nama: string, total_pendaftaran: int}>
     */
    public function getTopAdvokats(int $limit = 10): array
    {
        $data = $this->repository->getTopAdvokats($limit);

        return $data->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'nama' => $item->nama,
                'total_pendaftaran' => $item->total_pendaftaran,
            ];
        })->toArray();
    }

    /**
     * Get panitera performance data.
     *
     * @return array<int, array{rank: int, nama: string, jabatan: string, total_approval: int}>
     */
    public function getPaniteraPerformance(int $limit = 10): array
    {
        $data = $this->repository->getPaniteraPerformance($limit);

        return $data->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'nama' => $item->nama,
                'jabatan' => $item->jabatan,
                'total_approval' => $item->total_approval,
            ];
        })->toArray();
    }

    /**
     * Get verifier performance data.
     *
     * @return array<int, array{rank: int, nama: string, total_verifikasi: int}>
     */
    public function getVerifierPerformance(int $limit = 10): array
    {
        $data = $this->repository->getVerifierPerformance($limit);

        return $data->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'nama' => $item->nama,
                'total_verifikasi' => $item->total_verifikasi,
            ];
        })->toArray();
    }

    /**
     * Get latest testimonials formatted for display.
     *
     * @return array<int, array{nama: string, rating: int, testimoni: string, avatar: string|null, tanggal: string}>
     */
    public function getLatestTestimonials(int $limit = 10): array
    {
        $data = $this->repository->getLatestTestimonials($limit);

        return $data->map(function ($item) {
            return [
                'nama' => $item->user->name ?? 'Anonim',
                'rating' => $item->rating ?? 0,
                'testimoni' => $item->testimoni,
                'avatar' => $item->user->avatar ?? null,
                'tanggal' => $item->created_at->translatedFormat('d M Y'),
            ];
        })->toArray();
    }

    /**
     * Get status distribution for chart.
     *
     * @return array{series: array<int, int>, labels: array<int, string>}
     */
    public function getStatusDistribution(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->repository->getStatusDistribution($startDate, $endDate);
    }

    /**
     * Get payment type statistics for dashboard charts and summaries.
     *
     * @return array{total: int, total_types: int, top: array|null, items: array<int, array{rank: int, label: string, total: int, percentage: float}>, labels: array<int, string>, series: array<int, int>}
     */
    public function getPaymentTypeStats(?string $startDate = null, ?string $endDate = null): array
    {
        $data = $this->repository->getPaymentTypeDistribution($startDate, $endDate);
        $total = (int) $data->sum('total');

        $items = $data->map(function ($item, $index) use ($total) {
            $label = trim((string) $item->jenis_pembayaran);

            return [
                'rank' => $index + 1,
                'label' => $label !== '' ? $label : 'Tidak Diketahui',
                'total' => (int) $item->total,
                'percentage' => $total > 0 ? round(($item->total / $total) * 100, 1) : 0,
            ];
        })->values()->toArray();

        return [
            'total' => $total,
            'total_types' => count($items),
            'top' => $items[0] ?? null,
            'items' => $items,
            'labels' => array_column($items, 'label'),
            'series' => array_column($items, 'total'),
        ];
    }

    /**
     * Get overview summary stats for the dashboard header cards.
     *
     * @return array{total_pendaftaran: int, total_disetujui: int, total_ditolak: int, total_proses: int}
     */
    public function getOverviewStats(): array
    {
        $distribution = $this->repository->getStatusDistribution();
        $total = $this->repository->getTotalRegistrations();

        return [
            'total_pendaftaran' => $total,
            'total_disetujui' => $distribution['series'][0],
            'total_ditolak' => $distribution['series'][1],
            'total_proses' => $distribution['series'][2],
        ];
    }
}
