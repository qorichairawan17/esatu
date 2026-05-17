<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan\AplikasiModel;
use App\Services\MonitoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MonitoringController extends Controller
{
    protected $infoApp;

    public function __construct(private MonitoringService $monitoringService)
    {
        $this->infoApp = Cache::remember('infoApp', 3600, function () {
            return AplikasiModel::first();
        });
    }

    /**
     * Display monitoring evaluasi dashboard.
     */
    public function index()
    {
        $breadCumb = [
            ['title' => 'Dashboard', 'url' => route('dashboard.admin'), 'active' => '', 'aria' => ''],
            ['title' => 'Monitoring Evaluasi', 'url' => 'javascript:void(0);', 'active' => 'active', 'aria' => 'aria-current="page"'],
        ];

        $data = [
            'title' => 'Monitoring Evaluasi - '.config('app.name'),
            'pageTitle' => 'Monitoring & Evaluasi',
            'breadCumb' => $breadCumb,
            'infoApp' => $this->infoApp,
            'overview' => $this->monitoringService->getOverviewStats(),
            'growth' => $this->monitoringService->getAverageAndGrowth(),
            'topAdvokats' => $this->monitoringService->getTopAdvokats(),
            'paniteraPerformance' => $this->monitoringService->getPaniteraPerformance(),
            'verifierPerformance' => $this->monitoringService->getVerifierPerformance(),
            'testimonials' => $this->monitoringService->getLatestTestimonials(),
            'statusDistribution' => $this->monitoringService->getStatusDistribution(),
            'paymentTypes' => $this->monitoringService->getPaymentTypeStats(),
        ];

        return view('admin.monitoring.index', $data);
    }

    /**
     * API endpoint for chart data with filters.
     */
    public function chartData(Request $request): JsonResponse
    {
        $periodType = $request->input('period', 'monthly');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $stats = $this->monitoringService->getRegistrationStats($periodType, $startDate, $endDate);
        $distribution = $this->monitoringService->getStatusDistribution($startDate, $endDate);
        $paymentTypes = $this->monitoringService->getPaymentTypeStats($startDate, $endDate);

        return response()->json([
            'success' => true,
            'registration' => $stats,
            'distribution' => $distribution,
            'payment_types' => $paymentTypes,
        ]);
    }
}
