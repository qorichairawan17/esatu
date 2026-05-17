<?php

namespace App\Helpers;

use App\Enum\RoleEnum;
use App\Enum\StatusSuratKuasaEnum;
use App\Enum\TahapanSuratKuasaEnum;
use App\Models\AuditTrail\AuditTrailModel;
use App\Models\Suratkuasa\PendaftaranSuratKuasaModel;
use App\Models\Testimoni\TestimoniModel;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeHelper
{
    public function userTotal()
    {
        $userCount = User::where('role', '=', RoleEnum::User->value)->count();

        return $userCount;
    }

    public function suratKuasaTotal()
    {
        $suratKuasaCount = PendaftaranSuratKuasaModel::where('status', '=', StatusSuratKuasaEnum::Disetujui->value)->count();

        return $suratKuasaCount;
    }

    public function testimoniTotal()
    {
        $testimoniCount = TestimoniModel::all()->count();

        return $testimoniCount;
    }

    public function verifikasiSuratKuasa()
    {
        $suratKuasa = PendaftaranSuratKuasaModel::where('tahapan', '!=', TahapanSuratKuasaEnum::Verifikasi->value)
            ->orderBy('created_at', 'desc')
            ->limit(5)->get();

        return $suratKuasa;
    }

    public function statusSuratKuasa($param)
    {
        $suratKuasa = PendaftaranSuratKuasaModel::where('status', '=', $param)->whereYear('created_at', date('Y'))->count();

        return $suratKuasa;
    }

    public function tahapanSuratKuasa($param)
    {
        $suratKuasa = PendaftaranSuratKuasaModel::where('tahapan', '=', $param)->whereYear('created_at', date('Y'))->count();

        return $suratKuasa;
    }

    public function getChart(): array
    {
        $currentYear = date('Y');
        $cacheKey = "chart_data_{$currentYear}";

        // Cache the data for 1 day (3600 minutes) or until invalidated.
        return Cache::remember($cacheKey, 3600, function () use ($currentYear) {
            $monthlyCounts = array_fill(0, 12, 0);

            $results = PendaftaranSuratKuasaModel::select(
                DB::raw('MONTH(tanggal_daftar) as month'),
                DB::raw('count(*) as count')
            )
                ->where('status', StatusSuratKuasaEnum::Disetujui->value)
                ->whereYear('tanggal_daftar', $currentYear)
                ->groupBy('month')
                ->get();

            foreach ($results as $result) {
                // Ensure the index is not out of bounds if the month is 0.
                if ($result->month > 0) {
                    $monthlyCounts[$result->month - 1] = $result->count;
                }
            }

            return $monthlyCounts;
        });
    }

    public function getChartDitolak(): array
    {
        $currentYear = date('Y');
        $cacheKey = "chart_data_ditolak_{$currentYear}";

        return Cache::remember($cacheKey, 3600, function () use ($currentYear) {
            $monthlyCounts = array_fill(0, 12, 0);

            $results = PendaftaranSuratKuasaModel::select(
                DB::raw('MONTH(tanggal_daftar) as month'),
                DB::raw('count(*) as count')
            )
                ->where('status', StatusSuratKuasaEnum::Ditolak->value)
                ->whereYear('tanggal_daftar', $currentYear)
                ->groupBy('month')
                ->get();

            foreach ($results as $result) {
                if ($result->month > 0) {
                    $monthlyCounts[$result->month - 1] = $result->count;
                }
            }

            return $monthlyCounts;
        });
    }

    /**
     * Get statistik surat kuasa for current month (donut chart)
     */
    public function getStatistikSuratKuasaBulan(): array
    {
        $currentMonth = date('m');
        $currentYear = date('Y');

        $disetujui = PendaftaranSuratKuasaModel::where('status', '=', StatusSuratKuasaEnum::Disetujui->value)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $ditolak = PendaftaranSuratKuasaModel::where('status', '=', StatusSuratKuasaEnum::Ditolak->value)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $belumBayar = PendaftaranSuratKuasaModel::where('tahapan', '=', TahapanSuratKuasaEnum::Pendaftaran->value)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $sudahBayar = PendaftaranSuratKuasaModel::where('tahapan', '=', TahapanSuratKuasaEnum::Pembayaran->value)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return [
            'series' => [$disetujui, $ditolak, $belumBayar, $sudahBayar],
            'labels' => ['Disetujui', 'Ditolak', 'Belum Bayar', 'Sudah Bayar'],
        ];
    }

    public function lastAuditTrail()
    {
        $auditTrail = AuditTrailModel::with('user')->orderBy('created_at', 'desc')->first();

        return $auditTrail;
    }

    public function getPembayaranSuratKuasa()
    {
        return PendaftaranSuratKuasaModel::where('user_id', auth()->id())
            ->where(function ($query) {
                $query->where('tahapan', TahapanSuratKuasaEnum::Pendaftaran->value)
                    ->orWhere('tahapan', TahapanSuratKuasaEnum::PerbaikanPembayaran->value);
            })
            ->orderBy('created_at', 'desc')->get();
    }

    public function getTestimoniByUser()
    {
        return TestimoniModel::where('user_id', auth()->id())->first();
    }

    public function getChartForUser(): array
    {
        $userId = auth()->id();
        $currentYear = date('Y');
        $cacheKey = "chart_data_user_{$userId}_{$currentYear}";

        // Cache the data for 1 day (3600 minutes) or until invalidated.
        return Cache::remember($cacheKey, 3600, function () use ($userId, $currentYear) {
            $monthlyCounts = array_fill(0, 12, 0);

            $results = PendaftaranSuratKuasaModel::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
                ->where('user_id', $userId)
                ->where('status', StatusSuratKuasaEnum::Disetujui->value)
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->get();

            foreach ($results as $result) {
                if ($result->month > 0) {
                    $monthlyCounts[$result->month - 1] = $result->count;
                }
            }

            return $monthlyCounts;
        });
    }

    public function pendaftaranSuratKuasaByUser($id)
    {
        $suratKuasa = PendaftaranSuratKuasaModel::where('user_id', $id)
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->orderBy('created_at', 'desc')->limit(5)->get();

        return $suratKuasa;
    }

    /**
     * Get count of surat kuasa registered by user in current week
     */
    public function getSuratKuasaCountThisWeek($userId = null): int
    {
        $userId = $userId ?? auth()->id();

        return PendaftaranSuratKuasaModel::where('user_id', $userId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }

    /**
     * Check if user should be prompted to give testimoni
     * Returns true if: user has more than 3 surat kuasa this week AND no testimoni yet
     */
    public function shouldShowTestimoniPrompt($userId = null): bool
    {
        $userId = $userId ?? auth()->id();

        // Check if user already has testimoni
        $hasTestimoni = TestimoniModel::where('user_id', $userId)->exists();

        if ($hasTestimoni) {
            return false;
        }

        // Check if user has more than 3 surat kuasa this week
        $suratKuasaCount = $this->getSuratKuasaCountThisWeek($userId);

        return $suratKuasaCount > 3;
    }
}
