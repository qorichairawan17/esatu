<?php

namespace App\Repositories;

use App\Enum\StatusSuratKuasaEnum;
use App\Models\Pengguna\PaniteraModel;
use App\Models\Suratkuasa\PembayaranSuratKuasaModel;
use App\Models\Suratkuasa\PendaftaranSuratKuasaModel;
use App\Models\Suratkuasa\RegisterSuratKuasaModel;
use App\Models\Testimoni\TestimoniModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MonitoringRepository
{
    /**
     * Get registration counts grouped by a date format.
     *
     * @param  string  $groupFormat  SQL date format (e.g., '%Y-%m-%d', '%Y-%m')
     * @return Collection<int, object{period: string, total: int}>
     */
    public function getRegistrationCountsByPeriod(string $groupFormat, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = PendaftaranSuratKuasaModel::select(
            DB::raw("DATE_FORMAT(tanggal_daftar, '{$groupFormat}') as period"),
            DB::raw('COUNT(*) as total')
        );

        if ($startDate) {
            $query->where('tanggal_daftar', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('tanggal_daftar', '<=', $endDate);
        }

        return $query->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    /**
     * Get total registrations count within a date range.
     */
    public function getTotalRegistrations(?string $startDate = null, ?string $endDate = null): int
    {
        $query = PendaftaranSuratKuasaModel::query();

        if ($startDate) {
            $query->where('tanggal_daftar', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('tanggal_daftar', '<=', $endDate);
        }

        return $query->count();
    }

    /**
     * Get monthly registration data for a specific year.
     *
     * @return array<int, int> Array indexed 0-11 representing Jan-Dec
     */
    public function getMonthlyRegistrations(int $year): array
    {
        $monthlyCounts = array_fill(0, 12, 0);

        $results = PendaftaranSuratKuasaModel::select(
            DB::raw('MONTH(tanggal_daftar) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('tanggal_daftar', $year)
            ->groupBy('month')
            ->get();

        foreach ($results as $result) {
            if ($result->month > 0) {
                $monthlyCounts[$result->month - 1] = $result->total;
            }
        }

        return $monthlyCounts;
    }

    /**
     * Get top advocates by registration count.
     *
     * @return Collection<int, object{nama: string, total_pendaftaran: int}>
     */
    public function getTopAdvokats(int $limit = 10): Collection
    {
        return PendaftaranSuratKuasaModel::select(
            'pemohon as nama',
            DB::raw('COUNT(*) as total_pendaftaran')
        )
            ->groupBy('pemohon')
            ->orderByDesc('total_pendaftaran')
            ->limit($limit)
            ->get();
    }

    /**
     * Get panitera performance ranked by approval count.
     *
     * @return Collection<int, object{nama: string, jabatan: string, total_approval: int}>
     */
    public function getPaniteraPerformance(int $limit = 10): Collection
    {
        return PaniteraModel::select(
            'sk_panitera.nama',
            'sk_panitera.jabatan',
            DB::raw('COUNT(sk_register_surat_kuasa.id) as total_approval')
        )
            ->leftJoin('sk_register_surat_kuasa', 'sk_panitera.id', '=', 'sk_register_surat_kuasa.panitera_id')
            ->groupBy('sk_panitera.id', 'sk_panitera.nama', 'sk_panitera.jabatan')
            ->orderByDesc('total_approval')
            ->limit($limit)
            ->get();
    }

    /**
     * Get verifier (approval) performance ranked by verification count.
     *
     * @return Collection<int, object{nama: string, total_verifikasi: int}>
     */
    public function getVerifierPerformance(int $limit = 10): Collection
    {
        return RegisterSuratKuasaModel::select(
            'users.name as nama',
            DB::raw('COUNT(sk_register_surat_kuasa.id) as total_verifikasi')
        )
            ->join('users', 'sk_register_surat_kuasa.approval_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_verifikasi')
            ->limit($limit)
            ->get();
    }

    /**
     * Get latest testimonials with user relation.
     *
     * @return Collection<int, TestimoniModel>
     */
    public function getLatestTestimonials(int $limit = 10): Collection
    {
        return TestimoniModel::with('user:id,name,avatar')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get status distribution for donut chart.
     *
     * @return array{series: array<int, int>, labels: array<int, string>}
     */
    public function getStatusDistribution(?string $startDate = null, ?string $endDate = null): array
    {
        $query = PendaftaranSuratKuasaModel::query();

        if ($startDate) {
            $query->where('tanggal_daftar', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('tanggal_daftar', '<=', $endDate);
        }

        $stats = $query->toBase()
            ->selectRaw('COUNT(CASE WHEN status = ? THEN 1 END) as disetujui', [StatusSuratKuasaEnum::Disetujui->value])
            ->selectRaw('COUNT(CASE WHEN status = ? THEN 1 END) as ditolak', [StatusSuratKuasaEnum::Ditolak->value])
            ->selectRaw('COUNT(CASE WHEN status IS NULL THEN 1 END) as proses')
            ->first();

        return [
            'series' => [
                (int) ($stats->disetujui ?? 0),
                (int) ($stats->ditolak ?? 0),
                (int) ($stats->proses ?? 0),
            ],
            'labels' => ['Disetujui', 'Ditolak', 'Dalam Proses'],
        ];
    }

    /**
     * Get payment method distribution within a date range.
     *
     * @return Collection<int, object{jenis_pembayaran: string|null, total: int}>
     */
    public function getPaymentTypeDistribution(?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = PembayaranSuratKuasaModel::query()
            ->select('jenis_pembayaran', DB::raw('COUNT(*) as total'));

        if ($startDate) {
            $query->where('tanggal_pembayaran', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('tanggal_pembayaran', '<=', $endDate);
        }

        return $query->groupBy('jenis_pembayaran')
            ->orderByDesc('total')
            ->orderBy('jenis_pembayaran')
            ->get();
    }
}
