<?php

namespace App\Http\Controllers\Pengguna;

use App\DataTables\AdvokatDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pengguna\AdvokatRequest;
use App\Models\Pengaturan\AplikasiModel;
use App\Models\Suratkuasa\PendaftaranSuratKuasaModel;
use App\Models\User;
use App\Services\AdvokatNonAdvokatService;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class AdvokatNonAdvokatController extends Controller
{
    protected $infoApp;

    public function __construct(protected AdvokatNonAdvokatService $advokatNonAdvokatService)
    {
        $this->infoApp = Cache::remember('infoApp', 3600, function () {
            return AplikasiModel::first();
        });
    }

    private function breadCumb($parameters)
    {
        $breadCumb = [
            ['title' => 'Pengguna', 'url' => $parameters['url'], 'active' => $parameters['active'], 'aria' => $parameters['aria']],
        ];

        return $breadCumb;
    }

    public function index(AdvokatDataTable $dataTable)
    {
        $breadCumb = $this->breadCumb(['url' => route('advokat.index'), 'active' => '', 'aria' => '']);
        $breadCumb[] = ['title' => 'Advokat/Non Advokat', 'url' => 'javascript:void(0);', 'active' => 'active', 'aria' => 'aria-current="page"'];

        $data = [
            'title' => 'Adovkat/ Non Advokat - '.config('app.name'),
            'pageTitle' => 'Adovkat/ Non Advokat',
            'breadCumb' => $breadCumb,
            'infoApp' => $this->infoApp,
        ];

        return $dataTable->render('admin.pengguna.advokat-non-advokat.data-advokat-non-advokat', $data);
    }

    public function form(Request $request)
    {
        $param = $request->param;
        $id = $request->id ? Crypt::decrypt($request->id) : null;
        $user = $id ? User::with('profile')->find($id) : null;

        if ($param == 'add') {
            $title = 'Tambah Advokat/ Non Advokat';
        } else {
            if (! $user) {
                return redirect()->route('advokat.index')->with('error', 'Data Administrator tidak ditemukan.');
            }
            $title = 'Edit Advokat/ Non Advokat';
        }

        $breadCumb = $this->breadCumb(['url' => 'javascript:void(0);', 'active' => '', 'aria' => '']);
        $breadCumb[] = ['title' => 'Advokat/ Non Advokat', 'url' => route('advokat.index'), 'active' => '', 'aria' => ''];
        $breadCumb[] = ['title' => $title, 'url' => 'javascript:void(0);', 'active' => 'active', 'aria' => 'aria-current="page"'];

        $id = $request->id ? Crypt::decrypt($request->id) : null;

        $data = [
            'title' => $title.' - '.config('app.name'),
            'pageTitle' => $title,
            'breadCumb' => $breadCumb,
            'infoApp' => $this->infoApp,
            'user' => $user,
        ];

        return view('admin.pengguna.advokat-non-advokat.form-advokat-non-advokat', $data);
    }

    public function store(AdvokatRequest $request): JsonResponse
    {
        return $this->advokatNonAdvokatService->store($request);
    }

    public function destroy($id): JsonResponse
    {
        return $this->advokatNonAdvokatService->destroy($id);
    }

    public function detail(Request $request)
    {
        try {
            $id = $request->id ? Crypt::decrypt($request->id) : null;
        } catch (DecryptException) {
            return redirect()->route('advokat.index')->with('error', 'ID data tidak valid.');
        }

        $user = $id ? User::with('profile')->withCount('suratKuasa')->find($id) : null;

        if (! $user) {
            return redirect()->route('advokat.index')->with('error', 'Data advokat/non advokat tidak ditemukan.');
        }

        $breadCumb = $this->breadCumb(['url' => route('advokat.index'), 'active' => '', 'aria' => '']);
        $breadCumb[] = ['title' => 'Advokat/Non Advokat', 'url' => 'javascript:void(0);', 'active' => 'active', 'aria' => 'aria-current="page"'];
        $breadCumb[] = ['title' => 'Detail', 'url' => 'javascript:void(0);', 'active' => 'active', 'aria' => 'aria-current="page"'];

        $data = [
            'title' => 'Detail - '.config('app.name'),
            'pageTitle' => 'Detail Advokat/Non Advokat',
            'breadCumb' => $breadCumb,
            'infoApp' => $this->infoApp,
            'user' => $user,
            'detailTitle' => $user->name,
        ];

        return view('admin.pengguna.advokat-non-advokat.detail-advokat-non-advokat', $data);
    }

    public function suratKuasaData(string $id): JsonResponse
    {
        try {
            $userId = Crypt::decrypt($id);
        } catch (DecryptException) {
            return response()->json(['message' => 'ID data tidak valid.'], 400);
        }

        $user = User::query()->select('id')->findOrFail($userId);

        $query = PendaftaranSuratKuasaModel::query()
            ->select(['id', 'id_daftar', 'tanggal_daftar', 'tahapan', 'user_id'])
            ->where('user_id', $user->id)
            ->latest('tanggal_daftar')
            ->latest('id');

        return DataTables::eloquent($query)
            ->editColumn('id_daftar', function (PendaftaranSuratKuasaModel $row) {
                $detailUrl = route('surat-kuasa.detail', ['id' => Crypt::encrypt($row->id)]);

                return '<a href="'.$detailUrl.'" class="sk-link">'.e($row->id_daftar).'</a>';
            })
            ->editColumn('tanggal_daftar', function (PendaftaranSuratKuasaModel $row) {
                return $row->tanggal_daftar ? Carbon::parse($row->tanggal_daftar)->translatedFormat('d M Y') : '-';
            })
            ->editColumn('tahapan', function (PendaftaranSuratKuasaModel $row) {
                $badgeClass = match ($row->tahapan) {
                    'Verifikasi' => 'success',
                    'Pembayaran', 'Pengajuan Perbaikan Pembayaran' => 'primary',
                    'Perbaikan Data', 'Perbaikan Pembayaran' => 'danger',
                    'Pengajuan Perbaikan Data' => 'warning',
                    default => 'muted',
                };

                return '<span class="sk-stage is-'.$badgeClass.'">'.e($row->tahapan).'</span>';
            })
            ->rawColumns(['id_daftar', 'tahapan'])
            ->toJson();
    }

    public function sendWarning(Request $request, $id): JsonResponse
    {
        return $this->advokatNonAdvokatService->sendWarning($id);
    }
}
